$(document).ready(function(){
    
    // TOP MENU DROPDOWN ITEMS
    // --------------------------------------
    function DropDown(el){
        this.pdd = el;
        this.initEvents();
    }
    DropDown.prototype = {
        initEvents : function() {
            var obj = this;
            obj.pdd.on('click', function(event){
                $(this).toggleClass('active');
                event.stopPropagation();
            });	
        }
    }
    $(function(){
        var pdd = new DropDown($('#dd'));
        $(document).click(function(){            
            $('.wrapper-dropdown').removeClass('active');
        });
    });
    

    // SIDE MENU ITEMS
    // --------------------------------------
    // restore previous state for menu
    $('#sidebar>ul>li').each(function(){
        if($(this).find('li').length == 0){$(this).addClass('nosubmenu');}
    });
    
    var menuOpened = $.cookie('isOpened');
    if(menuOpened == 'all'){
        $('#sidebar>ul>li').addClass('active').find('ul:first').slideDown();
    }else if(menuOpened){
        $('#sidebar>ul>li.active').removeClass('active').find('ul:first').slideUp();
        $('#'+menuOpened).addClass('active').find('ul:first').slideDown();            
    }

    $('#sidebar>ul>li:not([class*="active"])>ul').hide();
    $('#sidebar>ul>li:not([class*="nosubmenu"])>a').click(function(){        
        var e = $(this).parent();
        if(e.hasClass('active')){
            $('#sidebar>ul>li.active').removeClass('active').find('ul:first').slideUp();
            $.cookie('isOpened', null);
        }else{
            $('#sidebar>ul>li.active').removeClass('active').find('ul:first').slideUp();
            e.addClass('active').find('ul:first').slideDown();            
            $.cookie('isOpened', e.attr('id'));
        }        
    });

    // SIDE MENU COLLAPSE
    // --------------------------------------
    var htmlCollapse = $('#menucollapse').html();
    var collapsedChar = ($('#menucollapse').data('direction') == 'rtl') ? '&#9664;' : '&#9654;';
    if($.cookie('isCollapsed') == 'true'){
        $('body').addClass('collapsed');
        $('#menucollapse').html(collapsedChar);
        $('#menuopen').hide();
        $('#menuclose').hide();
    }else{
        $('body').removeClass('collapsed');
        $('#menuopen').show();
        $('#menuclose').show();
    }    
    $('#menucollapse').click(function(){
        var body=$('body');
        body.toggleClass('collapsed');
        isCollapsed=body.hasClass('collapsed');
        if(isCollapsed){ $(this).html(collapsedChar); }else{ $(this).html(htmlCollapse); }
        $.cookie('isCollapsed', isCollapsed);
        $('#menuopen').toggle();
        $('#menuclose').toggle();
        return false;
    });

    $('#menuclose').click(function(){
        $('#sidebar>ul>li.active').removeClass('active').find('ul:first').slideUp();        
        $.cookie('isOpened', null);
        return false;
    });
    
    $('#menuopen').click(function(){
        $('#sidebar>ul>li').addClass('active').find('ul:first').slideDown();        
        $.cookie('isOpened', 'all');
        return false;
    });

    // BLOCK TITLE
    // --------------------------------------
    // restore previous state of blocks
    hideElementsFromArray('closedBlocs');
    hideElementsFromArray('collapsedBlocs', '.content');

    $('.bloc .title.closable').append('<a href="javascript:void(\'close\')" class="close"></a>');
    $('.bloc .title .close').click(function(){
        var cookieArrName = new cookieList('closedBlocs');
        var parentID = $(this).parent().parent().attr('id');
        if(typeof parentID != 'undefined') cookieArrName.add(parentID, {expires : 0});
        $(this).toggleClass('hide').parent().parent().hide();
        return false;
    });

    $('.bloc .title.collapsible').append('<a href="javascript:void(\'toggle\')" class="toggle"></a>');
    $('.bloc .title .toggle').click(function(){
        var cookieArrName = new cookieList('collapsedBlocs');
        var parentID = $(this).parent().parent().attr('id');        
        if(typeof parentID != 'undefined'){
            if(cookieArrName.exists(parentID)){
                cookieArrName.remove(parentID);
            }else{                
                cookieArrName.add(parentID, {expires : 0});
            }
        }
        $(this).toggleClass('hide').parent().parent().find('.content').slideToggle(300);
        return false;
    });    
 
    // ASSIGN ACTION TO ALL CLOSE BUTTONS
    // --------------------------------------
    hideElementsFromArray('closedAlerts');
    hideAlertsWrapper();
    $('.alert .close').click(function(){
        $(this).parent().hide();
        var cookieArrName = new cookieList('closedAlerts');
        var parentID = $(this).parent().attr('id');
        if(typeof parentID != 'undefined') cookieArrName.add(parentID, {expires : 0});
        hideAlertsWrapper();
    });    
    
    // INIT TOOLTIPS
    // --------------------------------------
    $('.tooltip-icon').tipTip({maxWidth: '270px', edgeOffset: 10}); /* maxWidth: 'auto' */
    $('.tooltip-link').tipTip({maxWidth: '270px', edgeOffset: 10});

    // INIT TABS
    // --------------------------------------
    var anchor = window.location.hash;
    $('.tabs.static').each(function(){        
        var current = null;
        var id = (typeof $(this).attr('id') != 'undefined') ? $(this).attr('id') : '';
        
        if(anchor != '' && $(this).find('a[href="'+anchor+'"]').length > 0){
            current = anchor;
        }else if($.cookie('tabs-'+id) && $(this).find('a[href="'+$.cookie('tabs-'+id)+'"]').length > 0){
            current = $.cookie('tabs-'+id);
        }else{
            current = $(this).find('a:first').attr('href');
        }

        $(this).find('a[href="'+current+'"]').addClass('active');
        $(current).siblings().hide();
        $(this).find('a').click(function(event){
            event.preventDefault();
            var link = $(this).attr('href');
            if(link == current){
                return false;
            }else{
                $(this).addClass('active').siblings().removeClass('active');
                $(link).show().siblings().hide();
                current = link;
                $.cookie('tabs-'+id, current);
                return false;
            }
        });
    });

    // INIT SORTABLE CONTENT
    // --------------------------------------
    $('.sortable-content').sortable({         
        update: function(event, ui) {
            var newOrder = $(this).sortable('toArray').join();
            $.cookie('sortableOrder', newOrder); /* { expires: 7} */
        }                                    
    });
    // restore saved order
    var oldOrder = $.cookie('sortableOrder');
    if(oldOrder){
        $(oldOrder.split(',')).each(function (i, id) {
            // appending the element with the ID given id should move each element to the end of the
            // list one after another, and at the end, the order should be restored.
            if(id != ''){
                $('#'+id).appendTo($('.sortable-content'));    
            } 
        });
        $('.sortable-content').append('<div style="clear:both"></div>');
    }
    
})

