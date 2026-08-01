<x-filament-panels::page @class(['fi-resource-edit-record-page'])>
    <form wire:submit="save" class="space-y-6">
        {{-- Hidden Filament form for validation & processing --}}
        <div class="hidden">{{ $this->form }}</div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Customer card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <x-filament::icon icon="heroicon-o-user" class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Cliente</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @php $customer = $this->getRecord()->customer; @endphp
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Nombre completo</span>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $customer->name }} {{ $customer->surname }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Email</span>
                            <a href="mailto:{{ $customer->email }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">{{ $customer->email }}</a>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Telefono</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $customer->phone ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <x-filament::icon icon="heroicon-o-cog-6-tooth" class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Estado del Pedido</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Estado</label>
                            <select id="status" wire:model="data.status"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm py-2.5">
                                @foreach([
                                    'Pago recibido', 'Pago fallido', 'Pago pendiente de aprobacion',
                                    'No pago', 'En proceso', 'Envio realizado',
                                    'Esperando que el cliente retire', 'Retiro realizado', 'Expirado',
                                ] as $statusOption)
                                    <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Observaciones</label>
                            <textarea id="observations" wire:model="data.observations" rows="3" maxlength="500"
                                      class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"
                                      placeholder="Notas internas sobre el pedido..."></textarea>
                            <p class="text-xs text-gray-400 dark:text-gray-500 text-right mt-1"><span x-data x-text="$wire.data.observations?.length ?? 0">0</span>/500</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" wire:model="data.is_packaged" value="0">
                                <input type="checkbox" wire:model="data.is_packaged" value="1"
                                       class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                            </label>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Empaquetado</span>
                        </div>
                        @php $packagedAt = $this->getRecord()->packaged_at; @endphp
                        @if($packagedAt)
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($packagedAt)->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i') }}
                            </p>
                        @endif

                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <x-filament::icon icon="heroicon-o-check" class="w-4 h-4" />
                                Guardar
                            </button>
                            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order details card --}}
                @php $record = $this->getRecord(); @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <x-filament::icon icon="heroicon-o-document-text" class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Detalles del Pedido</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Codigo</span>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $record->code }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Total</span>
                                <p class="text-sm font-semibold text-lg text-gray-900 dark:text-white">${{ number_format($record->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Fecha</span>
                                <p class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($record->created_at)->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Metodo de pago</span>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $record->payment_method ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Modo de envio</span>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $record->shipping_method ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Costo de envio</span>
                                <p class="text-sm text-gray-900 dark:text-white">${{ $record->shipping_cost ? number_format($record->shipping_cost, 0, ',', '.') : '0' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Vence</span>
                                <p class="text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($record->expiration_date)->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Preference ID</span>
                                <p class="text-sm text-gray-900 dark:text-white font-mono">{{ $record->preference_id ?? '—' }}</p>
                            </div>
                            @if($record->coupon)
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Cupon</span>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $record->coupon->code }}</p>
                            </div>
                            @endif
                            <div class="sm:col-span-2 lg:col-span-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Direccion de envio</span>
                                <p class="text-sm text-gray-900 dark:text-white">{{ $record->shipping_address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products table card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <x-filament::icon icon="heroicon-o-shopping-bag" class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">Productos del Pedido</h3>
                        <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                            {{ $record->products->sum('quantity') }} items
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30">
                                    <th class="py-3 px-6 w-16"></th>
                                    <th class="text-start py-3 px-4">Producto</th>
                                    <th class="text-center py-3 px-4">Talle</th>
                                    <th class="text-center py-3 px-4">Cant.</th>
                                    <th class="text-right py-3 px-4 whitespace-nowrap">Precio Unit.</th>
                                    <th class="text-right py-3 px-4 whitespace-nowrap">Descuento</th>
                                    <th class="text-right py-3 px-6 whitespace-nowrap">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($record->products as $item)
                                    @php
                                        $variant = $item->productVariant;
                                        $product = $variant->product ?? null;
                                        $image = $variant->pictures->first()?->path
                                            ?? $variant->findFirstSimilarVariantWithPicture()
                                            ?? null;
                                    @endphp
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td class="py-3 px-6">
                                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                                @if ($image)
                                                    <img src="{{ $image }}" alt="{{ $product->name ?? '' }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <x-filament::icon icon="heroicon-o-photo" class="w-4 h-4" />
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-start">
                                            <p class="font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $product->name ?? 'Producto eliminado' }}</p>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <span class="inline-block w-3 h-3 rounded-full border border-gray-300 dark:border-gray-600 flex-shrink-0" style="background: {{ $variant->color }}"></span>
                                                <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $variant->color_name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-medium">
                                                {{ $variant->size }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center font-medium text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap text-gray-900 dark:text-white">${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-right whitespace-nowrap">
                                            @if ($item->discount > 0)
                                                <span class="text-danger-600 dark:text-danger-400">-${{ number_format($item->discount, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-right font-semibold text-gray-900 dark:text-white whitespace-nowrap">${{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                                    <td colspan="6" class="py-3 px-6 text-right font-bold text-gray-900 dark:text-white">Total del pedido</td>
                                    <td class="py-3 px-6 text-right font-bold text-base text-gray-900 dark:text-white whitespace-nowrap">${{ number_format($record->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-filament-panels::page>
