<?php
class Model_TaxLocation extends Model_Table {
	public $table = 'tax_location';
	public $title_field='location';
	function init(){
		parent::init();
		$this->addField('location');
		$this->addField('coeff');		
	}
}