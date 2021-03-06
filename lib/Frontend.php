<?php
class Frontend extends ApiFrontend {
	function init(){
		parent::init();
		$this->addLocation('atk4-addons',array(
					'php'=>array(
              'mvc',
              'misc/lib',
              'filestore/lib',
						)
			//location for tree view multimenu addon
					,'template'=> array(
                        'tree/templates/default'
                    )
					))
			->setParent($this->pathfinder->base_location);
		//superfish
		$this->addLocation('templates/custom', array(
			'css' => 'css',
			
			))->setParent($this->pathfinder->base_location);


    
    $this->addLocation('addons', 'addons');
	
		$this->add('jUI');
		$this->js()
			->_load('atk4_univ')
			// ->_load('ui.atk4_expander')
			;

		$this->js()->_css('perso');


    //$menu = $this->add('Menu',null,'Menu');
	

	$menu = $this->add("tree/MultiMenu", null, "Menu");
    $docs = $menu->addMenuItem('documents','Documents');
    
    $menu->addMenuItem('balance','Balance Sheet');
    $menu->addMenuItem('connect','Connect');
    $menu->addMenuItem('contact','Contact');   
	$menu->addMenuItem('chart', 'Charts');
	$menu->addMenuItem('sequence', 'Sequences');
	$menu->addMenuItem('group', 'Groups');
	$menu->addMenuItem('chartlink', 'Chart Links');
	$menu->addMenuItem('product', 'Products');
	$menu->addMenuItem('logout','Logout');
	
	//documents submenus
		//si, so, sq
	$menu->addMenuItem('documents&&type=si', 'Sales invoices', $docs);
	$menu->addMenuItem('documents&&type=so', 'Sales Order', $docs);
	$menu->addMenuItem('documents&&type=sq', 'Sales Quote', $docs);
		//pi, po, pq
	$menu->addMenuItem('documents&&type=pi', 'Purchase Invoice', $docs);
	$menu->addMenuItem('documents&&type=po', 'Purchase Order', $docs);
	$menu->addMenuItem('documents&&type=pq', 'Purchase Quote', $docs);
		//gl
	$menu->addMenuItem('documents&&type=gl', 'General Ledger', $docs);
	$menu->addMenuItem('documents&&type=b', 'Bank', $docs);
    $this->dbConnect();
    
    $this->add('Auth')->setModel('User');
    $this->auth->check();
	
	
    
    // memorize business or default to first business
    if($b=$this->api->recall('business_id')) {
      $this->api->business=$this->auth->model->ref('UserBusiness')->loadBy('business_id',$b)->ref('business_id');
    } else {
      $this->api->business=$this->auth->model->ref('UserBusiness')->loadAny();
      $this->api->memorize('business_id',$this->api->business->id);
    }
  
  
    $pp=$this->api->add('P',null,'UserInfo');
    $pp->add('Text')->set('user: '.$this->auth->model->get('name'));
    $pp->add('HTML')->set('<br/>');
    $pp->add('Text')->set('business: '.$this->api->business->get('name'));
	}
  
    function page_index($page){
    /*  
        $this->add('Link')->set('invoice','Invoice');
        $this->add('Link')->set('balance','Balance Sheet');
        $this->add('Link')->set('bacth','Bank Import');
        $this->add('Link')->set('chart','Chart of accounts');

    */
    }
}

