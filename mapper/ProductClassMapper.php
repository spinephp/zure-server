<?php
namespace woo\mapper;

require_once("mapper/Mapper.php");
require_once("mapper/PersistenceFactory.php");
class ProductClassMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM productclass where id=?");
        $this->selectAllStmt = self::$PDO->prepare("SELECT * FROM productclass order by id asc");
		$this->selectRecursionStmt = self::$PDO->prepare("SELECT p2.name FROM productclass p1, productclass p2 WHERE p1.id = p2.parentid AND (p1.id=? OR p1.parentid=?)");
        $this->updateStmt = self::$PDO->prepare("update productclass set parentid=?,name=?,name_en=?,introduce=?,introduce_en=?,picture=?,homeshow=?,id=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into productclass (parentid,name,name_en,introduce,introduce_en,picture,homeshow) values (?,?,?,?,?)");
    }

    function getCollection(array $raw){
	    $factory = new DomainObjectFactory("Productclass");
        return new Collection($raw,$factory,"Productclass");
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\Productclass($array);
        return $obj;
    }

    function recursion($classid){
        $this->selectRecursionStmt->execute($classid,$classid);
        $array = $this->selectRecursionStmt->fetch();
        $this->selectRecursionStmt->closecursor();
		return doCreateobject($array);
	}

    protected function doInsert(\woo\domain\Domainobject $object){
        print "inserting\n";
        debug_print_backtrace();
        $values = array($object->getParentid(),$object->getName("C"),$object->getName("E"),$object->getIntorduce("C"),$object->getIntorduce("E"),$object->getPiture(),$object->getHomeshow());
        $this->insertStmt->execute($values);
        $id = self::$PDO->lastInsertId();
        $object->setId($id); 
    }

    function update(\woo\domain\Domainobject $object){
        print "updating\n";
        $values = array($object->getParentid(),$object->getName("C"),$object->getName("E"),$object->getIntorduce("C"),$object->getIntorduce("E"),$object->getPiture(),$object->getHomeshow(),$object->getId(),$object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt(){
        return $this->selectStmt;
    }

    function selectAllStmt(){
        return $this->selectAllStmt;
    }

    protected function targetClass(){
	    return "woo\\domain\\Productclass";
    }
}
?>
