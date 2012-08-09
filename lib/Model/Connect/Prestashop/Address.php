<?php
class Model_Connect_Prestashop_Address extends Model_Connect_Table {
  public $table='ps_address';
  public $id_field='id_address';
  function init() {
    parent::init();
    $this->addField('company');
    $this->addField('address1');
    $this->addField('address2');
    $this->addField('postcode');
    $this->addField('city');
    $this->addField('phone');
    $this->addField('phone_mobile');
    $country=$this->join('ps_country.id_country','id_country');
    $country->addField('iso_code');
    

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