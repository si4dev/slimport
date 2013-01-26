<?php
// ar=account receivables=debiteuren=verkoop
// ap=account payables=crebiteuren=inkoop
// http://en.wikipedia.org/wiki/Specialized_journals
class Model_Document_Open extends Model_Document {
  function init() {
    parent::init();
    $this->debug();
    $ledger=$this->join('ledger.document_id');
    $ledger->addField('chart_id');
    $this->addCondition('chart_id','chart_against_id');
//    $this->addExpression('s','sum(amount)');  

//    $this->addCondition('gender','M');
  //  $this->addCondition('age','>',65);
  }
}
