<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Stores one-time password reset tokens for customer accounts.
 */
class CreatePasswordResetsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id' => ['type' => 'INTEGER'],
            'email' => ['type' => 'VARCHAR', 'constraint' => 190],
            'token_hash' => ['type' => 'VARCHAR', 'constraint' => 64],
            'expires_at' => ['type' => 'DATETIME'],
            'used_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('password_resets');

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_password_resets_user ON password_resets(user_id)');
        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_password_resets_token_hash ON password_resets(token_hash)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_password_resets_token_hash');
        $this->db->query('DROP INDEX IF EXISTS idx_password_resets_user');
        $this->forge->dropTable('password_resets');
    }
}
