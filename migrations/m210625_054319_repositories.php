<?php

use yii\db\Migration;

/**
 * Class m210625_054319_repositories
 */
class m210625_054319_repositories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('repositories', [
            'id' => $this->primaryKey(),
            'html_url' => $this->string(),
            'name' => $this->string(),
            'updated_at' => $this->string(),
            'full_name' => $this->string(),
            'owner' => $this->string(),
            'description' => $this->text(),
            'id_rep' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210625_054319_repositories cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210625_054319_repositories cannot be reverted.\n";

        return false;
    }
    */
}
