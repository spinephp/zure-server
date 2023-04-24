<?php
namespace woo\mapper;

require_once("base/Registry.php");
require_once("mapper/SelectionFactory.php");
require_once("base/ApplicationRegistry.php");

//数据映射器
class DomainObjectAssembler{
	protected static $PDO;

    //PersistenceFactory本例中并未实现，按原书的说法读者自己完成
    //其主要功能就是生成相应类型的选择工厂类、更新工厂类或Collection对象
    //在初始化的时候根据传入的PersistenceFactory对象决定了这个类是映射那个数据库表和领域模型的
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
			self::$PDO->exec("SET NAMES 'utf8mb4';");
		}
	}
	
    static function getPDO(){
	    return self::$PDO;
	}

	//获取预处理对象，用sql语句本身做为对象数组的键
	function getStatement($str){
		if(!isset($this->statements[$str])){
			$this->statements[$str] = self::$PDO->prepare($str);
		}
		return $this->statements[$str];
	}

	//根据where条件返回一条数据
	function findOne(IdentityObject $idobj){
		$collection = $this->find($idobj);
		return $collection->next();
	}

	//根据where条件返回一个数据集合
	function find(IdentityObject $idobj){
		$selfact = $this->factory->getSelectionFactory();
		list($selection,$values) = $selfact->newSelection($idobj);
		// echo( json_encode($selection)."=>". json_encode($values));
		$stmt = $this->getStatement($selection);
		$result = null;
		if($stmt){
			$stmt->execute($values);
			$raw = $stmt->fetchAll();
			$result = $this->factory->getCollection($raw);
		}
		return $result;
	}

	//根据where条件插入或更新一条数据
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