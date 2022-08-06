<?php
/**
 * Settings controller
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------
 * __construct              _prepareTab
 * indexAction			   	_checkAlexaRank
 * generalAction			_checkGoogleRank
 * visualAction			   	_checkHash
 * localAction			   	_hashURL
 * emailAction				_strToNum
 * templatesAction			_yesNoArray
 * serverAction				_noYesArray
 * siteAction				_testDateFormat 
 * cronAction				_testTimeFormat
 *                          _checkDateTimeFormats
 *                          _checkModRewrite
 */

class SettingsController extends CController
{
	private $_settings;
	private $_msg;
	private $_errorType;
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
        
        // block access to this controller to non-logged users
		CAuth::handleLogin('backend/login');		
	        
		// block access if admin has no active privileges to access site settings
		if(!Admins::hasPrivilege('site_settings', 'view')){
			$this->redirect('backend/index');
		}
		
        // set meta tags according to active language
    	Website::setMetaTags(array('title'=>A::t('app', 'Site Settings')));
        // set backend mode
        Website::setBackend();

        $this->_view->actionMessage = '';
        $this->_view->errorField = ''; 
        $this->_msg = '';
        $this->_errorType = '';
        
        $this->_settings = Bootstrap::init()->getSettings();
        if(!$this->_settings){
    		$this->redirect('backend/index');
        }                    
        $this->_cRequest = A::app()->getRequest();       

    	$this->_arrDateFormats = array(
			'Y-m-d' => '[ Y-m-d ] ',
			'd-m-Y' => '[ d-m-Y ] ',
			'm-d-Y' => '[ m-d-Y ] ',
			'Y F d' => '[ Y F d ] ',
			'M d, Y' => '[ M d, Y ] ',
			'd M, Y' => '[ d M, Y ] ',
			'F d Y' => '[ F d Y ] ',
			'F j, Y' => '[ F j, Y ] ',
			'd F Y' => '[ d F Y ] ',
			'd F, Y' => '[ d F, Y ] ',
			'' => A::t('app', 'Custom...'),
		);		

    	$this->_arrTimeFormats = array(
			'H:i:s'=>'[ H:i:s ] ',
			'h:i:s'=>'[ h:i:s ] ',
			'g:i:s'=>'[ g:i:s ] ',
			'h:i a'=>'[ h:i a ] ',
			'h:i A'=>'[ h:i A ] ',
			'g:i a'=>'[ g:i a ] ',
			'g:i A'=>'[ g:i A ] ',
			'H:i'=>'[ H:i ] ',
			'h:i'=>'[ h:i ] ',
			'g:i'=>'[ g:i ] ',
			'' => A::t('app', 'Custom...'),
		);

    	$this->_arrDateTimeFormats = array(
			'Y-m-d H:i:s'=>'[ Y-m-d H:i:s ] ',
			'm-d-Y H:i:s'=>'[ m-d-Y H:i:s ] ',
			'm-d-Y g:ia'=>'[ m-d-Y g:ia ] ',
			'd-m-Y H:i:s'=>'[ d-m-Y H:i:s ] ',
			'd-m-Y g:ia'=>'[ d-m-Y g:ia ] ',
			'M d, Y g:ia'=>'[ M d, Y g:ia ] ',
			'd M, Y g:ia'=>'[ d M, Y g:ia ] ',
			'F j Y, g:ia'=>'[ F j Y, g:ia ] ',
			'j F Y, g:ia'=>'[ j F Y, g:ia ] ',
			'D, F j Y g:ia'=>'[ D, F j Y g:ia ] ',
			'D, M d Y g:ia'=>'[ D, M d Y g:ia ] ',
			'' => A::t('app', 'Custom...'),
		);		
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
        $this->_view->cacheLifetime = CConfig::get('cache.lifetime');
        $this->_view->cachePath = CConfig::get('cache.path');
		
		$this->_view->rssFeedPath = CConfig::get('rss.path');

