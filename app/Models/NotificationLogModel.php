<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationLogModel extends Model
{
    protected $table = 'notification_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'channel', 'subject', 'recipient', 'body', 'status', 'error_message', 'created_at', 'sent_at'];
    protected $useTimestamps = false;
}
