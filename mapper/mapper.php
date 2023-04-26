<?php
namespace woo\mapper;
require_once("base/ApplicationRegistry.php");

abstract class Mapper{
    protected static $PDO;
    function __construct(){
        if(!isset(self::$PDO)){
            $dsn = \woo\base\ApplicationRegistry::getDSN();
			$dbuser = \woo\base\ApplicationRegistry::getDBUser();
			$dbpwd = \woo\base\ApplicationRegistry::getDBPwd();
            if(is_null($dsn)){
                throw new \woo\base\AppException("NO DSN");
            }
			if(is_array($dsn))
                self::$PDO = new \PDO($dsn['dsn'],$dsn['dbuser'],$dsn['dbpwd']);
			else
                self::$PDO = new \PDO($dsn,$dbuser,$dbpwd);
//            self::$PDO = new \PDO($dsn,$dbuser,$dbpwd);
            self::$PDO->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
			      self::$PDO->exec("SET NAMES 'utf8';");
        }
    }

    private function getFromMap($id){
		return \woo\domain\ObjectWatcher::exists($this->targetClass(),$id);
	}

    private function addToMap(\woo\domain\DomainObject $obj){
		return \woo\domain\ObjectWatcher::add($obj);
	}

    function find($id){
		$old = $this->getFromMap($id);
		if($old){ return $old;}
		
		// 数据库操作
        $this->selectStmt()->execute(array($id));
        $array = $this->selectStmt()->fetch();
        $this->selectStmt()->closecursor();
        if(!is_array($array)){return null;}
        if(!isset($array['id'])){ return null;}
        $object = $this->createobject($array);
        return $object;
    }

    function findAll(){
		$this->selectAllStmt()->execute(array());
		return $this->getCollection($this->selectAllStmt()->fetchAll(\PDO::FETCH_ASSOC));
	}

    function createobject($array){
		$old = $this->getFromMap($array['id']);
		if($old){ return $old;}

        $obj = $this->doCreateobject($array);
		$this->addToMap($obj);
		$obj->markClean();
        return $obj;
    }

    function insert(\woo\domain\Domainobject $obj){
		// 用新id更新insert. $obj
        $this->doInsert($obj);

		$this->addToMap($obj);
    }

    protected abstract function targetClass();
    abstract function update(\woo\domain\Domainobject $object);
    protected abstract function doCreateobject(array $array);
    protected abstract function doInsert(\woo\domain\Domainobject $object);
    protected abstract function selectStmt();
}
?>
