<?php
class Controller_Connect_Abnamro extends AbstractController {
  function init() {
    parent::init();
    
    if(!($this->owner instanceof Model))throw $this->exception('Use addController() based on Model');
    $this->model=$this->owner;
    
  }
  
  function import() {
    // get business from logged in session
    if(!$b=$this->api->business) {
      if($this->api->admin) {
        // admin not yet working but then you need to get business via connect
        $b=$this->model->ref('connect_id')->ref('business_id');
      }
    }
    
    // http://agiletoolkit.org/learn/tutorial/jobeet/day9
        
    $batch_file_name = $this->add("filestore/Model_File")->load($this->model->get('filestore_id'))->getPath();
    
            
    if (($handle = fopen($batch_file_name, 'r')) !== FALSE) {
      $d=$b->ref('Document')->tryLoadBy('batch_id',$this->model->id);
      $d
        ->set('type','b')
        ->set('transdate',$d->dsql()->expr('now()'))
        ->set('currency','EUR')
        ->set('contact_id','13115')  // TODO: ?
        ->set('employee_id','1')
        ->set('chart_agains_id','298') // 2100 vraagstukken
        ->save();

      $m=$d->ref('Transaction');
      $m->deleteAll();

      while (($raw = fgets($handle)) !== FALSE) {
        $m->unload();
        
       //    $data=str_getcsv($raw,"\t",''); 
       // cannot use getcsv as note starts with a space and getcsv is trimming it.
       // https://bugs.php.net/bug.php?id=53848&edit=3
       
        $data=explode("\t",$raw);
        
        $content = $data[7];
        $content_line = str_split($content,33);
        $content_first = $content_line[0];
        
        $remote_account = "";
        $remote_owner = "";
        $notes=$data[7];
        if( $content_first[0] == ' ' ) {
          for ($i = 1; $i < strlen($content_first); $i++) {
            if( is_numeric( $content_first[$i] ) ) {
             $remote_account .= $content_first[$i];
            } elseif ( $content_first[$i] == ' ' ) {
              break;
            } elseif ( $content_first[$i] != '.' ) {
              $remote_account = "";
              break;
            }
          }
          
          // so bank number found
          if($remote_account) {
            // on same line the owner is there
            $remote_owner=trim(substr($content_first,$i));
//            unset($content_line[0]);
            // and if not then the next line
            if(!$remote_owner) {
              $remote_owner=trim($content_line[1]);
  //            unset($content_line[1]);
            }
            // rest is for notes
            $notes=implode($content_line);
          }
        }
        
        // http://bazaar.launchpad.net/~pieterj/account-banking/trunk/view/head:/account_banking_nl_abnamro/abnamro.py
        
        if( strpos($content,'GIRO') === 0 ) {
          for ($i = 4; $i < strlen($content); $i++) {
            if( is_numeric( $content[$i] ) ) {
             $remote_account .= $content[$i];
            } elseif ( $content[$i] == ' ' and $remote_account != '' ) {
              break;
            } elseif ( $content[$i] != ' ' and $remote_account != '' ) {
              $remote_account = "";
              break;
            }
          }
          // so bank number found
          if($remote_account) {
            $remote_account='P'.$remote_account;
            // on same line the owner is there
            $remote_owner=trim(substr($content_first,$i));
//            unset($content_line[0]);
            // and if not then the next line
            if(!$remote_owner) {
              $remote_owner=trim($content_line[1]);
  //            unset($content_line[1]);
            }
            // rest is for notes
            $notes=implode($content_line);
          }
        }
        //ONZE REF:    NI28061S32096100    OORSPR.      EUR           89,50 ONTV AAB     EUR           89,50 GEDEELDE KOSTEN OPDR./BEGUNST.   SEVILLA CARRASCO DANIEL          ES        N 52912022Q            PAGO PEDIDO .422 ( DANIEL SEVILL A C ARRASCO )                    EU BUITENLAND OVERBOEKING                                        
        //ONZE REF:    NI28061S32269400    OORSPR.      EUR           35,00 ONTV AAB     EUR           35,00 GEDEELDE KOSTEN OPDR./BEGUNST.   ALTMANN JAN                      75223 NIEFERN-OSCHELBRONN        0000136131                       ORDER 306 TW-STEEL-ARMBAND       EU BUITENLAND OVERBOEKING                                        


        // international bank transfer starts with EL
        if( strpos($content_first,'E') === 0 and preg_match('/^E[LMN][0-9]{13}I/',$content_first) ) {
          if(preg_match('/\/([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{11}[a-zA-Z0-9]{0,16})\s/',$content_line[2] )) {
            $remote_account=trim(substr($content_line[2],1));
            $remote_owner=trim($content_line[3]);
          }
        }        

        
        // international bank transfer starts with EL
        if( strpos($content_first,'BEA') === 0  ) {
          $split=explode(',',$content_line[1],2);
          $remote_owner=trim($split[0]);
        }        
        //SEPA Overboeking                 IBAN: DE57422600010244357700     BIC: GENODEM1GBU                 Naam: Boeck Mirko                Omschrijving: T UCHKELSTER UHR R ETRO XL MIRKO BOECK              Kenmerk: 51010114020                                             
        if( strpos($content_first,'SEPA') === 0  ) {
          preg_match('/IBAN: ([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{11}[a-zA-Z0-9]{0,16})\s/',$content,$iban);
           $remote_account=$iban[1]; // first match in array
          preg_match('/Naam: (.*?)\s*(\S*):/',$content,$owner);
          $remote_owner=trim($owner[1]);
        }        


        ///TRTP/SEPA OVERBOEKING/IBAN/DE73651901100009605002/BIC/GENODES1VF N/NAME/Paulo Jorge Dos Santos Lopes/REMI/Bestell ID 732 Gaeste Ge schenke/Promotion/EREF/NOTPROVIDE                              
        $iban='';
        if(!$remote_account and preg_match('/IBAN\/([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}[a-zA-Z0-9]{0,16})\//',$content,$iban))
          $remote_account=$iban[1]; // first match in array
// commented as it shows also bad ibans
//        if(!$remote_account and preg_match('/\s([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}[a-zA-Z0-9]{0,16})\s/',$content,$iban))
//          $remote_account=$iban[1]; // first match in array
        
        $transdate=$data[2][0].$data[2][1].$data[2][2].$data[2][3].'-'.$data[2][4].$data[2][5].'-'.$data[2][6].$data[2][7];
        $m->unload()->set( array(
                  'raw' => $raw,
                  'own_account' => $data[0],
                  'currency' => $data[1],
                  'remote_account' => $remote_account,
                  'remote_owner' => $remote_owner,
                  'transdate' => $transdate,
                  'amount' =>  str_replace(',','.',$data[6]), // positive means added to bank account (klanten betaling) ar
                  'description' => '',
                  'notes' => $notes,
                  'hash' => md5($raw),
                  'batch_id',$this->model->id,
                  ) );
        $m->update();
        /*

        Array
        (
        [0] => 429296339
        [1] => EUR
        [2] => 20110107
        [3] => 1515,68
        [4] => 1924,68
        [5] => 20110107
        [6] => 409,00
        [7] =>  88.75.01.818 OUDE G DEN         ZWALUWSINGEL 15                  2289 EP  RYSWYK ZH               2011000003TRNW_NL                tbv BTB chain big Dames                                          
        )

        */
      }
      fclose($handle);
    }
  }

}


