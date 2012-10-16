<?php
class Page_SelectType extends Page {
  function init() {
    parent::init();
		
		$this->add('h2')->set('Select type of document');
		
		$f = $this->add('Form');
		
		$f->addField('radio', 'type')->setValueList(
		array('si'=>'Sales Invoice', 'so'=> 'Sales Order', 'sq' => 'Sales quote', 'pi' => 'Purchase Invoice', 'po'=>'Purchase Order', 'pq'=> 'Purchase Quote', 'gl'=>'General Ledger', 'b'=>'Bank'));	

		$f->addSubmit('Go');
		
		if($f->isSubmitted()){
			$type = $f->get('type');			
			$this->api->redirect('documents', array('type' => $type));
		}
		
		
	 }
}