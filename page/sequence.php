<?php
//admin page for sequences of numbers of invoices
Class Page_Sequence extends Page {
	function init(){
	parent::init();
	
	$m = $this->add('Model_Sequences');
	$this->add('CRUD')->setModel($m);	
	}
}