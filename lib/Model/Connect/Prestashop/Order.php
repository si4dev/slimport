<?php
class Model_Connect_Prestashop_Order extends Model_Connect_Table {
  public $table='ps_orders';
  public $id_field='id_order';
  function init() {
    parent::init();
    $this->debug();
    $this->hasOne('Connect_Prestashop_Address','id_address_delivery','id_address');
    $this->addField('invoice_number');
    $this->addField('delivery_number');
    $this->addField('date_add');
    // get history with max date by this article:
    // http://stackoverflow.com/questions/121387/fetch-the-row-which-has-the-max-value-for-a-column/123481#123481
    $h=$this->join('ps_order_history.id_order','id_order',null,'his1');
    $h->addField('id_state','id_order_state');
    $hm=$this->leftJoin('ps_order_history.id_order',$this->dsql->expr('his2.id_order=his1.id_order and his2.date_add > his1.date_add'),null,'his2');
    $hm->addField('id_order_state')->hidden(true);
    $this->addCondition('id_order_state','', $this->dsql->expr('is null') );
    // get state name like shipped / etc
    $state=$h->join('ps_order_state_lang.id_order_state','id_order_state');
    $state->addField('id_lang')->hidden(true);
    $state->addField('status_name', 'name');
    // get default language id
    $sub=$this->api->db2->dsql();
    $sub->table('ps_configuration')->field('value')->where('name','PS_LANG_DEFAULT');
    $this->addCondition('id_lang',$sub);
    // currency conversion rate
    $cur=$this->join('ps_currency.id_currency','id_currency');
    $cur->addField('conversion_rate');
    // totals in euro
    $this->addField('total_paid');
    $this->hasOne('Connect_Prestashop_Customer','id_customer');
    $this->hasMany('Connect_Prestashop_Item','id_order');
  /*
          select
          	o.id_order OrderID, o.date_add OrderDate, 
          	h.id_order_state OrderStatusID, s.name OrderStatusName, round(o.total_paid / c.conversion_rate, 2) OrderTotalPriceIncl,
            c.conversion_rate OrderCurrencyRate
          from 
          	ps_orders o
            inner join ps_currency c on (c.id_currency = o.id_currency)
          	inner join 	ps_order_history h on (h.id_order = o.id_order)
          	inner join (select id_order, max(date_add) recent_date_add from ps_order_history group by id_order) h2 on (h2.id_order = h.id_order and h2.recent_date_add = h.date_add)
          	inner join ps_order_state_lang s on (h.id_order_state = s.id_order_state)
          	where s.id_lang = (select value from `ps_configuration` where name = 'PS_LANG_DEFAULT')
              and h.id_order_state = '".pSQL($status)."' 
              
            order by o.id_order asc  
              limit 0,1000
              ";  
    */
  }
}