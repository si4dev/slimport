<?php
// ar=account receivables=debiteuren=verkoop
// ap=account payables=crebiteuren=inkoop
// http://en.wikipedia.org/wiki/Specialized_journals
class Model_Document_SalesOrder extends Model_Document {
  function init() {
    parent::init();
    $this->addCondition('type','so');
  }
}
