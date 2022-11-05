/**
 * Validates form fields and submit the form
 * @param object el form element
 */
function users_RegistrationForm(el)
{	
    if(el == null) return false;
    // define this to prevent name overlapping
    var $ = jQuery;
    
    ///var users_registrationForm = $(el).closest('form');
    var token = $(el).closest('form').find('input[name=APPHP_CSRF_TOKEN]').val();
    var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;    
    
    var firstName = $('#first_name').val();
    var isFirstNameRequired = $('#first_name').data('required');    
    var lastName = $('#last_name').val();
    var isLastNameRequired = $('#last_name').data('required');        
    var birthDate = $('#birth_date').val();
    var isBirthDateRequired = $('#birth_date').data('required');    
    var website = $('#website').val();
    var isWebsiteRequired = $('#website').data('required');    
    var company = $('#company').val();
    var isCompanyRequired = $('#company').data('required');    
    var phone = $('#phone').val();
    var isPhoneRequired = $('#phone').data('required');        
    var fax = $('#fax').val();
    var isFaxRequired = $('#fax').data('required');        
    var address = $('#address').val();
    var isAddressRequired = $('#address').data('required');    
    var address2 = $('#address_2').val();
    var isAddress2Required = $('#address_2').data('required');    
    var city = $('#city').val();
    var isCityRequired = $('#city').data('required');        
    var zipCode = $('#zip_code').val();
    var isZipCodeRequired = $('#zip_code').data('required');    
    var countryCode = $('#country_code').val();
    var isCountryCodeRequired = $('#country_code').data('required');
    var state = $('#state').val();
    var isStateRequired = $('#state').data('required');    
    var email = $('#email').val();
    var isEmailRequired = $('#email').data('required');    
    var username = $('#username').val();
    var isUsernameRequired = $('#username').data('required');    
    var password = $('#password').val();
    var isPasswordRequired = $('#password').data('required');
    var confirmPassword = $('#confirm_password').val();
    var isConfirmPasswordRequired = $('#confirm_password').data('required');    
    var confirmPassword = $('#confirm_password').val();
    var notifications = $('#notifications').is(':checked') ? 1 : 0;
    var captcha = $('#captcha_validation').val();
    var isCaptchaRequired = $('#captcha_validation').data('required');
        
    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();                
    $('#messageInfo').show();                
    
    if(isFirstNameRequired && !firstName){
        $('#first_name').focus();
        $('#firstNameErrorEmpty').show();
    }else if(isLastNameRequired && !lastName){
        $('#last_name').focus();
        $('#lastNameErrorEmpty').show();
    }else if(isBirthDateRequired && !birthDate){
        $('#birth_date').focus();
        $('#birthDateErrorEmpty').show();
    }else if(isWebsiteRequired && !website){
        $('#website').focus();
        $('#websiteErrorEmpty').show();
    }else if(isCompanyRequired && !company){
        $('#company').focus();
        $('#companyErrorEmpty').show();
    }else if(isPhoneRequired && !phone){
        $('#phone').focus();
        $('#phoneErrorEmpty').show();
    }else if(isFaxRequired && !fax){
        $('#fax').focus();
        $('#faxErrorEmpty').show();
    }else if(isAddressRequired && !address){
        $('#address').focus();
        $('#addressErrorEmpty').show();
    }else if(isAddress2Required && !address2){
        $('#address_2').focus();
        $('#address2ErrorEmpty').show();
    }else if(isCityRequired && !city){
        $('#city').focus();
        $('#cityErrorEmpty').show();
    }else if(isZipCodeRequired && !zipCode){
        $('#zip_code').focus();
        $('#zipcodeErrorEmpty').show();
    }else if(isCountryCodeRequired && !countryCode){
        $('#countryCode').focus();
        $('#countrycodeErrorEmpty').show();
    }else if(isStateRequired && !state){
        $('#state').focus();
        $('#stateErrorEmpty').show();
    }else if(isEmailRequired && !email){
        $('#email').focus();
        $('#emailErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#email').focus();
        $('#emailErrorValid').show();
    }else if(isUsernameRequired && !username){
        $('#username').focus();
        $('#usernameErrorEmpty').show();
    }else if(isPasswordRequired && !password){
        $('#password').focus();
        $('#passwordErrorEmpty').show();
    }else if(isConfirmPasswordRequired && !confirmPassword){
        $('#confirm_password').focus();
        $('#confpasswordErrorEmpty').show();
    }else if(isConfirmPasswordRequired && confirmPassword != password){
        $('#confirm_password').focus();
        $('#confpasswordErrorEqual').show();
    }else if(isCaptchaRequired && !captcha){
        $('#captcha_validation').focus();
        $('#captchaError').show();
    }else{

        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled','disabled');
                
        $.ajax({
            url: 'users/registration',
            global: false,					  
            type: 'POST',
            data: ({
            	APPHP_CSRF_TOKEN: token,
                act: "send",
            	first_name: firstName,
            	last_name: lastName,
            	birth_date: birthDate,
            	website: website,
            	company: company,
            	phone: phone,
            	fax: fax,
            	address: address,
            	address_2: address2,
            	city: city,
            	zip_code: zipCode,
            	country_code: countryCode,
                state: state,
            	email: email,
            	username: username,
            	password: password,
                confirm_password: confirmPassword,
                notifications: notifications,
                captcha: captcha
            }),
            dataType: 'html',
            async: true,
            error: function(html){
                $('.error').hide();
                $('#messageInfo').hide();
                $('#messageError').show();                
            },
            success: function(html){
                try{
                    var obj = $.parseJSON(html);
                    if(obj.status == '1'){
                        $('.error').hide();
                        $('#first_name').val('');
                        $('#last_name').val('');
                        $('#birth_date').val('');
                        $('#website').val('');
                        $('#company').val('');
                        $('#phone').val('');
                        $('#fax').val('');
                        $('#address').val('');
                        $('#address_2').val('');
                        $('#city').val('');
                        $('#zip_code').val('');
                        //$('#country_code').val('');
                        //$('#state').val('');
                        $('#email').val('');
                        $('#username').val('');
                        $('#password').val('');
                        $('#confirm_password').val('');
                        $('#captcha_validation').val('');
                        
                        $('.registration-form-content').slideUp();
                        $('#messageSuccess').show();
                    }else{
                        users_RaiseError(el, obj.error, obj.error_field);
                    }                    
                }catch(err){
                    users_RaiseError(el, err.message);
                }
            }						  
        });
    }
    return false;   
}

