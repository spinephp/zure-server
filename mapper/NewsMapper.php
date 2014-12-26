<?php
namespace woo\mapper;

require_once("mapper/Mapper.php");
require_once("mapper/DomainObjectFactory.php");
class NewsMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM news where id=?");
        $this->selectAllStmt = self::$PDO->prepare("SELECT * FROM news order by time");
        $this->updateStmt = self::$PDO->prepare("update news set title=?,title_en=?,content=?,content_en=?,id=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into news (title,title_en,content,content_en,time) values (?,?,?,?,?)");
    }

    function getCollection(array $raw){
	    $factory = new DomainObjectFactory("News");
        return new NewsCollection($raw,$factory);
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\News($array['id']);
		$obj->setTitle($array['name']);
		$obj->setTitle_en($array['name_en']);
		$obj->setContent($array['content']);
		$obj->setContent_en($array['content_en']);
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
        $values = array($object->getName("C"),$object->getName("E"),$object->getContent("C"),$object->getContent("E"),$object->getId(),$object->getId());
        $this->updateStmt->execute($values);
    }

    function selectStmt(){
        return $this->selectStmt;
    }

    function selectAllStmt(){
        return $this->selectAllStmt;
    }

    protected function targetClass(){
	    return "woo\\domain\\News";
    }
}
?>
