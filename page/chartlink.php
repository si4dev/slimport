<?php 

class Page_chartlink extends Page {
	function init(){
		parent::init();
		
		$this->add('CRUD')->setModel('chart_link');
	}
}