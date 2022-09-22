<?php
/**
 * Settings controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _prepareTab
 * indexAction			   	_checkAlexaRank
 * generalAction			_checkGoogleRank
 * visualAction			   	_checkIndexedPages
 * localAction			   	_checkHash
 * emailAction				_hashURL
 * templatesAction			_strToNum
 * mappingApiAction			_yesNoArray
 * serverAction				_noYesArray
 * siteAction				_testDateFormat 
 * cronAction				_testTimeFormat
 *                          _checkDateTimeFormats
 *                          _checkModRewrite
 */

class SettingsController extends CController
{
	private $_settings;
	private $_alert;
	private $_alertType;
	private $_cRequest;
	private $_arrDateFormats;
	private $_arrTimeFormats;
	private $_arrDateTimeFormats;
	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();
        
        // Block access to this controller to non-logged users
		CAuth::handleLogin(Website::getDefaultPage());		
	        
		// Block access if admin has no active privileges to access site settings
		if(!Admins::hasPrivilege('site_settings', 'view')){
			$this->redirect('backend/index');
		}
		
        // Set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Site Settings')));
        // Set backend mode
        Website::setBackend();

        $this->_view->actionMessage = '';
        $this->_view->errorField = ''; 
        $this->_alert = '';
        $this->_alertType = '';
        
        $this->_settings = Bootstrap::init()->getSettings();
        if(!$this->_settings){
    		$this->redirect('backend/index');
        }                    
        $this->_cRequest = A::app()->getRequest();       
		$this->_cSession = A::app()->getSession();

    	$this->_arrDateFormats = CLocale::getDateFormats();
		$this->_arrDateFormats[''] = A::t('app', 'Custom...');

		$this->_arrTimeFormats = CLocale::getTimeFormats();
		$this->_arrTimeFormats[''] = A::t('app', 'Custom...');
		
