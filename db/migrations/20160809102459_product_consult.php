<?php

use Phinx\Migration\AbstractMigration;

class ProductConsult extends AbstractMigration
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
        $table = $this->table('productconsult');
        $table->addColumn('userid', 'integer')
            ->addColumn('proid', 'integer')
            ->addColumn('type', 'integer', array('limit'=>4))
            ->addColumn('content', 'string', array('limit'=>100))
            ->addColumn('time', 'datetime', array('default' => '1900-01-01 00:00:00'))
            ->addColumn('reply', 'string', array('limit'=>100, 'null'=>true, 'default'=>NULL))
            ->addColumn('replytime', 'datetime', array('default' => '1900-01-01 00:00:00', 'null'=>true))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('productconsult');
    }
}
