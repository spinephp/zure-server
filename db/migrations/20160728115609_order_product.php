<?php

use Phinx\Migration\AbstractMigration;

class OrderProduct extends AbstractMigration
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
        $table = $this->table('orderproduct');
        $table->addColumn('orderid',  'integer')
            ->addColumn('proid',  'integer')
            ->addColumn('number',  'float')
            ->addColumn('price',  'float')
            ->addColumn('returnnow',  'float')
            ->addColumn('modlcharge',  'float')
            ->addColumn('moldingnumber',  'integer', array('limit' => 4))
            ->addColumn('drynumber',  'integer', array('limit' => 4))
            ->addColumn('firingnumber',  'integer', array('limit' => 4))
            ->addColumn('packagenumber',  'integer', array('limit' => 4))
            ->addColumn('evalid', 'integer', array('signed'=>false ,'default'=>0))
            ->addColumn('feelid', 'integer', array('signed'=>false ,'default'=>0))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('orderproduct');
    }
}
