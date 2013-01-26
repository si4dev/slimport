<?php
class Model_Connect_Prestashop_Item extends Model_Connect_Table {
  public $table='ps_order_detail';
  public $id_field='id_order_detail';
  function init() {
    parent::init();
    $this->debug();
    $this->addField('id_order');
    $this->addField('product_reference');
    $this->addField('product_name');
    $this->addExpression('quantity')->set('product_quantity - product_quantity_refunded - product_quantity_return + product_quantity_reinjected');
    $this->addExpression('price')->set('product_price * (1 + tax_rate / 100) ');
  }
 
 /*
   select o.id_order_detail LineProductShopID, o.product_reference LineProductCode,
                sup.name LineProductSupplier,
                o.product_supplier_reference LineProductSupplierCode, 
                o.product_name LineProductTitle, 
                (o.product_quantity - o.product_quantity_refunded - o.product_quantity_return + o.product_quantity_reinjected) LineQuantity,
                round(o.product_price * (1 + tax_rate / 100) / '".pSQL($currency_rate)."' ,2) LinePriceIncl
              from 
                ps_order_detail o
                left join ps_product p on (p.id_product = o.product_id) 
                left join ps_supplier sup on (sup.id_supplier = p.id_supplier)            
              where o.id_order = ".pSQL($orderid)." ";  
*/
}