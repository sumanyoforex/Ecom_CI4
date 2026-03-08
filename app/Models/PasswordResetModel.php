<?php

namespace App\Models;

use CodeIgniter\Model;

class PasswordResetModel extends Model
{
    protected $table = 'password_resets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'email', 'token_hash', 'expires_at', 'used_at', 'created_at'];
    protected $useTimestamps = false;

    public function findValidByToken(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $now = date('Y-m-d H:i:s');

        return $this->where('token_hash', $hash)
            ->where('used_at', null)
            ->where('expires_at >=', $now)
            ->first();
    }

    public function markUsed(int $id): void
    {
        $this->update($id, ['used_at' => date('Y-m-d H:i:s')]);
    }
}
