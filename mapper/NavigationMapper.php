<?php
namespace woo\mapper;

require_once("mapper/Mapper.php");
require_once("mapper/Collection.php");
require_once("domain/Navigation.php");
class NavigationMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM daohang WHERE id=?");
        $this->selectAllStmt = self::$PDO->prepare("SELECT * FROM daohang");
        $this->updateStmt = self::$PDO->prepare("update daohang set name=?,name_en=?,url=?,id=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into daohang (name,name_en,url) values (?,?,?)");
    }

    function getCollection(array $raw){
        return new Collection($raw,$this,"Navigation");
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\Navigation($array);
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
        $values = array($object->getName("C"),$object->getName("E"),$object->getUrl(),$object->getId(),$object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt(){
        return $this->selectStmt;
    }

    function selectAllStmt(){
        return $this->selectAllStmt;
    }

    protected function targetClass(){
	    return "woo\\domain\\Navigation";
    }
}
?>
