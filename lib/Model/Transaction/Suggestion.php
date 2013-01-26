<?php
class Model_Transaction_Suggestion extends Model_Transaction {
  function init() {
    parent::init();
    
    // extend grid with one column to show suggestion status
    // http://stackoverflow.com/questions/10500859/extend-crud-with-column-from-other-model-array-in-atk4

    $this->addExpression('suggestion')->set('null'); // nothing by default

    $this->addHook('afterLoad',$this);
  
  }

  function afterLoad(){
    $this['suggestion']=$this->suggestion();
  }
  
  
  function suggestion() {
      return 
          $this->suggestionContact().
          $this->suggestionAccount().
          $this->suggestionInvoice()
          ;
  }
  
  // find contact based on bank nr / iban
  function suggestionContact() {
    $contact=array();
    if( $this['contra_account'] ) {
      $contact=$this->api->business->ref('Contact')->tryLoadBy('iban',$this['contra_account']);
    }
    if( $contact['name'] )
    return '.'.$contact['name'];
  }
  
  // find bank owner
  function suggestionAccount() {
    $bank=$this->api->business->ref('Contact_Bank')->setActualFields(array('company','chart_bank_id'))->getBy('iban',$this['account']);
    return $bank['chart_bank_id'];
  }

  
  function suggestionInvoice() {
    
      $document=$this->api->business->ref('Document')->dsql(); // clone of dsql 
      
      
      $document
        ->where($document->expr("'24' like number"));
      $r=$document  ->get();
echo '===';    //  print_r($r);
      
  }

}