		$this->_arrDateTimeFormats = CLocale::getDateTimeFormats();
		$this->_arrDateTimeFormats[''] = A::t('app', 'Custom...');
	}	

	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
        $this->redirect('settings/general');
    }    
		
    /**
     * General settings action handler
     * @param string $task
     */
	public function generalAction()
	{        
    	$this->_view->rssFeedTypesList = array(
			'rss1'=>A::t('app', 'RSS 1.0'), 
			'rss2'=>A::t('app', 'RSS 2.0'), 
			'atom'=>A::t('app', 'Atom')
    	);
    	$this->_view->rssItemsPerFeed = array(
			'1'=>'1',
			'2'=>'2',
			'3'=>'3',
			'4'=>'4', 
			'5'=>'5',
			'6'=>'6',
			'7'=>'7',
			'8'=>'8',
			'9'=>'9', 
			'10'=>'10',
			'12'=>'12',
			'15'=>'15',
			'20'=>'20',
			'25'=>'25', 
    	);
		$this->_view->searchItemsPerPage = array(
			'1'=>'1',
			'2'=>'2',
			'3'=>'3',
			'4'=>'4', 
			'5'=>'5',
			'6'=>'6',
			'7'=>'7',
			'8'=>'8',
			'9'=>'9', 
			'10'=>'10',
			'12'=>'12',
			'15'=>'15',
			'20'=>'20',
			'25'=>'25',
			'30'=>'30',
			'40'=>'40',
			'50'=>'50',
			'75'=>'75',
			'100'=>'100',
			'150'=>'150',
			'250'=>'250',
			'500'=>'500',
			'750'=>'750',
			'999'=>'999', 
		);
        $this->_view->sslModesList = array(
            '0'=>A::t('app', 'No'),
            '1'=>A::t('app', 'Entire Site'), 
            '2'=>A::t('app', 'Administrator Area Only'),
            '3'=>array('optionValue'=>A::t('app', 'Users/Clients Area Only'), 'optionDisabled'=>true),
            '4'=>array('optionValue'=>A::t('app', 'Payment Module Pages Only'), 'optionDisabled'=>true), 
        );
    	$this->_view->noYesArray = $this->_noYesArray();

        $this->_view->cacheEnable = CConfig::get('cache.enable');
		$this->_view->cacheType = CConfig::get('cache.type');
        $this->_view->cacheLifetime = CConfig::get('cache.lifetime');
        $this->_view->cachePath = CConfig::get('cache.path');
		
		$this->_view->rssFeedPath = CConfig::get('rss.path');

        if($this->_cRequest->getQuery('task') == 'clearCache'){

     		// Block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}

            if(APPHP_MODE == 'demo'){
                $this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
                $this->_alertType = 'warning';
            }elseif(CFile::isDirectoryEmpty('protected/tmp/cache/')){
                $this->_alert = A::t('app', 'No cache files found');
                $this->_alertType = 'error';                
            }elseif(CFile::emptyDirectory('protected/tmp/cache/')){
                $this->_alert = A::t('app', 'Clear Cache Success Message');
                $this->_alertType = 'success';
            }else{
                $this->_alert = A::t('app', 'Clear Cache Error Message');
                $this->_alertType = 'error';
            }            
        }elseif($this->_cRequest->getPost('act') == 'send'){
    		// Settings form submit
            
     		// Block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}
     		 
		    $this->_settings->is_offline = (int)$this->_cRequest->getPost('isOffline');
		    $this->_settings->offline_message = $this->_cRequest->getPost('offlineMsg');
            $this->_settings->ssl_mode = $this->_cRequest->getPost('sslMode');
		    $this->_settings->rss_feed_type = $this->_cRequest->getPost('rssFeedType');
			$this->_settings->rss_items_per_feed = $this->_cRequest->getPost('rssItemsPerFeed');			
			$this->_settings->search_items_per_page = (int)$this->_cRequest->getPost('searchItemsPerPage');
			$this->_settings->search_is_highlighted = (int)$this->_cRequest->getPost('searchIsHighlighted');			
            $this->_settings->dashboard_hotkeys = (int)$this->_cRequest->getPost('dashboardHotkeys');
            $this->_settings->dashboard_notifications = (int)$this->_cRequest->getPost('dashboardNotifications');
            $this->_settings->dashboard_statistics = (int)$this->_cRequest->getPost('dashboardStatistics');
		    
			$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
                	'isOffline'  		=> array('title'=>A::t('app', 'Site Offline'), 'validation'=>array('type'=>'set', 'source'=>array(0,1))),
					'offlineMsg' 		=> array('title'=>A::t('app', 'Offline Message'), 'validation'=>array('type'=>'any', 'maxLength'=>255)),
					'sslMode'    		=> array('title'=>A::t('app', 'Force SSL Mode'), 'validation'=>array('type'=>'set', 'source'=>array_keys($this->_view->sslModesList))),
                	'rssFeedType'		=> array('title'=>A::t('app', 'RSS Feed Type'), 'validation'=>array('type'=>'set', 'source'=>array_keys($this->_view->rssFeedTypesList))),
                	'rssItemsPerFeed'	=> array('title'=>A::t('app', 'RSS Feed Items'), 'validation'=>array('type'=>'set', 'source'=>array_keys($this->_view->rssItemsPerFeed))),
					'searchItemsPerPage'=> array('title'=>A::t('app', 'Search Results Page Size'), 'validation'=>array('type'=>'set', 'source'=>array_keys($this->_view->searchItemsPerPage))),
                	'cacheAllowed' 		=> array('title'=>A::t('app', 'Cache'), 'validation'=>array('type'=>'set', 'source'=>array(0,1))),
				),
		    ));
		    if($result['error']){
		    	$this->_alert = $result['errorMessage'];
		    	$this->_view->errorField = $result['errorField'];
		    	$this->_alertType = 'validation';
		    }else{
				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_alertType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_cSession->setFlash('alert', A::t('app', 'Settings Update Success Message'));
						$this->_cSession->setFlash('alertType', 'success');
						$this->redirect('settings/general');
					}else{
						$this->_alert = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_alertType = 'error';
					}
				}
		    }
    	}
		
		if($this->_cSession->hasFlash('alert')){
            $this->_alert = $this->_cSession->getFlash('alert');
            $this->_alertType = $this->_cSession->getFlash('alertType');
		}
		
        if(!empty($this->_alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
        }
   		
		$this->_view->settings = $this->_settings;        
    	$this->_view->tabs = $this->_prepareTab('general');		
    	$this->_view->render('settings/general');
    }
    
    /**
     * Visual settings action handler
     */
    public function visualAction()
    {    	 
    	// All active languages list for dropdown box
        $languages = Languages::model()->findAll(array('condition'=>'is_active = 1', 'orderBy'=>'sort_order ASC'));
        $langList = array();
       	if(is_array($languages)){
       		foreach($languages as $lang){
	       		$langList[$lang['code']] = $lang['name_native'];
	       	}
       	}
       	$this->_view->langList = $langList;
    	
    	if($this->_cRequest->getPost('act') == 'send'){
    		// settings form submit
    		
    		// Block access if admin has no active privilege to edit site settings
    		if(!Admins::hasPrivilege('site_settings', 'edit')){
    			$this->redirect('backend/index');
    		}
    		
            // Check site desc ID access
	    	$siteInfo = SiteInfo::model()->findByPk($this->_cRequest->getPost('siteDescId', 'int'));
            if(!$siteInfo){
                $this->redirect('backend/index');
            }

	    	$this->_view->selectedLanguage = $siteInfo->language_code;		
        	// Get selected site descriptions
    		$siteInfo->site_phone = $this->_cRequest->getPost('site_phone');
    		$siteInfo->site_fax = $this->_cRequest->getPost('site_fax');
			$siteInfo->site_email = $this->_cRequest->getPost('site_email');
    		$siteInfo->site_address = $this->_cRequest->getPost('site_address');			
    		$siteInfo->header = $this->_cRequest->getPost('header');
    		$siteInfo->slogan = $this->_cRequest->getPost('slogan');
    		$siteInfo->footer = $this->_cRequest->getPost('footer');
    		$siteInfo->meta_title = $this->_cRequest->getPost('meta_title');
    		$siteInfo->meta_keywords = $this->_cRequest->getPost('meta_keywords');
    		$siteInfo->meta_description = $this->_cRequest->getPost('meta_description');
    		$this->_view->siteInfo = $siteInfo;
    		
		   	$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
                	'selectedLanguage'	=> array('title'=>A::t('app', 'Language'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->langList))),
					'site_phone'		=> array('title'=>A::t('app', 'Phone'), 'validation'=>array('required'=>false, 'type'=>'phoneString', 'maxLength'=>50)),
					'site_fax'			=> array('title'=>A::t('app', 'Fax'), 'validation'=>array('required'=>false, 'type'=>'phoneString', 'maxLength'=>50)),
					'site_email'		=> array('title'=>A::t('app', 'Email'), 'validation'=>array('required'=>false, 'type'=>'email', 'maxLength'=>100)),
					'site_address'		=> array('title'=>A::t('app', 'Address'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'header'			=> array('title'=>A::t('app', 'Header Text'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>100)),
					'slogan'			=> array('title'=>A::t('app', 'Slogan'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'footer'			=> array('title'=>A::t('app', 'Footer Text'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'meta_title'		=> array('title'=>A::te('app', 'Tag TITLE'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>250)),
					'meta_keywords'		=> array('title'=>A::te('app', 'Meta tag KEYWORDS'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'meta_description'	=> array('title'=>A::te('app', 'Meta tag DESCRIPTION'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
				),
	   		));
    		if($result['error']){
    			$this->_alert = $result['errorMessage'];
    			$this->_view->errorField = $result['errorField'];
    			$this->_alertType = 'validation';
    		}else{
				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_alertType = 'warning';
				}else{
					if($siteInfo->save()){
						$this->_cSession->setFlash('alert', A::t('app', 'Settings Update Success Message'));
						$this->_cSession->setFlash('alertType', 'success');
						$this->redirect('settings/visual');
					}else{
						$this->_alert = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_alertType = 'error';
					}
				}
     		}
    	}else{	
    		// View the settings        	
	    	if($this->_cRequest->getPost('act') == 'changeLang'){
				// Block access if admin has no active privilege to edit site settings
				if(!Admins::hasPrivilege('site_settings', 'edit')){
					$this->redirect('backend/index');
				}
	    		// Language changed
	    		$selectedLanguage = $this->_cRequest->getPost('selectedLanguage');
	    	}else{
				// Default is current active language
				$selectedLanguage = $this->_cSession->hasFlash('selectedLanguage')
					? $this->_cSession->getFlash('selectedLanguage')
					: A::app()->getLanguage();
	    	}
	    	
			// Find site descriptions according to the selected language
            $siteInfo = SiteInfo::model()->find('language_code = :languageCode', array(':languageCode'=>$selectedLanguage));
			if(!$siteInfo){
                $this->redirect('backend/index');
            }else{
                $this->_view->selectedLanguage = $selectedLanguage;
                $this->_view->siteInfo = $siteInfo;                
            }
    	}        

		if($this->_cSession->hasFlash('alert')){
			$this->_alert = $this->_cSession->getFlash('alert');
			$this->_alertType = $this->_cSession->getFlash('alertType');
		}

		if(!empty($this->_alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
		}

    	$this->_view->tabs = $this->_prepareTab('visual');
    	$this->_view->render('settings/visual');
    }
    
    /**
     * Local settings action handler
     */
	public function localAction()
    {
		$weekdaysList = array(
			'1'=>A::t('app', 'Sunday'),
			'2'=>A::t('app', 'Monday'),
			'3'=>A::t('app', 'Tuesday'),
			'4'=>A::t('app', 'Wednesday'),
			'5'=>A::t('app', 'Thursday'),
			'6'=>A::t('app', 'Friday'),
			'7'=>A::t('app', 'Saturday'),				
		);
		$this->_view->weekdaysList = $weekdaysList;
		
    	$this->_view->timeZonesList = A::app()->getLocalTime()->getTimeZones();
        $timeZonesKeys = array();
        foreach($this->_view->timeZonesList as $key => $val){
            foreach($val as $vKey => $vVal){
                $timeZonesKeys[] = $vKey;
            }            
        }
        
    	$this->_view->numberFormatsList = array(
			'american'=>A::t('app', 'American Number Format'),
			'european'=>A::t('app', 'European Number Format'),			
		);
        
        $this->_view->utcTime = A::t('app', 'UTC time is').' '.gmdate('Y-m-d H:i:s', time()+date('Z'));
    	
    	if($this->_cRequest->getPost('act') != ''){
			// Settings form submit or select box change
			$this->_settings->date_format = $this->_cRequest->getPost('dateFormat');
			$this->_settings->time_format = $this->_cRequest->getPost('timeFormat');
			$this->_settings->datetime_format = $this->_cRequest->getPost('dateTimeFormat');
			$this->_settings->week_startday = $this->_cRequest->getPost('weekStartDay');
			$this->_settings->time_zone = $this->_cRequest->getPost('timeZone');
			$this->_settings->number_format = $this->_cRequest->getPost('numberFormat');
			$this->_settings->daylight_saving = $this->_cRequest->getPost('daylightSaving', '', 0);

			// Check date and time formats
			$isGoodFormats = $this->_checkDateTimeFormats();
		}

		if($this->_cRequest->getPost('act') == 'change'){
			// Select box changed
			$this->_alert = A::t('app', 'Save Changes Warning Message');
			$this->_alertType = 'warning';
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));							
		}elseif($this->_cRequest->getPost('act') == 'send'){							     		

     		// Block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}
			
     		// Settings form submit
			$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'dateFormat'     =>array('title'=>A::t('app', 'Date Format'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>10)),
					'timeFormat'     =>array('title'=>A::t('app', 'Time Format'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>5)),
					'dateTimeFormat' =>array('title'=>A::t('app', 'Date Time Format'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>16)),
                	'weekStartDay'   =>array('title'=>A::t('app', 'First Day of Week'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->weekdaysList))),
					'timeZone'       =>array('title'=>A::t('app', 'Time Zone'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>$timeZonesKeys)),
                	'daylightSaving' =>array('title'=>A::t('app', 'Daylight Saving'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1))),
 					'numberFormat'   =>array('title'=>A::t('app', 'Number Format'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->numberFormatsList))),
				),
			));
			if($result['error']){
				$this->_alert = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_alertType = 'validation';
			}else{
				// Check date and time formats
				if($isGoodFormats){
					if(APPHP_MODE == 'demo'){
						$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
						$this->_alertType = 'warning';
					}else{
						// Save the new settings
						if($this->_settings->save()){
							$this->_alert = A::t('app', 'Settings Update Success Message');
							$this->_alertType = 'success';
						}else{
							$this->_alert = A::t('app', 'Settings Update Error Message');
							$this->_view->errorField = '';
							$this->_alertType = 'error';
						}
					}
				}
			}

			if(!empty($this->_alert)){
				$this->_cSession->setFlash('alert', $this->_alert);
				$this->_cSession->setFlash('alertType', $this->_alertType);
				$this->redirect('settings/local');
			}
		}
        
		if($this->_cSession->hasFlash('alert')){
			$this->_alert = $this->_cSession->getFlash('alert');
			$this->_alertType = $this->_cSession->getFlash('alertType');
		}

		if(!empty($this->_alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
		}

		$this->_view->settings = $this->_settings;

		$dateFormatValue = array_key_exists($this->_settings->date_format, $this->_arrDateFormats) ? $this->_settings->date_format : '';
		$this->_view->dateFormatsList = CHtml::dropDownList(
			'dateFormatsDdl',
			$dateFormatValue,
			$this->_arrDateFormats, 
			array('submit'=>'$(this).closest("form").find("input[name=dateFormat]").val($(this).val());$(this).closest("form").find("input[name=act]").val("change");')
		).' ';
		$this->_view->dateFormatSample = $this->_view->dateFormatSample == 'no' ? '' : CLocale::date($this->_settings->date_format);
		
    	$timeFormatValue = array_key_exists($this->_settings->time_format, $this->_arrTimeFormats) ? $this->_settings->time_format : '';
    	$this->_view->timeFormatsList = CHtml::dropDownList('timeFormatsDdl', $timeFormatValue, $this->_arrTimeFormats,
			array('submit'=>'$(this).closest("form").find("input[name=timeFormat]").val($(this).val());$(this).closest("form").find("input[name=act]").val("change");')
    	).' ';		
    	$this->_view->timeFormatSample = $this->_view->timeFormatSample == 'no' ? '' : CLocale::date($this->_settings->time_format);
    	 
    	$dateTimeFormatValue = array_key_exists($this->_settings->datetime_format, $this->_arrDateTimeFormats) ? $this->_settings->datetime_format : '';
    	$this->_view->dateTimeFormatsList = CHtml::dropDownList('dateTimeFormatsDdl', $dateTimeFormatValue, $this->_arrDateTimeFormats,
			array('submit'=>'$(this).closest("form").find("input[name=dateTimeFormat]").val($(this).val());$(this).closest("form").find("input[name=act]").val("change");', 'style'=>'width:130px')
    	).' ';
    	$this->_view->dateTimeFormatSample = $this->_view->dateTimeFormatSample == 'no' ? '' : CLocale::date($this->_settings->datetime_format);
    	     	 
    	$this->_view->tabs = $this->_prepareTab('local');		
		$this->_view->render('settings/local');
	}
	
	/**
	 * Email settings action handler
	 */
	public function emailAction()
    {
		$nl = "\r\n";
		$this->_view->smtpPassword = '';
				
    	$this->_view->mailersList = array(
			'phpMail'=>'phpMail', 
			'phpMailer'=>'phpMailer', 
			'smtpMailer'=>'smtpMailer'
    	);
    	
		// Settings form submit
		if(in_array($this->_cRequest->getPost('act'), array('test', 'send'))){
			     		
     		// Block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}

			// Update
			$updateMalingLog = '';
			$mailingLog = (int)$this->_cRequest->getPost('mailing_log');
			if($this->_settings->mailing_log != $mailingLog){
				$updateMalingLog = $mailingLog;
			}
			
			$this->_settings->mailing_log = $mailingLog;
     		$this->_settings->mailer = $this->_cRequest->getPost('mailer');
			$this->_settings->general_email = $this->_cRequest->getPost('email');
            $this->_settings->general_email_name = $this->_cRequest->getPost('email_name');
			
			$fields['mailer'] = array('title'=>A::t('app', 'Mailer'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->mailersList)));
			$fields['email'] = array('title'=>A::t('app', 'Email Address'), 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100));
			$fields['email_name'] = array('title'=>A::t('app', 'Email Name'), 'validation'=>array('required'=>false, 'type'=>'mixed', 'maxLength'=>50));
			
			if($this->_settings->mailer == 'smtpMailer'){
                $this->_settings->smtp_auth = $this->_cRequest->getPost('smtpAuth');
				$this->_settings->smtp_secure = $this->_cRequest->getPost('smtpSecure');
				$this->_settings->smtp_host = $this->_cRequest->getPost('smtpHost');
				$this->_settings->smtp_port = $this->_cRequest->getPost('smtpPort','int');
				$this->_settings->smtp_username = $this->_cRequest->getPost('smtpUsername');
				$this->_view->smtpPassword = $this->_cRequest->getPost('smtpPassword');

                $fields['smtpAuth']     = array('title'=>A::t('app', 'SMTP Authentication'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)));
				$fields['smtpSecure']   = array('title'=>A::t('app', 'SMTP Secure'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array('ssl','tls','')));
				$fields['smtpHost']     = array('title'=>A::t('app', 'SMTP Host'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>70));
				$fields['smtpPort']     = array('title'=>A::t('app', 'SMTP Port'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>5));
				$fields['smtpUsername'] = array('title'=>A::t('app', 'SMTP Username'), 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>40));
				$fields['smtpPassword'] = array('title'=>A::t('app', 'SMTP Password'), 'validation'=>array('required'=>((!$this->_settings->smtp_password) ? true : false), 'type'=>'password', 'maxLength'=>20));
			}

			// Test email settings
			if($this->_cRequest->getPost('act') == 'test'){
				// Send email
				$body  = 'From: '.CConfig::get('name').$nl;
				$body .= 'Email: '.$this->_settings->general_email_name.' '.$this->_settings->general_email.$nl;
				$body .= 'Message: '.A::t('app', 'Test Email Body', array('{site}'=>CConfig::get('name')));

				if($this->_view->smtpPassword != ''){
					$smtp_password = $this->_view->smtpPassword;
				}else{
					$smtp_password = CHash::decrypt($this->_settings->smtp_password, CConfig::get('password.hashKey'));
				}
				
				CMailer::config(array(
					'mailer'=>$this->_settings->mailer,
                    'smtp_auth'=>$this->_settings->smtp_auth,
					'smtp_secure'=>$this->_settings->smtp_secure,
					'smtp_host'=>$this->_settings->smtp_host,
					'smtp_port'=>$this->_settings->smtp_port,
					'smtp_username'=>$this->_settings->smtp_username,
					'smtp_password'=>$smtp_password,
				));				
				
				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('app', 'Sending Email Blocked Alert');
					$this->_alertType = 'warning';
				}else{
					if(CMailer::send($this->_settings->general_email, A::t('app', 'Test Email Subject'), $body, array('from'=>$this->_settings->general_email, 'from_name'=>$this->_settings->general_email_name))){
						$this->_alert = A::t('app', 'Test Email Success Message');
						$this->_alertType = 'success';
					}else{
						if(APPHP_MODE == 'debug') $this->_alert = CMailer::getError();
						else $this->_alert = A::t('app', 'Test Email Error Message');
						$this->_alertType = 'error';
					}
				}
			// Submit the form
			}else{			
				$result = CWidget::create('CFormValidation', array('fields'=>$fields));
				if($result['error']){
					$this->_alert = $result['errorMessage'];
					$this->_view->errorField = $result['errorField'];
					$this->_alertType = 'validation';
				}else{
				    unset($this->_settings->smtp_password);
	                if($this->_view->smtpPassword != ''){
	                    $this->_settings->smtp_password = CHash::encrypt($this->_view->smtpPassword, CConfig::get('password.hashKey'));
	                }
					if(APPHP_MODE == 'demo'){
						$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
						$this->_alertType = 'warning';
					}else{
						if($this->_settings->save()){
							$this->_alert = A::t('app', 'Settings Update Success Message');
							$this->_alertType = 'success';
							
							if($updateMalingLog !== ''){
								$menu = BackendMenus::model()->find("url = 'mailingLog/'");
								$menu->is_visible = $updateMalingLog;
								$menu->save();
							}
						}else{
							$this->_alert = A::t('app', 'Settings Update Error Message');
							$this->_view->errorField = '';
							$this->_alertType = 'error';
						}
					}
				}
				
				// Always clear password field after form submission
				$this->_view->smtpPassword = '';
			}

			if(!empty($this->_alert) && $this->_alertType == 'success'){
				$this->_cSession->setFlash('alert', $this->_alert);
				$this->_cSession->setFlash('alertType', $this->_alertType);
				$this->redirect('settings/email');				
			}
		}

		if($this->_cSession->hasFlash('alert')){
			$this->_alert = $this->_cSession->getFlash('alert');
			$this->_alertType = $this->_cSession->getFlash('alertType');
		}

		if(!empty($this->_alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
		}

		$this->_view->settings = $this->_settings;
    	$this->_view->tabs = $this->_prepareTab('email');		
		$this->_view->render('settings/email');
	}
	
	/**
	 * Templates settings action handler
	 */
	public function templatesAction()
    {
		if($this->_cRequest->getPost('act') == 'send'){
			// Settings form submit
			
			// Block access if admin has no active privilege to edit site settings
			if(!Admins::hasPrivilege('site_settings', 'edit')){
				$this->redirect('backend/index');
			}
				
			$this->_view->selectedTemplate = $this->_cRequest->getPost('template');
				
			$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'template' =>array('title'=>A::t('app', 'Template'), 'validation'=>array('required'=>true, 'type'=>'fileName', 'maxLength'=>32)),
				),
			));
			if($result['error']){
				$this->_alert = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_alertType = 'validation';
			}else{
				$this->_settings->template = $this->_view->selectedTemplate;
				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_alertType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_alert = A::t('app', 'Settings Update Success Message');
						$this->_alertType = 'success';
					}else{
						$this->_alert = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_alertType = 'error';
					}
				}
			}

			if(!empty($this->_alert)){
				$this->_cSession->setFlash('alert', $this->_alert);
				$this->_cSession->setFlash('alertType', $this->_alertType);
				$this->redirect('settings/templates');				
			}	
		}elseif($this->_cRequest->getPost('act') == 'changeTemp'){
			// Template selection changed
			$this->_view->selectedTemplate = $this->_cRequest->getPost('template');
			$this->_alert = A::t('app', 'Save Changes Warning Message');
			$this->_alertType = 'warning';
		}else{
			$this->_view->selectedTemplate = $this->_settings->template;				
		}
        
		// Prepare alert message
		if($this->_cSession->hasFlash('alert')){
			$this->_alert = $this->_cSession->getFlash('alert');
			$this->_alertType = $this->_cSession->getFlash('alertType');
		}

		if(!empty($this->_alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
		}

        // Get all templates
		$this->_view->allTemplates = CFile::findSubDirectories('templates/');
        // Prepare available templates list 
		$templatesList = array();
        if(is_array($this->_view->allTemplates)){
        	foreach($this->_view->allTemplates as $temp){
        		if(!in_array($temp, array('backend', 'setup'))){
        			$templatesList[$temp] = ucfirst($temp);  			
        		}
        	}            
        }
        $this->_view->templatesList = $templatesList;
		
    	// Load XML file for template info
		if(array_key_exists($this->_view->selectedTemplate, $this->_view->templatesList) && @file_exists('templates/'.$this->_view->selectedTemplate.'/info.xml')) {
			$xml = simplexml_load_file('templates/'.$this->_view->selectedTemplate.'/info.xml');		 
        }
		$this->_view->name = isset($xml->name) ? $xml->name : A::t('app', 'Unknown');
		$this->_view->icon = isset($xml->icon) ? 'templates/'.$this->_view->selectedTemplate.'/'.$xml->icon : 'templates/backend/images/no_image.png';
		$this->_view->textDirection = isset($xml->direction) ? $xml->direction : A::t('app', 'Unknown');
		$this->_view->description = isset($xml->description) ? $xml->description : A::t('app', 'Unknown');
		$this->_view->author = isset($xml->author) ? $xml->author : A::t('app', 'Unknown');
		$this->_view->license = isset($xml->license) ? $xml->license : A::t('app', 'Unknown');
		$this->_view->version = isset($xml->version) ? $xml->version : A::t('app', 'Unknown');
		$this->_view->layout = isset($xml->layout) ? $xml->layout : A::t('app', 'Unknown');
		$this->_view->menus = '';
		if(isset($xml->menus->menu)){
			foreach($xml->menus->menu as $menu){
				$this->_view->menus .= (($this->_view->menus != '') ? ',' : '').$menu;
			}
		}			
				
    	$this->_view->tabs = $this->_prepareTab('templates');		
		$this->_view->render('settings/templates');
	}

	public function mappingApiAction()
    {
		// Settings form submit
		if($this->_cRequest->getPost('act') == 'send'){

			// Block access if admin has no active privilege to edit site settings
			if(!Admins::hasPrivilege('site_settings', 'edit')){
				$this->redirect('backend/index');
			}
				
			$this->_view->mappingApiType = $this->_cRequest->getPost('mapping_api_type');				
			$this->_view->mappingApiKey = $this->_cRequest->getPost('mapping_api_key');
			
			$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'mapping_api_type' =>array('title'=>A::t('app', 'Mapping API Type'), 'validation'=>array('required'=>true, 'type'=>'fileName', 'maxLength'=>32)),
					'mapping_api_key' =>array('title'=>A::t('app', 'Mapping API Key'), 'validation'=>array('required'=>false, 'type'=>'fileName', 'maxLength'=>50)),
				),
			));

			if($result['error']){
				$this->_alert = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_alertType = 'validation';
			}else{
				$this->_settings->mapping_api_type = $this->_view->mappingApiType;
				$this->_settings->mapping_api_key = $this->_view->mappingApiKey;

				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_alertType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_view->mappingType = $this->_settings->mapping_api_type;
						$this->_view->mappingTypeKey = $this->_settings->mapping_api_key;
						$this->_alert = A::t('app', 'Settings Update Success Message');
						$this->_alertType = 'success';
					}else{
						$this->_alert = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_alertType = 'error';
					}
				}
			}
			
			if(!empty($this->_alert)){
				$this->_cSession->setFlash('alert', $this->_alert);
				$this->_cSession->setFlash('alertType', $this->_alertType);
				$this->redirect('settings/mappingApi');
			}	
		}else{
			$this->_view->mappingType = $this->_settings->mapping_api_type;
			$this->_view->mappingTypeKey = $this->_settings->mapping_api_key;
			
			if($this->_settings->mapping_api_key == ''){
				$this->_alert = A::t('app', 'Mapping API Key is empty! It may lead to unstable work of map components.');
				$this->_alertType = 'warning';
			}
		}

		// Prepare alert message
		if($this->_cSession->hasFlash('alert')){
			$this->_alert = $this->_cSession->getFlash('alert');
			$this->_alertType = $this->_cSession->getFlash('alertType');
		}

		if(!empty($this->_alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
		}

    	$this->_view->tabs = $this->_prepareTab('mappingapi');
    	$this->_view->render('settings/mappingapi');
	}

	/**
	 * Server info action handler
	 */
	public function serverAction()
    {
    	ob_start();
		phpinfo(-1);
		$phpinfo = array('phpinfo' => array());
		if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
		foreach($matches as $match){
			$array_keys = array_keys($phpinfo);
			if(strlen($match[1])){
				$phpinfo[$match[1]] = array();
			}elseif(isset($match[3])){
				$phpinfo[end($array_keys)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			}else{
				$phpinfo[end($array_keys)][] = $match[2];
			}
		}
        
        $phpCoreIndex = ((version_compare(phpversion(), '5.3.0', '<'))) ? 'PHP Core' : 'Core';
        // For PHP v5.6 or later
        if(!isset($phpInfo[$phpCoreIndex]) && version_compare(phpversion(), '5.6.0', '>=') ){
            $phpCoreIndex = 'HTTP Headers Information';
        }
        $mysqlVersion = CDatabase::init()->getVersion();

	    $this->_view->phpVersion 	= function_exists('phpversion') ? phpversion() : A::t('app', 'Unknown');
	    $this->_view->dbDriver	    = ucfirst(CConfig::get('db.driver'));    
        
		$this->_view->mysqlVersion  = !empty($mysqlVersion) ? $mysqlVersion : A::t('app', 'Unknown');		
		$this->_view->aspTags 		= isset($phpinfo[$phpCoreIndex]['asp_tags']) ? $phpinfo[$phpCoreIndex]['asp_tags'][0] : A::t('app', 'Unknown');
		$this->_view->safeMode 		= isset($phpinfo[$phpCoreIndex]['safe_mode']) ? $phpinfo[$phpCoreIndex]['safe_mode'][0] : A::t('app', 'Unknown');
		$this->_view->shortOpenTag  = isset($phpinfo[$phpCoreIndex]['short_open_tag'][0]) ? $phpinfo[$phpCoreIndex]['short_open_tag'][0] : A::t('app', 'Unknown');
		$this->_view->vdSupport 	= isset($phpinfo['phpinfo']['Virtual Directory Support']) ? $phpinfo['phpinfo']['Virtual Directory Support'] : A::t('app', 'Unknown');
		$this->_view->modeRewrite 	= $this->_checkModRewrite() ? A::t('app', 'On') : A::t('app', 'Off');
		$this->_view->system 		= isset($phpinfo['phpinfo']['System']) ? $phpinfo['phpinfo']['System'] : A::t('app', 'Unknown');
		$this->_view->buildDate 	= isset($phpinfo['phpinfo']['Build Date']) ? $phpinfo['phpinfo']['Build Date'] : A::t('app', 'Unknown');
		$this->_view->serverApi 	= isset($phpinfo['phpinfo']['Server API']) ? $phpinfo['phpinfo']['Server API'] : A::t('app', 'Unknown');

		$this->_view->postMaxSize   = isset($phpinfo['Core']['post_max_size']) ? $phpinfo['Core']['post_max_size'][0] : A::t('app', 'Unknown');
		$this->_view->uploadMaxSize = isset($phpinfo['Core']['upload_max_filesize']) ? $phpinfo['Core']['upload_max_filesize'][0] : A::t('app', 'Unknown');
		
		$this->_view->smtp 	 		= (ini_get('SMTP') != '') ? ini_get('SMTP') : A::t('app', 'Unknown');
		$this->_view->smtpPort	 	= (ini_get('smtp_port') != '') ? ini_get('smtp_port') : A::t('app', 'Unknown');
		$this->_view->sendmailFrom 	= (ini_get('sendmail_from') != '') ? ini_get('sendmail_from') : A::t('app', 'Unknown');
		$this->_view->sendmailPath 	= (ini_get('sendmail_path') != '') ? ini_get('sendmail_path') : A::t('app', 'Unknown');

		$this->_view->sessionSupport = isset($phpinfo['session']['Session Support']) ? $phpinfo['session']['Session Support'] :  A::t('app', 'Unknown');
		if(version_compare(phpversion(), '5.3.0', '<')){
			$this->_view->magicQuotesGpc = ini_get('magic_quotes_gpc') ? A::t('app', 'On') : A::t('app', 'Off');
			$this->_view->magicQuotesRuntime = ini_get('magic_quotes_runtime') ? A::t('app', 'On') : A::t('app', 'Off');
			$this->_view->magicQuotesSybase = ini_get('magic_quotes_sybase') ? A::t('app', 'On') : A::t('app', 'Off');
		}
    	
		$this->_view->tabs = $this->_prepareTab('server');		
		$this->_view->render('settings/server');
	}

	/**
	 * Site info action handler
	 */
	public function siteAction()
    {
   		$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : A::t('app', 'Unknown');
		
		// Allow changes for site owner only
		if($this->_cRequest->getPost('act') == 'send'){
			if(CAuth::isLoggedInAs('owner')){				
				$domain = CString::substr($this->_cRequest->getPost('website_domain', '', ''), 255);
				$domain = preg_replace('/(www\.|http:\/\/|https:\/\/|ftp:\/\/| )/i', '', $domain);
				$isDomainValid = true;
				
				// Update button clicked - update the ranks
				if(!empty($domain)){
					if(APPHP_MODE !== 'demo'){				
						if(CValidator::isDomainName($domain)){							
							///$this->_settings->google_rank = (int)$this->_checkGoogleRank($domain);
							$this->_settings->alexa_rank = number_format((float)$this->_checkAlexaRank($domain));
							if($indexedPages = $this->_checkIndexedPages($domain, 'google')){
								$this->_settings->indexed_pages_google = $indexedPages;	
							}
							if($indexedPages = $this->_checkIndexedPages($domain, 'bing')){
								$this->_settings->indexed_pages_bing = $indexedPages;
							}
							if($indexedPages = $this->_checkIndexedPages($domain, 'yahoo')){
								$this->_settings->indexed_pages_yahoo = $indexedPages;	
							}
							if($indexedPages = $this->_checkIndexedPages($domain, 'yandex')){
								$this->_settings->indexed_pages_yandex = $indexedPages;	
							}
							if($indexedPages = $this->_checkIndexedPages($domain, 'baidu')){
								$this->_settings->indexed_pages_baidu = $indexedPages;	
							}
							if($indexedPages = $this->_checkIndexedPages($domain, 'goo')){
								$this->_settings->indexed_pages_goo = $indexedPages;	
							}
						}else{
							$isDomainValid = false;
						}
					}
				}else{
					$this->_settings->alexa_rank = 0;
					$this->_settings->indexed_pages_google = 0;
					$this->_settings->indexed_pages_bing = 0;
					$this->_settings->indexed_pages_yahoo = 0;
					$this->_settings->indexed_pages_yandex = 0;
					$this->_settings->indexed_pages_baidu = 0;
					$this->_settings->indexed_pages_goo = 0;
				}
				
				$this->_settings->site_last_updated = LocalTime::currentDateTime();
				$this->_settings->website_domain = $domain;
				
				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_alertType = 'warning';
				}else{
					if($isDomainValid){
						if($this->_settings->save()){
							$this->_alert = A::t('app', 'Site Info Success Message');
							$this->_alertType = 'success';
						}else{
							$this->_alert = A::t('app', 'Site Info Error Message');
							$this->_alertType = 'error';
						}
					}else{
						$this->_alert = A::t('app', 'Invalid domain name has been submitted! Please re-enter.');
						$this->_alertType = 'warning';
					}
				}
			}else{
				$this->_alert = A::t('app', 'You have no privileges to make changes on this page');
				$this->_alertType = 'warning';
			}
			
			if(!empty($this->_alert)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
			}
		}
		
		$this->_view->dateTimeFormat = Bootstrap::init()->getSettings('datetime_format');
		$this->_view->settings = $this->_settings;
		$this->_view->tabs = $this->_prepareTab('site');		
		$this->_view->render('settings/site');
	}
		
	/**
	 * Cron jobs settings action handler
	 */
	public function cronAction()
    {		
		$this->_view->cronTypesList = array(
			'batch' => A::t('app', 'Batch'),
			'non-batch' => A::t('app', 'Non-batch'),
			'stop' => A::t('app', 'Stop'),
		);		
		
		for($i=1; $i<=100; $i++) $numbersList[$i] = $i; 
		$this->_view->numbersList = $numbersList;
		
		$this->_view->cronRunPeriodsList = array(
			'minute' => A::t('app', 'minutes'),
			'hour' => A::t('app', 'hours'),
		);
    	
    	if($this->_cRequest->getPost('act') == 'send'){
			// Settings form submit
    		
    		// Block access if admin has no active privilege to edit site settings
    		if(!Admins::hasPrivilege('site_settings', 'edit')){
    			$this->redirect('backend/index');
    		}
    		
			$this->_settings->cron_type = $this->_cRequest->getPost('cronType');
			$this->_settings->cron_run_period_value = $this->_cRequest->getPost('cronRunPeriodValue', '', 0);
			$this->_settings->cron_run_period = $this->_cRequest->getPost('cronRunPeriod', '', '');
				
			$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'cronType' =>array('title'=>A::t('app', 'Run Cron'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->cronTypesList))),
					'cronRunPeriodValue' =>array('title'=>A::t('app', 'Run Every'), 'validation'=>array('required'=>false, 'type'=>'range', 'minValue'=>1, 'maxValue'=>100)),
					'cronRunPeriod' =>array('title'=>A::t('app', 'Run Every'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array_keys($this->_view->cronRunPeriodsList))),
				),
			));
			if($result['error']){
				$this->_alert = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_alertType = 'validation';
			}else{
				if(APPHP_MODE == 'demo'){
					$this->_alert = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_alertType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_alert = A::t('app', 'Settings Update Success Message');
						$this->_alertType = 'success';
					}else{
						$this->_alert = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_alertType = 'error';
					}
				}
			}
			if(!empty($this->_alert)){
				$this->_cSession->setFlash('alert', $this->_alert);
				$this->_cSession->setFlash('alertType', $this->_alertType);
				$this->redirect('settings/cron');				
			}
		}
        
		// Prepare alert message
		if($this->_cSession->hasFlash('alert')){
			$this->_alert = $this->_cSession->getFlash('alert');
			$this->_alertType = $this->_cSession->getFlash('alertType');
		}

		if(!empty($this->_alert)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_alertType, $this->_alert, array('button'=>true)));
		}

		$this->_view->settings = $this->_settings;
		
    	$this->_view->tabs = $this->_prepareTab('cron');		
		$this->_view->render('settings/cron');
	}

	/**
	 * Prepare settings tabs
	 * @param string $activeTab general|visual|local|email|templates|server|site|cron
	 */
	private function _prepareTab($activeTab = 'general')
    {
		return CWidget::create('CTabs', array(
		   	'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('app', 'General Settings')  =>array('href'=>'settings/general', 'id'=>'tab1', 'content'=>'', 'active'=>($activeTab == 'general' ? true : false)),
				A::t('app', 'Visual Settings')   =>array('href'=>'settings/visual', 'id'=>'tab2', 'content'=>'', 'active'=>($activeTab == 'visual' ? true : false)),
				A::t('app', 'Local Settings')  	 =>array('href'=>'settings/local', 'id'=>'tab3', 'content'=>'', 'active'=>($activeTab == 'local' ? true : false)),
				A::t('app', 'Email Settings')  	 =>array('href'=>'settings/email', 'id'=>'tab4', 'content'=>'', 'active'=>($activeTab == 'email' ? true : false)),
				A::t('app', 'Templates & Styles')=>array('href'=>'settings/templates', 'id'=>'tab5', 'content'=>'', 'active'=>($activeTab == 'templates' ? true : false)),
				A::t('app', 'Mapping APIs')		 =>array('href'=>'settings/mappingApi', 'id'=>'tab6', 'content'=>'', 'active'=>($activeTab == 'mappingapi' ? true : false)),
				A::t('app', 'Server Info')  	 =>array('href'=>'settings/server', 'id'=>'tab7', 'content'=>'', 'active'=>($activeTab == 'server' ? true : false)),
				A::t('app', 'Site Info')  		 =>array('href'=>'settings/site', 'id'=>'tab8', 'content'=>'', 'active'=>($activeTab == 'site' ? true : false)),
				A::t('app', 'Cron Jobs')  		 =>array('href'=>'settings/cron', 'id'=>'tab9', 'content'=>'', 'active'=>($activeTab == 'cron' ? true : false)),
			),
			'return'=>true,
		));
	}

	/**
	 * Returns Alexa Page Rank
	 * @param $url
	 */
	private function _checkAlexaRank($url)
	{
		$part = '';
		$remote_url = 'http://data.alexa.com/data?cli=10&dat=snbamz&url='.trim($url);
		$search_for = '<POPULARITY URL';
		if($handle = @fopen($remote_url, 'r')) {
			while(!feof($handle)){
				$part .= fread($handle, 100);
				$pos = strpos($part, $search_for);
				if($pos === false) continue;
				else break;
			}
			$part .= fread($handle, 100);
			fclose($handle);
		}
		$str = explode($search_for, $part);
		$str_1 = (isset($str[1])) ? $str[1] : '';
		$explode = explode('"/>', $str_1);
		$str = array_shift($explode);
		$str = explode('TEXT="', $str);
		$str_2 = (isset($str[1])) ? $str[1] : '';
		return $str_2;
	}
	
	/**
	 * Returns Google Page Rank
	 * @param $url
	 */
	private function _checkGoogleRank($url)
	{
		$pagerank = '-1';
		$nl = "\r\n";
		
		$fp = fsockopen('toolbarqueries.google.com', 80, $errno, $errstr, 30);
		if(!$fp){
			//echo '$errstr ($errno)<br />\n';
		}else{
			$out  = 'GET /tbr?client=navclient-auto&ch='.$this->_checkHash($this->_hashURL($url)).'&features=Rank&q=info:'.$url.'&num=100&filter=0 HTTP/1.1'.$nl;
			$out .= 'Host: toolbarqueries.google.com'.$nl;
			$out .= 'User-Agent: Mozilla/4.0 (compatible; GoogleToolbar 2.0.114-big; Windows XP 5.1)'.$nl;
			$out .= 'Connection: Close'.$nl.$nl;
			fwrite($fp, $out);
			while(!feof($fp)){
				$data = fgets($fp, 128);
				$pos = strpos($data, 'Rank_');
				if($pos === false){
				} else{
					$pagerank = substr($data, $pos + 9);
				}
			}
			fclose($fp);
		}
		return $pagerank;
	}
	
	/**
	 * Returns search engines indexed pages
	 * @param $url
	 */
	private function _checkIndexedPages($url, $searchEngine = 'google')
	{
		$indexedPages = 0;
		$nl = "\r\n";
		
		// Non empty URL
		if(!empty($url)){
			if($searchEngine == 'google'){
				$content = @$this->_cRequest->getUrlContent('https://www.google.com/search?filter=0&q=site:'.$url);
				$pos = strpos($content, 'id="resultStats"');
				if($pos !== false){
					$text = substr($content, $pos+15, 15);
					$indexedPages = preg_replace('/[^0-9,]/', '', $text);					
				}
			}elseif($searchEngine == 'bing'){
				$content = $this->_cRequest->getUrlContent('http://www.bing.com/search?scope=web&setmkt=en-US&setlang=match&FORM=W5WA&q=site:'.$url);
				$pos = strpos($content, 'class="sb_count"');
				if($pos !== false){
					$text = substr($content, $pos+15, 17);
					$indexedPages = preg_replace('/[^0-9,]/', '', $text);
				}
			}elseif($searchEngine == 'yahoo'){
				$content = @$this->_cRequest->getUrlContent('https://search.yahoo.com/search?fr=sfp&p=site:'.$url);
				$pos = strpos($content, 'results</span>'); 
				if($pos !== false){
					$text = substr($content, $pos-10, 15);
					$indexedPages = preg_replace('/[^0-9,]/', '', $text);
				}
			}elseif($searchEngine == 'yandex'){
				$content = @$this->_cRequest->getUrlContent('http://yandex.ru/yandsearch?text=site:'.$url.'&lr=10418');
				$pos = strpos($content, 'serp-adv__found"'); 
				if($pos !== false){
					$text = substr($content, $pos+12, 17);
					$indexedPages = preg_replace('/[^0-9,]/', '', $text);
				}
			}elseif($searchEngine == 'baidu'){
				$content = @$this->_cRequest->getUrlContent('http://www.baidu.com/s?ie=utf-8&wd=site:'.$url);
				preg_match('/<div class="c-span21 c-span-last">(.*?)<\/div>/i', $content, $matches);
				if(isset($matches[0])){
					$matches[0] = strip_tags($matches[0]);
					$indexedPages = preg_replace('/[^0-9,]/', '', $matches[0]);
				}
			}elseif($searchEngine == 'goo'){
				$indexedPages = 0;
			}
		}
		
		return $indexedPages;
	}
	
	/**
	 * Checks hash for url
	 * @param $hashnum
	 */
	private function _checkHash($hashnum)
	{
		$check_byte = 0;
		$flag = 0;
		$hash_str = sprintf('%u', $hashnum);
		$length = strlen($hash_str);
		for($i = $length - 1; $i >= 0; $i --){
			$re = $hash_str{$i};
			if (1 === ($flag % 2)){
				$re += $re;
				$re = (int)($re / 10) + ($re % 10);
			}
			$check_byte += $re;
			$flag ++;
		}
		$check_byte %= 10;
		if(0 !== $check_byte){
			$check_byte = 10 - $check_byte;
			if (1 === ($flag % 2)){
				if(1 === ($check_byte % 2)) $check_byte += 9;
				$check_byte >>= 1;
			}
		}
		return '7'.$check_byte.$hash_str;
	}
	
	/**
	 * Genearate hash for url
	 * @param $str
	 */
	private function _hashURL($str)
	{
		$check1 = $this->_strToNum($str, 0x1505, 0x21);
		$check2 = $this->_strToNum($str, 0, 0x1003F);
		$check1 >>= 2;
		$check1 = (($check1 >> 4) & 0x3FFFFC0 ) | ($check1 & 0x3F);
		$check1 = (($check1 >> 4) & 0x3FFC00 ) | ($check1 & 0x3FF);
		$check1 = (($check1 >> 4) & 0x3C000 ) | ($check1 & 0x3FFF);
		$t1 = (((($check1 & 0x3C0) << 4) | ($check1 & 0x3C)) <<2 ) | ($check2 & 0xF0F);
		$t2 = (((($check1 & 0xFFFFC000) << 4) | ($check1 & 0x3C00)) << 0xA) | ($check2 & 0xF0F0000);
		return ($t1 | $t2);
	}
	
	/**
	 * Converts string into 32-bit integer
	 * @param $str
	 * @param $check
	 * @param $magic
	 */
	private function _strToNum($str, $check, $magic)
	{
		$int_32_u = 4294967296;  // 2^32
		$length = strlen($str);
		for ($i = 0; $i < $length; $i++) {
			$check *= $magic;
			// If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
			// the result of converting to integer is undefined
			// refer to http://www.php.net/manual/en/language.types.integer.php
			if ($check >= $int_32_u) {
				$check = ($check - $int_32_u * (int) ($check / $int_32_u));
				// If the check less than -2^31
				$check = ($check < -2147483648) ? ($check + $int_32_u) : $check;
			}
			$check += ord($str{$i});
		}
		return $check;
	}

	/**
	 * Returns array with Yes/No values
	 */
	private function _yesNoArray()
	{
		return array(
			'1'=>A::t('app', 'Yes'), 
			'0'=>A::t('app', 'No'),
		);
	}
	/**
	 * Returns array with No/Yes values
	 */
	private function _noYesArray()
	{
		return array(
			'0'=>A::t('app', 'No'),
			'1'=>A::t('app', 'Yes'),
		);
	}
	
	/**
	 * Check if date format is valid
	 * @param string $format
	 * @return boolean
	 */
	private function _testDateFormat($format)
	{
		if(version_compare(phpversion(), '5.3.0', '>=')){
			$testDate = CTime::dateParseFromFormat($format, date($format));
			return checkdate($testDate['month'], $testDate['day'], $testDate['year']);	
		}else{
			return array_key_exists($format, $this->_arrDateFormats) ? true : false;
		}
	}
	
	/**
	 * Check if time format is valid
	 * @param string $format
	 * @return boolean
	 */
	private function _testTimeFormat($format)
	{
		if(version_compare(phpversion(), '5.3.0', '>=')){
			$testTime = CTime::dateParseFromFormat($format, date($format));
			return ($testTime['error_count'] == 0 && is_int($testTime['hour']) && is_int($testTime['minute']) && is_int($testTime['second']));
		}else{
			return (array_key_exists($format, $this->_arrTimeFormats) || array_key_exists($format, $this->_arrDateTimeFormats)) ? true : false;			
		}
	}
	
	/**
	 * Check if user entered date, time, date & time formats are valid 
	 * @return boolean
	 */
	private function _checkDateTimeFormats()
	{
		// Check date and time formats
		if(!$this->_testDateFormat($this->_settings->date_format)){
			$this->_view->dateFormatSample = 'no';
			$this->_alert = A::t('app', 'Date Format Error Message');
			$this->_view->errorField = 'dateFormat';
			$this->_alertType = 'validation';
		}
		if(!$this->_testTimeFormat($this->_settings->time_format)){
			$this->_view->timeFormatSample = 'no';
			if($this->_alert == ''){
				$this->_alert = A::t('app', 'Time Format Error Message');
				$this->_view->errorField = 'timeFormat';
				$this->_alertType = 'validation';
			}
		}
		if(!$this->_testDateFormat($this->_settings->datetime_format) && !$this->_testTimeFormat($this->_settings->datetime_format)){
			$this->_view->dateTimeFormatSample = 'no';
			if($this->_alert == ''){
				$this->_alert = A::t('app', 'Date Time Format Error Message');
				$this->_view->errorField = 'dateTimeFormat';
				$this->_alertType = 'validation';
			}
		}
		if($this->_alert == ''){
			return true;
		}else{
			return false;
		}
	}

	/**
     * Checks mod_rewrite
	 */
    private function _checkModRewrite()
    {
        if(function_exists('apache_get_modules')){
            // Works only if PHP is not running as CGI module
            $mod_rewrite = in_array('mod_rewrite', apache_get_modules());
        }else{
            return true;            
        }   
    }

}