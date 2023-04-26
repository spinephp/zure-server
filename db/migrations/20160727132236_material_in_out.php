<?php

use Phinx\Migration\AbstractMigration;

class MaterialInOut extends AbstractMigration
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
        $table = $this->table('materialinout');
        $table->addColumn('materialid',  'integer')
            ->addColumn('number',  'float')
            ->addColumn('operatorid',  'integer')
            ->addColumn('authorizerid',  'integer', array('default' => 0))
            ->addColumn('operatortime',  'datetime')
            ->addColumn('authorizertime',  'datetime', array('null' => true))
            ->addColumn('note',  'string', array('limit' => 100,'null' => true))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('materialinout');
    }
}
