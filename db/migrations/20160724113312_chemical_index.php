<?php

use Phinx\Migration\AbstractMigration;

class ChemicalIndex extends AbstractMigration
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
        $table = $this->table('chemicalindex');
        $table->addColumn('sic','float')
            ->addColumn('si3n4','float')
            ->addColumn('sio2','float')
            ->addColumn('si','float')
            ->addColumn('fe2o3','float')
            ->addColumn('cao','float')
            ->addColumn('al2o3','float')
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('chemicalindex');
    }
}
