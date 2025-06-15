<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    private function generateOrderCode($length = 4) {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = 'ORD-';

        for ($i = 0; $i < $length; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        if(Order::where("code", $code)->first()){
            $this->generateOrderCode();
        }

        return $code;
    }

    public function definition()
    {
        $orderDate = $this->faker->dateTimeBetween('2023-01-01', now());
        $expirationDate = $this->faker->dateTimeBetween($orderDate, '+3 days');
        return [
            'customer_id' => Customer::factory(),
            'order_date' => $orderDate,
            'total_amount' => $this->faker->randomFloat(2, 15000, 300000),
            'status' => $this->faker->randomElement(['Orden recibida', 'Pago fallido', 'Pago pendiente de aprobación', 'No pago', "En proceso", "Envío realizado"]),
            'code' => $this->generateOrderCode(),
//            'status' => $this->faker->randomElement(['completed']),
            'payment_method' => $this->faker->randomElement(['Mercado Pago', 'Transferencia', 'Efectivo']),
            'shipping_method' => $this->faker->randomElement(['Correo Argentino', 'Andreani', 'Retiro en CABA']),
            'shipping_address' => $this->faker->address(),
            'expiration_date' => $expirationDate,
            'preference_id' => $this->faker->creditCardNumber,
            'coupon_id' => \App\Models\Coupon::factory(),
            'created_at' => now(),
        ];
    }
}
