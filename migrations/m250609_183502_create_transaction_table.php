
<?php
use yii\db\Migration;

class m250609_183502_create_transaction_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'service' => $this->string()->notNull(), // M-Pesa, Halopesa, etc.
            'type' => $this->string()->notNull(), // Received or Sent
            'amount' => $this->decimal(10, 2)->notNull(),
            'transaction_date' => $this->dateTime()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'FOREIGN KEY (user_id) REFERENCES {{%user}} (id) ON DELETE CASCADE',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%transaction}}');
    }
}