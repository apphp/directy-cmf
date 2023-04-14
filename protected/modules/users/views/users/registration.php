<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'User Registration')));
    
    A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
    if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');
    A::app()->getClientScript()->registerScriptFile('assets/modules/users/js/users.js'); 

	// Define active menu	
	$this->_activeMenu = 'users/registration';

	// Define breadcrumbs title
	$this->_breadcrumbsTitle = A::t('users', 'User Registration');

	// Define breadcrumbs for this page
	$this->_breadCrumbs = array(
		array('label'=>A::t('users', 'Home'), 'url'=>Website::getDefaultPage()),
		array('label'=>A::t('users', 'User Registration')),
	);
?>
<h1 class="title"><?= A::t('users', 'User Registration'); ?></h1>
<?php      
    echo CHtml::tag('p', array('class'=>'alert alert-success', 'style'=>'display:none', 'id'=>'messageSuccess'), $messageSuccess);
    
    echo CHtml::openTag('div', array('class'=>'registration-form-content'));
    echo CHtml::openForm('users/registration', 'post', array('name'=>'registration-form', 'id'=>'registration-form')) ;
    echo CHtml::hiddenField('act', 'send');

    //echo CHtml::tag('p',array(),A::t('users', 'Please fill out the form below to perform registration.'));
    $errorMsg = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('users', 'An error occurred while registration process! Please try again later.');
    echo CHtml::tag('p', array('style'=>'display:none', 'id'=>'messageError'), $errorMsg);


    $personalInfo = '';
    if($fieldFirstName == 'allow-optional' || $fieldFirstName == 'allow-required'){
        $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
        $personalInfo .= CHtml::tag('label', array(), (($fieldFirstName == 'allow-required') ? ' &#42; ' : '').A::t('users', 'First Name'));
        $personalInfo .= CHtml::textField('first_name', '', array('data-required'=>($fieldFirstName == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'32', 'autocomplete'=>'off'));
        if($fieldFirstName == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'firstNameErrorEmpty'), A::t('users', 'The field first name cannot be empty!'));
        $personalInfo .= CHtml::closeTag('div'); 
    }
    if($fieldLastName == 'allow-optional' || $fieldLastName == 'allow-required'){
        $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
        $personalInfo .= CHtml::tag('label', array(), (($fieldLastName == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Last Name'));
        $personalInfo .= CHtml::textField('last_name', '', array('data-required'=>($fieldLastName == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'32', 'autocomplete'=>'off'));
        if($fieldLastName == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'lastNameErrorEmpty'), A::t('users', 'The field last name cannot be empty!'));
        $personalInfo .= CHtml::closeTag('div'); 
    }
    if($fieldBirthDate == 'allow-optional' || $fieldBirthDate == 'allow-required'){
        $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
        $personalInfo .= CHtml::tag('label', array(), (($fieldBirthDate == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Birth Date'));
        $personalInfo .= CHtml::textField('birth_date', '', array('data-required'=>($fieldBirthDate == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'10', 'autocomplete'=>'off'));
        if($fieldBirthDate == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'birthDateErrorEmpty'), A::t('users', 'The field birth date cannot be empty!'));
        $personalInfo .= CHtml::closeTag('div');
        
        $format = 'yy-mm-dd';
        A::app()->getClientScript()->registerCssFile('assets/vendors/jquery/jquery-ui.min.css');
        A::app()->getClientScript()->registerScriptFile('assets/vendors/jquery/jquery-ui.min.js', 1);
        /* formats: dd/mm/yy | d M, y | mm/dd/yy  | yy-mm-dd  | */
        A::app()->getClientScript()->registerScript(
            'datepicker',
            '$("#birth_date").datepicker({
                showOn: "button",
                buttonImage: "assets/vendors/jquery/images/calendar.png",
                buttonImageOnly: true,
                showWeek: false,
                firstDay: 1,
                maxDate: -1,
                dateFormat: "'.$format.'",
                changeMonth: true,
                changeYear: true,
                appendText : "'.A::t('app', 'Format').': yyyy-mm-dd"
            });'
        );
    }
    if($fieldWebsite == 'allow-optional' || $fieldWebsite == 'allow-required'){
        $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
        $personalInfo .= CHtml::tag('label', array(), (($fieldWebsite == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Website'));
        $personalInfo .= CHtml::textField('website', '', array('data-required'=>($fieldWebsite == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'255', 'autocomplete'=>'off'));
        if($fieldWebsite == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'websiteErrorEmpty'), A::t('users', 'The field website cannot be empty!'));
        $personalInfo .= CHtml::closeTag('div');
    }
    if($fieldCompany == 'allow-optional' || $fieldCompany == 'allow-required'){
        $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
        $personalInfo .= CHtml::tag('label', array(), (($fieldCompany == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Company'));
        $personalInfo .= CHtml::textField('company', '', array('data-required'=>($fieldCompany == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
        if($fieldCompany == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'companyErrorEmpty'), A::t('users', 'The field company cannot be empty!'));
        $personalInfo .= CHtml::closeTag('div');
    }
    if(!empty($personalInfo)){
        echo CHtml::tag('p', array('class'=>'form-subtitle'), A::t('users', 'Personal Information'));    
        echo $personalInfo;
    }
    
    
    $contactInfo = '';
    if($fieldPhone == 'allow-optional' || $fieldPhone == 'allow-required'){
        $contactInfo .= CHtml::openTag('div', array('class'=>'row'));
        $contactInfo .= CHtml::tag('label', array(), (($fieldPhone == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Phone'));
        $contactInfo .= CHtml::textField('phone', '', array('data-required'=>($fieldPhone == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
        if($fieldPhone == 'allow-required') $contactInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'phoneErrorEmpty'), A::t('users', 'The field phone cannot be empty!'));
        $contactInfo .= CHtml::closeTag('div');
    }
    if($fieldFax == 'allow-optional' || $fieldFax == 'allow-required'){
        $contactInfo .= CHtml::openTag('div', array('class'=>'row'));
        $contactInfo .= CHtml::tag('label', array(), (($fieldFax == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Fax'));
        $contactInfo .= CHtml::textField('fax', '', array('data-required'=>($fieldFax == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
        if($fieldFax == 'allow-required') $contactInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'faxErrorEmpty'), A::t('users', 'The field fax cannot be empty!'));
        $contactInfo .= CHtml::closeTag('div');
    }
    if(!empty($contactInfo)){
        echo CHtml::tag('p', array('class'=>'form-subtitle'), A::t('users', 'Contact Information'));
        echo $contactInfo;
    }
   
    
    $addressInfo = '';
    if($fieldAddress == 'allow-optional' || $fieldAddress == 'allow-required'){
        $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
        $addressInfo .= CHtml::tag('label', array(), (($fieldAddress == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Address'));
        $addressInfo .= CHtml::textField('address', '', array('data-required'=>($fieldAddress == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldAddress == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'addressErrorEmpty'), A::t('users', 'The field address cannot be empty!'));
        $addressInfo .= CHtml::closeTag('div');
    }
    if($fieldAddress2 == 'allow-optional' || $fieldAddress2 == 'allow-required'){
        $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
        $addressInfo .= CHtml::tag('label', array(), (($fieldAddress2 == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Address (line 2)'));
        $addressInfo .= CHtml::textField('address_2', '', array('data-required'=>($fieldAddress2 == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldAddress2 == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'address2ErrorEmpty'), A::t('users', 'The field address (line 2) cannot be empty!'));
        $addressInfo .= CHtml::closeTag('div');
    }
    if($fieldCity == 'allow-optional' || $fieldCity == 'allow-required'){
        $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
        $addressInfo .= CHtml::tag('label', array(), (($fieldCity == 'allow-required') ? ' &#42; ' : '').A::t('users', 'City'));
        $addressInfo .= CHtml::textField('city', '', array('data-required'=>($fieldCity == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldCity == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'cityErrorEmpty'), A::t('users', 'The field city cannot be empty!'));
        $addressInfo .= CHtml::closeTag('div');
    }
    if($fieldZipCode == 'allow-optional' || $fieldZipCode == 'allow-required'){
        $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
        $addressInfo .= CHtml::tag('label', array(), (($fieldZipCode == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Zip Code'));
        $addressInfo .= CHtml::textField('zip_code', '', array('data-required'=>($fieldZipCode == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldZipCode == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'zipcodeErrorEmpty'), A::t('users', 'The field zip code cannot be empty!'));
        $addressInfo .= CHtml::closeTag('div');
    }
    if($fieldCountry == 'allow-optional' || $fieldCountry == 'allow-required'){
        $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
        $addressInfo .= CHtml::tag('label', array(), (($fieldCountry == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Country'));
        $onchange = ($fieldState == 'allow-optional' || $fieldState == 'allow-required') ? "users_ChangeCountry('registration-form',this.value)" : ''; 
        $addressInfo .= CHtml::dropDownList('country_code', $defaultCountryCode, $countries, array('data-required'=>($fieldCountry == 'allow-required' ? 'true' : 'false'), 'onchange'=>$onchange));
        if($fieldCountry == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'countrycodeErrorEmpty'), A::t('users', 'The field country cannot be empty!'));
        $addressInfo .= CHtml::closeTag('div');
    }
    if($fieldState == 'allow-optional' || $fieldState == 'allow-required'){
        $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
        $addressInfo .= CHtml::tag('label', array(), (($fieldState == 'allow-required') ? ' &#42; ' : '').A::t('users', 'State/Province'));
        $addressInfo .= CHtml::textField('state', '', array('data-required'=>($fieldState == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
        if($fieldState == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'stateErrorEmpty'), A::t('users', 'The field state cannot be empty!'));
        $addressInfo .= CHtml::closeTag('div');
    }
    if($addressInfo){    
        echo CHtml::tag('p', array('class'=>'form-subtitle'), A::t('users', 'Address Information'));    
        echo $addressInfo;
    }
    

    $accountInfo = '';
    if($fieldEmail == 'allow-optional' || $fieldEmail == 'allow-required'){
        $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
        $accountInfo .= CHtml::tag('label', array(), (($fieldEmail == 'allow-required') ? ' &#42; ' : '').A::t('users', 'Email'));
        $accountInfo .= CHtml::textField('email', '', array('data-required'=>($fieldEmail == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
        if($fieldEmail == 'allow-required') $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorEmpty'), A::t('users', 'The field email cannot be empty!'));
        $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorValid'), A::t('users', 'You must provide a valid email address!'));
        $accountInfo .= CHtml::closeTag('div');
    }
    if($fieldUsername == 'allow'){
        $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
        $accountInfo .= CHtml::tag('label', array(), (($fieldUsername == 'allow') ? ' &#42; ' : '').A::t('users', 'Username'));
        $accountInfo .= CHtml::textField('username', '', array('data-required'=>'true', 'maxlength'=>'32', 'autocomplete'=>'off'));
        $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameErrorEmpty'), A::t('users', 'The field username cannot be empty!'));
        $accountInfo .= CHtml::closeTag('div');
    }
    if($fieldPassword == 'allow'){
        $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
        $accountInfo .= CHtml::tag('label', array(), (($fieldPassword == 'allow') ? ' &#42; ' : '').A::t('users', 'Password'));
        $accountInfo .= CHtml::passwordField('password', '', array('data-required'=>'true', 'maxlength'=>'25', 'autocomplete'=>'off'));
        $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordErrorEmpty'), A::t('users', 'The field password cannot be empty!'));
        $accountInfo .= CHtml::closeTag('div');
    }
    if($fieldConfirmPassword == 'allow'){
        $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
        $accountInfo .= CHtml::tag('label', array(), (($fieldConfirmPassword == 'allow') ? ' &#42; ' : '').A::t('users', 'Confirm Password'));
        $accountInfo .= CHtml::passwordField('confirm_password', '', array('data-required'=>'true', 'maxlength'=>'25', 'autocomplete'=>'off'));
        $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'confpasswordErrorEmpty'), A::t('users', 'The field confirm password cannot be empty!'));
        $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'confpasswordErrorEqual'), A::t('users', 'The password field must match the password confirmation field!'));        
        $accountInfo .= CHtml::closeTag('div');
    }
    if($fieldNotifications == 'allow'){
        $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
        $accountInfo .= CHtml::tag('label', array('for'=>'notifications'), A::t('users', 'Send Notifications'));
        $accountInfo .= CHtml::checkBox('notifications', false, array());
        $accountInfo .= CHtml::closeTag('div');
    }
    if($accountInfo){
        echo CHtml::tag('p', array('class'=>'form-subtitle'), A::t('users', 'Account Information'));
        echo $accountInfo;
    }
    

    if($fieldCaptcha == 'allow'){
        echo CHtml::tag('p', array('class'=>'form-subtitle'), A::t('users', 'Human Verification'));
        echo CHtml::openTag('div', array('class'=>'row'));
        echo CWidget::create('CCaptcha', array('math', true, array('return'=>true)));
        echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'captchaError'), A::t('users', 'The field captcha cannot be empty!'));
        echo CHtml::closeTag('div');
        echo '<br>';
    }

    echo CHtml::openTag('div', array('class'=>'row'));
	echo CHtml::tag('button', array('type'=>'button', 'class'=>'btn v-btn v-btn-default v-small-button', 'data-sending'=>A::t('users', 'Sending...'), 'data-send'=>A::t('webforms', 'Send'), 'onclick'=>'javascript:users_RegistrationForm(this)'), A::t('users', 'Register'));
    echo CHtml::closeTag('div');
    echo CHtml::closeForm();
    echo CHtml::closeTag('div');


    if($fieldCountry !== 'no' && $fieldState !== 'no'){
        A::app()->getClientScript()->registerScript(
            'usersChangeCountry',
            '$(document).ready(function(){
                users_ChangeCountry(
                    "registration-form","'.$defaultCountryCode.'"
                );
            });',
            1
        );
    }    

?>