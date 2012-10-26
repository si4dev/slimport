<?php 

class Page_producttype extends Page {
	function init(){
		parent::init();
		
		$this->add('CRUD')->setModel('product_type');
	}
}