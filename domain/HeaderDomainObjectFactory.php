<?php
namespace woo\mapper;

class HeaderDomainObjectFactory extends DomainObjectFactory{
	function createObject(array $array){
		$old = $this->getFromMap($array['id']);
		if($old) { return $old;}

		$obj = new \woo\domain\Header($array['id']);
		$obj->setName($array['name']);
		$obj->setDomain($array['domain']);
		$obj->setNavigationMenu($this->getNavigationMenu());
		$obj->setNavigation($this->getNavigation());
		$this->addToMap($obj);
		return $obj;
	}

	// ���ɵ�����
	private function getNavigationMenu(){
        $mapper = new \woo\mapper\NavigationMapper();
		$collection = $mapper->findAll();
		$result = "<a href=/yrr>�ס�ҳ</a>";
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
//				    $reault .= "<font size=2 color=#00aaaa> >> </font><a href='".$file."'>".$rowdh1["name"]."</a><font size=2 color=#00aaaa> >> ��������</font>";
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