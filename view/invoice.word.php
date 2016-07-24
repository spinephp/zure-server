<?php
//============================================================+
// File name   : contract.edit.php
// Begin       : 2008-09-15
// Last Update : 2016-06-02
//
// Description : Contract for TCPDF class
//               CID-0 CJK unembedded font
//
// Author: Xingming Liu
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
//namespace woo\view;

// Include the main TCPDF library (search for installation path).
require_once('invoice.word.include.php');
require_once('domain/Order.php');
require_once('domain/Product.php');
require_once('domain/Bill.php');
require_once('domain/Transport.php');
// New Word Document

// Load table data from file
function LoadProduct($orderid) {
	$proids = array();
	$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",array('id','orderid','proid','number','price','returnnow','modlcharge'));
	$finder = new \woo\mapper\DomainObjectAssembler($factory);
	$idobj = $factory->getIdentityObject()->field('orderid')->eq($orderid);
	$order_pro = $finder->find($idobj);

	$factory = \woo\mapper\PersistenceFactory::getFactory("product",array('id','classid','length','width','think','unitlen','unitwid','unitthi','unit','sharp','note'));
	$finder = new \woo\mapper\DomainObjectAssembler($factory);
	$pro = $order_pro->next();
	$i = 0;
	$data = array();
	while($pro){
		$data[$i][0] = $i+1;
		$idobj = $factory->getIdentityObject()->field('id')->eq($pro->getProid());
		$collection = $finder->find($idobj);
		$product = $collection->current();
		$data[$i][1] = $product->getSize();
		$data[$i][2] = $pro->getNumber();
		$price = $pro->getPrice()-$pro->getReturnnow();
		$data[$i][3] = sprintf("%.2f",$price);
		$data[$i][4] = sprintf("%.2f",$price*$data[$i][2]);
		$i++;
		$pro = $order_pro->next();
	}
	return $data;
}

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add header
//$header = $section->createHeader();
//$table = $header->addTable();
//$table->addRow();
//$table->addCell(4500)->addText('This is the header.');
//$table->addCell(4500)->addImage('_earth.jpg', array('width'=>50, 'height'=>50, 'align'=>'right'));

// Add footer
//$footer = $section->createFooter();
//$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));

// Write some text
$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>false,'size'=>22)); 
$PHPWord->addParagraphStyle('pStyle', array('align'=>'center','spaceAfter'=>100)); 
$PHPWord->addParagraphStyle('lStyle', array('align'=>'left','spaceAfter'=>100)); 
$PHPWord->addParagraphStyle('prStyle', array('align'=>'right','spaceAfter'=>100)); 
$section->addTextBreak();
$section->addText('连云港云瑞耐火材料有限公司', 'rStyle', 'pStyle');
$PHPWord->addFontStyle('r1Style', array('bold'=>true, 'italic'=>false,'size'=>14)); 
$section->addText('LIANYUNGANG YUNRUI REFRACTORY CO., LTD.', 'r1Style', 'pStyle');
$PHPWord->addFontStyle('r2Style', array('bold'=>false, 'italic'=>false,'size'=>10.5)); 
$section->addText('15 LIANYUNGANG ECONOMIC AND TECHNICAL DEV. ZONE, JIANGSU, CHINA', 'r2Style', 'pStyle');

$section->addTextBreak();
$section->addTextBreak();
$PHPWord->addFontStyle('r3Style', array('bold'=>true, 'italic'=>false,'size'=>16)); 
$section->addText('COMMERCIAL INVOICE', 'r3Style', 'pStyle');
$section->addText('ORIGINAL', 'r3Style', 'pStyle');

//$PHPWord->setDefaultFontName('Times New Roman'); // 全局字体
//定义样式数组
$styleTable = array(
'borderSize'=>0,
'borderColor'=>'ffffff',
'cellMargin'=>80
);
$styleFirstRow = array(
'borderBottomSize'=>0,
'borderBottomColor'=>'ffffff',
'bgColor'=>'ffffff'
);

//定义单元格样式数组
$styleCell = array('valign'=>'center');
$styleCellBTLR = array('valign'=>'center','textDirection'=>PHPWord_Style_Cell::TEXT_DIR_BTLR);

//定义第一行的字体
$fontStyle = array('bold'=>true,'align'=>'center');

