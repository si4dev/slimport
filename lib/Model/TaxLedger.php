<?php 

Class Model_TaxLedger extends Model_Table {
		public $table = 'tax_ledger';
		function init(){
			parent::init();
			
			$this->addField('tax_type')->enum(array('21%', '19%', '6%', '0%'))->caption('Tax Tarive (%)');
			$this->hasOne('taxLocation')->caption('Location');
			$this->addField('amount');	
			$this->addField('revenue')->editable(false);	
			$this->addField('tax_value')->editable(false);
			$this->addField('percentage')->editable(false);			
			$this->addField('tax_code');		
	
		}
}