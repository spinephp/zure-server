<?php

use Phinx\Migration\AbstractMigration;

class News extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
    public function change()
    {

    }
     */

    public function up()
    {
       // create order packing tray carton table
        $table = $this->table('news');
        $table->addColumn('title',  'string', array('limit' => 200))
            ->addColumn('title_en',  'string', array('limit' => 200))
            ->addColumn('content',  'text')
            ->addColumn('content_en',  'text')
            ->addColumn('time',  'date', array('default'=>'1900-01-01 00:00:00'))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('news');
    }
}
