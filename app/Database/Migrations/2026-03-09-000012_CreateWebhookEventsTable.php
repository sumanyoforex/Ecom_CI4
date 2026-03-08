<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Keeps webhook event IDs for idempotent processing.
 */
class CreateWebhookEventsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'INTEGER', 'auto_increment' => true],
            'provider' => ['type' => 'VARCHAR', 'constraint' => 40],
            'event_id' => ['type' => 'VARCHAR', 'constraint' => 120],
            'event_type' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'payload' => ['type' => 'TEXT', 'null' => true],
            'processed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('webhook_events');

        $this->db->query('CREATE UNIQUE INDEX IF NOT EXISTS idx_webhook_provider_event ON webhook_events(provider, event_id)');
    }

    public function down(): void
    {
        $this->db->query('DROP INDEX IF EXISTS idx_webhook_provider_event');
        $this->forge->dropTable('webhook_events');
    }
}
