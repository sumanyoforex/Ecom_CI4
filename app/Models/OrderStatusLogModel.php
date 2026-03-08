<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderStatusLogModel extends Model
{
    protected $table = 'order_status_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['order_id', 'from_status', 'to_status', 'note', 'changed_by', 'created_at'];
    protected $useTimestamps = false;
}
