<?php
//admin page for sequences of numbers of invoices
Class Page_Sequence extends Page {
	function init(){
	parent::init();
	$this->add('h1')->set('Manage Sequences');
	$this->add('hr');
	$m = $this->add('Model_Sequences');
	$this->add('CRUD')->setModel($m, array('sequence', 'type'));	
	}
}