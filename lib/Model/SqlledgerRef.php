<?php
class Model_SqlledgerRef extends Model_Table {
  public $table='sqlledger';
  function init() {
    parent::init();
    $this->addField('sl_id');
    $this->addField('contact_id');
    $this->addField('chart_id');
    $this->addField('document_id');
  }
}