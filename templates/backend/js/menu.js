/**
 * Changes menu type with form reloading
 */
function changeMenuType(el){
    $(el).closest('form').find('input[name=APPHP_FORM_ACT]').val('change');
    $(el).closest('form').submit();				
}	

/**
 * Sets menu type and link to the HTML form
 */
function setMenuType(el, link, module){
    $('#frmMenu'+el+'_link_url').val(link);
    if(module != '') $('#frmMenu'+el+'_module_code').val(module);
    $('#dialog').dialog('close');    
}

