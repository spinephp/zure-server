<?php

use Phinx\Migration\AbstractMigration;

class Product extends AbstractMigration
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
        $table = $this->table('product');
        $table->addColumn('classid', 'integer')
            ->addColumn('length', 'float')
            ->addColumn('width', 'float')
            ->addColumn('think', 'float')
            ->addColumn('unitlen', 'enum', array('values'=>['mm', '"']))
            ->addColumn('unitwid', 'enum', array('values'=>['mm', '"']))
            ->addColumn('unitthi', 'enum', array('values'=>['mm', '"']))
            ->addColumn('picture', 'char', array('limit'=>36))
            ->addColumn('sharp', 'integer', array('limit'=>4))
            ->addColumn('unit', 'char', array('limit'=>4))
            ->addColumn('weight', 'float')
            ->addColumn('homeshow', 'enum', array('values'=>['Y', 'N']))
            ->addColumn('price', 'float')
            ->addColumn('returnnow', 'float')
            ->addColumn('evalintegral', 'integer', array('limit'=>4))
            ->addColumn('feelintegral', 'integer', array('limit'=>4))
            ->addColumn('amount', 'integer', array('limit'=>4))
            ->addColumn('cansale', 'enum', array('values'=>['Y', 'N']))
            ->addColumn('physicoindex', 'integer', array('limit'=>4))
            ->addColumn('chemicalindex', 'integer', array('limit'=>4))
            ->addColumn('status', 'enum', array('values'=>['O', 'D', 'P', 'N']))
            ->addColumn('note', 'text')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('product');
    }
}
