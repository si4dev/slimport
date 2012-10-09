<?php
class Model_Connect_Prestashop_Customer extends Model_Connect_Table {
  public $table='ps_customer';
  public $id_field='id_customer';
  function init() {
    parent::init();
    $this->debug();
    $this->addField('id_gender'); // 1=male
    $this->addField('date_add');
    $this->addField('lastname');
    $this->addField('firstname');
    $this->addField('email');

    /*
          select 
            c.id_customer CustomerID, if(c.id_gender = 1, 'male', 'female') CustomerGender, c.date_add CustomerStartDate,
          	c.lastname CustomerLastName, c.firstname CustomerFirstName, 
            a.company CustomerCompany, c.email CustomerEmail, a.address1 DeliveryAddress,
            a.address2 DeliveryAddress2, a.postcode DeliveryPostcode, a.city DeliveryCity, 
            lcase( cy.iso_code) DeliveryCountryIso, concat(cyl.name, ' [',ucase( cy.iso_code) ,']') DeliveryCountry,
            a.phone CustomerPhone, a.phone_mobile CustomerPhoneMobile
          from 
          	ps_orders o
          	inner join ps_customer c on (c.id_customer = o.id_customer)
          	inner join ps_address a on (a. id_address = o.id_address_delivery)
          	inner join ps_country cy on (cy.id_country = a.id_country)
          	inner join ps_country_lang cyl on (cyl.id_country = cy.id_country and cyl.id_lang = o.id_lang)
          where o.id_order = ".pSQL($orderid)." ";  
    */
  }
}