<?php

use Phinx\Migration\AbstractMigration;

class Person extends AbstractMigration
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
        $table = $this->table('person');
        $table->addColumn('username', 'string', array('limit'=>20))
            ->addColumn('pwd', 'char', array('limit'=>47))
            ->addColumn('email', 'string', array('limit'=>50))
            ->addColumn('active', 'enum', array('values'=>['Y','N']))
            ->addColumn('companyid', 'integer')
            ->addColumn('name', 'string', array('limit'=>40))
            ->addColumn('nick', 'string', array('limit'=>16))
            ->addColumn('sex', 'enum', array('values'=>['M','F']))
            ->addColumn('country', 'integer', array('signed'=>false, 'limit'=>4))
            ->addColumn('county', 'char', array('limit'=>6))
            ->addColumn('address', 'string', array('limit'=>40))
            ->addColumn('mobile', 'string', array('limit'=>18))
            ->addColumn('tel', 'string', array('limit'=>18))
            ->addColumn('qq', 'string', array('limit'=>50))
            ->addColumn('identitycard', 'char', array('limit'=>18))
            ->addColumn('picture', 'char', array('limit'=>36))
            ->addColumn('registertime', 'datetime')
            ->addColumn('lasttime', 'datetime')
            ->addColumn('times', 'integer')
            ->addColumn('hash', 'char', array('limit'=>32))
            ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('person');
    }
}
