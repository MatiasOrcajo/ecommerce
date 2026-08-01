<div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm divide-y divide-gray-200 dark:divide-white/10">
            <thead class="bg-gray-50/50 dark:bg-white/5">
            <tr>
                <th class="px-4 py-3 font-medium text-gray-500 dark:text-gray-400 whitespace-nowrap">Producto</th>
                <th class="px-4 py-3 font-medium text-center text-gray-500 dark:text-gray-400 whitespace-nowrap">Talle</th>
                <th class="px-4 py-3 font-medium text-center text-gray-500 dark:text-gray-400 whitespace-nowrap">Cant.</th>
                <th class="px-4 py-3 font-medium text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">Precio Unit.</th>
                <th class="px-4 py-3 font-medium text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">Descuento</th>
                <th class="px-4 py-3 font-medium text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">Subtotal</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
            @foreach ($products as $item)
                @php
                    $variant = $item->productVariant;
                    $product = $variant->product ?? null;
                    $image = $variant->pictures->first()?->path
                        ?? $variant->findFirstSimilarVariantWithPicture()
                        ?? null;
                @endphp
                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex-shrink-0 border border-gray-200 dark:border-white/10 overflow-hidden flex items-center justify-center">
                                @if ($image)
                                    <img src="{{ $image }}" alt="{{ $product->name ?? '' }}" class="w-full h-full object-cover">
                                @else
                                    <x-filament::icon icon="heroicon-o-photo" class="w-5 h-5 text-gray-400 dark:text-gray-500" />
                                @endif
                            </div>
                            <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-950 dark:text-white max-w-[280px] truncate">
                                        {{ $product->name ?? 'Producto eliminado' }}
                                    </span>
                                <div class="flex items-center gap-1.5 mt-1">
                                        <span class="w-2.5 h-2.5 rounded-full ring-1 ring-gray-950/10 dark:ring-white/20 flex-shrink-0"
                                              style="background-color: {{ $variant->color }}"></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $variant->color_name }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 dark:bg-white/5 dark:text-gray-300 dark:ring-white/10">
                                {{ $variant->size }}
                            </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-medium text-gray-950 dark:text-white">{{ $item->quantity }}</span>
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap text-gray-700 dark:text-gray-300">
                        ${{ number_format($item->unit_price, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        @if ($item->discount > 0)
                            <span class="inline-flex items-center rounded-md bg-danger-50 px-2 py-1 text-xs font-medium text-danger-700 ring-1 ring-inset ring-danger-600/10 dark:bg-danger-400/10 dark:text-danger-400 dark:ring-danger-400/20">
                                    -${{ number_format($item->discount, 0, ',', '.') }}
                                </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-semibold whitespace-nowrap text-gray-950 dark:text-white">
                        ${{ number_format($item->total, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="bg-gray-50/50 dark:bg-white/5 border-t-2 border-gray-200 dark:border-white/10">
            <tr>
                <td colspan="5" class="px-4 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                    Total del pedido
                </td>
                <td class="px-4 py-4 text-right text-base font-bold text-gray-950 dark:text-white whitespace-nowrap">
                    ${{ number_format($totalAmount, 0, ',', '.') }}
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
