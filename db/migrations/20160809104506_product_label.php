<?php

use Phinx\Migration\AbstractMigration;

class ProductLabel extends AbstractMigration
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
        $table = $this->table('productlabel');
        $table->addColumn('name', 'string', array('limit'=>20))
            ->addColumn('name_en', 'string', array('limit'=>20))
           ->create();

        $rows = [
            ['id'=>1,'name'=>'质量好','name_en'=>'Good quality'],
            ['id'=>2,'name'=>'外观好','name_en'=>'Good Appearance'],
            ['id'=>3,'name'=>'强度高','name_en'=>'High intensity'],
            ['id'=>4,'name'=>'寿命长','name_en'=>'Long Life'],
            ['id'=>5,'name'=>'包装好','name_en'=>'Packaging good'],
            ['id'=>6,'name'=>'性价比高','name_en'=>'Cost-effective'],
            ['id'=>7,'name'=>'颜色好','name_en'=>'Good color'],
            ['id'=>8,'name'=>'密度高','name_en'=>'High-density'],
        ];

        $this->insert('productlabel', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('productlabel');
    }
}
