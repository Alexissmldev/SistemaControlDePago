<div id="db-rate-source" data-rate="{{ $exchangeRate ?? 0 }}" style="display: none;"></div>

<div 
    x-data="paymentLogic()" 
    @open-payment-modal.window="openModal($event.detail)"
    @keydown.escape.window="!isLoading && closeModal()"
    class="relative z-50" 
    style="display: none;" 
    x-show="openPaymentModal" 
    
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
>
    <div 
        x-show="openPaymentModal" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity" 
        @click="!isLoading && closeModal()"
    ></div>

    <div class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4 pointer-events-none">
        <div 
            x-show="openPaymentModal"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
            class="relative w-full max-w-lg bg-white shadow-2xl rounded-2xl flex flex-col max-h-[95vh] pointer-events-auto"
            @click.stop
        >
            <div class="bg-indigo-600 px-6 py-4 flex justify-between items-center shrink-0">
                <div class="text-white">
                    <h3 class="text-lg font-bold">Registrar Pago</h3>
                    <p class="text-xs opacity-80" x-text="studentName"></p>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] text-indigo-200 uppercase font-bold">Tasa BCV</span>
                    <span class="bg-indigo-700 text-white px-2 py-1 rounded text-xs font-bold shadow-sm">
                        Bs. <span x-text="formatNumber(exchangeRate)"></span>
                    </span>
                </div>
            </div>

            <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar">
                
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-xs">
                        <strong>Ups! Algo salió mal:</strong>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="paymentForm" action="{{ route('payments.store') }}" method="POST" @submit="isLoading = true">
                    @csrf
                    <input type="hidden" name="student_id" x-model="studentId">
                    <input type="hidden" name="exchange_rate" x-model="exchangeRate">

                    <div class="text-center p-3 bg-gray-50 rounded-xl border border-gray-100 mb-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Deuda Pendiente</p>
                        <div class="text-3xl font-black text-gray-800 my-1">
                            $<span x-text="formatNumber(studentDebt)"></span>
                        </div>
                        <div class="text-xs font-bold text-gray-500">
                            ≈ Bs. <span x-text="formatNumber(studentDebt * exchangeRate)"></span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm relative hover:border-indigo-300 transition">
                                <div class="flex gap-3 mb-2">
                                    <div class="w-1/2">
                                        <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Método</label>
                                        <select :name="`payments[${index}][method]`" x-model="item.method" class="w-full pl-2 pr-6 py-2.5 rounded-lg bg-gray-50 border-transparent focus:bg-white focus:border-indigo-500 text-xs font-bold text-gray-600 cursor-pointer focus:ring-2 focus:ring-indigo-200">
                                            <option value="" disabled selected>Seleccione...</option>
                                            <option value="Pago Movil">Pago Móvil</option>
                                             <option value="Efectivo Divisa">Efectivo ($)</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Efectivo Bs">Efectivo (Bs)</option>
                                        </select>
                                    </div>
                                    <div class="w-1/2">
                                        <label class="text-[9px] font-bold text-gray-400 uppercase ml-1">Monto</label>
                                        <input type="number" step="0.01" min="0" :name="`payments[${index}][amount]`" x-model="item.amount" :disabled="!item.method" class="w-full px-3 py-2.5 rounded-lg border-transparent text-gray-800 font-bold focus:ring-2 transition-all text-sm bg-gray-50" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2 h-8">
                                    <div class="flex-1 mr-2">
                                        <div x-show="needsRef(item.method)" class="flex items-center bg-indigo-50 rounded px-2 py-1 border border-indigo-100">
                                            <span class="text-[9px] text-indigo-500 font-bold mr-2">REF:</span>
                                            <input type="text" :name="`payments[${index}][reference]`" x-model="item.reference" placeholder="0000" class="w-full bg-transparent border-none text-xs font-bold text-indigo-700 p-0 focus:ring-0">
                                        </div>
                                    </div>
                                    <div x-show="isBs(item.method) && item.amount > 0" class="text-right">
                                        <span class="text-[9px] font-bold text-gray-400">Son:</span>
                                        <span class="text-[10px] font-black text-green-600 bg-green-50 px-1.5 rounded">$<span x-text="formatNumber(item.amount / exchangeRate)"></span></span>
                                    </div>
                                    <button type="button" x-show="items.length > 1" @click="removeItem(index)" class="ml-2 w-8 h-8 rounded-full text-red-300 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition"><i class="fas fa-times text-sm"></i></button>
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="addItem()" class="w-full py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-gray-400 font-bold text-[10px] uppercase hover:border-indigo-400 hover:text-indigo-500 transition flex justify-center items-center gap-2"><i class="fas fa-plus"></i> Agregar pago mixto</button>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-end mt-2">
                        <p class="text-xs text-gray-400 font-bold">Total Abonando</p>
                        <span class="text-3xl font-black text-indigo-600 tracking-tighter">$<span x-text="formatNumber(getTotalPaid())">0.00</span></span>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-gray-100 shrink-0">
                <button type="submit" form="paymentForm" :disabled="isLoading || getTotalPaid() <= 0" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-md transition transform text-sm flex justify-center items-center gap-2" :class="{'opacity-50 cursor-not-allowed': isLoading || getTotalPaid() <= 0, 'hover:bg-indigo-700 active:scale-95': !isLoading}">
                    <span x-show="!isLoading"><i class="fas fa-check-circle"></i> Procesar Pago</span>
                    <span x-show="isLoading">Procesando...</span>
                </button>
                <button type="button" @click="closeModal()" :disabled="isLoading" class="w-1/3 bg-white border border-gray-200 text-gray-700 font-bold py-3 rounded-xl hover:bg-gray-50 transition text-sm disabled:opacity-50">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function paymentLogic() {
        return {
            openPaymentModal: false,
            isLoading: false, // ¡VARIABLE IMPORTANTE AGREGADA!
            studentId: null,
            studentName: '',
            studentDebt: 0,
            
            exchangeRate: (function() {
                const source = document.getElementById('db-rate-source');
                if (source) {
                    let rate = source.getAttribute('data-rate');
                    return parseFloat(rate.replace(',', '.')) || 1;
                }
                return 1;
            })(),
            
            items: [],
            
            init() {
                this.items = [{ amount: '', method: '', reference: '' }];
            },

            openModal(data) {
                console.log('Abriendo modal...', data);
                this.isLoading = false;
                this.studentId = data.id;
                this.studentName = data.name;
                this.studentDebt = parseFloat(data.debt || 0);
                this.items = [{ amount: '', method: '', reference: '' }];
                this.openPaymentModal = true;
            },

            closeModal() {
                this.openPaymentModal = false;
            },

            addItem() {
                this.items.push({ amount: '', method: '', reference: '' });
            },

            removeItem(index) {
                if(this.items.length > 1) this.items.splice(index, 1);
            },

            isBs(method) {
                return ['Pago Movil', 'Transferencia', 'Efectivo Bs', 'Punto'].includes(method);
            },

            needsRef(method) {
                return ['Pago Movil', 'Transferencia'].includes(method);
            },

            getTotalPaid() {
                return this.items.reduce((total, item) => {
                    let amount = parseFloat(item.amount);
                    if (isNaN(amount) || amount < 0) return total;
                    if (this.isBs(item.method)) {
                        return total + (amount / this.exchangeRate);
                    }
                    return total + amount;
                }, 0);
            },

            formatNumber(num) {
                return (parseFloat(num) || 0).toFixed(2);
            }
        }
    }
</script>

@if(session('success'))

@endif