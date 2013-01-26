<?php 

class Page_chartlink extends Page {
	function init(){
		parent::init();
		$this->add('H1')->set('Manage Chart Link');
		$this->add('HR');
		$crud = $this->add('CRUD');
		$m = $this->add('Model_Chart_link');
		$crud->setModel($m);
		if($crud->grid){
			$crud->grid->removeColumn('business');
		}
		if($crud->form){
		  $f=$crud->form;
		  if($f->isSubmitted()){
		    $m['business_id'] = $this->api->business->id;
			$m->save();
			}
		}		
	}
}