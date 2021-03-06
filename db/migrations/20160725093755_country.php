<?php

use Phinx\Migration\AbstractMigration;

class Country extends AbstractMigration
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
        $table = $this->table('country');
        $table->addColumn('code2',  'char', array('limit' => 2))
            ->addColumn('code3',  'char', array('limit' => 3))
            ->addColumn('number',  'char', array('limit' => 3))
            ->addColumn('name',  'string', array('limit' => 40))
            ->addColumn('name_en',  'string', array('limit' => 50))
            ->create();

        $rows = [
            ['id'=>1, 'code2'=>'AD','code3'=>'AND','number'=>'020','name'=>'安道尔','name_en'=>'Andorra'],
            ['id'=>2, 'code2'=>'AE','code3'=>'ARE','number'=>'784','name'=>'阿联酋','name_en'=>'United Arab Emirates'],
            ['id'=>3,'code2'=> 'AF','code3'=>'AFG','number'=>'004','name'=>'阿富汗','name_en'=>'Afghanistan'],
            ['id'=>4,'code2'=> 'AG','code3'=>'ATG','number'=>'028','name'=>'安提瓜和巴布达','name_en'=>'Antigua and Barbuda'],
            ['id'=>5,'code2'=> 'AI','code3'=>'AIA','number'=>'660','name'=>'安圭拉','name_en'=>'Anguilla'],
            ['id'=>6, 'code2'=>'AL','code3'=>'ALB','number'=>'008','name'=>'阿尔巴尼亚','name_en'=>'Albania'],
            ['id'=>7, 'code2'=>'AM','code3'=>'ARM','number'=>'051','name'=>'亚美尼亚','name_en'=>'Armenia'],
            ['id'=>8, 'code2'=>'AO','code3'=>'AGO','number'=>'024','name'=>'安哥拉','name_en'=>'Angola'],
            ['id'=>9, 'code2'=>'AQ','code3'=>'ATA','number'=>'010','name'=>'南极洲','name_en'=>'Antarctica'],
            ['id'=>10,'code2'=> 'AR','code3'=>'ARG','number'=>'032','name'=>'阿根廷','name_en'=>'Argentina'],
            ['id'=>11, 'code2'=>'AS','code3'=>'ASM','number'=>'016','name'=>'美属萨摩亚','name_en'=>'American Samoa'],
            ['id'=>12, 'code2'=>'AT','code3'=>'AUT','number'=>'040','name'=>'奥地利','name_en'=>'Austria'],
            ['id'=>13, 'code2'=>'AU','code3'=>'AUS','number'=>'036','name'=>'澳大利亚','name_en'=>'Australia'],
            ['id'=>14,'code2'=> 'AW','code3'=>'ABW','number'=>'533','name'=>'阿鲁巴','name_en'=>'Aruba'],
            ['id'=>15, 'code2'=>'AX','code3'=>'ALA','number'=>'248','name'=>'奥兰群岛','name_en'=>'Åaland Islands'],
            ['id'=>16,'code2'=> 'AZ','code3'=>'AZE','number'=>'031','name'=>'阿塞拜疆','name_en'=>'Azerbaijan'],
            ['id'=>17,'code2'=> 'BA','code3'=>'BIH','number'=>'070','name'=>'波黑','name_en'=>'Bosnia and Herzegovina'],
            ['id'=>18, 'code2'=>'BB','code3'=>'BRB','number'=>'052','name'=>'巴巴多斯','name_en'=>'Barbados'],
            ['id'=>19, 'code2'=>'BD','code3'=>'BGD','number'=>'050','name'=>'孟加拉','name_en'=>'Bangladesh'],
            ['id'=>20,'code2'=> 'BE','code3'=>'BEL','number'=>'056','name'=>'比利时','name_en'=>'Belgium'],
            ['id'=>21, 'code2'=>'BF','code3'=>'BFA','number'=>'854','name'=>'布基纳法索','name_en'=>'Burkina Faso'],
            ['id'=>22, 'code2'=>'BG','code3'=>'BGR','number'=>'100','name'=>'保加利亚','name_en'=>'Bulgaria'],
            ['id'=>23, 'code2'=>'BH','code3'=>'BHR','number'=>'048','name'=>'巴林','name_en'=>'Bahrain'],
            ['id'=>24, 'code2'=>'BI','code3'=>'BDI','number'=>'108','name'=>'布隆迪','name_en'=>'Burundi'],
            ['id'=>25, 'code2'=>'BJ','code3'=>'BEN','number'=>'204','name'=>'贝宁','name_en'=>'Benin'],
            ['id'=>26, 'code2'=>'BL','code3'=>'BLM','number'=>'652','name'=>'圣巴泰勒米岛','name_en'=>'Saint Barthélemy'],
            ['id'=>27, 'code2'=>'BM','code3'=>'BMU','number'=>'060','name'=>'百慕大','name_en'=>'Bermuda'],
            ['id'=>28, 'code2'=>'BN','code3'=>'BRN','number'=>'096','name'=>'文莱','name_en'=>'Brunei Darussalam'],
            ['id'=>29, 'code2'=>'BO','code3'=>'BOL','number'=>'068','name'=>'玻利维亚','name_en'=>'Bolivia, Plurinational'],
            ['id'=>30, 'code2'=>'BQ','code3'=>'BES','number'=>'535','name'=>'荷兰加勒比区','name_en'=>'Bonaire, Sint Eustatius and Saba'],
            ['id'=>31, 'code2'=>'BR','code3'=>'BRA','number'=>'076','name'=>'巴西','name_en'=>'Brazil'],
            ['id'=>32, 'code2'=>'BS','code3'=>'BHS','number'=>'044','name'=>'巴哈马','name_en'=>'Bahamas'],
            ['id'=>33, 'code2'=>'BT','code3'=>'BTN','number'=>'064','name'=>'不丹','name_en'=>'Bhutan'],
            ['id'=>34, 'code2'=>'BV','code3'=>'BVT','number'=>'074','name'=>'布韦岛','name_en'=>'Bouvet Island'],
            ['id'=>35, 'code2'=>'BW','code3'=>'BWA','number'=>'072','name'=>'博茨瓦纳','name_en'=>'Botswana'],
            ['id'=>36, 'code2'=>'BY','code3'=>'BLR','number'=>'112','name'=>'白俄罗斯','name_en'=>'Belarus'],
            ['id'=>37, 'code2'=>'BZ','code3'=>'BLZ','number'=>'084','name'=>'伯利兹','name_en'=>'Belize'],
            ['id'=>38, 'code2'=>'CA','code3'=>'CAN','number'=>'124','name'=>'加拿大','name_en'=>'Canada'],
            ['id'=>39, 'code2'=>'CC','code3'=>'CCK','number'=>'166','name'=>'科科斯群岛','name_en'=>'Cocos (Keeling) Islands'],
            ['id'=>40, 'code2'=>'CD','code3'=>'COD','number'=>'180','name'=>'刚果（金）','name_en'=>'Congo, the Democratic Republic of the'],
            ['id'=>41, 'code2'=>'CF','code3'=>'CAF','number'=>'140','name'=>'中非','name_en'=>'Central African Republic'],
            ['id'=>42, 'code2'=>'CG','code3'=>'COG','number'=>'178','name'=>'刚果（布）','name_en'=>'Congo'],
            ['id'=>43, 'code2'=>'CH','code3'=>'CHE','number'=>'756','name'=>'瑞士','name_en'=>'Switzerland'],
            ['id'=>44, 'code2'=>'CI','code3'=>'CIV','number'=>'384','name'=>'科特迪瓦','name_en'=>"Côte d'Ivoire"],
            ['id'=>45, 'code2'=>'CK','code3'=>'COK','number'=>'184','name'=>'库克群岛','name_en'=>'Cook Islands'],
            ['id'=>46, 'code2'=>'CL','code3'=>'CHL','number'=>'152','name'=>'智利','name_en'=>'Chile'],
            ['id'=>47, 'code2'=>'CM','code3'=>'CMR','number'=>'120','name'=>'喀麦隆','name_en'=>'Cameroon'],
            ['id'=>48, 'code2'=>'CN','code3'=>'CHN','number'=>'156','name'=>'中国； 内地','name_en'=>'China'],
            ['id'=>49, 'code2'=>'CO','code3'=>'COL','number'=>'170','name'=>'哥伦比亚','name_en'=>'Colombia'],
            ['id'=>50, 'code2'=>'CR','code3'=>'CRI','number'=>'188','name'=>'哥斯达黎加','name_en'=>'Costa Rica'],
            ['id'=>51, 'code2'=>'CU','code3'=>'CUB','number'=>'192','name'=>'古巴','name_en'=>'Cuba'],
            ['id'=>52, 'code2'=>'CV','code3'=>'CPV','number'=>'132','name'=>'佛得角','name_en'=>'Cape Verde'],
            ['id'=>53, 'code2'=>'CW','code3'=>'CUW','number'=>'531','name'=>'库拉索','name_en'=>'Curaçao'],
            ['id'=>54, 'code2'=>'CX','code3'=>'CXR','number'=>'162','name'=>'圣诞岛','name_en'=>'Christmas Island'],
            ['id'=>55, 'code2'=>'CY','code3'=>'CYP','number'=>'196','name'=>'塞浦路斯','name_en'=>'Cyprus'],
            ['id'=>56, 'code2'=>'CZ','code3'=>'CZE','number'=>'203','name'=>'捷克','name_en'=>'Czech Republic'],
            ['id'=>57, 'code2'=>'DE','code3'=>'DEU','number'=>'276','name'=>'德国','name_en'=>'Germany'],
            ['id'=>58, 'code2'=>'DJ','code3'=>'DJI','number'=>'262','name'=>'吉布提','name_en'=>'Djibouti'],
            ['id'=>59, 'code2'=>'DK','code3'=>'DNK','number'=>'208','name'=>'丹麦','name_en'=>'Denmark'],
            ['id'=>60, 'code2'=>'DM','code3'=>'DMA','number'=>'212','name'=>'多米尼克','name_en'=>'Dominica'],
            ['id'=>61, 'code2'=>'DO','code3'=>'DOM','number'=>'214','name'=>'多米尼克','name_en'=>'Dominican Republic'],
            ['id'=>62, 'code2'=>'DZ','code3'=>'DZA','number'=>'012','name'=>'阿尔及利亚','name_en'=>'Algeria'],
            ['id'=>63,'code2'=>'EC','code3'=>'ECU','number'=>'218','name'=>'厄瓜多尔','name_en'=>'Ecuador'],
            ['id'=>64, 'code2'=>'EE','code3'=>'EST','number'=>'233','name'=>'爱沙尼亚','name_en'=>'Estonia'],
            ['id'=>65, 'code2'=>'EG','code3'=>'EGY','number'=>'818','name'=>'埃及','name_en'=>'Egypt'],
            ['id'=>66, 'code2'=>'EH','code3'=>'ESH','number'=>'732','name'=>'西撒哈拉','name_en'=>'Western Sahara'],
            ['id'=>67, 'code2'=>'ER','code3'=>'ERI','number'=>'232','name'=>'厄立特里亚','name_en'=>'Eritrea'],
            ['id'=>68, 'code2'=>'ES','code3'=>'ESP','number'=>'724','name'=>'西班牙','name_en'=>'Spain'],
            ['id'=>69, 'code2'=>'ET','code3'=>'ETH','number'=>'231','name'=>'埃塞俄比亚','name_en'=>'Ethiopia'],
            ['id'=>70, 'code2'=>'FI','code3'=>'FIN','number'=>'246','name'=>'芬兰','name_en'=>'Finland'],
            ['id'=>71, 'code2'=>'FJ','code3'=>'FJI','number'=>'242','name'=>'斐济群岛','name_en'=>'Fiji'],
            ['id'=>72, 'code2'=>'FK','code3'=>'FLK','number'=>'238','name'=>'马尔维纳斯群岛（福克兰）','name_en'=>'Falkland Islands (Malvinas)'],
            ['id'=>73, 'code2'=>'FM','code3'=>'FSM','number'=>'583','name'=>'密克罗尼西亚联邦','name_en'=>'Micronesia, Federated States of'],
            ['id'=>74, 'code2'=>'FO','code3'=>'FRO','number'=>'234','name'=>'法罗群岛','name_en'=>'Faroe Islands'],
            ['id'=>75, 'code2'=>'FR','code3'=>'FRA','number'=>'250','name'=>'法国','name_en'=>'France'],
            ['id'=>76, 'code2'=>'GA','code3'=>'GAB','number'=>'266','name'=>'加蓬','name_en'=>'Gabon'],
            ['id'=>77, 'code2'=>'GB','code3'=>'GBR','number'=>'826','name'=>'英国','name_en'=>'United Kingdom'],
            ['id'=>78, 'code2'=>'GD','code3'=>'GRD','number'=>'308','name'=>'格林纳达','name_en'=>'Grenada'],
            ['id'=>79, 'code2'=>'GE','code3'=>'GEO','number'=>'268','name'=>'格鲁吉亚','name_en'=>'Georgia'],
            ['id'=>80, 'code2'=>'GF','code3'=>'GUF','number'=>'254','name'=>'法属圭亚那','name_en'=>'French Guiana'],
            ['id'=>81, 'code2'=>'GG','code3'=>'GGY','number'=>'831','name'=>'根西岛','name_en'=>'Guernsey'],
            ['id'=>82, 'code2'=>'GH','code3'=>'GHA','number'=>'288','name'=>'加纳','name_en'=>'Ghana'],
            ['id'=>83, 'code2'=>'GI','code3'=>'GIB','number'=>'292','name'=>'直布罗陀','name_en'=>'Gibraltar'],
            ['id'=>84, 'code2'=>'GL','code3'=>'GRL','number'=>'304','name'=>'格陵兰','name_en'=>'Greenland'],
            ['id'=>85, 'code2'=>'GM','code3'=>'GMB','number'=>'270','name'=>'冈比亚','name_en'=>'Gambia'],
            ['id'=>86, 'code2'=>'GN','code3'=>'GIN','number'=>'324','name'=>'几内亚','name_en'=>'Guinea'],
            ['id'=>87, 'code2'=>'GP','code3'=>'GLP','number'=>'312','name'=>'瓜德罗普','name_en'=>'Guadeloupe'],
            ['id'=>88, 'code2'=>'GQ','code3'=>'GNQ','number'=>'226','name'=>'赤道几内亚','name_en'=>'Equatorial Guinea'],
            ['id'=>91, 'code2'=>'GR','code3'=>'GRC','number'=>'300','name'=>'希腊','name_en'=>'Greece'],
            ['id'=>92, 'code2'=>'GS','code3'=>'SGS','number'=>'239','name'=>'南乔治亚岛和南桑威奇群岛','name_en'=>'South Georgia and the South Sandwich Islands'],
            ['id'=>93, 'code2'=>'GT','code3'=>'GTN','number'=>'320','name'=>'危地马拉','name_en'=>'Guatemala'],
            ['id'=>94, 'code2'=>'GU','code3'=>'GUM','number'=>'316','name'=>'关岛','name_en'=>'Guam'],
            ['id'=>95, 'code2'=>'GW','code3'=>'GNB','number'=>'624','name'=>'几内亚比绍','name_en'=>'Guinea-Bissau'],
            ['id'=>96, 'code2'=>'GY','code3'=>'GUY','number'=>'328','name'=>'圭亚那','name_en'=>'Guyana'],
            ['id'=>97, 'code2'=>'HK','code3'=>'HKG','number'=>'344','name'=>'香港','name_en'=>'Hong Kong'],
            ['id'=>98, 'code2'=>'HM','code3'=>'HMD','number'=>'334','name'=>'赫德岛和麦克唐纳群岛','name_en'=>'Heard Island and McDonald Islands'],
            ['id'=>99, 'code2'=>'HN','code3'=>'HND','number'=>'340','name'=>'洪都拉斯','name_en'=>'Honduras'],
            ['id'=>100, 'code2'=>'HR','code3'=>'HRV','number'=>'191','name'=>'克罗地亚','name_en'=>'Croatia'],
            ['id'=>101, 'code2'=>'HT','code3'=>'HTI','number'=>'332','name'=>'海地','name_en'=>'Haiti'],
            ['id'=>102, 'code2'=>'HU','code3'=>'HUN','number'=>'348','name'=>'匈牙利','name_en'=>'Hungary'],
            ['id'=>103, 'code2'=>'ID','code3'=>'IDN','number'=>'360','name'=>'印尼','name_en'=>'Indonesia'],
            ['id'=>104, 'code2'=>'IE','code3'=>'IRL','number'=>'372','name'=>'爱尔兰','name_en'=>'Ireland'],
            ['id'=>105, 'code2'=>'IL','code3'=>'ISR','number'=>'376','name'=>'以色列','name_en'=>'Israel'],
            ['id'=>106, 'code2'=>'IM','code3'=>'IMN','number'=>'833','name'=>'马恩岛','name_en'=>'Isle of Man'],
            ['id'=>107, 'code2'=>'IN','code3'=>'IND','number'=>'356','name'=>'印度','name_en'=>'India'],
            ['id'=>108, 'code2'=>'IO','code3'=>'IOT','number'=>'086','name'=>'英属印度洋领地','name_en'=>'British Indian Ocean Territory'],
            ['id'=>109, 'code2'=>'IQ','code3'=>'IRQ','number'=>'368','name'=>'伊拉克','name_en'=>'Iraq'],
            ['id'=>110, 'code2'=>'IR','code3'=>'IRN','number'=>'364','name'=>'伊朗','name_en'=>'Iran, Islamic Republic of'],
            ['id'=>111, 'code2'=>'IS','code3'=>'ISL','number'=>'352','name'=>'冰岛','name_en'=>'Iceland'],
            ['id'=>112, 'code2'=>'IT','code3'=>'ITA','number'=>'380','name'=>'意大利','name_en'=>'Italy'],
            ['id'=>113, 'code2'=>'JE','code3'=>'JEY','number'=>'832','name'=>'泽西岛','name_en'=>'Jersey'],
            ['id'=>114, 'code2'=>'JM','code3'=>'JAM','number'=>'388','name'=>'牙买加','name_en'=>'Jamaica'],
            ['id'=>115, 'code2'=>'JO','code3'=>'JOR','number'=>'400','name'=>'约旦','name_en'=>'Jordan'],
            ['id'=>116, 'code2'=>'JP','code3'=>'JPN','number'=>'392','name'=>'日本','name_en'=>'Japan'],
            ['id'=>117, 'code2'=>'KE','code3'=>'KEN','number'=>'404','name'=>'肯尼亚','name_en'=>'Kenya'],
            ['id'=>118, 'code2'=>'KG','code3'=>'KGZ','number'=>'417','name'=>'吉尔吉斯斯坦','name_en'=>'Kyrgyzstan'],
            ['id'=>119, 'code2'=>'KH','code3'=>'KHM','number'=>'116','name'=>'柬埔寨','name_en'=>'Cambodia'],
            ['id'=>120, 'code2'=>'KI','code3'=>'KIR','number'=>'296','name'=>'基里巴斯','name_en'=>'Kiribati'],
            ['id'=>121, 'code2'=>'KM','code3'=>'COM','number'=>'174','name'=>'科摩罗','name_en'=>'Comoros'],
            ['id'=>122, 'code2'=>'KN','code3'=>'KNA','number'=>'659','name'=>'圣基茨和尼维斯','name_en'=>'Saint Kitts and Nevis'],
            ['id'=>123, 'code2'=>'KP','code3'=>'KPK','number'=>'408','name'=>'朝鲜； 北朝鲜','name_en'=>"Korea,Democratic People's Republic of"],
            ['id'=>124, 'code2'=>'KR','code3'=>'KOR','number'=>'410','name'=>'韩国； 南朝鲜','name_en'=>'Korea, Republic of'],
            ['id'=>125, 'code2'=>'KW','code3'=>'KWT','number'=>'414','name'=>'科威特','name_en'=>'Kuwait'],
            ['id'=>126, 'code2'=>'KY','code3'=>'KYM','number'=>'136','name'=>'开曼群岛','name_en'=>'Cayman Islands'],
            ['id'=>127, 'code2'=>'KZ','code3'=>'KAZ','number'=>'398','name'=>'哈萨克斯坦','name_en'=>'Kazakhstan'],
            ['id'=>128, 'code2'=>'LA','code3'=>'LAO','number'=>'418','name'=>'老挝','name_en'=>"Lao People's Democratic Republic"],
            ['id'=>129, 'code2'=>'LB','code3'=>'LBN','number'=>'422','name'=>'黎巴嫩','name_en'=>'Lebanon'],
            ['id'=>130, 'code2'=>'LC','code3'=>'LCA','number'=>'662','name'=>'圣卢西亚','name_en'=>'Saint Lucia'],
            ['id'=>131, 'code2'=>'LI','code3'=>'LIE','number'=>'4','name'=>'','name_en'=>''],
            ['id'=>132, 'code2'=>'LI','code3'=>'LIE','number'=>'438','name'=>'列支敦士登','name_en'=>'Liechtenstein'],
            ['id'=>133, 'code2'=>'LK','code3'=>'LKA','number'=>'144','name'=>'斯里兰卡','name_en'=>'Sri Lanka'],
            ['id'=>134, 'code2'=>'LR','code3'=>'LBR','number'=>'430','name'=>'利比里亚','name_en'=>'Liberia'],
            ['id'=>135, 'code2'=>'LS','code3'=>'LSO','number'=>'426','name'=>'莱索托','name_en'=>''],
            ['id'=>136, 'code2'=>'LT','code3'=>'LTU','number'=>'440','name'=>'立陶宛','name_en'=>'Lithuania'],
            ['id'=>137, 'code2'=>'LU','code3'=>'LUX','number'=>'442','name'=>'卢森堡','name_en'=>'Luxembourg'],
            ['id'=>138, 'code2'=>'LV','code3'=>'LVA','number'=>'428','name'=>'拉脱维亚','name_en'=>'Latvia'],
            ['id'=>139, 'code2'=>'LY','code3'=>'LBY','number'=>'434','name'=>'利比亚','name_en'=>'Libya'],
            ['id'=>140, 'code2'=>'MA','code3'=>'MAR','number'=>'504','name'=>'摩洛哥','name_en'=>'Morocco'],
            ['id'=>141, 'code2'=>'MC','code3'=>'MCO','number'=>'492','name'=>'摩纳哥','name_en'=>'Monaco'],
            ['id'=>142, 'code2'=>'MD','code3'=>'MDA','number'=>'498','name'=>'摩尔多瓦','name_en'=>'Moldova, Republic of'],
            ['id'=>143, 'code2'=>'ME','code3'=>'MNE','number'=>'499','name'=>'黑山','name_en'=>'Montenegro'],
            ['id'=>144, 'code2'=>'MF','code3'=>'MAF','number'=>'663','name'=>'法属圣马丁','name_en'=>'Saint Martin (French part)'],
            ['id'=>145,'code2'=>'MG','code3'=>'MDG','number'=>'450','name'=>'马达加斯加','name_en'=>'Madagascar'],
            ['id'=>146, 'code2'=>'MH','code3'=>'MHL','number'=>'584','name'=>'马绍尔群岛','name_en'=>'Marshall islands'],
            ['id'=>147,'code2'=>'MK','code3'=>'MKD','number'=>'807','name'=>'马其顿','name_en'=>'Macedonia, the former Yugoslav Republic of'],
            ['id'=>148, 'code2'=>'ML','code3'=>'MLI','number'=>'466','name'=>'马里','name_en'=>'Mali'],
            ['id'=>149, 'code2'=>'MM','code3'=>'MMI','number'=>'104','name'=>'缅甸','name_en'=>'Myanmar'],
            ['id'=>150, 'code2'=>'MN','code3'=>'MNG','number'=>'496','name'=>'蒙古国；蒙古','name_en'=>'Mongolia'],
            ['id'=>151, 'code2'=>'MO','code3'=>'MAC','number'=>'446','name'=>'澳门','name_en'=>'Macao'],
            ['id'=>152, 'code2'=>'MP','code3'=>'MNP','number'=>'580','name'=>'北马里亚纳群岛','name_en'=>'Northern Mariana Islands'],
            ['id'=>153, 'code2'=>'MQ','code3'=>'MTQ','number'=>'474','name'=>'马提尼克','name_en'=>'Martinique'],
            ['id'=>154, 'code2'=>'MR','code3'=>'MRT','number'=>'478','name'=>'毛里塔尼亚','name_en'=>'Mauritania'],
            ['id'=>155, 'code2'=>'MS','code3'=>'MSR','number'=>'500','name'=>'蒙塞拉特岛','name_en'=>'Montserrat'],
            ['id'=>156, 'code2'=>'MT','code3'=>'MLT','number'=>'470','name'=>'马耳他','name_en'=>'Malta'],
            ['id'=>157, 'code2'=>'MU','code3'=>'MUS','number'=>'480','name'=>'毛里求斯','name_en'=>'Mauritius'],
            ['id'=>158, 'code2'=>'MV','code3'=>'MDV','number'=>'462','name'=>'马尔代夫','name_en'=>'Maldives'],
            ['id'=>159, 'code2'=>'MW','code3'=>'MWI','number'=>'454','name'=>'马拉维','name_en'=>'Malawi'],
            ['id'=>160, 'code2'=>'MX','code3'=>'MEX','number'=>'484','name'=>'墨西哥','name_en'=>'Mexico'],
            ['id'=>161, 'code2'=>'MY','code3'=>'MYS','number'=>'458','name'=>'马来西亚','name_en'=>'Malaysia'],
            ['id'=>162, 'code2'=>'MZ','code3'=>'MOZ','number'=>'508','name'=>'莫桑比克','name_en'=>'Mozambique'],
            ['id'=>163, 'code2'=>'NA','code3'=>'NAM','number'=>'516','name'=>'纳米比亚','name_en'=>'Namibia'],
            ['id'=>164, 'code2'=>'NC','code3'=>'NCL','number'=>'540','name'=>'新喀里多尼亚','name_en'=>'New Caledonia'],
            ['id'=>165, 'code2'=>'NE','code3'=>'NER','number'=>'562','name'=>'尼日尔','name_en'=>'Niger'],
            ['id'=>166, 'code2'=>'NF','code3'=>'NFK','number'=>'574','name'=>'诺福克岛','name_en'=>'Norfolk Island'],
            ['id'=>167, 'code2'=>'NG','code3'=>'NGA','number'=>'566','name'=>'尼日利亚','name_en'=>'Nigeria'],
            ['id'=>168, 'code2'=>'NL','code3'=>'NLD','number'=>'528','name'=>'荷兰','name_en'=>'Netherlands'],
            ['id'=>169, 'code2'=>'NO','code3'=>'NOR','number'=>'578','name'=>'挪威','name_en'=>'Norway'],
            ['id'=>170, 'code2'=>'NP','code3'=>'NPL','number'=>'524','name'=>'尼泊尔','name_en'=>'Nepal'],
            ['id'=>171, 'code2'=>'NR','code3'=>'NRU','number'=>'520','name'=>'瑙鲁','name_en'=>'Nauru'],
            ['id'=>172, 'code2'=>'NU','code3'=>'NIU','number'=>'570','name'=>'纽埃','name_en'=>'Niue'],
            ['id'=>173, 'code2'=>'NZ','code3'=>'NZL','number'=>'554','name'=>'新西兰','name_en'=>'New Zealand'],
            ['id'=>174, 'code2'=>'OM','code3'=>'OMN','number'=>'512','name'=>'阿曼','name_en'=>'Oman'],
            ['id'=>175, 'code2'=>'PA','code3'=>'PAN','number'=>'591','name'=>'巴拿马','name_en'=>'Panama'],
            ['id'=>176, 'code2'=>'PE','code3'=>'PER','number'=>'604','name'=>'秘鲁','name_en'=>'Peru'],
            ['id'=>177, 'code2'=>'PF','code3'=>'PYF','number'=>'258','name'=>'法属波利尼西亚','name_en'=>'French Polynesia'],
            ['id'=>178, 'code2'=>'PG','code3'=>'PNG','number'=>'598','name'=>'巴布亚新几内亚','name_en'=>'Papua New Guinea'],
            ['id'=>179, 'code2'=>'PH','code3'=>'PHL','number'=>'608','name'=>'菲律宾','name_en'=>'Philippines'],
            ['id'=>180, 'code2'=>'PK','code3'=>'PAK','number'=>'586','name'=>'巴基斯坦','name_en'=>'Pakistan'],
            ['id'=>181, 'code2'=>'PL','code3'=>'POL','number'=>'616','name'=>'波兰','name_en'=>'Poland'],
            ['id'=>182, 'code2'=>'PM','code3'=>'SPM','number'=>'666','name'=>'圣皮埃尔和密克隆','name_en'=>'Saint Pierre and Miquelon'],
            ['id'=>183, 'code2'=>'PN','code3'=>'PCN','number'=>'612','name'=>'皮特凯恩群岛','name_en'=>'Pitcairn Islands'],
            ['id'=>184, 'code2'=>'PR','code3'=>'PRI','number'=>'630','name'=>'波多黎各','name_en'=>'Puerto Rico'],
            ['id'=>185, 'code2'=>'PS','code3'=>'PSE','number'=>'275','name'=>'巴勒斯坦','name_en'=>'Palestine, State of'],
            ['id'=>186, 'code2'=>'PT','code3'=>'PRT','number'=>'620','name'=>'葡萄牙','name_en'=>'Portugal'],
            ['id'=>187, 'code2'=>'PW','code3'=>'PLW','number'=>'585','name'=>'帕劳','name_en'=>'Palau'],
            ['id'=>188, 'code2'=>'PY','code3'=>'PRY','number'=>'600','name'=>'巴拉圭','name_en'=>'Paraguay'],
            ['id'=>189, 'code2'=>'QA','code3'=>'QAT','number'=>'634','name'=>'卡塔尔','name_en'=>'Qatar'],
            ['id'=>190, 'code2'=>'RE','code3'=>'REU','number'=>'638','name'=>'留尼汪','name_en'=>'Réunion'],
            ['id'=>191, 'code2'=>'RO','code3'=>'ROU','number'=>'642','name'=>'罗马尼亚','name_en'=>'Romania'],
            ['id'=>192, 'code2'=>'RS','code3'=>'SRB','number'=>'688','name'=>'塞尔维亚','name_en'=>'Serbia'],
            ['id'=>193, 'code2'=>'RU','code3'=>'RUS','number'=>'643','name'=>'俄罗斯','name_en'=>'Russian Federation'],
            ['id'=>194, 'code2'=>'RW','code3'=>'RWA','number'=>'646','name'=>'卢旺达','name_en'=>'Rwanda'],
            ['id'=>195, 'code2'=>'SA','code3'=>'SAU','number'=>'682','name'=>'沙特阿拉伯','name_en'=>'Saudi Arabia'],
            ['id'=>196, 'code2'=>'SB','code3'=>'SLB','number'=>'090','name'=>'所罗门群岛','name_en'=>'Solomon Islands'],
            ['id'=>197, 'code2'=>'SC','code3'=>'SYC','number'=>'690','name'=>'塞舌尔','name_en'=>'Seychelles'],
            ['id'=>198, 'code2'=>'SD','code3'=>'SDN','number'=>'729','name'=>'苏丹','name_en'=>'Sudan'],
            ['id'=>199, 'code2'=>'SE','code3'=>'SWE','number'=>'752','name'=>'瑞典','name_en'=>'Sweden'],
            ['id'=>200, 'code2'=>'SG','code3'=>'SGP','number'=>'702','name'=>'新加坡','name_en'=>'Singapore'],
            ['id'=>201, 'code2'=>'SH','code3'=>'SHN','number'=>'654','name'=>'圣赫勒拿','name_en'=>'Saint Helena, Ascension and Tristan da Cunha'],
            ['id'=>202, 'code2'=>'SI','code3'=>'SVN','number'=>'705','name'=>'斯洛文尼亚','name_en'=>'Slovenia'],
            ['id'=>203, 'code2'=>'SJ','code3'=>'SJM','number'=>'744','name'=>'斯瓦尔巴群岛和扬马延岛','name_en'=>'Svalbard and Jan Mayen'],
            ['id'=>204, 'code2'=>'SK','code3'=>'SVK','number'=>'703','name'=>'斯洛伐克','name_en'=>'Slovakia'],
            ['id'=>205, 'code2'=>'SL','code3'=>'SLE','number'=>'694','name'=>'塞拉利昂','name_en'=>'Sierra Leone'],
            ['id'=>206, 'code2'=>'SM','code3'=>'SMR','number'=>'674','name'=>'圣马力诺','name_en'=>'San Marino'],
            ['id'=>207, 'code2'=>'SN','code3'=>'SEN','number'=>'686','name'=>'塞内加尔','name_en'=>'Senegal'],
            ['id'=>208, 'code2'=>'SO','code3'=>'SOM','number'=>'706','name'=>'索马里','name_en'=>'Somalia'],
            ['id'=>209, 'code2'=>'SR','code3'=>'SUR','number'=>'740','name'=>'苏里南','name_en'=>'Suriname'],
            ['id'=>210, 'code2'=>'SS','code3'=>'SSD','number'=>'728','name'=>'南苏丹','name_en'=>'South Sudan'],
            ['id'=>211, 'code2'=>'ST','code3'=>'STP','number'=>'678','name'=>'圣多美和普林西比','name_en'=>'Sao Tome and Principe'],
            ['id'=>212, 'code2'=>'SV','code3'=>'SLV','number'=>'222','name'=>'萨尔瓦多','name_en'=>'El Salvador'],
            ['id'=>213, 'code2'=>'SX','code3'=>'SXM','number'=>'534','name'=>'荷属圣马丁','name_en'=>'Sint Maarten (Dutch part)'],
            ['id'=>214, 'code2'=>'SY','code3'=>'SYR','number'=>'760','name'=>'叙利亚','name_en'=>'Syrian Arab Republic'],
            ['id'=>215, 'code2'=>'SZ','code3'=>'SWZ','number'=>'748','name'=>'斯威士兰','name_en'=>'Swaziland'],
            ['id'=>216, 'code2'=>'TC','code3'=>'TCA','number'=>'796','name'=>'特克斯和凯科斯群岛','name_en'=>'Turks and Caicos Islands'],
            ['id'=>217, 'code2'=>'TD','code3'=>'TCD','number'=>'148','name'=>'乍得','name_en'=>'Chad'],
            ['id'=>218, 'code2'=>'TF','code3'=>'ATF','number'=>'260','name'=>'法属南部领地','name_en'=>'French Southern Territories'],
            ['id'=>219, 'code2'=>'TG','code3'=>'TGO','number'=>'768','name'=>'多哥','name_en'=>'Togo'],
            ['id'=>220, 'code2'=>'TH','code3'=>'THA','number'=>'764','name'=>'泰国','name_en'=>'Thailand'],
            ['id'=>221, 'code2'=>'TJ','code3'=>'TJK','number'=>'762','name'=>'塔吉克斯坦','name_en'=>'Tajikistan'],
            ['id'=>222, 'code2'=>'TK','code3'=>'TKL','number'=>'772','name'=>'托克劳','name_en'=>'Tokelau'],
            ['id'=>223, 'code2'=>'TL','code3'=>'TLS','number'=>'626','name'=>'东帝汶','name_en'=>'Timor-Leste'],
            ['id'=>224, 'code2'=>'TM','code3'=>'TKM','number'=>'795','name'=>'土库曼斯坦','name_en'=>'Turkmenistan'],
            ['id'=>225, 'code2'=>'TN','code3'=>'TUN','number'=>'788','name'=>'突尼斯','name_en'=>'Tunisia'],
            ['id'=>226, 'code2'=>'TO','code3'=>'TON','number'=>'776','name'=>'汤加','name_en'=>'Tonga'],
            ['id'=>227,'code2'=>'TR','code3'=>'TUR','number'=>'792','name'=>'土耳其','name_en'=>'Turkey'],
            ['id'=>228, 'code2'=>'TT','code3'=>'TTO','number'=>'780','name'=>'特立尼达和多巴哥','name_en'=>'Trinidad and Tobago'],
            ['id'=>229, 'code2'=>'TV','code3'=>'TUV','number'=>'798','name'=>'图瓦卢','name_en'=>'Tuvalu'],
            ['id'=>230, 'code2'=>'TW','code3'=>'TWN','number'=>'158','name'=>'台湾','name_en'=>'Taiwan, Province of China'],
            ['id'=>231, 'code2'=>'TZ','code3'=>'TZA','number'=>'834','name'=>'坦桑尼亚','name_en'=>'Tanzania, United Republic of'],
            ['id'=>232, 'code2'=>'UA','code3'=>'UKR','number'=>'804','name'=>'乌克兰','name_en'=>'Ukraine'],
            ['id'=>233, 'code2'=>'UG','code3'=>'UGA','number'=>'800','name'=>'乌干达','name_en'=>'Uganda'],
            ['id'=>234, 'code2'=>'UM','code3'=>'UMI','number'=>'581','name'=>'美国本土外小岛屿','name_en'=>'United States Minor Outlying Islands'],
            ['id'=>235, 'code2'=>'US','code3'=>'USA','number'=>'840','name'=>'美国','name_en'=>'United States'],
            ['id'=>236, 'code2'=>'UY','code3'=>'URY','number'=>'858','name'=>'乌拉圭','name_en'=>'Uruguay'],
            ['id'=>237, 'code2'=>'UZ','code3'=>'UZB','number'=>'860','name'=>'乌兹别克斯坦','name_en'=>'Uzbekistan'],
            ['id'=>238, 'code2'=>'VA','code3'=>'VAT','number'=>'336','name'=>'梵蒂冈','name_en'=>'Holy See (Vatican City State)'],
            ['id'=>239, 'code2'=>'VC','code3'=>'VCT','number'=>'670','name'=>'圣文森特和格林纳丁斯','name_en'=>'Saint Vincent and the Grenadines'],
            ['id'=>240, 'code2'=>'VE','code3'=>'VEN','number'=>'862','name'=>'委内瑞拉','name_en'=>'Venezuela, Bolivarian Republic of'],
            ['id'=>241, 'code2'=>'VG','code3'=>'VGB','number'=>'092','name'=>'英属维尔京群岛','name_en'=>'Virgin Islands, U.S.'],
            ['id'=>242, 'code2'=>'VI','code3'=>'VIR','number'=>'850','name'=>'美属维尔京群岛','name_en'=>'United States Virgin Islands'],
            ['id'=>243,'code2'=>'VN','code3'=>'VNM','number'=>'704','name'=>'越南','name_en'=>'Vietnam'],
            ['id'=>244, 'code2'=>'VU','code3'=>'VUT','number'=>'548','name'=>'瓦努阿图','name_en'=>'Vanuatu'],
            ['id'=>245, 'code2'=>'WF','code3'=>'WLF','number'=>'876','name'=>'瓦利斯和富图纳','name_en'=>'Wallis and Futuna'],
            ['id'=>246, 'code2'=>'WS','code3'=>'WSM','number'=>'882','name'=>'萨摩亚','name_en'=>'Samoa'],
            ['id'=>247, 'code2'=>'YE','code3'=>'YEM','number'=>'887','name'=>'也门','name_en'=>'Yemen'],
            ['id'=>248, 'code2'=>'YT','code3'=>'MYT','number'=>'175','name'=>'马约特','name_en'=>'Mayotte'],
            ['id'=>249, 'code2'=>'ZA','code3'=>'ZAF','number'=>'710','name'=>'南非','name_en'=>'South Africa'],
            ['id'=>250, 'code2'=>'ZM','code3'=>'ZMB','number'=>'894','name'=>'赞比亚','name_en'=>'Zambia'],
            ['id'=>251, 'code2'=>'ZW','code3'=>'ZWE','number'=>'716','name'=>'津巴布韦','name_en'=>'Zimbabwe']
        ];

        $this->insert('country', $rows);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('country');
    }
}
