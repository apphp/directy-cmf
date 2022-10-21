<?php
/**
 * Cron controller
 *
 * PUBLIC:                 	PROTECTED:					PRIVATE:
 * ---------------         	---------------         	---------------
 * __construct				_accessRules
 * indexAction            	
 * runAction
 *
 */

class CronController extends CController
{
    
	public function __construct()
	{
        parent::__construct();
    }

	public function indexAction()
	{
		$this->redirect('cron/run');	
	}
	
	/**
	 * Run the cron jobs
	 */
	public function runAction()
	{
		$cron = new Cron();
		$cron->run();
    } 
   
	/**
     * Used to define access rules to controller
	 * This method should be overridden
	 * @return array
	 * 
	 * @usage
	 * 	return array(
	 * 		array('allow',
	 * 			'actions' => array('*'),
	 * 			'ips' => array('127.0.0.1')
	 * 		),
	 *		array('deny',
	 * 			'actions' => array('index','view', 'create', 'update', 'manage'),
	 * 			'ips' => array('*')
	 * 		),
	 * );
	 * 
	 */
	protected function _accessRules()
	{
	  	return array(
	 		array('allow',
	  			//'actions' => array('*'),
	  			//'ips' => array('127.0.0.1')
	  		),
	 		array('deny',
	  			//'actions' => array('*'),
	  			//'ips' => array('127.0.0.1')
	  		)
		);
	}
	
}
