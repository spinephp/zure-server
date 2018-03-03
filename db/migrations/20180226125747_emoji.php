<?php

use Phinx\Migration\AbstractMigration;

class Emoji extends AbstractMigration
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
        $table = $this->table('emoji');
        $table->addColumn('name', 'string', array('limit'=>20))
            ->addColumn('name_en', 'string', array('limit'=>40 ,'null'=>true,'default'=>NULL))
            ->addColumn('introduction', 'string', array('limit'=>200 ,'null'=>true,'default'=>NULL))
            ->addColumn('introduction_en', 'string', array('limit'=>300 ,'null'=>true,'default'=>NULL))
            ->addColumn('stand', 'string', array('limit'=>200 ,'null'=>true,'default'=>NULL))
            ->addColumn('stand_en', 'string', array('limit'=>200 ,'null'=>true,'default'=>NULL))
            ->addColumn('alias', 'string', array('limit'=>100))
            ->addColumn('alias_en', 'string', array('limit'=>100))
            ->addColumn('code', 'string', array('limit'=>8 ,'null'=>true,'default'=>NULL))
            ->addColumn('shortcode', 'string', array('limit'=>18 ,'null'=>true,'default'=>NULL))
            ->addColumn('related', 'string', array('limit'=>180 ,'null'=>true,'default'=>NULL))
             ->create();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('emoji');
    }
}
