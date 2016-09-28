<?php

use Phinx\Migration\AbstractMigration;

class WereHouse extends AbstractMigration
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
        $table = $this->table('werehouse');
        $table->addColumn('typeid',  'integer')
            ->addColumn('name', 'string', array('limit'=>50))
            ->addColumn('size', 'string', array('limit'=>12))
            ->addColumn('unit', 'string', array('limit'=>10))
            ->addColumn('number', 'float', array('default'=>0))
            ->addColumn('picture', 'char', array('limit'=>36,'default'=>''))
            ->addColumn('note', 'string', array('limit'=>50,'default'=>''))
           ->create();

        $rows = array(
            array('id'=>1, 'typeid'=>1, 'name'=>'碳化硅', 'size'=>'7/12', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>2, 'typeid'=>1, 'name'=>'碳化硅', 'size'=>'14/30', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>3, 'typeid'=>1, 'name'=>'碳化硅', 'size'=>'36/70', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>4, 'typeid'=>1, 'name'=>'碳化硅', 'size'=>'80F', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>5, 'typeid'=>1, 'name'=>'碳化硅', 'size'=>'200F', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>6, 'typeid'=>1, 'name'=>'金属硅', 'size'=>'350F', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>7, 'typeid'=>1, 'name'=>'硅灰', 'size'=>'95%', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>8, 'typeid'=>1, 'name'=>'粘土', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>9, 'typeid'=>1, 'name'=>'钢玉', 'size'=>'3-1', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>10, 'typeid'=>1, 'name'=>'钢玉', 'size'=>'1-0', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>11, 'typeid'=>1, 'name'=>'钢玉', 'size'=>'200F', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>12, 'typeid'=>1, 'name'=>'锻烧氧化铝', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>13, 'typeid'=>1, 'name'=>'球土', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>14, 'typeid'=>1, 'name'=>'膨润土', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>15, 'typeid'=>1, 'name'=>'锻烧莫来石', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>16, 'typeid'=>1, 'name'=>'锻烧莫来石', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>17, 'typeid'=>1, 'name'=>'电熔莫来石', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>18, 'typeid'=>1, 'name'=>'电熔莫来石', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>19, 'typeid'=>1, 'name'=>'糊精', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>20, 'typeid'=>1, 'name'=>'木钙', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>21, 'typeid'=>2, 'name'=>'液化气', 'size'=>'', 'unit'=>'瓶', 'number'=>7, 'note'=>''),
            array('id'=>22, 'typeid'=>2, 'name'=>'液氮', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>23, 'typeid'=>2, 'name'=>'烟煤', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>24, 'typeid'=>1, 'name'=>'耐火孰料', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>25, 'typeid'=>1, 'name'=>'水玻璃', 'size'=>'', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>26, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'24x24x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>27, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'18x24x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>28, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'18x18x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>29, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'16x18x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>30, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'16x16x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>31, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'12x24x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>32, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'13x26x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>33, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'14x28x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>34, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'11x28x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>35, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'21x10.5x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>36, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'22x11x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>37, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'21x21x100', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>38, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'22x22', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>39, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>40, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>41, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>42, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>43, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>44, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>45, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>46, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>47, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>48, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>49, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>50, 'typeid'=>3, 'name'=>'纸箱', 'size'=>'', 'unit'=>'个', 'number'=>0, 'note'=>''),
            array('id'=>51, 'typeid'=>3, 'name'=>'槽钢', 'size'=>'#6', 'unit'=>'m', 'number'=>6, 'note'=>''),
            array('id'=>52, 'typeid'=>3, 'name'=>'槽钢', 'size'=>'#8', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>53, 'typeid'=>3, 'name'=>'角钢', 'size'=>'#60', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>54, 'typeid'=>3, 'name'=>'角钢', 'size'=>'#40', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>55, 'typeid'=>3, 'name'=>'角钢', 'size'=>'#25', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>56, 'typeid'=>3, 'name'=>'角钢', 'size'=>'', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>57, 'typeid'=>3, 'name'=>'带铁', 'size'=>'#20', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>58, 'typeid'=>3, 'name'=>'带铁', 'size'=>'#40', 'unit'=>'m', 'number'=>0, 'note'=>''),
            array('id'=>59, 'typeid'=>3, 'name'=>'塑料布', 'size'=>'4m', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>60, 'typeid'=>3, 'name'=>'三合板', 'size'=>'2440x1220x1.', 'unit'=>'张', 'number'=>0, 'note'=>''),
            array('id'=>61, 'typeid'=>3, 'name'=>'塑钢打包带', 'size'=>'16x0.8', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>62, 'typeid'=>3, 'name'=>'打包扣', 'size'=>'16x0.8', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>63, 'typeid'=>3, 'name'=>'高密度板', 'size'=>'2440x1220x10', 'unit'=>'张', 'number'=>0, 'note'=>''),
            array('id'=>64, 'typeid'=>3, 'name'=>'包装溥膜', 'size'=>'600X0.5', 'unit'=>'kg', 'number'=>0, 'note'=>''),
            array('id'=>65, 'typeid'=>3, 'name'=>'角纸', 'size'=>'50x50x6000', 'unit'=>'根', 'number'=>0, 'note'=>''),
            array('id'=>66, 'typeid'=>3, 'name'=>'透明胶带', 'size'=>'42x20', 'unit'=>'盘', 'number'=>0, 'note'=>'')
        );
        $this->insert('werehouse', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('werehouse');
    }
}
