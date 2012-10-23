<?php 

class Page_TaxLedger extends Page {
		function init(){
			parent::init();
			
			$c= $this->add('CRUD');
			$m = $this->add('Model_TaxLedger');
		
			$l= $m->ref('tax_location_id');	
			$c->setModel($m);
			if($c->form){
				$f = $c->form;
				$t = $f->getElement('tax_type');
				$a = $f->getElement('amount');
				$l = $f->getElement('tax_location_id');
				
	
				if($f->isSubmitted()){				
				
					$tax_type = $f->get('tax_type');
					$tax_location = $f->get('tax_location_id');
					$amount = $f->get('amount');
					
					($tax_location == 1)? $coef=1 : $coef=0;
					$percent = $coef * $tax_type /100;
					
					$tax_value = $amount * $percent;
					$revenue = $amount - $tax_value;
					
					$m['percentage'] = $percent;
					$m['tax_value'] = $tax_value;
					$m['revenue'] = $revenue;
					$m->save();
				
				}
			}

		}
}