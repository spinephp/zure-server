<?php
namespace woo\mapper;

require_once("map/Mapper.php");
require_once("map/NavigationMapper.php");
class QiyeMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM qiye where id=?");
        $this->updateStmt = self::$PDO->prepare("update qiye set name=?,name_en=?,domain=?,qq=?,tel=?,fax=?,address=?,address_en=?,email=?,tectel=?,business=?,techical=?,icp=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into qiye (name,name_en,domain,qq,tel,fax,address,address_en,email,tectel,business,techical,icp) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");
    }

    function getCollection(array $raw){
        return new QiyeCollection($raw,$this);
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\Qiye($array['id']);
		  $obj->setObjects($array);
      return $obj;
    }

    protected function doInsert(\woo\domain\Domainobject $object){
        print "inserting\n";
        debug_print_backtrace();
        $values = array($object->getName());
        $this->insertStmt->execute($values);
        $id = self::$PDO->lastInsertId();
        $object->setId($id); 
    }

    function update(\woo\domain\Domainobject $object){
        print "updating\n";
        $values = array($object->getName("C"),
			            $object->getName("E"),
			            $object->getDomain(),
			            $object->getQq(),
			            $object->getTel(),
			            $object->getFax(),
			            $object->getAddress("C"),
			            $object->getAddress("E"),
			            $object->getEmail(),
			            $object->getTectel(),
			            $object->getBusiness(),
			            $object->getTechnical(),
			            $object->getIcp(),
			            $object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt(){
        return $this->selectStmt;
    }

    protected function targetClass(){
	    return "woo\\domain\\Qiye";
    }
}
?>
