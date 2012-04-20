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
					))
			->setParent($this->pathfinder->base_location);
		$this->add('jUI');
		$this->js()
			->_load('atk4_univ')
			// ->_load('ui.atk4_expander')
			;
    
    $menu = $this->add('Menu',null,'Menu');
    $menu->addMenuItem('documents','Documents');
    $menu->addMenuItem('document','Document');
    $menu->addMenuItem('balance','Balance Sheet');
    $menu->addMenuItem('batch','Bank Import');
    $menu->addMenuItem('contact','Contact');
    $menu->addMenuItem('logout','Logout');

    $this->dbConnect();
    
    $this->add('Auth')->setModel('User');
    $this->auth->check();
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
