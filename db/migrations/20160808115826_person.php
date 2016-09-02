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
            ->addColumn('active', 'enum', array('values'=>['Y','N'] ,'default'=>'N'))
            ->addColumn('companyid', 'integer', array('null'=>true,'default'=>NULL))
            ->addColumn('name', 'string', array('limit'=>40 ,'null'=>true,'default'=>NULL))
            ->addColumn('nick', 'string', array('limit'=>16 ,'null'=>true,'default'=>NULL))
            ->addColumn('sex', 'enum', array('values'=>['M','F'] ,'default'=>'M'))
            ->addColumn('country', 'integer', array('signed'=>false, 'limit'=>4, 'default'=>48))
            ->addColumn('county', 'char', array('limit'=>6 ,'null'=>true,'default'=>NULL))
            ->addColumn('address', 'string', array('limit'=>40 ,'null'=>true,'default'=>NULL))
            ->addColumn('mobile', 'string', array('limit'=>18 ,'null'=>true,'default'=>NULL))
            ->addColumn('tel', 'string', array('limit'=>18 ,'null'=>true,'default'=>NULL))
            ->addColumn('qq', 'char', array('limit'=>15 ,'null'=>true,'default'=>NULL))
            ->addColumn('identitycard', 'char', array('limit'=>18 ,'null'=>true,'default'=>NULL))
            ->addColumn('picture', 'char', array('limit'=>36 ,'null'=>true,'default'=>NULL))
            ->addColumn('registertime', 'datetime', array('default' => '1900-01-01 00:00:00'))
            ->addColumn('lasttime', 'datetime')
            ->addColumn('times', 'integer')
            ->addColumn('hash', 'char', array('limit'=>32 ,'null'=>true,'default'=>NULL))
            ->addIndex(array('hash'))
            ->create();
        $rows = array(
            array(
                'id'=>1,
                'username'=>'liuxingming',
                'pwd'=>'90327fa281fee18e3a9acf80f2189b5b880dc73562d2496',
                'email'=>'lxm@gmail.com',
                'active'=>'Y',
                'companyid'=>0,
                'name'=>'liuxingming',
                'nick'=>NULL,
                'sex'=>'M',
                'country'=>48,
                'county'=>320705,
                'address'=>'xhhyc6_2_301',
                'mobile'=>'13961370715',
                'tel'=>'0518 85466356',
                'qq'=>'1619584123',
                'identitycard'=>'320705196206150533',
                'picture'=>'u00000003.png',
                'registertime'=>'2013-06-27 00:00:00',
                'lasttime'=>'2016-03-27 10:48:22',
                'times'=>1,
                'hash'=>NULL
            )
        );
        $this->insert('person', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('person');
    }
}
