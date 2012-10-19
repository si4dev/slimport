<?php 

Class Model_InvoiceSequence extends Model_Table {
		public $table='invoicesequence';
		
		function init(){
			parent::init();			
			$this->addField('sequence');
			$this->addField('type');//si or pi
			
		}
		
		function getNext($type){
			$next = $this->loadAny()->addCondition('type', $type)->get('sequence');
			$next++;
			$this['sequence'] = $next;
			$this->update();
			return $next;
		}
		
}