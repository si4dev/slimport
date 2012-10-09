<?php

Class Page_chartaccount extends Page
{
	public function init()
	{
		parent::init();	
		$this->add('H1')->set('Chart of accounts');
		$this->add('hr');
		
		//datas from database
		
		$list = $this->add('Grid');
		$list->setModel('Model_chart');		
	}
}
?>