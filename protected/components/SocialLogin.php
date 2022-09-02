<?php
/**
 * SocialLogin - component for working with website
 *
 * PUBLIC: (static)         PROTECTED:                  PRIVATE: (static)
 * ---------------          ---------------             ---------------
 * init
 * config
 * login
 * drawButtons
 */

class SocialLogin extends CComponent
{
    /**
     *  Returns the instance of object
     *  @return current class
     */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Method for setting configurations
     * @param array $params
     * @param string $method
     * @return bool
     */
    public static function config($params = array())
    {
        $returnUrl = isset($params['returnUrl']) && !empty($params['returnUrl']) ? $params['returnUrl'] : '';
        $model     = isset($params['model']) && !empty($params['model']) ? $params['model'] : 'Accounts';
        $method    = isset($params['method']) && !empty($params['method']) ? $params['method'] : 'registrationSocial';
        if(class_exists($model)){
            if(is_callable($model, $method)){
                $socialLogin = array(
                    'model' => $model,
                    'method' => $method,
                    'returnUrl' => $returnUrl
                );

                A::app()->getSession()->set('socialLogin', $socialLogin);

                return true;
            }
        }

        CDebug::addMessage('warning', 'The method '.CHtml::encode($model).'::'.CHtml::encode($method).'can not be called');

        return false;
    }

    /**
     * Method for login through social networks
     * @param string $strategy
     * @param bool $exit
     * @return bool
    */
    public static function login($strategy = 'facebook', $exit = true)
    {
        $strategies = COauth::getStrategies();
        $nameStrategies = array_map('strtolower', array_keys($strategies));
        if(in_array($strategy, $nameStrategies)){
            $baseUrl = A::app()->getRequest()->getBaseUrl();
            $url = A::app()->getRequest()->getBaseUrl().'socialNetworks/oauth/type/'.$strategy;
            header('Location: '.$url);
            if($exit){
                exit();
            }
            return true;
        }

        return false;
    }

    /**
     * Draws payment form
     * @param array $links
     * @param bool $showOr
     * @return HTML
     */
    public static function drawButtons($links = array(), $showOr = true)
    {
        $output  = '';

        A::app()->getClientScript()->registerCssFile('css/font-awesome.css');

        $activeSocial = SocialNetworksLogin::model()->findAll('is_active = 1');
        if(!empty($activeSocial) && is_array($activeSocial)){
            $output .= CHtml::openTag('div', array('class'=>'social-signup'));
            if($showOr == true){
                $output .= CHtml::tag('span', array('class'=>'or-break'), A::t('app', 'or'));
            }
            foreach($activeSocial as $social){
                $link = isset($links[$social['type']]) ? $links[$social['type']] : 'socialNetworks/oauth/type/'.$social['type'];
                $output .= CHtml::tag('a', array('href'=>$link, 'class'=>'btn btn-block btn-social btn-'.$social['type'], 'type'=>'button', 'title'=>A::te('app', 'Signup with').' '.$social['name']), '<i class="fa fa-'.CHtml::encode($social['type']).'" aria-hidden="true"></i><span class="text"> '.A::t('app', 'Signup with').' '.$social['name'].'</span>');
            }
            $output .= CHtml::closeTag('div');
        }

        return $output;
    }
}
