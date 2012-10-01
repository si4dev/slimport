<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 01.10.12
 * Time: 19:56
 * To change this template use File | Settings | File Templates.
 */
class Grid_Balance extends Grid {
    private $sub_total = 0;
    function init() {
        parent::init();
        $this->addColumn('debet');
        $this->addColumn('credit');
    }
    function renderRows() {
        $order=$this->addOrder();
        $order->move('debet','last')->now();
        $order->move('credit','last')->now();
        $order->move('amount','last')->now();
        parent::renderRows();
    }
    function formatRow(){
        parent::formatRow();

        // header & sub total
        if ($this->current_row['category']=='L') {
//        if ($this->current_row['chart-type']=='H') {
            $this->current_row_html['sub_total']=$this->getSubTotalHTML();
            $this->current_row_html['additional_header']=$this->getAdditionalHeader();
        } else {
            $this->current_row_html['sub_total']='';
            $this->current_row_html['additional_header']='';
        }

        if ($this->current_row['amount']<0) {
            $this->current_row['debet'] = $this->current_row['amount'];
            $this->current_row['credit'] = '';
        } else {
            $this->current_row['credit'] = $this->current_row['amount'];
            $this->current_row['debet'] = '';
        }

        // sub total
        $this->sub_total += $this->current_row['amount'];
    }
    function precacheTemplate(){
        $this->row_t->trySetHTML('additional_header','<?$additional_header?>');
        $this->row_t->trySetHTML('sub_total','<?$sub_total?>');
        parent::precacheTemplate();
    }
    function getAdditionalHeader(){
        $st = $this->sub_total;
        $this->sub_total = 0;
        return '
            <tr style="background-color:#F1F1F1;">
              <td colspan="8">
                '.$this->current_row['description'].'
                ['.$this->current_row['chart_type'].']
                ['.$this->current_row['category'].']
                +['.$this->current_row['context'].']+
              </td>
            </tr>';
    }
    function getSubTotalHTML(){
        $html = '
            <tr style="background-color:#FFFEEC;">
              <td colspan="8">
                sub total = [ '. number_format($this->sub_total,2).' ]
              </td>
            </tr>';
        $this->sub_total = 0;
        return $html;
    }
    function defaultTemplate() {
        return array('view/lister-balance');
    }
}