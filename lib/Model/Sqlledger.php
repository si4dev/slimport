<?php
class Model_Sqlledger extends AbstractObject {
  function init() {
    parent::init();
    $this->api->db2=$this->api->add('DB')->connect($this->api->getConfig('dsn_sqlledger'));
  }
}
