<?php
/**
 * Cron controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
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
   
}