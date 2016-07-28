<?php

use Phinx\Migration\AbstractMigration;

class Language extends AbstractMigration
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
        $table = $this->table('language');
        $table->addColumn('name',  'string', array('limit' => 20))
            ->addColumn('name_en',  'string', array('limit' => 20))
            ->create();

        $rows = [
            ['id'=>1,'name'=>'英语','name_en'=>'english'],
            ['id'=>2,'name'=>'汉语','name_en'=>'chinese'],
        ];

        $this->insert('language', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('language');
    }
}
