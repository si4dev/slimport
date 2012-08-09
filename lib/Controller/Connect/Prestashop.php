<?php
class Controller_Connect_Prestashop extends AbstractController {
  function init() {
    parent::init();
    
    if(!($this->owner instanceof Model))throw $this->exception('Use addController() based on Model');
    $this->model=$this->owner; // instance of Model_Connect with loaded id so we know what to conncect to
    $this->api->db2=$this->add('DB')->connect($this->model->get('source'));
  }
  
  function importOrders() {

    $i=0;
    $orders=$this->add('Model_Connect_Prestashop_Order');
   // $address=$orders->ref('Connect_Prestashop_Address');
    $orders->selectQuery(); // solves issue to get all fields and now it gets only applicable fields definined in model
    
    $business=$this->model->ref('business_id');
    
    echo $business->id;echo '+++';
    $contact=$business->ref('Contact_Customer');
    $document=$business->ref('Document_SalesOrder')->addCondition('connect_id',$this->model->id);
    
    
    foreach($orders as $order) {
      print_r($order);
      $customer=$orders->ref('id_customer');
      $address=$orders->ref('id_address_delivery');
      // set contact field in slimport based on prestashop
      $contact->tryLoadBy('email',$customer['email'])
          ->set('startdate',$customer['date_add'])
          ->set('lastname',$customer['lastname'])
          ->set('firstname',$customer['firstname'])
          ->set('phone',$address['phone'])
          ->set('mobile',$address['phone_mobile'])
          ->set('company',$address['company'])
          ->set('connect_id',$this->model->id)
          ->set('employee_id',1);
          
      $contact->getElement('terms')->defaultValue(14);
      $contact->save();

      // set addrss fields in slimport based on prestashop
      $contact->ref('Address')->tryLoadBy('type','delivery')
          ->set('address',$address['address1'])
          ->set('address2',$address['address2'])
          ->set('postcode',$address['postcode'])
          ->set('city',$address['city'])
          ->set('country_iso',$address['iso_code'])
          ->save();

      $document->tryLoadBy('connect_ref',$order['id_order'])
          ->set('number',$order['id_order'])
          ->set('transdate',$order['date_add'])
          ->set('contact_id',$contact->id)
          ->set('employee_id',1);
          ;
      $document
          ->debug()->save();
      
      $i++; if($i > 2) break;
    }

  }
}