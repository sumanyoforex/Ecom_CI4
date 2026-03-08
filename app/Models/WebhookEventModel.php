<?php

namespace App\Models;

use CodeIgniter\Model;

class WebhookEventModel extends Model
{
    protected $table = 'webhook_events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['provider', 'event_id', 'event_type', 'payload', 'processed_at', 'created_at'];
    protected $useTimestamps = false;
}
