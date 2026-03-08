<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tracks outgoing notifications and delivery status.
 */
class CreateNotificationLogsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'user_id' => ['type' => 'INTEGER', 'null' => true],
            'channel' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'email'],
            'subject' => ['type' => 'VARCHAR', 'constraint' => 190],
            'recipient' => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'body' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'queued'],
            'error_message' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'sent_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('notification_logs');

        $this->db->query('CREATE INDEX IF NOT EXISTS idx_notification_user_status ON notification_logs(user_id, status)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_notification_user_status');
        $this->forge->dropTable('notification_logs');
    }
}
