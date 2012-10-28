<?php 

class Page_producttype extends Page {
	function init(){
		parent::init();
		$this->add('h1')->set('Products types');
		
		$this->add('CRUD')->setModel('product_type', array('type'));
	}
}