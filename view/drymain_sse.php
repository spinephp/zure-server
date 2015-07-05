<?php
	namespace woo\view;
	require_once("mapper/PersistenceFactory.php");
	require_once("mapper/DomainObjectAssembler.php");
	
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	$object = "woo\\domain\\drymain";
	$factory = \woo\mapper\PersistenceFactory::getFactory($object);
	$finder = new \woo\mapper\DomainObjectAssembler($factory);
	$idobj = $factory->getIdentityObject()->field('state')->eq(0);
	$collection = $finder->find($idobj);
	$rec = $collection->current();
	if(is_null($rec))
		throw new \woo\base\AppException("Record ID is'n exist!");
	$result["id"] = $rec->getId();
	$result["starttime"] = $rec->getStarttime();
	$result["lineno"] = $rec->Lineno();
	$result["state"] = $rec->getState();
	echo "data: ".json_encode($result);
	flush();
?>