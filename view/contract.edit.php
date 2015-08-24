<?php
//============================================================+
// File name   : example_038.php
// Begin       : 2008-09-15
// Last Update : 2013-05-14
//
// Description : Example 038 for TCPDF class
//               CID-0 CJK unembedded font
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: CID-0 CJK unembedded font
 * @author Nicola Asuni
 * @since 2008-09-15
 */

// Include the main TCPDF library (search for installation path).
require_once('view\contract.edit.include.php');
require_once('domain\Order.php');
require_once('domain\Product.php');
require_once('domain\Bill.php');
require_once('domain\Transport.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF {

	// 转换人民币为大写
	public function RMB($money) {
		$money = round($money, 2);    // 四舍五入
    
		if ($money <= 0) {
			return '零元';
		}
    
		$units = array ( '', '拾', '佰', '仟', '', '万', '亿', '兆' );
		$amount = array( '零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖' );
    
		$arr = explode('.', $money);    // 拆分小数点
		$money = strrev($arr[0]);        // 翻转整数
		$length = strlen($money);        // 获取数字的长度
    
		for ($i = 0; $i < $length; $i++) {
			$int[$i] = $amount[$money[$i]];    // 获取大写数字
			if (!empty($money[$i])) {
				$int[$i] .= $units[$i%4];    // 获取整数位
			} 
        
			if ($i%4 == 0) {
				$int[$i] .= $units[4+floor($i/4)];    // 取整
			}
		}
    
		$con = isset($arr[1]) ? '元' . $amount[$arr[1][0]] . '角' . $amount[$arr[1][1]] . '分' : '元整'; 
		return implode('', array_reverse($int)) . $con;    // 整合数组为字符串
	}

	// Load table data from file
	public function LoadProduct($order) {
		$proids = array();
		$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",array('id','orderid','proid','number','price','returnnow','modlcharge'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('orderid')->eq($order->getId());
		$order_pro = $finder->find($idobj);

		$factory = \woo\mapper\PersistenceFactory::getFactory("product",array('id','classid','length','width','think','unitlen','unitwid','unitthi','unit'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$pro = $order_pro->next();
		$i = 0;
		$data = array();
		$shipdate = $order->getShipdate();
		$ship = $shipdate=='0'? "现货":sprintf("%d%s",$shipdate," 天");
		while($pro){
			$data[$i][0] = $i+1;
			$idobj = $factory->getIdentityObject()->field('id')->eq($pro->getProid());
			$collection = $finder->find($idobj);
			$product = $collection->current();
			$data[$i][1] = $product->getLongname();
			$data[$i][2] = $product->getSize();
			$data[$i][3] = $product->getUnit();
			$data[$i][4] = $pro->getNumber();
			$data[$i][5] = sprintf("%.2f",$pro->getPrice()-$pro->getReturnnow());
			$data[$i][6] = sprintf("%.2f",$pro->getModlcharge());
			$data[$i][7] = sprintf("%.2f",$data[$i][4]*$data[$i][5]+$data[$i][6]);
			$data[$i][8] = $ship;
			$i++;
			$pro = $order_pro->next();
		}
		return $data;
	}


	// Colored table
	public function ColoredTable($header,$data,$footer,$pos="left") {
		// Colors, line width and bold font
		$this->SetFillColor(245,245,245);
		$this->SetTextColor(0,0,0);
		$this->SetDrawColor(128,128,128);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B');
		// Header
		$w = array(40, 35, 40, 45);
		$num_headers = count($header);
		$tablewidth = 0;
		for($i = 0; $i < $num_headers; ++$i) {
			$tablewidth += $header[$i][0];
		}
		switch($pos){
			case 'left': $spac = 10;break;
			case 'center': $spac = (210-$tablewidth)/2;break;
			case 'right': $spac = 210-$tablewidth;break;
			default: $spac = (int)$pos+10;
		}
		$this->SetX($spac);
		for($i = 0; $i < $num_headers; ++$i) {
			$this->Cell($header[$i][0], 7, $header[$i][1], 1, 0, 'C', 1);
		}
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(245, 245, 245);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach($data as $row) {
			$this->SetX($spac);
			for($i = 0; $i < $num_headers; ++$i)
				$this->Cell($header[$i][0], 6, $row[$i], 'LR', 0, $header[$i][2], $fill);
			$this->Ln();
			$fill=!$fill;
		}
		if($footer != null){
			$fill = !0;
			$this->SetX($spac);
			$num_footers = count($footer);
			for($i = 0; $i < $num_footers; ++$i)
				$this->Cell($footer[$i][0], 6, $footer[$i][1], 'LTR', 0, $footer[$i][2], $fill);
			$this->Ln();
		}
		$this->SetX($spac);
		$this->Cell($tablewidth, 0, '', 'T');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 038');
//$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 20);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('droidsansfallback', '', 24);

$txt = '产 品 购 销 合 同';
$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

$request = \woo\base\RequestRegistry::getRequest();
$orderid = $request->getProperty("orderid");
$factory = \woo\mapper\PersistenceFactory::getFactory("order",array('id','userid','billtypeid','transportid','shipdate','code','downpayment','guarantee','guaranteeperiod','carriagecharge'));
$finder = new \woo\mapper\DomainObjectAssembler($factory);
$idobj = $factory->getIdentityObject()->field('id')->eq($orderid);
$collection = $finder->find($idobj);
$order = $collection->current();

$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','companyid'));
$finder = new \woo\mapper\DomainObjectAssembler($factory);
$idobj = $factory->getIdentityObject()->field('id')->eq($order->getUserid());
$collection = $finder->find($idobj);
$custom = $collection->current();
//$company2 = $custom->getCompany();

$factory = \woo\mapper\PersistenceFactory::getFactory("company",array('id','name','address','tel','fax','bank','account'));
$finder = new \woo\mapper\DomainObjectAssembler($factory);
$idobj = $factory->getIdentityObject()->field('id')->eq('0');
$collection = $finder->find($idobj);
$company1 = $collection->current();

$idobj = $factory->getIdentityObject()->field('id')->eq($custom->getCompanyid());
$collection = $finder->find($idobj);
$company2 = $collection->current();


$no = "YRR".$order->getCode();

$pdf->SetFont('droidsansfallback', '', 9);

// define barcode style
$style = array(
	'position' => '',
	'align' => 'C',
	'stretch' => false,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => false,
	'hpadding' => 'auto',
	'vpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 8,
	'stretchtext' => 4
);

$rh = 7;

$y = 20;
$pdf->SetXY(10,$y);
$pdf->Write($rh,"甲方: ".$company1->getName()); 
$pdf->SetXY(150,$y);
$pdf->Write($rh,"合同编号: YRR".$order->getCode()); 

$pdf->SetXY(83,$y);
$pdf->write1DBarcode('C'.$order->getCode(), 'C128', '', '', '', 18, 0.4, $style, 'N');
$y += $rh;
$pdf->SetXY(10,$y);
$pdf->Write($rh,"乙方: ".$company2->getName()); 
$pdf->SetXY(150,$y);
date_default_timezone_set("PRC");
$date = "签订日期:  ".date("Y-m-d");

$pdf->Write($rh,$date);
$y += $rh; 
$pdf->SetXY(16,$y);
$pdf->Write($rh,'依据《中华人民共和国合同法》，为明确双方的权利和义务，双方本着平等、协商一致的原则，特订立本合同，以便共同遵守。'); 
$y += $rh;
$pdf->SetXY(10,$y);
$pdf->Write($rh,'一、产品名称、规格型号、数量、单价、金额、交货期限：'); 
$y += $rh;
$pdf->SetXY(10,$y);


$header = array(array(8,'序号','C'),array(50,'产品名称','L'),array(30,'规格型号','L'),array(8,'单位','C'),
               array(10,'数量','R'),array(18,'单价','R'),array(20,'模具费','R'),array(20,'金额','R'),array(15,'交货时间','C'));

// data loading
$products = $pdf->LoadProduct($order);

$sum = 0;
foreach($products as $product){
	$y += 6;
	$sum += $product[7];
}
$sumcn = "人民币大写：".$pdf->RMB($sum);

$footer = array(array(8,'合计','C'),array(80,$sumcn,'R'),array(91,sprintf("人民币小写：￥%.2f",$sum),'R'));

// print colored table
$pdf->ColoredTable($header, $products,$footer,8);

$y = $pdf->GetY();
$pdf->SetXY(10,$y);
$pdf->Write($rh,'二、质量要求及物理化学指标：'); 
$y += $rh;
$pdf->SetXY(10,$y);
$header = array(array(8,'项目','C'),array(10,'密度','C'),array(20,'显气孔率','C'),array(20,'常温抗折','C'),array(20,'常温抗压','C'),array(10,'SiC','C'),
            array(10,'N4Si3','C'),array(10,'SiO2','C'),array(10,'Si','C'),array(10,'Fe2O3','C'),array(20,'尺寸公差','C'));
$qs = array(array('单位','g/cm3','%','MPa','MPa','%','%','%','%','%','mm'),
            array('指标','2.60','≤ 16','≥ 40','≥ 150','≥ 75','≥ 20','≤ 0.5','≤ 0.5','≤ 0.5','± 1'));
$pdf->ColoredTable($header, $qs,null,8);

$y += 3*$rh-2;
$pdf->SetXY(10,$y);
$pdf->Write($rh,'三、结算方式、条件及期限：'); 

$payment = $order->getDownpayment();
$guarantee = $order->getGuarantee();
$str = "";
if($payment==100)
	$str = "合同签订后，乙方即预付全部货款给甲方。交货时间从甲方收到乙方全部货款时起算。";
else if($payment>0){
	$str = "合同签订后，乙方预付货款总额的 $payment% 给甲方。";
	if($guarantee==0)
		$str .= "发货前，乙方须一次性付清余款。";
	else
		$str .= "发货前，乙方须再支付货款的 ".(100-$payment-$guarantee)."%。余款在交货后 ".$order->getGuaranteeperiod()." 个月内付清。";
	$str .= "交货时间从甲方收到乙方预付货款时起算。";
}else{
	if($guarantee==0)
		$str .= "发货前，乙方须一次性支付全部货款给甲方。";
	else
		$str .= "发货前，乙方须支付全部货款的 ".(100-$guarantee)."% 给甲方。余款在交货后 ".$order->getGuaranteeperiod()." 个月内付清。";
}

$factory = \woo\mapper\PersistenceFactory::getFactory("bill",array('id','name'));
$finder = new \woo\mapper\DomainObjectAssembler($factory);
$idobj = $factory->getIdentityObject()->field('id')->eq($order->getBilltypeid());
$collection = $finder->find($idobj);
$bill = $collection->current();
$str .=  "甲方向乙方开具全额".$bill->getName()."。";
$y += $rh-2;
$pdf->SetXY(16,$y);
$pdf->Write($rh,$str); 
$y = $pdf->GetY()+$rh;
$pdf->SetXY(10,$y);
$pdf->Write($rh,'四、运输方式及费用负担：'); 

$factory = \woo\mapper\PersistenceFactory::getFactory("transport",array('id','name','note'));
$finder = new \woo\mapper\DomainObjectAssembler($factory);
$idobj = $factory->getIdentityObject()->field('id')->eq($order->getTransportid());
$collection = $finder->find($idobj);
$transport = $collection->current();
$str = $transport->getNote();
$str = str_replace("云瑞","甲方",$str);
$str = str_replace("客户","乙方",$str);
if($order->getCarriagecharge()>0)
	$str .= "运费：¥".$order->getCarriagecharge();
$y += $rh;
$pdf->SetXY(16,$y);
$pdf->Write($rh,$transport->getName()."。 ".$str); 
$y += $rh;
$pdf->SetXY(10,$y);
$pdf->Write($rh,'五、解决合同纠纷的方式：'); 
$y += $rh;
$pdf->SetXY(16,$y);
$pdf->Write($rh,'合同在履行过程中，如双方发生争议首先通过友好协商，协商不成时均可向当地人民法院起诉。'); 
$y += $rh;
$pdf->SetXY(10,$y);
$pdf->Write($rh,'六、其它约定事项：'); 

$y += $rh;
$pdf->SetXY(16,$y);
$pdf->Write($rh,'1．对本合同条款及技术协议的任何变更、修改或增减，须经双方协商同意后授权代表签字认可。'); 
$y += $rh;
$pdf->SetXY(16,$y);
$pdf->Write($rh,'2．双方认可的传真文件、定单和图纸等均作为合同的组成部分具有同等的效力。'); 
$y = $pdf->GetY()+$rh;
$pdf->SetXY(16,$y);
$pdf->Write($rh,'3．本合同正式文本一式两份，甲乙双方各持一份。双方代表签字盖章后生效。'); 
$ltd = array(85,'','  单位名称：','  单位地址：','法定代表人：','委托代理人：','电话：','传真：','开户银行：','帐号：');

$ltd1 = array(array(85,'甲方',
						$company1->getName(),
						$company1->getAddress(),
						'',
						'',
						$company1->getTel(),
						$company1->getFax(),
						$company1->getBank(),
						$company1->getAccount()),
			array(85,'乙方',
						$company2->getName(),
						$company2->getAddress(),
						'',
						'',
						$company2->getTel(),
						$company2->getFax(),
						$company2->getBank(),
						$company2->getAccount()));
$count = count($ltd);
$y += $rh;
$y1 = $y;
$pdf->Image('images/lxmseal.png',42,$y+27,20);

// set style for barcode
$style = array(
	'border' => 0,
	'vpadding' => 'auto',
	'hpadding' => 'auto',
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255)
	'module_width' => 1, // width of a single module in points
	'module_height' => 1 // height of a single module in points
);

// QRCODE,L : QR-CODE Low error correction
$pdf->write2DBarcode("ORG:".$company1->getName().';EMAIL:'.$company1->getEmail().';M:18961386627;URL:'.$company1->getWww().';', 'QRCODE,L',74, $y+41, 30, 30, $style, 'N');
for($i=1;$i<$count;$i++){
	if($i==1){
		$line = 'T';
	}else if($i==$count-1){
		$line = 'B';
	}else{
		$line = '';
	}
	$y += $rh;
	$pdf->SetXY(19,$y);
	$pdf->Cell(25,$rh,$ltd[$i],$line."L",0,'R');
	$pdf->Cell(60,$rh,$ltd1[0][$i],$line,0,'L');
	$pdf->Cell(25,$rh,$ltd[$i],$line."L",0,'R');
	$pdf->Cell(60,$rh,$ltd1[1][$i],$line."R",0,'L');
}
$pdf->Image("images/sealcontract.png",65,$y1+8,35);


// ---------------------------------------------------------

//Close and output PDF document
$pdfname = 'YRR'.$order->getCode().'.pdf';
$pdf->Output($pdfname, 'I');

//============================================================+
// END OF FILE
//============================================================+
