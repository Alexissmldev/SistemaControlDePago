<?php

namespace App\Http\Controllers;

use App\Models\{Payment, Debt};
use App\Http\Requests\PaymentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{DB, Auth};

class PaymentController extends Controller
{

    //  Registra un pago multimoneda y distribuye el saldo entre las deudas pendientes

    public function store(PaymentRequest $request): RedirectResponse
    {
        return DB::transaction(function () use ($request) {
            $rate = $request->exchange_rate;
            $bsMethods = ['Pago Movil', 'Transferencia', 'Efectivo Bs', 'Punto'];

            // Calculamos el total abonado convertido a USD según la tasa del día
            $totalPaidUsd = collect($request->payments)->sum(function ($item) use ($rate, $bsMethods) {
                return in_array($item['method'], $bsMethods)
                    ? ($item['amount'] / $rate)
                    : $item['amount'];
            });

            // Creamos la cabecera del recibo
            $payment = Payment::create([
                'student_id'     => $request->student_id,
                'total_amount'   => $totalPaidUsd,
                'receipt_number' => $this->generateReceiptNumber(),
                'user_id'        => Auth::id(),
                'exchange_rate'  => $rate,
            ]);

            // Registramos cada método de pago utilizado en la transacción
            foreach ($request->payments as $item) {
                $isBs = in_array($item['method'], $bsMethods);

                $payment->paymentMethods()->create([
                    'method'        => $item['method'],
                    'currency'      => $isBs ? 'BS' : 'USD',
                    'amount'        => $item['amount'],
                    'exchange_rate' => $isBs ? $rate : 1,
                    'amount_usd'    => $isBs ? ($item['amount'] / $rate) : $item['amount'],
                    'reference'     => $item['reference'] ?? null,
                ]);
            }

            // Aplicamos el saldo a las deudas del estudiante 
            $this->processDebts($payment, $totalPaidUsd);

            return back()->with('success', "Pago registrado exitosamente: {$payment->receipt_number}");
        });
    }

    // Procesa y amortiza las deudas pendientes usando el saldo del pago actual
    private function processDebts(Payment $payment, float $saldo): void
    {
        $debts = Debt::where('student_id', $payment->student_id)
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($debts as $debt) {
            if ($saldo <= 0.01) break;

            $montoAbonar = min($saldo, $debt->amount);

            // Vinculamos el pago con la deuda en la tabla pivote
            $payment->debts()->attach($debt->id, ['amount' => $montoAbonar]);

            // Actualizamos el balance de la deuda y el saldo disponible
            $debt->amount -= $montoAbonar;
            $saldo -= $montoAbonar;

            // Definimos el nuevo estatus de la deuda
            $debt->status = ($debt->amount <= 0.01) ? 'paid' : 'partial';
            if ($debt->status === 'paid') $debt->amount = 0;

            $debt->save();
        }
    }

    // Genera un formato de recibo  
    private function generateReceiptNumber(): string
    {
        return 'REC-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
    }
}
