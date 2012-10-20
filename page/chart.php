<?php

Class Page_chart extends Page
{
	public function init()
	{
		parent::init();	
		$this->add('H1')->set('Chart of accounts');
		$this->add('hr');
		
		$mc = $this->add('Model_chart');		
		$c = $this->add('CRUD');
		$c->setModel($mc);
		
		if($c->grid){
			$c->grid->removeColumn('delete'); //hide delete column
			$c->grid->addPaginator(10);
		}
		
		
	}
}
?>