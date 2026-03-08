<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'order_id',
        'provider',
        'provider_payment_id',
        'amount',
        'currency',
        'status',
        'payment_url',
        'raw_payload',
    ];
    protected $useTimestamps = true;
}
