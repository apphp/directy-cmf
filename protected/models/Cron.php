<?php
/**
 * Cron model
 *
 * PUBLIC:                PROTECTED               PRIVATE
 * ---------------        ---------------         ---------------
 * __construct
 * run
 *
 */

class Cron extends CModel
{

    /**
	 * Class default constructor
     */
	public function __construct()
    {
        parent::__construct();
    }

	/**
     * 
	 */
	public function run()
	{
		// get cron settings
		$settings = Settings::model()->findByPk(1);
		if(!$settings) return;

        $offset = ($settings->daylight_saving && date('I', time())) ? 3600 : 0;            		
		$performActions = false;
		
		// run the cron
		switch($settings->cron_type){
			case 'batch':
				$performActions = true;
				break;
			case 'non-batch': 
				$period = $settings->cron_run_period;
				$periodValue = $settings->cron_run_period_value;
				$lastTimeRun = $settings->cron_run_last_time;
				$currentTime = date('Y-m-d H:i:s', time() + $offset);
				if(CTime::getTimeDiff($currentTime, $lastTimeRun, $period) > $periodValue){
					$performActions = true;	
				}
				break;
			case 'stop':
			default:
				$performActions = false;
		}
		
		if($performActions){
			// your code is here...

			// update cron last run time
			$settings->cron_run_last_time = date('Y-m-d H:i:s', time() + $offset);
			$settings->save();			
		}        
    }    

}