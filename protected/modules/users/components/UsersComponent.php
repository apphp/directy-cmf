<?php
/**
 * Users component for user management
 *
 * PUBLIC (static):         PRIVATE:
 * -----------              ------------------
 * init
 * drawAlerts
 * prepareTab
 * drawLoginBlock
 *
 */

namespace Modules\Users\Components;

// Modules
use \Modules\Users\Models\UserGroups,
	\Modules\Users\Models\Users;

// Global
use \A,
	\CAuth,
	\CWidget,
	\CComponent,
	\CConfig,
	\CHtml,
	\CLoader,
	\CTime;

// Directy
use \Website,
	\Bootstrap,
	\LocalTime,
	\ModulesSettings,
	\SocialLogin;


class UsersComponent extends CComponent
{
	
	const NL = "\n";

	/**
     *	Returns the instance of object
     *	@return current class
     */
	public static function init()
	{
		return parent::init(__CLASS__);
	}

    /**
     * Draws alerts
     * @param string $activeTab
     */
    public static function drawAlerts()
    {
        $output = '';
        if(ModulesSettings::model()->param('users', 'approval_type') == 'by_admin'){
            if($count = Users::model()->count("is_active = 0 AND registration_code != ''")){
                $output = A::t('users', 'There are {count} user/s awaiting your approval.', array('{count}'=>$count));                
            }
        }
        
        return $output;
    }
        
    /**
     * Prepares users module tabs
     * @param string $activeTab
     */
    public static function prepareTab($activeTab = 'users')
    {
        return CWidget::create('CTabs', array(
            'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
            'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
            'contentWrapper'=>array(),
            'contentMessage'=>'',
            'tabs'=>array(
                A::t('users', 'Settings') => array('href'=>Website::getBackendPath().'modules/settings/code/users', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
                A::t('users', 'Users')  => array('href'=>'users/index', 'id'=>'tabUsers', 'content'=>'', 'active'=> ($activeTab == 'users')),
                A::t('users', 'User Groups')  => array('href'=>'userGroups/index', 'id'=>'tabUserGroups', 'content'=>'', 'active'=>($activeTab == 'usergroup')),                
            ),
            'events'=>array(
                
            ),
            'return'=>true,
        ));
    }    
    
    /**
     * Draws login form side block
     * @param string $title
     * @param string $activeMenu
     */
    public static function drawLoginBlock($title = '', $activeMenu = '')
    {        
        $output = '';
        
        // Check if login action is allowed
        if(!ModulesSettings::model()->param('users', 'allow_login')){
            return '';
        }
        
		// Don't draw login block if were on login page
		if(strtolower(A::app()->view->getController()) == 'users' && strtolower(A::app()->view->getAction()) == 'login'){
            return '';			
		}

        // Draw block content for logged in users
		if(CAuth::isLoggedInAs('user')){
            $output .= CHtml::openTag('div', array('class'=>'side-panel-block'));
            $output .= CHtml::tag('h3', array('class'=>'title'), A::t('users', 'My Account')).self::NL;            
 
            $output .= CWidget::create('CMenu', array(
                'items'=>array(
                    array('label'=>A::t('users', 'Dashboard'), 'url'=>'users/dashboard', 'target'=>'', 'id'=>''),           
                    array('label'=>A::t('users', 'My Account'), 'url'=>'users/myAccount', 'target'=>'', 'id'=>''),           
                    array('label'=>A::t('users', 'Logout'), 'url'=>'users/logout', 'target'=>'', 'id'=>''),           
                ),
                'type'=>'vertical',					
                'class'=>'side-panel-links',
                'subMenuClass'=>'',
                'dropdownItemClass'=>'',
                'separator'=>'',
                'id'=>'',
                'selected'=>$activeMenu,
                'return'=>true
            ));                                           
            $output .= CHtml::closeTag('div').self::NL; /* side-panel-block */
            
        }else{            
            $allowRememberMe = ModulesSettings::model()->param('users', 'allow_remember_me');
            $allowRegistration = ModulesSettings::model()->param('users', 'allow_registration');
            $allowRestorePassword = ModulesSettings::model()->param('users', 'allow_restore_password');
            
            $output .= CHtml::openTag('div', array('class'=>'side-panel-block'));
            $output .= CHtml::tag('h3', array('class'=>'title'), $title).self::NL;
            $output .= CHtml::openTag('div', array('class'=>'block-body'));
            $output .= CHtml::openForm('users/login', 'post', array('id'=>'login-side-form'));
            $output .= CHtml::hiddenField('act', 'send');
            
            // Username row
            $output .= CHtml::openTag('div', array('class'=>'block-row'));
            $output .= CHtml::tag('label', array(), A::t('users', 'Username').':');
            $output .= CHtml::textField('login_username', '', array('data-required'=>'true', 'maxlength'=>'25', 'autocomplete'=>'off'));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameSideErrorEmpty'), A::t('users', 'Username field cannot be empty!'));
            $output .= CHtml::closeTag('div').self::NL;
            
            // Password row
            $output .= CHtml::openTag('div', array('class'=>'block-row'));
            $output .= CHtml::tag('label', array(), A::t('users', 'Password').':');
            $output .= CHtml::passwordField('login_password', '', array('data-required'=>'true', 'maxlength'=>'20', 'autocomplete'=>'off'));
            $output .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordSideErrorEmpty'), A::t('users', 'Password field cannot be empty!'));
            $output .= CHtml::closeTag('div').self::NL;
    
            $output .= CHtml::openTag('div', array('class'=>'block-row'));
            $output .= CHtml::tag('button', array('type'=>'button', 'class'=>'btn v-btn v-btn-default v-small-button', 'onclick'=>'javascript:users_SideLoginForm(this)'), A::t('users', 'Login')).self::NL;
            // Remember me
            if($allowRememberMe){
                $output .= CHtml::checkBox('remember', false, array('id'=>'login_remember'));
                $output .= CHtml::label(A::t('users', 'Remember Me'), 'login_remember');            
            }
            $output .= CHtml::closeTag('div').self::NL;
            
            // Create account link        
            $output .= CHtml::openTag('div', array('class'=>'block-row'));
            if($allowRegistration) $output .= CHtml::link(A::t('users', 'Create account'), 'users/registration');            
            $output .= CHtml::closeTag('div').self::NL;
    
            // Restore password link        
            $output .= CHtml::openTag('div', array('class'=>'block-row'));
            if($allowRestorePassword) $output .= CHtml::link(A::t('users', 'Forgot your password?'), 'users/restorePassword');             
            $output .= CHtml::closeTag('div').self::NL;
            
            $output .= CHtml::closeForm().self::NL;
            $output .= CHtml::closeTag('div').self::NL; /* block-body */
            $output .= CHtml::closeTag('div').self::NL; /* side-panel-block */
    
            // Register module javascript and css
            A::app()->getClientScript()->registerScriptFile('assets/modules/users/js/users.js');
            A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
            if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');
        }

        return $output;
    }
    
}