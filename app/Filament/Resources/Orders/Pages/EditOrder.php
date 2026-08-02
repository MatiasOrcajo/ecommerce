<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public function getMaxContentWidth(): Width
    {
        return Width::None;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function getRecord(): Order
    {
        return Order::with([
            'products.productVariant.product',
            'products.productVariant.pictures',
            'customer',
            'coupon',
        ])->findOrFail($this->record->getKey());
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['is_packaged'] && ! $this->record->packaged_at) {
            $data['packaged_at'] = now();
        }

        if (! $data['is_packaged']) {
            $data['packaged_at'] = null;
        }

        return $data;
    }
}
