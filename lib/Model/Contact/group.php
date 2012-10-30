<?php 

class Model_Contact_Group extends Model_table {
	public $table="contact_group";
	function init(){
	  parent::init();
		$this->addField('name');
		$this->hasOne('business')->system(true);
	}
}