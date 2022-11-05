/**
 * Validates form fields and submit the form
 */
   
function webforms_SubmitForm(el)
{
    if(el == null) return false;
    // define this to prevent name overlapping
    var $ = jQuery;
    
    var contactForm = $(el).closest('form');
    var token = $(el).closest('form').find('input[name=APPHP_CSRF_TOKEN]').val();
    var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,7})+$/;

    var name = $('#contact_name').val();
    var isNameRequired = $('#contact_name').data('required');
    var email = $('#contact_email').val();
    var isEmailRequired = $('#contact_email').data('required');
    var phone = $('#contact_phone').val();
    var isPhoneRequired = $('#contact_phone').data('required');
    var company = $('#contact_company').val();
    var isCompanyRequired = $('#contact_company').data('required');
    var message = $('#contact_message').val().trim();
    var isMessageRequired = $('#contact_message').data('required');    
    var captcha = $('#captcha_validation').val();
    var isCaptchaRequired = $('#captcha_validation').data('required');
    
    $('.error').hide();
    $('.success').hide();
    $('#messageError').hide();
    $('#messageInfo').show();                
    
    if(isNameRequired && !name){
        $('#contact_name').focus();
        $('#nameError').show();
    }else if(isEmailRequired && !email){
        $('#contact_email').focus();
        $('#emailErrorEmpty').show();
    }else if(email && !re.test(email)){
        $('#contact_email').focus();
        $('#emailErrorValid').show();
    }else if(isPhoneRequired && !phone){
        $('#contact_phone').focus();
        $('#phoneError').show();
    }else if(isCompanyRequired && !company){
        $('#contact_company').focus();
        $('#companyError').show();				
    }else if(isMessageRequired && !message){
        $('#contact_message').focus();
        $('#messageErrorEmpty').show();				
    }else if(message.length < 10){
        $('#contact_message').focus();
        $('#messageErrorValid').show();
    }else if(isCaptchaRequired && !captcha){
        $('#captcha_validation').focus();
        $('#captchaError').show();
    }else{
        
        $(el).html($(el).data('sending'));
        $(el).addClass('hover');
        $(el).attr('disabled','disabled');
        
        $.ajax({
            url: 'WebForms/submit',
            global: false,					  
            type: 'POST',
            data: ({
                APPHP_CSRF_TOKEN: token,
                contact_name: name,
                contact_email: email,
                contact_phone: phone,
                contact_company: company,
                contact_message: message,
                contact_captcha: captcha
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
                        $('#contact_name').val('');
                        $('#contact_email').val('');
                        $('#contact_phone').val('');
                        $('#contact_company').val('');
                        $('#contact_message').val('');
                        $('#captcha_validation').val('');
                        $('.contact-form-content').slideUp();
                        $('.error').hide();
                        $('#messageSuccess').show();
                    }else{
                        webforms_RaiseError(el, obj.error);
                    }                    
                }catch(err){
                    // debug: webforms_RaiseError(el, err.message);
                    webforms_RaiseError(el);
                }
            }					  
        });
    }
    // prevent the default form submission occurring
    return false;
}

/**
 * Raise error message
 */
function webforms_RaiseError(el, errorDescription)
{
    // define this to prevent name overlapping
    var $ = jQuery;

    $('.error').hide();
    $('#messageInfo').hide();
    if(errorDescription !== null) $('#messageError').html(errorDescription);
    $('#messageError').show();

    $(el).html($(el).data('send'));
    $(el).removeClass('hover');
    $(el).removeAttr('disabled');
}