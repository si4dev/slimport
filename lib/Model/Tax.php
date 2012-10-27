<?php
//old version of Model_tax
/* 
class Model_Tax extends Model_Table {
  public $table='tax';
  function init() {
    parent::init();
    
    $this->hasOne('Chart');
    $this->addField('rate');
    $this->addField('amount');
    $this->addField('taxnumber');
    $this->addField('startdate');
  }
}
*/

/**
Model Tax
 *[1] Tax 21%,21
 *[2] Tax 19%,19
 *[3] Tax 6%,6
 *[4] Tax 0% NL,0
 *[5] Tax Export EU,0
 *[6] Tax Export NON-EU,0
 */


Class Model_Tax extends Model_Table {
  public $table = 'tax';
  function init() {
	parent::init();
	$this->addField('name'); 
	$this->addField('rate'); // 21, 16, 6, 0   
	$this->hasOne('Business')->system(true);
  }

}
