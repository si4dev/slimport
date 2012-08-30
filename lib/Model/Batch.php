<?php

class Model_Batch extends Model_Table {
  public $table='batch';
  public $title_field='id';
  function init() {
    parent::init();
    $this->hasOne('Connect',null,'platform');
    
    /*
    let's say you need a form with file upload. how to do that? here's how: 
      1) make sure you have added proper add-on location (misc/lib) 
      2) Then, you need to add filestore "sql" structure to your project, look for it under addons/misc/docs/filestore.sql also the update!
      3) Then in your form $form->addField("upload", "my_file")->setController("Controller_Filestore_File"); and that's about it :) 
      3.1) If you use MVCForm (based on model), then go to your Model and set refModel("Model_Filestore_File")->display("file") 
           to have file uploader visible
    */
    
    // https://groups.google.com/forum/#!topic/agile-toolkit-devel/7CFqBuoUfpY
    $this->add("filestore/Field_File", "filestore_id")->setModel('filestore/File','original_filename'); //->display(array('form'=>'upload','original_filename'=>'text'));
//    $this->addField('bank_file')->refModel('Model_Filestore_File')->display('file');


    $this->hasMany('Transaction');
  
    
  }

  function import(){
    // connect field contains the platform to connect to like abnamro / prestshop / ogone
      return $this->setController('Connect_'.ucwords($this->get('connect')))->import();
  }

  function ZZZimport() {
    switch ( $this->get('bank_file_type') ) {
      case 'abn':
        $this->importAbn();
        break;
      case 'ing':
        $this->importIng();
        break;
    }
    return " test";
  }
    
  function ZZZimportAbn() {  
    $m=$this->ref('Transaction');
    $m->deleteAll();
        // http://agiletoolkit.org/learn/tutorial/jobeet/day9
        
//echo '[[test['.$this->add("filestore/Model_File")->debug()->load(15)->getPath().']]]';
        
    $batch_file_name = $this->add("filestore/Model_File")->load(15)->getPath();
    if (($handle = fopen($batch_file_name, 'r')) !== FALSE) {
      while (($raw = fgets($handle)) !== FALSE) {
        $m->unload();
        
       //    $data=str_getcsv($raw,"\t",''); 
       // cannot use getcsv as note starts with a space and getcsv is trimming it.
       // https://bugs.php.net/bug.php?id=53848&edit=3
       
        $data=explode("\t",$raw);
        
        $content = $data[7];
        $contra_account = "";
        if( $content[0] == ' ' ) {
          for ($i = 1; $i < strlen($content); $i++) {
            if( is_numeric( $content[$i] ) ) {
             $contra_account .= $content[$i];
            } elseif ( $content[$i] == ' ' ) {
              break;
            } elseif ( $content[$i] != '.' ) {
              $contra_account = "";
              break;
            }
          }
        }
        
        if( strpos($content,'GIRO') === 0 ) {
          for ($i = 4; $i < strlen($content); $i++) {
            if( is_numeric( $content[$i] ) ) {
             $contra_account .= $content[$i];
            } elseif ( $content[$i] == ' ' and $contra_account != '' ) {
              break;
            } elseif ( $content[$i] != ' ' and $contra_account != '' ) {
              $contra_account = "";
              break;
            }
          }
        }
        //ONZE REF:    NI28061S32096100    OORSPR.      EUR           89,50 ONTV AAB     EUR           89,50 GEDEELDE KOSTEN OPDR./BEGUNST.   SEVILLA CARRASCO DANIEL          ES        N 52912022Q            PAGO PEDIDO .422 ( DANIEL SEVILL A C ARRASCO )                    EU BUITENLAND OVERBOEKING                                        
        //ONZE REF:    NI28061S32269400    OORSPR.      EUR           35,00 ONTV AAB     EUR           35,00 GEDEELDE KOSTEN OPDR./BEGUNST.   ALTMANN JAN                      75223 NIEFERN-OSCHELBRONN        0000136131                       ORDER 306 TW-STEEL-ARMBAND       EU BUITENLAND OVERBOEKING                                        


        
        ///TRTP/SEPA OVERBOEKING/IBAN/DE73651901100009605002/BIC/GENODES1VF N/NAME/Paulo Jorge Dos Santos Lopes/REMI/Bestell ID 732 Gaeste Ge schenke/Promotion/EREF/NOTPROVIDE                              
        $iban='';
        if(!$contra_account and preg_match('/IBAN\/([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}[a-zA-Z0-9]{0,16})\//',$content,$iban))
          $contra_account=$iban[1]; // first match in array
// commented as it shows also bad ibans
//        if(!$contra_account and preg_match('/\s([a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}[a-zA-Z0-9]{0,16})\s/',$content,$iban))
//          $contra_account=$iban[1]; // first match in array
        
        $transdate=$data[2][0].$data[2][1].$data[2][2].$data[2][3].'-'.$data[2][4].$data[2][5].'-'.$data[2][6].$data[2][7];
        $m->set( array(
                  'raw' => $raw,
                  'account' => $data[0],
                  'currency' => $data[1],
                  'contra_account' => $contra_account,
                  'date' => $transdate,
                  'amount' =>  $data[6], // positive means added to bank account (klanten betaling) ar
                  'description' => '',
                  'notes' => $data[7]
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

/*
Array ( [0] => Datum [1] => Naam / Omschrijving [2] => Rekening [3] => Tegenrekening [4] => Code [5] => Af Bij 
[6] => Bedrag (EUR) [7] => MutatieSoort [8] => Mededelingen )
*/

  function ZZZimportIng() {  
    $batch_id = $this->get( 'id' );
    $m=$this->add('Model_Transaction');
        // http://agiletoolkit.org/learn/tutorial/jobeet/day9
    $m->dsql(null,false)->where('batch_id=',$batch_id)->delete()->debug();
    $batch_file_name = $this->add("filestore/Model_File")->loadData($this->get('bank_file'))->getPath();


    if (($handle = fopen($batch_file_name, 'r')) !== FALSE) {
      $i=0;
      while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
print_r($data);
        if($i++ != 0) {
            
          $m->unload();

          /* Array ( 
                    [0] => Datum 
                    [1] => Naam / Omschrijving 
                    [2] => Rekening 
                    [3] => Tegenrekening 
                    [4] => Code 
                    [5] => Af Bij 
                    [6] => Bedrag (EUR) 
                    [7] => MutatieSoort 
                    [8] => Mededelingen 
                  )

          Array ( [0] => 20120206 [1] => 02-02-12 19:04 BETAALAUTOMAAT [2] => 5510523 [3] => [4] => BA [5] => Af 
          [6] => 1319,68 [7] => Betaalautomaat [8] => MEDIA MARKT EDE / EDE GLD 024 310774 5LZQ02 ING BANK NV PASTRANSACTIES )
          */
          $m->set( array(
                    'batch_id' => $batch_id,
                    'account' => $data[2],
                    'currency' => 'EUR',
                    'contra_account' => $data[3],
                    'date' => $data[0][0].$data[0][1].$data[0][2].$data[0][3].'-'.$data[0][4].$data[0][5].'-'.$data[0][6].$data[0][7].' 00:00:00',
                    'amount' =>  ($data[5] == 'Af'?-$data[6]:$data[6]),
                    'description' => $data[1],
                    'notes' => $data[8]
                    ) );
          $m->update();
        }
      }
      fclose($handle);
    }
  }


}
