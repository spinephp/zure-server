<?php
namespace woo\mapper;

require_once("mapper/Mapper.php");
require_once("mapper/NavigationMapper.php");
class HeaderMapper extends Mapper{
    function __construct(){
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare("SELECT * FROM qiye where id=?");
        $this->updateStmt = self::$PDO->prepare("update Venue set name=?,id=? WHERE id=?");
        $this->insertStmt = self::$PDO->prepare("insert into Venue (name) values (?)");
    }

    function getCollection(array $raw){
        return new HeaderCollection($raw,$this);
    }

    protected function doCreateobject(array $array){
        $obj = new \woo\domain\Header($array);
		$obj->setNavigationMenu($this->getNavigationMenu());
		$obj->setNavigation($this->getNavigation());
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

    protected function targetClass(){
	    return "woo\\domain\\Header";
    }

	// 生成导航栏
	private function getNavigationMenu(){
        $mapper = new \woo\mapper\NavigationMapper();
		$collection = $mapper->findAll();
		$result = "<a href=/yrr>首　页</a>";
		while($collection->valid()){
			$row = $collection->current();
			$result .= " | &nbsp;<a href='".$row->getUrl()."'>".$row->getName("C")."</a>";
			$collection->next();
		}
		return $result;
	}

	private function getNavigation(){

        $str = $_SERVER["REQUEST_URI"];
        $len = strlen($str);
		$result = "";
		//$obj = $this->find(1);
        if($len>1){
//			$reault .= "<a href=/>".$row["name"]."</a>";
//			$substr = substr($str,1,$len-1);
//			while($pos1 = strpos($substr,"/"))
//			$substr = substr($substr,$pos1+1);
//			$pos = strpos($substr,"?");
//			if($pos>0){
//				$file = substr($substr,0,$pos);
//				$para = substr($substr,$pos+1);
//			}
//			else
//				$file = substr($substr,0);
//            $sqldh1="select * from daohang where url='".$file."'";
//			$querydh1=mysql_query($sqldh1);
//			$rowdh1=mysql_fetch_array($querydh1);
//			if(strcasecmp($file,$rowdh1["url"])==0)
//			{
//				if($pos>0)
//				    $reault .= "<font size=2 color=#00aaaa> >> </font><a href='".$file."'>".$rowdh1["name"]."</a><font size=2 color=#00aaaa> >> 文章内容</font>";
//				else
//				    $result .= "<font size=2 color=#00aaaa> >> ".$rowdh1["name"]."</font>";
//									
//			}
        }
        else
            //$result .= "<font size=2 color=#00aaaa>".$obj->getName()."</font>";
		return $result;
	}
}
?>