/**
 * Hides elements from array
 */
function hideElementsFromArray(cName, elFind){
    var cookieArrName = $.cookie(cName).split(',');
    for(var i = 0; i < cookieArrName.length; i++){        
        if(cookieArrName[i] != ''){
            if(typeof elFind != 'undefined'){
                $('#'+cookieArrName[i]).find(elFind).hide();
            }else{
                $('#'+cookieArrName[i]).hide();
            }
        } 
    }    
}

/**
 * Hide alerts wrapper
 */
function hideAlertsWrapper(){
    if($('#blocAlerts').find('.alert:visible').length <= 0){
        $('#blocAlerts').hide();
    }
}

/**
 * Cookie function
 * Usage :
 *  $.cookie(name, value, { expires: 7, path: '/', domain: 'domain.com', secure: true })
 *  $.cookie(name, value)
 *  $.cookie(name)
 * TODO
 *  - implement options { expires: 7, path: '/', domain: 'domain.com', secure: true }
 */
jQuery.cookie = function(name, value, options){
    var days = 7;
    var expires = '';
    var domain = (typeof cookieDomain != 'undefined') ? cookieDomain : '';
    var path = (typeof cookiePath != 'undefined') ? cookiePath : '/';
    
    if(typeof value != 'undefined'){
        // name and value given, set cookie        
        options = jQuery.extend({}, options);
        if(value === null){
            options.expires = -1;
        }
        if(typeof options.expires === 'number'){
            days = options.expires;
        }
        
        if(days){
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            expires = ';expires='+date.toGMTString();
        }
        if(jQuery.browser.msie == true){
            // IE ignores cookies with domain=...
            document.cookie = name+'='+escape(value)+expires+';path='+escape(path);    
        }else{
            document.cookie = name+'='+escape(value)+expires+';domain='+escape(domain)+';path='+escape(path);    
        }        

        return true;
    }else{
        // only name given, get cookie
        if(document.cookie.length > 0){
            start_c = document.cookie.indexOf(name + '=');
            if(start_c != -1){
                start_c += (name.length + 1);
                end_c = document.cookie.indexOf(';', start_c);
                if(end_c == -1) end_c = document.cookie.length;
                return unescape(document.cookie.substring(start_c, end_c));
            }
        }	
        return '';
    }
};

/**
 * Cookie list class
 */
var cookieList = function(cookieName) {
    // when the cookie is saved the items will be a comma seperated string
    // so we will split the cookie by comma to get the original array
    var cookie = $.cookie(cookieName);
    // load the items or a new array if null.
    var items = cookie ? cookie.split(/,/) : new Array();
    
    // return a object that we can use to access the array.
    // while hiding direct access to the declared items array

    return {
        'add': function(val, options){
            var optionsArray = (typeof options.expires === 'undefined') ? null : options; 
            // add to the items
            items.push(val);
            // save the items to a cookie
            $.cookie(cookieName, items, optionsArray);
        },
        'remove': function(value){
            // remove element from array 
            items.splice(items.indexOf(value), 1);
            // save the items to a cookie
            $.cookie(cookieName, items);
        },
        'clear': function(){
            // clear the cookie
            $.cookie(cookieName, null);
        },
        'exists': function(value){
            // check if coockie exists the cookie            
            return ($.inArray(value, items) > -1) ? true : false;
        },        
        'items': function(){
            // get all the items
            return items;
        }
    }
}

