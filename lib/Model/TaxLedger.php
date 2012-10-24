<?php 

Class Model_TaxLedger extends Model_Table {
		public $table = 'tax_ledger';
		function init(){
			parent::init();
			
			$this->hasOne('Tax');
			$this->addField('product_type')->enum(array('product', 'service'));
			$this->addField('tax_rate');			
			$this->addField('tax_code');			
			$this->addField('chart');				
	
		}
}