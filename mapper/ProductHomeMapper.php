<?php
namespace woo\mapper;

require_once("mapper/Mapper.php");
require_once("mapper/ProductDomainObjectFactory.php");
class ProductHomeMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM pro_size where id=?");
        $this->selectAllStmt = self::$PDO->prepare("SELECT * FROM pro_size where homeshow='Y' order by length,width,think");
        $this->updateStmt = self::$PDO->prepare("update pro_size set classid=?,length=?,width=?,think=?,unitlen=?,unitwid=?,unitthi=?,picture=?,sharp=?,unit=?,weight=?,homeshow=?,price=?,amount=?,cansale=?,status=?,not=?,id=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into pro_size (classid,length,width,think,unitlen,unitwid,unitthi,picture,sharp,unit,weight,homeshow,price,amount,cansale,status,not) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    }

    function getCollection(array $raw){
	    $factory = new ProductDomainObjectFactory();
        return new ProductClassCollection($raw,$factory);
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\Product($array['id']);
        $obj->setObjects($array);
        return $obj;
    }

    protected function doInsert(\woo\domain\Domainobject $object){
        print "inserting\n";
        debug_print_backtrace();
        $values = array($object->getClassid(),
			            $object->getLength(),
			            $object->getWidth(),
			            $object->getThink(),
			            $object->getLengthUnit(),
			            $object->getWidthUnit(),
			            $object->getThinkUnit(),
			            $object->getPicture(),
			            $object->getSharp(),
			            $object->getUnit(),
			            $object->getWeight(),
			            $object->getHomeshow(),
			            $object->getPrice(),
			            $object->getAmount(),
			            $object->getCansale(),
			            $object->getStatus(),
			            $object->getNot());
        $this->insertStmt->execute($values);
        $id = self::$PDO->lastInsertId();
        $object->setId($id); 
    }

    function update(\woo\domain\Domainobject $object){
        print "updating\n";
        $values = array($object->getClassid(),
			            $object->getLength(),
			            $object->getWidth(),
			            $object->getThink(),
			            $object->getLengthUnit(),
			            $object->getWidthUnit(),
			            $object->getThinkUnit(),
			            $object->getPicture(),
			            $object->getSharp(),
			            $object->getUnit(),
			            $object->getWeight(),
			            $object->getHomeshow(),
			            $object->getPrice(),
			            $object->getAmount(),
			            $object->getCansale(),
			            $object->getStatus(),
			            $object->getNot(),
			            $object->getId(),
			            $object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt(){
        return $this->selectStmt;
    }

    function selectAllStmt(){
        return $this->selectAllStmt;
    }

    protected function targetClass(){
	    return "woo\\domain\\Product";
    }
}
?>
