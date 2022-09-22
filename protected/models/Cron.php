<?php
/**
 * Cron model
 *
 * PUBLIC:                 	PROTECTED:                 	PRIVATE:
 * ---------------         	---------------            	---------------
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
		if(CAuth::isLoggedInAsAdmin()) return false;

		// Get cron settings
		$settings = Settings::model()->findByPk(1);
		if(!$settings) return false;

		$performActions = false;
		
		// Run the cron
		switch($settings->cron_type){
			case 'batch':
				$performActions = true;
				break;
			case 'non-batch': 
				$period = $settings->cron_run_period;
				$periodValue = $settings->cron_run_period_value;
				$lastTimeRun = $settings->cron_run_last_time;
				$currentTime = LocalTime::currentDateTime();
				if(CTime::getTimeDiff($currentTime, $lastTimeRun, $period) > $periodValue){
					$performActions = true;	
				}
				break;
			case 'stop':
			default:
				$performActions = false;
		}
		
		if($performActions){
			
			// Run cron jobs of modules
			$modules = Modules::model()->findAll('is_installed = 1');
			if(is_array($modules)){
				foreach($modules as $module){
					$moduleName = ! empty($module['class_code']) ? $module['class_code'] : ucfirst($module['code']);
					// For PHP_VERSION | phpversion() >= 5.3.0 you may use
					// $callbackClass = $moduleName::cronJob();
					$object = $moduleName;
					$method = 'cron';
					if(is_string($object)){
						@call_user_func_array($object.'::cron', array());
					}elseif(method_exists($object, $method)){
						$object->$method();
					}else{
						CDebug::addMessage('errors', '', A::t('core', 'Component or method does not exist: {component}', array('{component}'=>$moduleName.'::cron()')), 'session');
					}
				}
			}

			// Update cron last run time
			$settings->cron_run_last_time = LocalTime::currentDateTime();
			$settings->save();			
		}        
    }
}
