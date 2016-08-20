<?php

use Phinx\Migration\AbstractMigration;

class ProductCare extends AbstractMigration
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
        $table = $this->table('productcare');
        $table->addColumn('userid', 'integer')
            ->addColumn('proid', 'integer')
            ->addColumn('currencyid', 'integer')
            ->addColumn('exchangerate', 'float')
            ->addColumn('price', 'float')
            ->addColumn('date', 'datetime', array('default' => '1900-01-01 00:00:00'))
            ->addColumn('label', 'string', array('limit'=>40, 'null'=>true,'default'=>NULL))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('productcare');
    }
}
