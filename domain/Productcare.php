<?php
namespace woo\domain;
require_once("domain/domain.php");

/**
 * 产品关注类
 * @package domain
 * @author  Liu xingming
 * @copyright 2013 Azuresky safe group
 */
 
class ProductCare extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("proid","userid","currencyid","price","exchangerate","date","label"));
	}

	/**
	 * 设置产品 id
	 * @param {int} $proid_s - 产品 id
	 * @return 无
	 */
	function setProid($proid_s){
		$this->objects["proid"] = (int)$proid_s;
	}

	/**
	 * 获取产品 id
	 * @param 无
	 * @return {int} - 产品 id
	 */
	function getProid(){
		return $this->objects["proid"];
	}

	/**
	 * 设置用户 id
	 * @param {int} $userid_s - 用户 id
	 * @return 无
	 */
	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	/**
	 * 获取用户 id
	 * @param 无
	 * @return {int} - 用户 id
	 */
	function getUserid(){
		return $this->objects["userid"];
	}

	/**
	 * 获取货币 id
	 * @param 无
	 * @return {int} - 货币 id
	 */
	function getCurrencyid(){
		return $this->objects["currencyid"];
	}

	/**
	 * 设置货币 id
	 * @param {int} $currencyid_s - 货币 id
	 * @return 无
	 */
	function setCurrencyid($currencyid_s){
		$this->objects["currencyid"] = (int)$currencyid_s;
	}

	/**
	 * 设置关注产品的价格
	 * @param {float} $price_s - 加关注时该产品的价格
	 * @return 无
	 */
	function setPrice($price_s){
		$this->objects["price"] = (float)$price_s;
	}

	/**
	 * 获取关注产品的价格
	 * @param 无
	 * @return {float} - 加关注时该产品的价格
	 */
	function getPrice(){
		return $this->objects["price"];
	}

	/**
	 * 设置加关注时的汇率
	 * @param {float} $exchangerate_s - 加关注时的汇率
	 * @return 无
	 */
	function setExchangerate($exchangerate_s){
		$this->objects["exchangerate"] = (float)$exchangerate_s;
	}

	/**
	 * 获取加关注时的汇率
	 * @param 无
	 * @return {float} - 加关注时的汇率
	 */
	function getExchangerate(){
		return $this->objects["exchangerate"];
	}

	/**
	 * 设置关注产品的时间
	 * @param {time} $date_s - 加产品关注的时间
	 * @return 无
	 */
	function setDate($date_s){
		$this->objects["date"] = $this->checkTime($date_s);
	}

	/**
	 * 获取关注产品的时间
	 * @param 无
	 * @return {time} - 加产品关注的时间
	 */
	function getDate(){
		return $this->objects["date"];
	}

	/**
	 * 获取关注产品的时间
	 * @param 无
	 * @return {time} - 加产品关注的时间
	 */
	function setLabel($label_s){
		$this->objects["label"] = htmlentities($label_s,ENT_QUOTES,'UTF-8');
	}

	function getLabel(){
		return $this->objects["label"];
	}
}
?>