//添加表格样式
$PHPWord->addTableStyle('myOwnTableStyle',$styleTable,$styleFirstRow);

$PHPWord->addFontStyle('r4Style', array('name'=>'Times New Roman','bold'=>false, 'italic'=>false,'size'=>10)); 
$request = \woo\base\RequestRegistry::getRequest();
$orderid = $request->getProperty("orderid");
$factory = \woo\mapper\PersistenceFactory::getFactory("order",array('id','userid','code'));
$finder = new \woo\mapper\DomainObjectAssembler($factory);
$idobj = $factory->getIdentityObject()->field('id')->eq($orderid);
$collection = $finder->find($idobj);
$order = $collection->current();
$userid = $order->getUserid();
$code = $order->getCode();

//添加表格
$table = $section->addTable('myOwnTableStyle');
$table->addRow();
$table->addCell(4000, $styleCell)->addText('To: ASHINE INDUSTRIES INC.','r4Style');
$table->addCell(4000, $styleCell)->addText('');
$table->addRow();
$table->addCell(4000, $styleCell)->addText('      272 PARKVILLE AVENUE','r4Style');
$table->addCell(3500, $styleCell)->addText('Invoice No.:','r4Style','prStyle');
$table->addCell(4000, $styleCell)->addText('YRR'.$code,'r4Style','lStyle');
$table->addRow();
$table->addCell(4000, $styleCell)->addText('      NEW YORK, NY 11230,USA','r4Style');
$table->addCell(3500, $styleCell)->addText('Date:','r4Style','prStyle');
$table->addCell(4000, $styleCell)->addText(date('F j,Y'),'r4Style','lStyle');
$section->addText('===============================================================================', 'r4Style', 'pStyle');
$section->addText('Shipping Marks   Names & Specifications      Quantities                   Unit Price                 Amount', 'r4Style', 'pStyle');
$section->addText('---------------------------------------------------------------------------------------------------------------------------------------', 'r4Style', 'pStyle');

//添加表格
$table = $section->addTable('myOwnTableStyle');
$table->addRow();
$table->addCell(8000, $styleCell)->addText('PRODUCT CODE #3535','r4Style');
$table->addRow();
$table->addCell(8000, $styleCell)->addText('          FOB   QINGDAO  TO  USA');
$table->addRow();
$table->addCell(8000, $styleCell)->addText('          N/M  KILN  SUPPORT');
$table->addRow();
$table->addCell(3000, $styleCell)->addText('','r4Style');

$products = LoadProduct($orderid);

$sum = 0;
$num = 0;
foreach($products as $product){
	$table->addRow();
	$table->addCell(2000, $styleCell)->addText('','r4Style');
	$table->addCell(2500, $styleCell)->addText($product[1],'r4Style','lStyle');
	$table->addCell(1200, $styleCell)->addText($product[2].'PCS','r4Style');
	$table->addCell(1800, $styleCell)->addText('USD'.$product[3].'/PC','r4Style');
	$table->addCell(1500, $styleCell)->addText('USD'.$product[4],'r4Style');
	$sum += $product[4];
	$num += $product[2];
}
$table->addRow();
$table->addCell(2000, $styleCell)->addText('','r4Style');
$table->addCell(2500, $styleCell)->addText('TOTAL:','r4Style','lStyle');
$table->addCell(1200, $styleCell)->addText($num.'PCS','r4Style');
$table->addCell(1800, $styleCell)->addText('','r4Style');
$table->addCell(1500, $styleCell)->addText('USD'.$sum,'r4Style');

$section->addTextBreak(3);
$section->addText("TOTAL{$num}PCS  (4 PALLETS,3.4 M3)", 'r2Style', 'pStyle');
$section->addText('G.W.:5.00MTS  N.W.:4.80MTS', 'r2Style', 'pStyle');
$section->addText('PACKING: NON-SOLID WOOD PACKGING', 'r2Style', 'pStyle');
$section->addText('MERCHANDISE IS IN ACCORDANCE WITH ORDER#3535', 'r2Style', 'pStyle');

// Save File
$fileName = "word报表".date("YmdHis"); 
header("Content-type: application/vnd.ms-word"); 
header("Content-Disposition:attachment;filename=".$fileName.".docx"); 
header('Cache-Control: max-age=0'); 
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('php://output'); 
//$objWriter->save('HeaderFooter.docx');

?>
