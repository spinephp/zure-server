<?php

use Phinx\Migration\AbstractMigration;

class PackingTypeTable extends AbstractMigration
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
    /**
     * Migrate Up.
     */
    public function up()
    {
       // create packingtype table
        $table = $this->table('packingtype');
        $table->addColumn('type',  'string', array('limit' => 20))
            ->create();

        $rows = [
            ['id'=>1,'type'=>'草绳'],
            ['id'=>2,'type'=>'打包带'],
            ['id'=>3,'type'=>'纸箱'],
            ['id'=>4,'type'=>'木托盘'],
            ['id'=>5,'type'=>'铁托盘'],
            ['id'=>6,'type'=>'铁托盘+纸箱']
        ];

        $this->insert('packingtype', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('packingtype');
    }
}
