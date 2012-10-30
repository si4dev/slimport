<?php 

class Model_Chart_Link extends Model_Table {
	public $table='chart_link';
	function init(){
	  parent::init();
	  
		$this->hasOne('product_group');
		$this->hasOne('tax');
		$this->addField('ar_revenue_chart_id'); //TODO as hasOne but need to create tables
		$this->addField('ar_tax_chart_id');
		$this->addField('ar_inventory_chart_id');
		$this->addField('ap_revenue_chart_id');
		$this->addField('ap_tax_chart_id');
		$this->addField('ap_inventory_chart_id');
		$this->hasOne('Business')->system(true);
		$this->addCondition('business_id', $this->api->business->id);
	}
}