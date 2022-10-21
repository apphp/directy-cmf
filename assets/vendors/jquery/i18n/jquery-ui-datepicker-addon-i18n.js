/*! jQuery Datepicker Addon - v1.0.0 - 2018-12-01
* Copyright (c) 2018 ApPHP; Licensed MIT */

(function($){

    $.datepicker.regional['ar'] = {
        closeText: "إغلاق",
        prevText: "&#x3C;السابق",
        nextText: "التالي&#x3E;",
        currentText: "اليوم",
        monthNames: [ "يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو","يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر" ],
        monthNamesShort: [ "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12" ],
        dayNames: [ "الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت" ],
        dayNamesShort: [ "أحد", "اثنين", "ثلاثاء", "أربعاء", "خميس", "جمعة", "سبت" ],
        dayNamesMin: [ "ح", "ن", "ث", "ر", "خ", "ج", "س" ],
        weekHeader: "أسبوع",
        dateFormat: "dd/mm/yy",
        firstDay: 0,
        isRTL: true,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['de'] = {
        closeText: "Schließen",
        prevText: "&#x3C;Zurück",
        nextText: "Vor&#x3E;",
        currentText: "Heute",
        monthNames: [ "Januar","Februar","März","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember" ],
        monthNamesShort: [ "Jan","Feb","Mär","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez" ],
        dayNames: [ "Sonntag","Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag" ],
        dayNamesShort: [ "So","Mo","Di","Mi","Do","Fr","Sa" ],
        dayNamesMin: [ "So","Mo","Di","Mi","Do","Fr","Sa" ],
        weekHeader: "KW",
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['es'] = {
        closeText: "Cerrar",
        prevText: "&#x3C;Ant",
        nextText: "Sig&#x3E;",
        currentText: "Hoy",
        monthNames: [ "enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre" ],
        monthNamesShort: [ "ene","feb","mar","abr","may","jun","jul","ago","sep","oct","nov","dic" ],
        dayNames: [ "domingo","lunes","martes","miércoles","jueves","viernes","sábado" ],
        dayNamesShort: [ "dom","lun","mar","mié","jue","vie","sáb" ],
        dayNamesMin: [ "D","L","M","X","J","V","S" ],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['fr'] = {
        closeText: "Fermer",
        prevText: "Précédent",
        nextText: "Suivant",
        currentText: "Aujourd'hui",
        monthNames: [ "janvier", "février", "mars", "avril", "mai", "juin","juillet", "août", "septembre", "octobre", "novembre", "décembre" ],
        monthNamesShort: [ "janv.", "févr.", "mars", "avr.", "mai", "juin","juil.", "août", "sept.", "oct.", "nov.", "déc." ],
        dayNames: [ "dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi" ],
        dayNamesShort: [ "dim.", "lun.", "mar.", "mer.", "jeu.", "ven.", "sam." ],
        dayNamesMin: [ "D","L","M","M","J","V","S" ],
        weekHeader: "Sem.",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['he'] = {
        closeText: "סגור",
        prevText: "&#x3C;הקודם",
        nextText: "הבא&#x3E;",
        currentText: "היום",
        monthNames: [ "ינואר","פברואר","מרץ","אפריל","מאי","יוני","יולי","אוגוסט","ספטמבר","אוקטובר","נובמבר","דצמבר" ],
        monthNamesShort: [ "ינו","פבר","מרץ","אפר","מאי","יוני","יולי","אוג","ספט","אוק","נוב","דצמ" ],
        dayNames: [ "ראשון","שני","שלישי","רביעי","חמישי","שישי","שבת" ],
        dayNamesShort: [ "א'","ב'","ג'","ד'","ה'","ו'","שבת" ],
        dayNamesMin: [ "א'","ב'","ג'","ד'","ה'","ו'","שבת" ],
        weekHeader: "Wk",
        dateFormat: "dd/mm/yy",
        firstDay: 0,
        isRTL: true,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['it'] = {
        closeText: "Chiudi",
        prevText: "&#x3C;Prec",
        nextText: "Succ&#x3E;",
        currentText: "Oggi",
        monthNames: [ "Gennaio","Febbraio","Marzo","Aprile","Maggio","Giugno","Luglio","Agosto","Settembre","Ottobre","Novembre","Dicembre" ],
        monthNamesShort: [ "Gen","Feb","Mar","Apr","Mag","Giu","Lug","Ago","Set","Ott","Nov","Dic" ],
        dayNames: [ "Domenica","Lunedì","Martedì","Mercoledì","Giovedì","Venerdì","Sabato" ],
        dayNamesShort: [ "Dom","Lun","Mar","Mer","Gio","Ven","Sab" ],
        dayNamesMin: [ "Do","Lu","Ma","Me","Gi","Ve","Sa" ],
        weekHeader: "Sm",
        dateFormat: "dd/mm/yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['nl'] = {
        closeText: "Sluiten",
        prevText: "←",
        nextText: "→",
        currentText: "Vandaag",
        monthNames: [ "januari", "februari", "maart", "april", "mei", "juni", "juli", "augustus", "september", "oktober", "november", "december" ],
        monthNamesShort: [ "jan", "feb", "mrt", "apr", "mei", "jun", "jul", "aug", "sep", "okt", "nov", "dec" ],
        dayNames: [ "zondag", "maandag", "dinsdag", "woensdag", "donderdag", "vrijdag", "zaterdag" ],
        dayNamesShort: [ "zon", "maa", "din", "woe", "don", "vri", "zat" ],
        dayNamesMin: [ "zo", "ma", "di", "wo", "do", "vr", "za" ],
        weekHeader: "Wk",
        dateFormat: "dd-mm-yy",
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ""
    };

    $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: '<Пред',
        nextText: 'След>',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        weekHeader: 'Не',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };

})(jQuery);
