<?php
/**
 * Events components
 *
 * PUBLIC:                  PRIVATE:
 * -----------         		------------------
 * drawCalendar              
 * drawShortcode             
 * prepareTab
 * 
 */

namespace Modules\Events\Components;

// Modules
use \Modules\Events\Models\Events;

// Global
use \A,
	\CWidget,
	\CClientScript,
	\CComponent,
	\CHtml;

// CMF
use \Website,
	\Bootstrap,
	\ModulesSettings;

class EventsComponent extends CComponent {

    const NL = "\n";

    /**
     * Prepares  module tabs
     * @param string $activeTab
     */
    public static function prepareTab($activeTab = 'events') {
        return CWidget::create('CTabs', array(
			'tabsWrapper' => array('tag' => 'div', 'class' => 'title'),
			'tabsWrapperInner' => array('tag' => 'div', 'class' => 'tabs'),
			'contentWrapper' => array(),
			'contentMessage' => '',
			'tabs' => array(
				A::t('events', 'Settings') => array('href' => Website::getBackendPath().'modules/settings/code/events', 'id' => 'tabSettings', 'content' => '', 'active' => false, 'htmlOptions' => array('class' => 'modules-settings-tab')),
				A::t('events', 'Events Categories') => array('href' => 'eventsCategories/manage', 'id' => 'tabEvents', 'content' => '', 'active' => (($activeTab == 'eventscategories' || $activeTab == 'events') ? true : false)),
			),
			'events' => array(),
			'return' => true,
        ));
    }

    /**
     * Draws Events shortcode output
     * @param array $params
     */
    public static function drawShortcode($params = array()) {
        $output = '';
        
        $timezone = Bootstrap::init()->getSettings('time_zone');
        $currentLangCode = A::app()->getLanguage();
        $categories = EventsCategories::loadEventsCategories();
        $url = 'events/getEventsCalendar';

        $output = self::drawCalendar($currentLangCode, $categories, $url, $timezone);

        return $output;
    }

    /**
     * Draws Calendar
     * @param array $params
     * @param string $timezone
     * @param string $currentLangCode
     * @param array  $categories
     * @param string $url
     */
    public static function drawCalendar($currentLangCode, $categories, $url, $timezone = '') {
        $output = '';
        static $uid = 1;

        A::app()->getClientScript()->registerCssFile('assets/modules/events/css/fullcalendar.min.css');
        A::app()->getClientScript()->registerCssFile('assets/modules/events/css/fullcalendar.print.css', 'print');
        A::app()->getClientScript()->registerScriptFile('assets/modules/events/js/moment.min.js', CClientScript::POS_BODY_END);
        A::app()->getClientScript()->registerScriptFile('assets/modules/events/js/fullcalendar.min.js', CClientScript::POS_BODY_END);
        A::app()->getClientScript()->registerCssFile('assets/modules/events/css/events.css');
        A::app()->getClientScript()->registerScript('eventsCalendar', " 
    $(document).ready(function () {
 
        function func(eventId, divEvent, jsEvent) {
        
            for(var u=0; u<window.events.length; u++){
                if(window.events[u].id==eventId){
                   $('#eventDetailsBlock').html(window.events[u].title+\"<br>\" +window.events[u].description);

                   $('#eventDetailsBlock').css({left: (parseInt($(window).scrollLeft())+parseInt(jsEvent.clientX)+5) + \"px\",
                                                 top: (parseInt($(window).scrollTop())+parseInt(jsEvent.clientY)+5)  + \"px\" }).show();                              
                   return;  
                }
            }   
        }
        function startEvent(eventId, divEvent, jsEvent){
            if(window.startEventTimeout){
               clearTimeout(window.startEventTimeout);
            }
            window.startEventTimeout= setTimeout(func, 1000, eventId, divEvent, jsEvent);        
        }
        function stopEvent(){
            $('#eventDetailsBlock').hide();
            if(window.startEventTimeout){
               clearTimeout(window.startEventTimeout);
            }
            window.startEventTimeout=false;
        }

       $('#calendar{$uid}').fullCalendar({
            eventMouseover: function(event, jsEvent, view){
              startEvent(event.id, this, jsEvent);
            },
            eventMouseout: stopEvent,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: '" . date('Y-m-d') . "',
            lang: '$currentLangCode',
            editable: false,
            eventLimit: true, // allow \"more\" link when too many events
            events: function(start, end, timezone, callback) {
        
                 $.ajax({
                        url: '$url',
                        dataType: 'json',
                        data: {
                            start: start.format('YYYY-MM-DD'),
                            end: end.format('YYYY-MM-DD'),
                            catId: $('#eventsCategories').val()
                        },
                        success: function(events) {
                                    window.events = events;
                                    callback(events);
                                 },
                        error: function () {
                                    $('#script-warning').show();
                               }
                    });
                 },
            
            loading: function (bool) {
                $('#loading').toggle(bool);
            }
        });
        $('#eventsCategories').change(function () {
            $('#calendar{$uid}').fullCalendar( 'refetchEvents' );
        });
        $('body').append($('<div id=eventDetailsBlock></div>'));
   });

");
        $output .= " 
        <div id = 'script-warning'>
        <code>{$url}</code> must be running.
        </div>

        <div id = 'loading'>loading...</div>";
		
        if(count($categories)>2){
            $output .= "<div class='events-category-calendar'>
            <span class='events-category-calendar-label'>".A::t('events', 'Category')."</span>  
            <span class='events-category-calendar-listbox'>
				".CHtml::dropDownList('eventsCategories', 0, $categories, array('id' => 'eventsCategories',  'class'=>'form-control', 'style'=>'width:160px;margin-bottom:10px;height:35px;'))."
            </span>    
            </div>";
        }
        
		$output .= "<div id='calendar{$uid}'></div>";

        $uid++;
        return $output;
    }

}
