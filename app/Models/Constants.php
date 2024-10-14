<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
    use HasFactory;

    /**
     * CARTS
     */

    const EMPTY             = "EMPTY";
    const EMPTY_BY_CUSTOMER = "EMPTY BY CUSTOMER";
    const ACTIVE            = "ACTIVE";
    const SAVED             = "SAVED";
    const PENDING           = "PENDING";
    const FAILED            = "PENDING";
    const CONFIRMED         = "CONFIRMED";
    const ABANDONED         = "ABANDONED";

    const MENU_STATUS = [
        self::EMPTY             => 'El carrito no tiene productos',
        self::EMPTY_BY_CUSTOMER => 'El carrito fue vaciado por completo por el cliente',
        self::ACTIVE            => 'El cliente ha agregado productos, pero aún no ha iniciado el proceso de compra',
        self::SAVED             => 'El cliente guarda el carrito para continuar comprando más tarde, sin iniciar el pago.',
        self::PENDING           => 'El cliente ha iniciado el proceso de pago, pero no ha completado el pago.',
        self::FAILED            => 'Hubo un error o rechazo en el intento de pago, y el carrito permanece en espera de otra acción del cliente.',
        self::CONFIRMED         => 'El carrito se convierte en un pedido confirmado tras el éxito del pago.',
        self::ABANDONED         => 'El cliente no ha completado el proceso de pago en un tiempo determinado, y el carrito se considera abandonado.',

    ];

}
