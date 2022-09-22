var tinymceConfigs = [
{
    // General options
    mode : 'simplest',
    theme : 'advanced',
    width: '660px',
    height: '300px',
    entity_encoding : 'raw',
    //force_br_newlines : false,
    //force_p_newlines : false,
    forced_root_block : '',
    language: 'en',

    // Theme options
    theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,code,|',
    theme_advanced_buttons2 : '',
    theme_advanced_buttons3 : '',
    theme_advanced_toolbar_location : 'top',
    theme_advanced_toolbar_align : 'left',
    theme_advanced_statusbar_location : 'bottom',
    theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : false, 
},			  
{
    // General options
    mode : 'simple',
    theme : 'advanced',
    width: '660px',
    height: '300px',
    entity_encoding : 'raw',
    //force_br_newlines : false,
    //force_p_newlines : false,
    forced_root_block : '',
    language: 'en',

    // Theme options
    theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,link,unlink,|,bullist,numlist,|,undo,redo,|,hr,removeformat,|,image,|,forecolor,backcolor,|,code,|',
    theme_advanced_buttons2 : '',
    theme_advanced_buttons3 : '',
    theme_advanced_toolbar_location : 'top',
    theme_advanced_toolbar_align : 'left',
    theme_advanced_statusbar_location : 'bottom',
    theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : false, 
},					  
{
    // General options
    mode : 'advanced',
    theme : 'advanced',
    width: '660px',
    height: '300px',
    entity_encoding : 'raw',
    //force_br_newlines : false,
    //force_p_newlines : false,
    forced_root_block : '',
    language: 'en',
    plugins : 'imageupload,pagebreak,advhr,advimage,advlink,insertdatetime,preview,media,print,contextmenu,directionality,fullscreen,visualchars',

    // Theme options
    theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,charmap,media,advhr,|,fullscreen,preview,|,print',
    theme_advanced_buttons2 : 'bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,image,imageupload,|,code,|,insertdate,inserttime,|,forecolor,backcolor,|,hr,removeformat,visualaid,|,blockquote,pagebreak,|,ltr,rtl,',
    theme_advanced_buttons3 : '',
    theme_advanced_toolbar_location : 'top',
    theme_advanced_toolbar_align : 'left',
    theme_advanced_statusbar_location : 'bottom',
    theme_advanced_resizing : true,
	theme_advanced_resize_horizontal : false, 

    // Example word content CSS (should be your site CSS) this one removes paragraph margins
    // content_css : 'css/style.css',

    // Drop lists for link/image/media/template dialogs
    // template_external_list_url : 'lists/template_list.js',
    // external_link_list_url : 'lists/link_list.js',
    // external_image_list_url : 'lists/image_list.js',
    // media_external_list_url : 'lists/media_list.js',

    // Extended elements
    // extended_valid_elements : "iframe[src|width|height|name|align]",

	// Allow absolute urls
	remove_script_host : false,
    convert_urls : false,
    
	
}];

function setEditor(id, set_focus, mode){	
	// get mode
	var mce_mode = (mode != null) ? mode : 'advanced';
	
	tinyMCE.execCommand('mceRemoveControl', true, id);
	
	// set mode
	if (mce_mode == 'simplest') {
		tinyMCE.settings = tinymceConfigs[0];
	}else if (mce_mode == 'simple') {
		tinyMCE.settings = tinymceConfigs[1];
	}else{
		tinyMCE.settings = tinymceConfigs[2];
	}
	
    // set focus on editor
    if(set_focus) tinyMCE.settings.auto_focus = id;
	tinyMCE.execCommand('mceAddControl', true, id);
}