/**
 * Validates form fields and submit the form
 * @param object el form element
 */
function users_RestorePasswordForm(el)
{	
    if(el == null) return false;
    // define this to prevent name overlapping
    var $ = jQuery;
    
    var frm = $(el).closest('form');
    var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;
    
    var email = $('#email').val();
    
    $('.alert').hide(); 
    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();                
    $('#messageInfo').show();                
    
    if(!email){
        $('#email').focus();
        $('#emailErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#email').focus();
        $('#emailErrorValid').show();
    }else{
        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled','disabled');

        frm.submit();
		return true;
	}   
    // prevent the default form submission occurring
    return false;    
}

/**
 * Validates form fields and submit the side login form
 * @param object el form element
 */
function users_SideLoginForm(el)
{
    if(el == null) return false;    
    // define this to prevent name overlapping
    var $ = jQuery;
    
    var username = $('#login-side-form input[name=login_username]').val();    
    var password = $('#login-side-form input[name=login_password]').val();       
    
    $('.error').hide();
    
    if(!username){
        $('#login-side-form #login_username').focus();
        $('#login-side-form #usernameSideErrorEmpty').show();        
    }else if(!password){
        $('#login-side-form #login_password').focus();
        $('#login-side-form #passwordSideErrorEmpty').show();       
    }else{
        $(el).closest('form').submit();
		return true;
	}   
    return false;    
}

/**
 * Validates form fields and submit the login form
 * @param object el form element
 */
function users_LoginForm(el)
{
    if(el == null) return false;    
    // define this to prevent name overlapping
    var $ = jQuery;
    
    var username = $('#login_username').val();
    var password = $('#login_password').val();    
    
    $('.alert').hide(); 
    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();                
    $('#messageInfo').show();                
    
    if(!username){
        $('#login_username').focus();
        $('#usernameErrorEmpty').show();       
    }else if(!password){
        $('#login_password').focus();
        $('#passwordErrorEmpty').show();       
    }else{
		return true;
	}   
    // prevent the default form submission occurring
    return false;       
}

/**
 * Raise error message
 * @param el
 * @param errorDescription
 * @param errorField
 */
function users_RaiseError(el, errorDescription, errorField)
{
    // define this to prevent name overlapping
    var $ = jQuery;

    $('.error').hide();
    $('#messageInfo').hide();
    if(errorDescription !== null) $('#messageError').html(errorDescription);
    if(errorField !== null) $('#'+errorField).focus();
    $('#messageError').show();

    $(el).html($(el).data('send'));
    $(el).removeClass('hover');
    $(el).removeAttr('disabled');    
}

/**
 * Change country from dropdown box
 * @param frm
 * @param country
 * @param state
 */
function users_ChangeCountry(frm, country, state)
{
    // define this to prevent name overlapping
    var $ = jQuery;

    var token = $('#'+frm).find('input[name=APPHP_CSRF_TOKEN]').val();
    var stateId = $('#'+frm).find('*[name=state]').attr('id');
    var countryCode = (country != null) ? country : '';
    var stateCode = (state != null) ? state : '';
        
    $.ajax({
        url: 'locations/getSubLocations',
        global: false,					  
        type: 'POST',
        data: ({
            APPHP_CSRF_TOKEN: token,
            act: 'send',
            country_code: countryCode
        }),
        dataType: 'html',
        async: true,
        error: function(html){
            //alert("AJAX: cannot connect to the server or server response error! Please try again later.");
        },
        success: function(html){
            try{
                var obj = $.parseJSON(html);
                if(obj[0].status == "1"){
                    if(obj.length > 1){
                        $("#"+stateId).replaceWith('<select id="'+stateId+'" name="state"></select>');
                        $("#"+stateId).empty();
						// add empty option
						$("<option />", {val: "", text: "--"}).appendTo("#"+stateId);
                        for(var i = 1; i < obj.length; i++){
                            if(obj[i].code == stateCode && stateCode != ''){
                                $("<option />", {val: obj[i].code, text: obj[i].name, selected: true}).appendTo("#"+stateId);					
                            }else{
                                $("<option />", {val: obj[i].code, text: obj[i].name}).appendTo("#"+stateId);					
                            }
                        }
                    }else{
                        $("#"+stateId).replaceWith('<input type="text" id="'+stateId+'" name="state" data-required="false" maxlength="64" value="'+stateCode+'" />');
                    }
                }else{
                    //alert("An error occurred while receiving data! Please try again later.");
                }                    
            }catch(err){
                //alert("An error occurred while receiving data! Please try again later.");
            }
        }						  
    });
}