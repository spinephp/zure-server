<?php

use Phinx\Migration\AbstractMigration;

class ProductClass extends AbstractMigration
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
        $table = $this->table('productclass');
        $table->addColumn('parentid', 'integer')
            ->addColumn('name', 'string', array('limit'=>60))
            ->addColumn('name_en', 'string', array('limit'=>60))
            ->addColumn('introduction', 'text')
            ->addColumn('introduction_en', 'text')
            ->addColumn('picture', 'string', array('limit'=>40))
            ->addColumn('homeshow', 'enum', array('values'=>['Y', 'N'],'default'=>'N'))
            ->create();

        $rows = array(
            array('id'=>1, 'parentid'=>0, 'name'=>'氮化硅结合碳化硅制品', 'name_en'=>'Si3N4 bonded SiC Products', 'introduction'=>'碳化硅是人工合成的高级无机非金属材料，由共价健结合，硬度接近于金刚石，熔点2600℃，真密度3.21g/cm3。热导率达8.37W/m&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;middot;k；氮化硅熔点1900℃，莫氏硬度9，真密度3.19 g/cm3，热导率15W/m&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;middot;k ，氮化硅结合碳化硅是结合双方性能研制的一种新型高性能的耐火制品，化学性能稳定，常温抗折强度是普通耐火材料的4~8倍，是传统碳化硅制品（粘土结合碳化硅）的2~3倍，导热系数是一般耐火材料的7~8倍，热膨胀系数是一般耐火材料的一半。实践证明，氮化硅结合碳化硅制品有较好的热震稳定性。抗高温蠕变、抗酸性、抗碱性、抗氧化性能优异，抗铝（Al ）、铅（Pb ）、锡（Sn ）、锌（Zn ）、铜（Cu ）等熔融金属侵蚀，电绝缘性良好，常温比电阻高。用氮化硅结合碳化硅代替传统的碳化硅制品，可减轻窑具重量50%，提高炉池装载率16%，节能15%以上，是企业提高产品质量，增加经济效益的理想耐火材料。rn   氮化硅产品以其优良的性能及较高的性能价格比在有色冶炼行业中已体现出卓越作用, 目前国外有色行业都广泛采用氮化硅结合碳化硅制品，无论是铝行业的电解槽、熔化炉， 铜行业的鼓风炉、熔化炉，还是锌铅行业的竖炉、精熘炉等等,氮化硅都是目前最好的耐火材料。rn   氮化硅结合碳化硅制品，已被广泛用来制造炉体测温用的热电偶保护管、吹氧气和氮气的吹气管，放液口管子及压力浇注用铸模等。其在工业节能等方面的用途也在不断扩大。', 'introduction_en'=>'Silicon carbide is a senior inorganic non-metallic materials, synthetic by covalence, close to the diamond hardness, melting point 2600 ℃, true density of 3.21 g/cm3. Thermal conductivity of 8.37 W/m&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;middot; K; Silicon nitride melting point 1900 ℃, mohs hardness 9, true density of 3.19 g/cm3, thermal conductivity of 15 w/m&amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;amp;middot; K, silicon nitride combined with silicon carbide is based on the performance of a new type of high performance refractory products, stable chemical performance, room temperature flexural strength is 4 ~ 8 times of ordinary refractory material, is a traditional silicon carbide products combined with silicon carbide (clay) 2 ~ 3 times, coefficient of thermal conductivity is generally 7 ~ 8 times of refractory, thermal expansion coefficient is generally half of the refractory material. Practice has proved that silicon nitride combined with silicon carbide products have better thermal shock resistance. High temperature creep resistance, acid resistance, alkali resistance, excellent oxidation resistance, resistance to aluminum (Al), lead (Pb), tin (Sn), zinc (zinc), copper (Cu), such as molten metal erosion, good electrical insulation, high room temperature resistivity. With silicon nitride combined with silicon carbide instead of traditional silicon carbide products, can reduce weight by 50%, and are given to improve furnace pool cubed out 16%, energy saving more than 15%, is the enterprise to improve product quality, increase the economic benefit is the ideal refractories.rnrnSilicon nitride products for its excellent performance and high cost performance has showed remarkable role in nonferrous smelting industry, the current foreign non-ferrous industry are widely used in silicon nitride combined with silicon carbide products, whether aluminium electrolyzer, melting furnace, blast furnace, melting furnace of copper industry, lead and zinc industry of shaft furnace, the furnace center and so on, silicon nitride is currently the best refractory materials.rnrnSilicon nitride combined with silicon carbide products, has been widely used in the manufacture of the furnace temperature with the cervix thermocouple protection tube, blow oxygen and nitrogen blow pipe, fluid pipe and pressure casting mold for the and so on. Its use in industrial energy saving, etc, are also growing.', 'picture'=>'0_1.png', 'homeshow'=>'N'),
            array('id'=>2, 'parentid'=>0, 'name'=>'氧化物结合碳化硅制品', 'name_en'=>'Oxide bonded SiC Products', 'introduction'=>'氧化物结合碳化硅复合材料是一种具有很好发展前途的高性能材料 ,它具有其它普通耐火材料所不具备的特性 ,如高热导率、低膨胀系数、很好的热稳定性及耐磨性 ,可以用作陶瓷窑具、各种加热炉内衬和换热器管材等等。', 'introduction_en'=>'Oxide combined with silicon carbide composite material is a very good development prospect of high-performance materials, it has features of other common refractory materials do not have, such as high heat conductivity, low expansion coefficient, good thermal stability and wear resistance, can be used as a ceramic kiln furniture, all kinds of heating furnace lining and heat exchanger pipes, etc.', 'picture'=>'1278076625.jpg', 'homeshow'=>'N'),
            array('id'=>3, 'parentid'=>0, 'name'=>'碳化硅浇注料', 'name_en'=>'SiC Castables', 'introduction'=>'碳化硅浇注料', 'introduction_en'=>'SiC Castables', 'picture'=>'1278076625.jpg', 'homeshow'=>'N'),
            array('id'=>4, 'parentid'=>1, 'name'=>'棚板', 'name_en'=>'Slab', 'introduction'=>'氮化硅结合碳化硅棚板广泛应用于日用陶瓷业、卫生陶瓷业、高压电瓷业、电子陶瓷业、砂轮行业、磁性材料行业等，适用于隧道窑、梭式窑、辊道窑、推板窑等。\r\n　　氮化硅结合碳化硅棚板造型科学、合理，结构致密，具有耐热温度高、高机械强度和导热性、较小的热膨胀系数，抗氧化性强、热震稳定性好、使用寿命长，在高温状态下、不开裂、不变型、不起泡、不落渣等优点，能明显降低瓷器产品的变形及落渣缺陷，产品质量在国内同类产品中处于领先地位，是日用陶瓷、卫生陶瓷、高压电瓷、电子陶瓷、砂轮、磁性材料等行业的理想窑具。', 'introduction_en'=>'Silicon nitride combined with silicon carbide plate is widely used in daily-use ceramics, electronic ceramics, sanitary ceramics, high voltage industry, grinding wheel, magnetic material industry, etc., applicable to the tunnel kiln, shuttle kiln, roller kiln, push board kiln, etc.\r\n\r\nSilicon nitride combined with silicon carbide plate modelling is scientific, reasonable, compact structure, high heat resistance, high mechanical strength and thermal conductivity, smaller thermal expansion coefficient, good thermal shock stability, oxidation resistance, long service life, under high temperature condition, no crack, no variant, and the advantages of no bubbles, no residue, can obviously reduce the porcelain product deformation and slag defects, the quality of the products in the domestic leading position among the similar products, daily-use ceramics, sanitary ceramics, high voltage electrical porcelain, electronic ceramic, emery wheel, magnetic materials and other industries ideal kiln furniture.', 'picture'=>'1_4.png', 'homeshow'=>'N'),
            array('id'=>5, 'parentid'=>1, 'name'=>'砖', 'name_en'=>'brick', 'introduction'=>'以氮化硅为主要结合相的碳化硅制品。一般含碳化硅70%～75%，氮化硅18%～25%。具有良好抗腐蚀能力，1400℃抗折强度达50～55MPa，显气孔率15%。热膨胀系数(4.5～5.0)&times;10-2℃-1。采用高温烧成法制备。主要用于高炉风口、铝电解槽内衬等。', 'introduction_en'=>'Is mainly made of silicon nitride combined with silicon carbide products. Containing 70% ~ 75%, silicon carbide silicon nitride 18% ~ 25%. Has good corrosion resistance, 1400 ℃ flexural strength of up to 50 to 55 mpa, the porosity of 15%. Thermal expansion coefficient (4.5 ~ 5.0) x 10 ℃ - 1-2. Using high temperature burning preparation method. It is mainly used for blast furnace tuyere, aluminum electrolytic cell lining, etc.', 'picture'=>'1_5.png', 'homeshow'=>'Y'),
            array('id'=>6, 'parentid'=>1, 'name'=>'其他', 'name_en'=>'other', 'introduction'=>'包括氮化硅结合碳化硅', 'introduction_en'=>'other', 'picture'=>'1326723819.jpg', 'homeshow'=>'N'),
            array('id'=>7, 'parentid'=>4, 'name'=>'普通板', 'name_en'=>'Normal slab', 'introduction'=>'　　氮化硅结合碳化硅普通棚板能有效减少板的膨胀应力，显著增加棚板的使用寿命。广泛应用于陶瓷和磨具磨料行业。  ', 'introduction_en'=>'Normal slab  ', 'picture'=>'10_100.png', 'homeshow'=>'Y'),
            array('id'=>8, 'parentid'=>4, 'name'=>'膨胀线板', 'name_en'=>'Expand line slab', 'introduction'=>'氮化硅结合碳化硅膨胀线棚板 ', 'introduction_en'=>'Expand line slab ', 'picture'=>'NP1.jpg', 'homeshow'=>'Y'),
            array('id'=>9, 'parentid'=>4, 'name'=>'膨胀孔板', 'name_en'=>'Expand Hole slab', 'introduction'=>'氮化硅结合碳化硅多孔板', 'introduction_en'=>'Expand Hole slab', 'picture'=>'4_9.png', 'homeshow'=>'Y'),
            array('id'=>10, 'parentid'=>4, 'name'=>'三明治板', 'name_en'=>'Sendwish Plant', 'introduction'=>'三明治板是以碳化硅，氧化铝，氧化锆复合成型，经高温烧结而成的一种复合型新产品。具有碳化硅优良的热传导性和高温荷重性能，同时氧化铝面层能有效确保棚板在高温使用时不会与产品起反应。适合在各种高性能电子元件烧结窑炉中使用。', 'introduction_en'=>'Sandwish Plant : It is a new combined product, made by high sintering silicon carbide ,alumina ,zirconia together . \r\n\r\nIt has good thermal conductivity and the heat load functions like silicon carbide. The surface made by aluminum and zirconia will not rise reaction with products .\r\nIt is suitable to be used in various kiln stove for high performance electronics component.', 'picture'=>'4_10.png', 'homeshow'=>'N'),
            array('id'=>11, 'parentid'=>5, 'name'=>'电解槽侧板砖', 'name_en'=>'Electrobath Side plate brick', 'introduction'=>'氮化硅结合碳化硅电解槽侧板砖，具有相当高的体积密度、耐压强度和抗折强度', 'introduction_en'=>'Electrobath Side plate brick', 'picture'=>'5_11.png', 'homeshow'=>'N'),
            array('id'=>12, 'parentid'=>5, 'name'=>'推板砖', 'name_en'=>'pusher', 'introduction'=>'本产品应用于电子元件、磁性材料、特种陶瓷的遂道炉上。产品具有良好的耐磨性、耐温性和热震稳定性，推板不会因使用时间的推移而产生膨胀，所以不会造成捧窑事故。', 'introduction_en'=>'pusher   ', 'picture'=>'11_111.png', 'homeshow'=>'N'),
            array('id'=>13, 'parentid'=>6, 'name'=>'退火托盘', 'name_en'=>'Tray  ', 'introduction'=>'退火托盘       ', 'introduction_en'=>'Tray         ', 'picture'=>'15_150.png', 'homeshow'=>'Y'),
            array('id'=>14, 'parentid'=>6, 'name'=>'匣钵', 'name_en'=>'Sagger', 'introduction'=>'匣钵', 'introduction_en'=>'Sagger', 'picture'=>'1292230575.jpg', 'homeshow'=>'N'),
            array('id'=>15, 'parentid'=>6, 'name'=>'管件', 'name_en'=>'Pipe', 'introduction'=>'管件', 'introduction_en'=>'Pipe', 'picture'=>'1292208439.jpg', 'homeshow'=>'Y'),
            array('id'=>16, 'parentid'=>2, 'name'=>'棚板', 'name_en'=>'slab', 'introduction'=>'氧化物结合碳化硅棚板具有优异的热震稳定性和抗氧化性、极高的高温抗折强度、高温下抗变形性能强、热传导度高、抗化学侵蚀及耐磨性好等优点。', 'introduction_en'=>'Our carborundum planks bonded of oxide can offer high mechanical strength at high temperature , excellent heat stability , oxidization resistance , good distortion resistance at high temperature ; high thermal conductivity , excellent corrosion resistance at high temperature ; excellent corrosion resistance against chemicals ; high abrasion resistance', 'picture'=>'2_16.png', 'homeshow'=>'N'),
            array('id'=>17, 'parentid'=>6, 'name'=>'烧嘴', 'name_en'=>'Burner', 'introduction'=>'no', 'introduction_en'=>'no', 'picture'=>'95jh20tqo4pf0ube2tn2gq08m4.png', 'homeshow'=>'N'),
            array('id'=>19, 'parentid'=>6, 'name'=>'梁', 'name_en'=>'Beam', 'introduction'=>'梁', 'introduction_en'=>'Beam', 'picture'=>'6_19.png', 'homeshow'=>'N'),
            array('id'=>20, 'parentid'=>2, 'name'=>'砖', 'name_en'=>'brick', 'introduction'=>'砖', 'introduction_en'=>'brick', 'picture'=>'2_20.png', 'homeshow'=>'N'),
            array('id'=>24, 'parentid'=>16, 'name'=>'普通板', 'name_en'=>'Normal slab', 'introduction'=>'普通板', 'introduction_en'=>'Normal slab', 'picture'=>'16_24.png', 'homeshow'=>'N'),
            array('id'=>25, 'parentid'=>16, 'name'=>'膨胀线板', 'name_en'=>'Normal slab', 'introduction'=>'普通板', 'introduction_en'=>'Normal slab', 'picture'=>'h0khta5jkm5mn8d5csajdc0k61.png', 'homeshow'=>'N'),
            array('id'=>26, 'parentid'=>16, 'name'=>'普通板', 'name_en'=>'Normal slab', 'introduction'=>'普通板', 'introduction_en'=>'Normal slab', 'picture'=>'h0khta5jkm5mn8d5csajdc0k61.png', 'homeshow'=>'N'),
            array('id'=>27, 'parentid'=>16, 'name'=>'普通板', 'name_en'=>'Normal slab', 'introduction'=>'普通板', 'introduction_en'=>'Normal slab', 'picture'=>'h0khta5jkm5mn8d5csajdc0k61.png', 'homeshow'=>'N'),
            array('id'=>28, 'parentid'=>16, 'name'=>'普通板', 'name_en'=>'Normal slab', 'introduction'=>'普通板', 'introduction_en'=>'Normal slab', 'picture'=>'h0khta5jkm5mn8d5csajdc0k61.png', 'homeshow'=>'N'),
            array('id'=>30, 'parentid'=>16, 'name'=>'普通板', 'name_en'=>'Normal slab', 'introduction'=>'普通板', 'introduction_en'=>'Normal slab', 'picture'=>'h0khta5jkm5mn8d5csajdc0k61.png', 'homeshow'=>'N')
        );
        $this->insert('productclass', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('productclass');
    }
}
