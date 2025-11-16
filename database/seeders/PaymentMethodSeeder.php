<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'GCash',
                'type' => 'qr_code',
                'instructions' => 'Scan the QR code below to pay via GCash. Upload your receipt after payment.',
                'account_name' => 'Your Business Name',
                'account_number' => '09XX XXX XXXX',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'PayPal',
                'type' => 'email',
                'instructions' => 'Send payment to our PayPal email address. Include your name and room number in the notes.',
                'email' => 'payments@yourbusiness.com',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Wise',
                'type' => 'bank_account',
                'instructions' => 'Transfer funds to our Wise account. Use your name as reference.',
                'account_name' => 'Your Business Name',
                'account_number' => 'XX-XXXX-XXXX-XXXX',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}