<?php
namespace woo\mapper;

require_once("mapper/DomainObjectFactory.php");
class HeaderDomainObjectFactory extends DomainObjectFactory{
    function __construct(){
	    parent::__construct("Header");
	}
	function createObject(array $array){
		$old = $this->getFromMap($array['id']);
		if($old) { return $old;}

		$obj = new \woo\domain\Header($array);
		$obj->setNavigationMenu($this->getNavigationMenu());
		$this->addToMap($obj);
		return $obj;
	}

    function targetClass(){
	    return "woo\\domain\\Header";
    }

	// 生成导航栏
	private function getNavigationMenu(){
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Navigation");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('id')->gt('0');
		$collection = $finder->find($idobj);
		$result = "<a href='? cmd=ShowHome'>首　页</a>";
		while($collection->valid()){
			$row = $collection->current();
			$result .= " | &nbsp;<a href='? cmd=".$row->getCommand()."'>".$row->getName("C")."</a>";
			$collection->next();
		}
		return $result;
	}
}
?>