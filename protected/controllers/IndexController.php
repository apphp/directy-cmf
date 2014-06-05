<?php
/**
 * IndexController
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct
 * indexAction
 *
 */

class IndexController extends CController
{	
    /**
	 * Class default constructor
     */
	public function __construct()
	{
        parent::__construct();

    	// set template
    	$settings = Settings::model()->findByPk(1);
    	A::app()->view->setTemplate($settings->template);    	
    	$this->view->isOffline = $settings->is_offline;
    	$this->view->offlineMessage = $settings->offline_message;

		if($this->view->isOffline){
			$siteInfo = SiteInfo::model()->find('language_code = :lang', array(':lang'=>A::app()->getLanguage()));
			$this->view->siteTitle = isset($siteInfo[0]['header']) ? $siteInfo[0]['header'] : '';
			$this->view->slogan = isset($siteInfo[0]['slogan']) ? $siteInfo[0]['slogan'] : '';
			$this->view->footer = isset($siteInfo[0]['footer']) ? $siteInfo[0]['footer'] : '';
		}
	}
	
	/**
	 * Controller default action handler
	 */
	public function indexAction()
	{
		// check if site is offline
    	if($this->view->isOffline){
			echo '<!DOCTYPE HTML>
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>'.$this->view->siteTitle.'</title>
				<style type="text/css">        
					#wrapper { background-color:#f1f2f3; margin:100px auto; text-align:center; width:500px; border:1px solid #ccc; border-radius:5px; padding-top:0px; -moz-box-shadow: 4px 5px 5px 1px #777; -webkit-box-shadow: 4px 5px 5px 1px #777; box-shadow: 4px 5px 5px 1px #777; background: -webkit-gradient(linear, left top, left bottom, from(#f1f2f3), to(#ffffff)); background: -moz-linear-gradient(top,  #f1f2f3,  #ffffff); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#f1f2f3", endColorstr="#ffffff"); }
					#header { height:100px; margin-top:0px; }
					#content {height:200px; padding:30px 0;color:#3f4c6b; }
					#footer { margin:10px; color:#36393d; }
					h1 { color:#3f4c6b; }
					h3 { color:#555; }
				</style>
			</head>
			<body>
				<div id="wrapper">
					<div id="header">
						<h1>'.$this->view->siteTitle.'</h1>
						<h3>'.$this->view->slogan.'</h3>
					</div>    
					<div id="content">'.$this->view->offlineMessage.'</div>
					<div id="footer">'.$this->view->footer.'</div>
				</div>
			</body>
			</html>';
		}else{
			// check if CMS module installed and redirect to view pages
			if(Modules::model()->exists('code = :code AND is_installed = 1', array(':code'=>'cms'))){
				$this->redirect('pages/view');
			}else{
				$this->view->title = '';
				$this->view->text = 'This is a home page! Your text will be here...';
				$this->view->render('index/index');	
			}
		}
	}	
}