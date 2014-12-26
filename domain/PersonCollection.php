<?php 
namespace woo\domain;

interface PersonCollection extends \Iterator{
	function add(DomainObject $person);
}
?>