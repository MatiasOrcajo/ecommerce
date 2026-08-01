<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    private function generateOrderCode(int $length = 4): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (Order::where('code', $code)->exists());

        return $code;
    }

    public function definition()
    {
        $orderDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $expirationDate = (clone $orderDate)->modify('+3 days');
        $status = $this->faker->randomElement([
            'Pago recibido',
            'Pago recibido',
            'Pago recibido',
            'Pago fallido',
            'Pago pendiente de aprobación',
            'No pago',
            'En proceso',
            'Envío realizado',
        ]);

        $isCompleted = in_array($status, ['Pago recibido', 'Envío realizado']);

        return [
            'customer_id' => Customer::factory(),
            'order_date' => $orderDate,
            'total_amount' => $this->faker->randomFloat(2, 8000, 250000),
            'status' => $status,
            'code' => $this->generateOrderCode(),
            'payment_method' => $this->faker->randomElement(['Mercado Pago', 'Mercado Pago', 'Mercado Pago', 'Transferencia', 'Efectivo']),
            'shipping_method' => $this->faker->randomElement(['Correo Argentino', 'Correo Argentino', 'Andreani', 'Retiro en CABA']),
            'shipping_address' => $this->faker->streetAddress().', '.$this->faker->city(),
            'shipping_company' => $this->faker->optional(0.3)->company(),
            'shipping_cost' => $this->faker->optional(0.7)->randomFloat(2, 1000, 5000),
            'expiration_date' => $expirationDate,
            'preference_id' => $this->faker->optional(0.8)->uuid(),
            'coupon_id' => $this->faker->boolean(20) ? Coupon::factory() : null,
            'observations' => $this->faker->optional(0.3)->sentence(),
            'valid_for_arrives_today' => $this->faker->boolean(10),
            'email_sent' => $isCompleted ? $this->faker->boolean(80) : false,
            'ctx' => $this->faker->optional(0.5)->passthrough([
                'payment_id' => $this->faker->optional()->randomNumber(9),
                'payment_type' => $this->faker->optional()->randomElement(['credit_card', 'debit_card', 'account_money']),
                'installments' => $this->faker->optional()->numberBetween(1, 12),
            ]),
            'created_at' => $orderDate,
            'updated_at' => $this->faker->dateTimeBetween($orderDate, 'now'),
        ];
    }
}
