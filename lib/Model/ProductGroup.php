<?php 

Class Model_ProductGroup extends Model_Table {
	public $table='product_group';
	public $title_field='type';
	function init(){
		parent::init();
		$this->addField('type');
		$this->hasOne('business')->system(true);
	}
}