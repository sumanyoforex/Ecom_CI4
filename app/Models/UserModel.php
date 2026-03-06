<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel — handles all database operations for the users table.
 *
 * Stores customer accounts.
 * Admin accounts are stored in .env, NOT in this table.
 */
class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    // Only these fields can be inserted/updated (security whitelist)
    protected $allowedFields = ['name', 'email', 'password', 'role', 'remember_token'];

    // CI4 will auto-manage these timestamps
    protected $useTimestamps = true;

    // Hash the password automatically before saving
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /** Find a user by their email address */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
