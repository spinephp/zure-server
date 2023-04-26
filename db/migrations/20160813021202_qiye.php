<?php

use Phinx\Migration\AbstractMigration;

class Qiye extends AbstractMigration
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
        $table = $this->table('qiye');
        $table->addColumn('name', 'string', array('limit'=>40))
            ->addColumn('name_en', 'string', array('limit'=>40))
            ->addColumn('domain', 'string', array('limit'=>30))
            ->addColumn('QQ', 'string', array('limit'=>10))
            ->addColumn('tel', 'string', array('limit'=>20))
            ->addColumn('fax', 'string', array('limit'=>20))
            ->addColumn('address', 'string', array('limit'=>20))
            ->addColumn('address_en', 'string', array('limit'=>60))
            ->addColumn('email', 'string', array('limit'=>20))
            ->addColumn('techid', 'integer')
            ->addColumn('busiid', 'integer')
            ->addColumn('introduction', 'string', array('limit'=>500))
            ->addColumn('introduction_en', 'string', array('limit'=>1000))
            ->addColumn('icp', 'string', array('limit'=>20))
            ->addColumn('exchangerate', 'float', array('default'=>6))
            ->addIndex(array('name'))
           ->create();
        $rows = array(
            array(
                'id'=>1,
                'name'=>'连云港云瑞耐火材料有限公司',
                'name_en'=>'LIANYUNGANG YUNRUI REFRACTORY CO,.LTD',
                'domain'=>'http://www.yrr8.com',
                'QQ'=>'2531841386',
                'tel'=>'+86 518 82340137',
                'fax'=>'+86 518 82340137',
                'address'=>'江苏连云港经济技术开发区泰山路12号',
                'address_en'=>'12 Taishan Road,Lianyungang Eco. &amp; Tech. Developme',
                'email'=>'admin@yrr8.com',
                'techid'=>1,
                'busiid'=>1,
                'introduction'=>'　　连云港云瑞耐火材料有限公司系生产碳化硅耐火制品的专业厂家，年生产能力2000吨。
　　公司全部生产设备和工艺技术均从英国引进，拥有国际先进水平的高效高强度混料机、大型冲击式压机、大型氮化穿梭窑及先进完备的物化检测设施。
　　公司主要产品为氮化硅结合碳化硅和氧化物结合碳化硅制品，现多种规格型号的产品畅销国内并远销国外多个国家，受到国内外用户的欢迎。
　　公司位于江苏连云港市，地理位置优越，水陆交通便利。竭诚欢迎新老客户光临指导，洽谈业务。',
                'introduction_en'=>'YunRui refractories co., LTD is a manufacturer specialized in the production of silicon carbide refractory products, annual production capacity of 2000 tons. 
Company all production equipment and technology are introduced from the UK, with the international advanced level of efficient high intensity mixer, large impact type compressor, large nitride shuttle kiln of the physical and chemical testing facilities and advanced and complete. 
Company main products are silicon nitride combined with silicon carbide and oxide combined with silicon carbide products, is now a variety of specifications of the product best-selling domestic and exported to foreign countries, was welcomed by customers at home and abroad. 
The company is located in lianyungang city, jiangsu province, the geographical position is superior, the amphibious transportation is convenient. We sincerely welcome new and old customers coming guidance, business negotiations. 
',
                'icp'=>'苏ICP备1201145号',
                'exchangerate'=>6.5
            )
        );
        $this->insert('qiye', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('qiye');
    }
}
