<?php
class CharaParamBean {

	private $name;
	private $img;
	private $upHP;
	private $hpUpFlg;
	private $upAtk;
	private $atkUpFlg;
	private $cost;
	private $costDwnFlg;
	private $attri;
	private $tribe;
	private $boost;
	private $fast;
	private $cc;
	private $drop;
	private $exp;
	private $gold;
	private $charaId;


	public function __construct() {
		$this->name = "";
		$this->img = "";
		$this->upHP = 0;
		$this->hpUpFlg = false;
		$this->upAtk = 0;
		$this->atkUpFlg = false;
		$this->cost = 0;
		$this->costDwnFlg = false;
		$this->attri = "";
		$this->tribe = "";
		$this->boost = 0;
		$this->fast = 0;
		$this->cc = 0;
		$this->drop = 0;
		$this->exp = 0;
		$this->gold = 0;
		$this->charaId = 0;
	}

	public function getName () {
		return $this->name;
	}
	public function setName ($name) {
		$this->name = $name;
	}
	public function getImg () {
		return $this->img;
	}
	public function setImg ($img) {
		$this->img = $img;
	}
	public function getUpHP () {
		return $this->upHP;
	}
	public function setUpHP ($upHP) {
		$this->upHP = $upHP;
	}
	public function isHpUpFlg () {
		return $this->hpUpFlg;
	}
	public function setHpUpFlg ($hpUpFlg) {
		$this->hpUpFlg = $hpUpFlg;
	}
	public function isAtkUpFlg () {
		return $this->atkUpFlg;
	}
	public function setAtkUpFlg ($atkUpFlg) {
		$this->atkUpFlg = $atkUpFlg;
	}
	public function getUpAtk () {
		return $this->upAtk;
	}
	public function setUpAtk ($upAtk) {
		$this->upAtk = $upAtk;
	}
	public function getCost () {
		return $this->cost;
	}
	public function setCost ($cost) {
		$this->cost = $cost;
	}
	public function isCostDwnFlg () {
		return $this->costDwnFlg;
	}
	public function setCostDwnFlg ($costDwnFlg) {
		$this->costDwnFlg = $costDwnFlg;
	}
	public function getAttri () {
		return $this->attri;
	}
	public function setAttri ($attri) {
		$this->attri = $attri;
	}
	public function getTribe () {
		return $this->tribe;
	}
	public function setTribe ($tribe) {
		$this->tribe = $tribe;
	}
	public function getBoost () {
		return $this->boost;
	}
	public function setBoost ($boost) {
		$this->boost = $boost;
	}
	public function getFast () {
		return $this->fast;
	}
	public function setFast ($fast) {
		$this->fast = $fast;
	}
	public function getCc () {
		return $this->cc;
	}
	public function setCc ($cc) {
		$this->cc = $cc;
	}
	public function getDrop () {
		return $this->drop;
	}
	public function setDrop ($drop) {
		$this->drop = $drop;
	}
	public function getExp () {
		return $this->exp;
	}
	public function setExp ($exp) {
		$this->exp = $exp;
	}
	public function getGold () {
		return $this->gold;
	}
	public function setGold ($gold) {
		$this->gold = $gold;
	}
	public function getCharaId () {
		return $this->charaId;
	}
	public function setCharaId ($charaId) {
		$this->charaId = $charaId;
	}
}
?>