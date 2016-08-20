<?php

use Phinx\Migration\AbstractMigration;

class PhysicoIndex extends AbstractMigration
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
        $table = $this->table('physicoindex');
        $table->addColumn('name', 'char', array('limit'=>20))
            ->addColumn('name_en', 'char', array('limit'=>40))
            ->addColumn('unit', 'char', array('limit'=>20))
            ->addColumn('operator', 'char', array('limit'=>2))
            ->addColumn('environment', 'char', array('limit'=>10 ,'null'=>true,'default'=>NULL))
            ->addColumn('value', 'float')
            ->create();

        $rows = [
            array('id'=>1, 'name'=>'体积密度', 'name_en'=>'Bulk Density', 'unit'=>'g/cm3', 'operator'=>'>', 'environment'=>NULL, 'value'=>2.62),
            array('id'=>2, 'name'=>'显气孔率', 'name_en'=>'Apparent porosity', 'unit'=>'%', 'operator'=>'<', 'environment'=>NULL, 'value'=>16),
            array('id'=>3, 'name'=>'耐压强度', 'name_en'=>'Pressure resistant intensity', 'unit'=>'MPa', 'operator'=>'>', 'environment'=>'20℃', 'value'=>150),
            array('id'=>4, 'name'=>'抗折强度', 'name_en'=>'modulus of repture', 'unit'=>'MPa', 'operator'=>'>', 'environment'=>'20℃', 'value'=>40),
            array('id'=>5, 'name'=>'抗折强度', 'name_en'=>'modulus of repture', 'unit'=>'MPa', 'operator'=>'>', 'environment'=>'1400℃x0.5h', 'value'=>43),
            array('id'=>6, 'name'=>'热膨胀系数', 'name_en'=>'Coefficient of thermal liner expansion', 'unit'=>'×10-6/ ℃', 'operator'=>'<', 'environment'=>'1100℃', 'value'=>4.18),
            array('id'=>7, 'name'=>'导热系数', 'name_en'=>'Thermal  conductivity', 'unit'=>'w/m·k', 'operator'=>'>', 'environment'=>'350℃', 'value'=>16),
            array('id'=>8, 'name'=>'耐火度', 'name_en'=>'Refractoriness', 'unit'=>'℃', 'operator'=>'<', 'environment'=>NULL, 'value'=>1800),
            array('id'=>9, 'name'=>'荷重软化温度', 'name_en'=>'Loading softening point', 'unit'=>'℃', 'operator'=>'<', 'environment'=>'0.2MPa', 'value'=>1600),
            array('id'=>10, 'name'=>'最高使用温度', 'name_en'=>'Max working temperature', 'unit'=>'℃', 'operator'=>'<', 'environment'=>NULL, 'value'=>1550)
        ];

        $this->insert('physicoindex', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('physicoindex');
    }
}
