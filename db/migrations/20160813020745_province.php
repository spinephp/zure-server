<?php
require_once('db/migrations/ProvinceCode.php');

use Phinx\Migration\AbstractMigration;

class Province extends AbstractMigration
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
        $table = $this->table('province');
        $table->addColumn('name', 'char', array('limit'=>21))
            ->create();

        $this->insert('province', \provinceCode::getCode());
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('province');
    }
}
