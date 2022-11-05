<?php 
    Website::setMetaTags(array('title'=>A::t('users', 'Edit Account')));

    $this->_activeMenu = 'users/myAccount';

    A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.css');
    if(A::app()->getLanguage('direction') == 'rtl') A::app()->getClientScript()->registerCssFile('assets/modules/users/css/users.rtl.css');

    $breadCrumbs = array(
        array('label'=>A::t('users', 'Dashboard'), 'url'=>'users/dashboard'),
        array('label'=>A::t('users', 'My Account')),
        array('label'=>A::t('users', 'Edit'), 'url'=>'users/editAccount'),
    );   
?>

<h1 class="title"><?= A::t('users', 'My Account'); ?></h1>
<div class="block-body">    
<?php
    CWidget::create('CBreadCrumbs', array(
        'links' => $breadCrumbs,
        'separator' => '&nbsp;/&nbsp;',
        'return'=>false
    ));
?>

<div id="my-account">
<?php
    $personalInformation = '';
    if($fieldFirstName !== 'no' || $fieldLastName !== 'no') $personalInformation .= '<p><label>'.A::t('users', 'Name').':</label> <label>'.$user->first_name.' '.$user->last_name.'</p>';
    if($fieldBirthDate !== 'no') $personalInformation .= '<p><label>'.A::t('users', 'Birth Date').':</label> '.$birthDate.'</p>';
    if($fieldWebsite !== 'no') $personalInformation .= '<p><label>'.A::t('users', 'Website').':</label> '.$user->website.'</p>';
    if($fieldCompany !== 'no') $personalInformation .= '<p><label>'.A::t('users', 'Company').':</label> '.$user->company.'</p>';
    if($personalInformation) echo '<fieldset><legend>'.A::t('users', 'Personal Information').'</legend>'.$personalInformation.'</fieldset>'; 

    $contactInformation = '';
    if($fieldPhone !== 'no') $contactInformation .= '<p><label>'.A::t('users', 'Phone').':</label> '.$user->phone.'</p>';
    if($fieldFax !== 'no') $contactInformation .= '<p><label>'.A::t('users', 'Fax').':</label> '.$user->fax.'</p>';
    if($contactInformation) echo '<fieldset><legend>'.A::t('users', 'Contact Information').'</legend>'.$contactInformation.'</fieldset>'; 

    $addressInformation = '';
    if($fieldAddress !== 'no') $addressInformation .= '<p><label>'.A::t('users', 'Address').':</label> '.$user->address.'</p>';
    if($fieldAddress2 !== 'no') $addressInformation .= '<p><label>'.A::t('users', 'Address (line 2)').':</label> '.$user->address_2.'</p>';
    if($fieldCity !== 'no') $addressInformation .= '<p><label>'.A::t('users', 'City').':</label> '.$user->city.'</p>';
    if($fieldZipCode !== 'no') $addressInformation .= '<p><label>'.A::t('users', 'Zip Code').':</label> '.$user->zip_code.'</p>';
    if($fieldCountry !== 'no') $addressInformation .= '<p><label>'.A::t('users', 'Country').':</label> '.$countryName.'</p>';
    if($fieldState !== 'no') $addressInformation .= '<p><label>'.A::t('users', 'State/Province').':</label> '.$stateName.'</p>';
    if($addressInformation) echo '<fieldset><legend>'.A::t('users', 'Address Information').'</legend>'.$addressInformation.'</fieldset>';
    
    $accountIformation = '';
    if($fieldEmail !== 'no') $accountIformation .= '<p><label>'.A::t('users', 'Email').':</label> '.$user->email.'</p>';
    $accountIformation .= '<p><label>'.A::t('users', 'Username').':</label> '.$user->username.'</p>';
    $accountIformation .= '<p><label>'.A::t('users', 'Password').':</label> *****</p>';
    if(count($groups)) $accountIformation .= '<p><label>'.A::t('users', 'Group').':</label> '.$user->group_name.'</p>';        
    $accountIformation .= '<p><label>'.A::t('users', 'Preferred Language').':</label> '.$langName.'</p>';
    if($accountIformation) echo '<fieldset><legend>'.A::t('users', 'Account Information').'</legend>'.$accountIformation.'</fieldset>';
    
    
    $otherIformation = '';
    $otherIformation .= '<p><label>'.A::t('users', 'Created at').':</label> '.$createdAt.'</p>';
    $otherIformation .= '<p><label>'.A::t('users', 'Last visit at').':</label> '.$lastVisitedAt.'</p>';
    $otherIformation .= '<p><label>'.A::t('users', 'Notifications').':</label> '.$notifications.'</p>';
    
    $otherIformation .= '<p><label>'.A::t('users', 'Remove Account').':</label> [ <a href="users/removeAccount">'.A::t('users', 'Remove').'</a> ]</p>';
    if($otherIformation) echo '<fieldset><legend>'.A::t('users', 'Other').'</legend>'.$otherIformation.'</fieldset>';
    
?>
[ <a href="users/editAccount"><?= A::t('users', 'Edit Account'); ?></a> ]

</div>
</div>


