@foreach ($products as $orderProduct)
    @php $product = $orderProduct->productVariant?->product; @endphp
    <div style="padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
        <span style="font-weight: 700; font-size: 15px;">{{ $orderProduct->quantity }}x</span>
        <span style="font-size: 15px; margin-left: 6px;">{{ $product?->name ?? 'N/A' }}</span>
        <div style="margin-top: 2px; margin-left: 4px; font-size: 13px; color: #6b7280;">
            Talle {{ $orderProduct->productVariant?->size->name ?? 'N/A' }}
            &middot;
            <span style="display: inline-block; width: 11px; height: 11px; border-radius: 50%; vertical-align: middle; margin-right: 3px; border: 1px solid #d1d5db;"
                  @style(['background-color: '.$orderProduct->productVariant?->color->hex => $orderProduct->productVariant?->color->hex])></span>
            {{ $orderProduct->productVariant?->color->name ?? 'N/A' }}
        </div>
    </div>
@endforeach
