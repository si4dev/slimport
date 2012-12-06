<?php
class Model_Product extends Model_Table {
  public $table='product';
  public $title_field='product_code';
  function init() {
      parent::init();		
	
		$this->addField('product_code');
		$this->hasOne('product_group')
			->mandatory('Please select a type')
			->caption('Group')
			->emptyText(null);
		$this->addField('description');
		$this->addField('category');
		$this->addField('unit')->defaultValue(1);
		$this->addField('purchase_price');
		$this->addField('sell_price');
		$this->hasOne('tax')->emptyText(null);  	
		$this->addField('active');	
		$this->addExpression('name',"concat(product_code,' ',description)");

		$this->addHook('beforeSave', $this);
	
    $group=$this->join('product_group');
    $group->hasOne('Business')->system(true);
    $group->addField('Type');
		$this->addCondition('business_id',$this->api->business->id);
    }


  function sl_import() {
    $this->deleteAll();
    $this->join('sqlledger.product_id','id')->addField('sl_id');

   
    $q=$this->api->db2->dsql();
    $q->table('parts')
      ->field('id',null,'sl_id')
      ->field('partnumber',null,'product_code')
      ->field('description')
      ->field('unit')
      ->field('sell_price')
      ;
    foreach($q as $row) {
      $this->unload()->set($row)->save(); 
    }
  }
  
  function beforeSave(){
	$fpc = $this['product_code'];
		if(!isset($fpc) || $fpc == '' || $fpc == null || empty($fpc)){					
		  $sequence = $this->add('Model_Sequences');			
		   $pcode = $sequence->getNext('product');
		   $this['product_code'] = $pcode;
		}
  }
  
}
