<?php
namespace woo\mapper;

require_once("base/Registry.php");
require_once("mapper/SelectionFactory.php");
require_once("base/ApplicationRegistry.php");
class DomainObjectAssembler{
	protected static $PDO;
	function __construct(PersistenceFactory $factory){
		$this->factory = $factory;
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
            self::$PDO->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
			self::$PDO->exec("SET NAMES 'utf8';");
		}
	}
	
    static function getPDO(){
	    return self::$PDO;
	}
	
	function getStatement($str){
		if(!isset($this->statements[$str])){
			$this->statements[$str] = self::$PDO->prepare($str);
		}
		return $this->statements[$str];
	}

	function findOne(IdentityObject $idobj){
		$collection = $this->find($idobj);
		return $collection->next();
	}

	function find(IdentityObject $idobj){
		$selfact = $this->factory->getSelectionFactory();
		list($selection,$values) = $selfact->newSelection($idobj);
		$stmt = $this->getStatement($selection);
		$result = null;
		if($stmt){
			$stmt->execute($values);
			$raw = $stmt->fetchAll();
			$result = $this->factory->getCollection($raw);
		}
		return $result;
	}

	function insert(\woo\domain\DomainObject $obj){
		try{
			$upfact = $this->factory->getUpdateFactory();
			list($update,$values) = $upfact->newUpdate($obj);
			$stmt = $this->getStatement($update);
			$stmt->execute($values);
			if($obj->getId()<0){
				$obj->setId(self::$PDO->lastInsertId());
			}
			$obj->markClean();
		}catch(PDOException $e){
			throw new \woo\base\AppException($e->getMessage());
		}
	}

	function delete(IdentityObject $idobj){
		$delfact = $this->factory->getDeletionFactory();
		list($delection,$values) = $delfact->newSelection($idobj);
		$stmt = $this->getStatement($delection);
		$result = false;
		if($stmt){
			$result = $stmt->execute($values);
		}
    return $result;
	}
}
?>