        if($this->_cRequest->getQuery('task') == 'clearCache'){

     		// block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}

            if(APPHP_MODE == 'demo'){
                $this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
                $this->_errorType = 'warning';
            }else if(CFile::isDirectoryEmpty('protected/tmp/cache/')){
                $this->_msg = A::t('app', 'No cache files found');
                $this->_errorType = 'error';                
            }else if(CFile::emptyDirectory('protected/tmp/cache/')){
                $this->_msg = A::t('app', 'Clear Cache Success Message');
                $this->_errorType = 'success';
            }else{
                $this->_msg = A::t('app', 'Clear Cache Error Message');
                $this->_errorType = 'error';
            }            
        }else if($this->_cRequest->getPost('act') == 'send'){
    		// settings form submit
            
     		// block access if admin has no active privilege to edit site settings
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
		    	$this->_msg = $result['errorMessage'];
		    	$this->_view->errorField = $result['errorField'];
		    	$this->_errorType = 'validation';
		    }else{
				if(APPHP_MODE == 'demo'){
					$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_errorType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_msg = A::t('app', 'Settings Update Success Message');
						$this->_errorType = 'success';
					}else{
						$this->_msg = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_errorType = 'error';
					}
				}
		    }
    	}
		
        if(!empty($this->_msg)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
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
    	// all active languages list for dropdown box
        $languages = Languages::model()->findAll('is_active = 1');
        $langList = array();
       	if(is_array($languages)){
       		foreach($languages as $lang){
	       		$langList[$lang['code']] = $lang['name_native'];
	       	}
       	}
       	$this->_view->langList = $langList;
    	
    	if($this->_cRequest->getPost('act') == 'send'){
    		// settings form submit
    		
    		// block access if admin has no active privilege to edit site settings
    		if(!Admins::hasPrivilege('site_settings', 'edit')){
    			$this->redirect('backend/index');
    		}
    		
            // check site desc ID access
	    	$siteInfo = SiteInfo::model()->findByPk($this->_cRequest->getPost('siteDescId', 'int'));
            if(!$siteInfo){
                $this->redirect('backend/index');
            }

	    	$this->_view->selectedLanguage = $siteInfo->language_code;		
        	// get selected site descriptions
    		$siteInfo->header = $this->_cRequest->getPost('siteHeader');
    		$siteInfo->slogan = $this->_cRequest->getPost('slogan');
    		$siteInfo->footer = $this->_cRequest->getPost('footer');
    		$siteInfo->meta_title = $this->_cRequest->getPost('metaTagTitle');
    		$siteInfo->meta_keywords = $this->_cRequest->getPost('metaTagKeywords');
    		$siteInfo->meta_description = $this->_cRequest->getPost('metaTagDescription');
    		$this->_view->siteInfo = $siteInfo;
    		
		   	$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
                	'selectedLanguage'=>array('title'=>A::t('app', 'Language'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->langList))),
					'siteHeader'	=>array('title'=>A::t('app', 'Header Text'), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>100)),
					'slogan'		=>array('title'=>A::t('app', 'Slogan'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'footer'		=>array('title'=>A::t('app', 'Footer Text'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'metaTagTitle'		=>array('title'=>CHtml::encode(A::t('app', 'Tag TITLE')), 'validation'=>array('required'=>true, 'type'=>'any', 'maxLength'=>250)),
					'metaTagKeywords'	=>array('title'=>CHtml::encode(A::t('app', 'Meta tag KEYWORDS')), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
					'metaTagDescription'=>array('title'=>CHtml::encode(A::t('app', 'Meta tag DESCRIPTION')), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>250)),
				),
	   		));
    		if($result['error']){
    			$this->_msg = $result['errorMessage'];
    			$this->_view->errorField = $result['errorField'];
    			$this->_errorType = 'validation';
    		}else{
				if(APPHP_MODE == 'demo'){
					$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_errorType = 'warning';
				}else{
					if($siteInfo->save()){
						$this->_msg = A::t('app', 'Settings Update Success Message');
						$this->_errorType = 'success';
					}else{
						$this->_msg = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_errorType = 'error';
					}
				}
     		}
	    	if(!empty($this->_msg)){
	    		$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
	    	}	    
	    		    	
    	}else{	
    		// view the settings        	
	    	if($this->_cRequest->getPost('act') == 'changeLang'){
				
				// block access if admin has no active privilege to edit site settings
				if(!Admins::hasPrivilege('site_settings', 'edit')){
					$this->redirect('backend/index');
				}
				
	    		// language changed
	    		$selectedLanguage = $this->_cRequest->getPost('selectedLanguage');
	    	}else{
	    		// default is current active language
		    	$selectedLanguage = A::app()->getLanguage();		
	    	}
	    	// find site descriptions according to the selected language
            $siteInfo = SiteInfo::model()->find('language_code = :languageCode', array(':languageCode'=>$selectedLanguage));
			if(!$siteInfo){
                $this->redirect('backend/index');
            }else{
                $this->_view->selectedLanguage = $selectedLanguage;
                $this->_view->siteInfo = $siteInfo;                
            }
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
			// settings form submit or select box change
			$this->_settings->date_format = $this->_cRequest->getPost('dateFormat');
			$this->_settings->time_format = $this->_cRequest->getPost('timeFormat');
			$this->_settings->datetime_format = $this->_cRequest->getPost('dateTimeFormat');
			$this->_settings->week_startday = $this->_cRequest->getPost('weekStartDay');
			$this->_settings->time_zone = $this->_cRequest->getPost('timeZone');
			$this->_settings->number_format = $this->_cRequest->getPost('numberFormat');
			$this->_settings->daylight_saving = $this->_cRequest->getPost('daylightSaving', '', 0);

			// check date and time formats
			$isGoodFormats = $this->_checkDateTimeFormats();
		}

		if($this->_cRequest->getPost('act') == 'change'){
			// select box changed
			$this->_msg = A::t('app', 'Save Changes Warning Message');
			$this->_errorType = 'warning';
			$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));							
		}else if($this->_cRequest->getPost('act') == 'send'){							     		

     		// block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}
			
     		// settings form submit
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
				$this->_msg = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_errorType = 'validation';
			}else{
				// check date and time formats
				if($isGoodFormats){
					if(APPHP_MODE == 'demo'){
						$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
						$this->_errorType = 'warning';
					}else{
						// save the new settings
						if($this->_settings->save()){
							$this->_msg = A::t('app', 'Settings Update Success Message');
							$this->_errorType = 'success';
						}else{
							$this->_msg = A::t('app', 'Settings Update Error Message');
							$this->_view->errorField = '';
							$this->_errorType = 'error';
						}
					}
				}
			}
			if(!empty($this->_msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
			}
		}
        
		$this->_view->settings = $this->_settings;

		$dateFormatValue = array_key_exists($this->_settings->date_format, $this->_arrDateFormats) ? $this->_settings->date_format : '';
		$this->_view->dateFormatsList = CHtml::dropDownList(
			'dateFormatsDdl',
			$dateFormatValue,
			$this->_arrDateFormats, 
			array('submit'=>'$(this).closest("form").find("input[name=dateFormat]").val($(this).val());$(this).closest("form").find("input[name=act]").val("change");')
		).' ';
		$this->_view->dateFormatSample = $this->_view->dateFormatSample == 'no' ? '' : date($this->_settings->date_format);
		
    	$timeFormatValue = array_key_exists($this->_settings->time_format, $this->_arrTimeFormats) ? $this->_settings->time_format : '';
    	$this->_view->timeFormatsList = CHtml::dropDownList('timeFormatsDdl', $timeFormatValue, $this->_arrTimeFormats,
			array('submit'=>'$(this).closest("form").find("input[name=timeFormat]").val($(this).val());$(this).closest("form").find("input[name=act]").val("change");')
    	).' ';		
    	$this->_view->timeFormatSample = $this->_view->timeFormatSample == 'no' ? '' : date($this->_settings->time_format);
    	 
    	$dateTimeFormatValue = array_key_exists($this->_settings->datetime_format, $this->_arrDateTimeFormats) ? $this->_settings->datetime_format : '';
    	$this->_view->dateTimeFormatsList = CHtml::dropDownList('dateTimeFormatsDdl', $dateTimeFormatValue, $this->_arrDateTimeFormats,
			array('submit'=>'$(this).closest("form").find("input[name=dateTimeFormat]").val($(this).val());$(this).closest("form").find("input[name=act]").val("change");')
    	).' ';		
    	$this->_view->dateTimeFormatSample = $this->_view->dateTimeFormatSample == 'no' ? '' : date($this->_settings->datetime_format);
    	     	 
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
    	
		if(in_array($this->_cRequest->getPost('act'), array('test', 'send'))){
			// settings form submit
			     		
     		// block access if admin has no active privilege to edit site settings
     		if(!Admins::hasPrivilege('site_settings', 'edit')){
     			$this->redirect('backend/index');
     		}
			
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

			// test email settings
			if($this->_cRequest->getPost('act') == 'test'){
				// send email
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
					$this->_msg = A::t('app', 'Sending Email Blocked Alert');
					$this->_errorType = 'warning';
				}else{
					if(CMailer::send($this->_settings->general_email, A::t('app', 'Test Email Subject'), $body, array('from'=>$this->_settings->general_email, 'from_name'=>$this->_settings->general_email_name))){
						$this->_msg = A::t('app', 'Test Email Success Message');
						$this->_errorType = 'success';
					}else{
						if(APPHP_MODE == 'debug') $this->_msg = CMailer::getError();
						else $this->_msg = A::t('app', 'Test Email Error Message');
						$this->_errorType = 'error';
					}
				}
			// submit the form
			}else{			
				$result = CWidget::create('CFormValidation', array('fields'=>$fields));
				if($result['error']){
					$this->_msg = $result['errorMessage'];
					$this->_view->errorField = $result['errorField'];
					$this->_errorType = 'validation';
				}else{
				    unset($this->_settings->smtp_password);
	                if($this->_view->smtpPassword != ''){
	                    $this->_settings->smtp_password = CHash::encrypt($this->_view->smtpPassword, CConfig::get('password.hashKey'));
	                }
					if(APPHP_MODE == 'demo'){
						$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
						$this->_errorType = 'warning';
					}else{
						if($this->_settings->save()){
							$this->_msg = A::t('app', 'Settings Update Success Message');
							$this->_errorType = 'success';
						}else{
							$this->_msg = A::t('app', 'Settings Update Error Message');
							$this->_view->errorField = '';
							$this->_errorType = 'error';
						}
					}
				}

				// always clear password field after form submission
				$this->_view->smtpPassword = '';
			}
			if(!empty($this->_msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
			}
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
			// settings form submit
			
			// block access if admin has no active privilege to edit site settings
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
				$this->_msg = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_errorType = 'validation';
			}else{
				$this->_settings->template = $this->_view->selectedTemplate;
				if(APPHP_MODE == 'demo'){
					$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_errorType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_msg = A::t('app', 'Settings Update Success Message');
						$this->_errorType = 'success';
					}else{
						$this->_msg = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_errorType = 'error';
					}
				}
			}
			if(!empty($this->_msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
			}
		
		}else if($this->_cRequest->getPost('act') == 'changeTemp'){
			// template selection changed
			$this->_view->selectedTemplate = $this->_cRequest->getPost('template');
		}else{
			$this->_view->selectedTemplate = $this->_settings->template;				
		}
        
        // get all templates
		$this->_view->allTemplates = CFile::findSubDirectories('templates/');
        // prepare available templates list 
		$templatesList = array();
        if(is_array($this->_view->allTemplates)){
        	foreach($this->_view->allTemplates as $temp){
        		if(!in_array($temp, array('backend', 'setup'))){
        			$templatesList[$temp] = ucfirst($temp);  			
        		}
        	}            
        }
        $this->_view->templatesList = $templatesList;
		
    	// load XML file for template info
		if(array_key_exists($this->_view->selectedTemplate, $this->_view->templatesList) && @file_exists('templates/'.$this->_view->selectedTemplate.'/info.xml')) {
			$xml = simplexml_load_file('templates/'.$this->_view->selectedTemplate.'/info.xml');		 
        }
		$this->_view->name = isset($xml->name) ? $xml->name : A::t('app', 'Unknown');
		$this->_view->icon = isset($xml->icon) ? 'templates/'.$this->_view->selectedTemplate.'/'.$xml->icon : 'templates/backend/images/no_image.png';
		$this->_view->textDirection = isset($xml->direction) ? $xml->direction : A::t('app', 'Unknown');
		$this->_view->description = isset($xml->description) ? $xml->description : A::t('app', 'Unknown');
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
			}else if(isset($match[3])){
				$phpinfo[end($array_keys)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			}else{
				$phpinfo[end($array_keys)][] = $match[2];
			}
		}
        
        $phpCoreIndex = ((version_compare(phpversion(), '5.3.0', '<'))) ? 'PHP Core' : 'Core';
        $mysqlVersion = CDatabase::init()->getVersion();

	    $this->_view->phpVersion 	= function_exists('phpversion') ? phpversion() : A::t('app', 'Unknown');
	    $this->_view->dbDriver	    = ucfirst(CConfig::get('db.driver'));    
        
		$this->_view->mysqlVersion  = !empty($mysqlVersion) ? $mysqlVersion : A::t('app', 'Unknown');		
		$this->_view->aspTags 		= isset($phpinfo[$phpCoreIndex]['asp_tags']) ? $phpinfo[$phpCoreIndex]['asp_tags'][0] : A::t('app', 'Unknown');
		$this->_view->safeMode 		= isset($phpinfo[$phpCoreIndex]['safe_mode']) ? $phpinfo[$phpCoreIndex]['safe_mode'][0] : A::t('app', 'Unknown');
		$this->_view->shortOpenTag  = isset($phpinfo[$phpCoreIndex]['short_open_tag']) ? $phpinfo[$phpCoreIndex]['short_open_tag'][0] : A::t('app', 'Unknown');
		$this->_view->vdSupport 	= isset($phpinfo['phpinfo']['Virtual Directory Support']) ? $phpinfo['phpinfo']['Virtual Directory Support'] : A::t('app', 'Unknown');
		$this->_view->modeRewrite 	= $this->_checkModRewrite() ? A::t('app', 'On') : A::t('app', 'Off');
		$this->_view->system 		= isset($phpinfo['phpinfo']['System']) ? $phpinfo['phpinfo']['System'] : A::t('app', 'Unknown');
		$this->_view->buildDate 	= isset($phpinfo['phpinfo']['Build Date']) ? $phpinfo['phpinfo']['Build Date'] : A::t('app', 'Unknown');
		$this->_view->serverApi 	= isset($phpinfo['phpinfo']['Server API']) ? $phpinfo['phpinfo']['Server API'] : A::t('app', 'Unknown');
		
		$this->_view->smtp 	 		= (ini_get('SMTP') != '') ? ini_get('SMTP') : A::t('app', 'Unknown');
		$this->_view->smtpPort	 	= (ini_get('smtp_port') != '') ? ini_get('smtp_port') : A::t('app', 'Unknown');
		$this->_view->sendmailFrom 	= (ini_get('sendmail_from') != '') ? ini_get('sendmail_from') : A::t('app', 'Unknown');
		$this->_view->sendmailPath 	= (ini_get('sendmail_path') != '') ? ini_get('sendmail_path') : A::t('app', 'Unknown');

		$this->_view->sessionSupport = isset($phpinfo['session']['Session Support']) ? $phpinfo['session']['Session Support'] :  A::t('app', 'Unknown');
		$this->_view->magicQuotesGpc = ini_get('magic_quotes_gpc') ? A::t('app', 'On') : A::t('app', 'Off');
		$this->_view->magicQuotesRuntime = ini_get('magic_quotes_runtime') ? A::t('app', 'On') : A::t('app', 'Off');
		$this->_view->magicQuotesSybase = ini_get('magic_quotes_sybase') ? A::t('app', 'On') : A::t('app', 'Off');
    	
		$this->_view->tabs = $this->_prepareTab('server');		
		$this->_view->render('settings/server');
	}

	/**
	 * Site info action handler
	 */
	public function siteAction()
    {
   		$this->_view->domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : A::t('app', 'Unknown');
		
		if($this->_cRequest->getPost('act') == 'send'){
			// update button clicked - update the ranks
    		$this->_settings->google_rank = (int)$this->_checkGoogleRank($this->_view->domain);
    		$this->_settings->alexa_rank = number_format((float)$this->_checkAlexaRank($this->_view->domain));
			
			if(APPHP_MODE == 'demo'){
				$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
				$this->_errorType = 'warning';
			}else{
				if($this->_settings->save()){
					$this->_msg = A::t('app', 'Site Info Success Message');
					$this->_errorType = 'success';
				}else{
					$this->_msg = A::t('app', 'Site Info Error Message');
					$this->_errorType = 'error';
				}
			}
			if(!empty($this->_msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
			}
		}
		
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
			// settings form submit
    		
    		// block access if admin has no active privilege to edit site settings
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
				$this->_msg = $result['errorMessage'];
				$this->_view->errorField = $result['errorField'];
				$this->_errorType = 'validation';
			}else{
				if(APPHP_MODE == 'demo'){
					$this->_msg = A::t('core', 'This operation is blocked in Demo Mode!');
					$this->_errorType = 'warning';
				}else{
					if($this->_settings->save()){
						$this->_msg = A::t('app', 'Settings Update Success Message');
						$this->_errorType = 'success';
					}else{
						$this->_msg = A::t('app', 'Settings Update Error Message');
						$this->_view->errorField = '';
						$this->_errorType = 'error';
					}
				}
			}
			if(!empty($this->_msg)){
				$this->_view->actionMessage = CWidget::create('CMessage', array($this->_errorType, $this->_msg, array('button'=>true)));
			}
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
				A::t('app', 'Server Info')  	 =>array('href'=>'settings/server', 'id'=>'tab6', 'content'=>'', 'active'=>($activeTab == 'server' ? true : false)),
				A::t('app', 'Site Info')  		 =>array('href'=>'settings/site', 'id'=>'tab7', 'content'=>'', 'active'=>($activeTab == 'site' ? true : false)),
				A::t('app', 'Cron Jobs')  		 =>array('href'=>'settings/cron', 'id'=>'tab8', 'content'=>'', 'active'=>($activeTab == 'cron' ? true : false)),
			),
			'return'=>true,
		));
	}

	/**
	 * Returns Alexa Page Rank
	 * 		@param $url
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
		$str = array_shift(explode('"/>', $str_1));
		$str = explode('TEXT="', $str);
		$str_2 = (isset($str[1])) ? $str[1] : '';
		return $str_2;
	}
	
	/**
	 * Returns Google Page Rank
	 * 		@param $url
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
			// if the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31),
			//  the result of converting to integer is undefined
			//  refer to http://www.php.net/manual/en/language.types.integer.php
			if ($check >= $int_32_u) {
				$check = ($check - $int_32_u * (int) ($check / $int_32_u));
				// if the check less than -2^31
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
		if(version_compare(PHP_VERSION, '5.3.0', '>=')){
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
		if(version_compare(PHP_VERSION, '5.3.0', '>=')){
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
		// check date and time formats
		if(!$this->_testDateFormat($this->_settings->date_format)){
			$this->_view->dateFormatSample = 'no';
			$this->_msg = A::t('app', 'Date Format Error Message');
			$this->_view->errorField = 'dateFormat';
			$this->_errorType = 'validation';
		}
		if(!$this->_testTimeFormat($this->_settings->time_format)){
			$this->_view->timeFormatSample = 'no';
			if($this->_msg == ''){
				$this->_msg = A::t('app', 'Time Format Error Message');
				$this->_view->errorField = 'timeFormat';
				$this->_errorType = 'validation';
			}
		}
		if(!$this->_testDateFormat($this->_settings->datetime_format) && !$this->_testTimeFormat($this->_settings->datetime_format)){
			$this->_view->dateTimeFormatSample = 'no';
			if($this->_msg == ''){
				$this->_msg = A::t('app', 'Date Time Format Error Message');
				$this->_view->errorField = 'dateTimeFormat';
				$this->_errorType = 'validation';
			}
		}
		if($this->_msg == ''){
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
            // works only if PHP is not running as CGI module
            $mod_rewrite = in_array('mod_rewrite', apache_get_modules());
        }else{
            return true;            
        }   
    }

}