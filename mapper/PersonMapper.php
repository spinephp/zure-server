<?php
namespace woo\mapper;

require_once("map/Mapper.php");
class PersonMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM person WHERE id=?");
        $this->updateStmt = self::$PDO->prepare("update person set name=?,id=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into person (name) values (?)");
    }

    function getCollection(array $raw){
        return new SpaceCollection($raw,$this);
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\Person($array['id']);
        $obj->setname($array['name']);
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
        $values = array($object->getName(),$object->getId(),$object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt(){
        return $this->selectStmt;
    }
}
?>
