/*
 * -- CONTENTS
 *
 * - MODERNIZR
 * - HOVERINTENT
 * - SMARTRESIZE
 * - EQUALHEIGHTS
 * - EASY PIE CHART
 * - ANIMATE NUMBER
 * - CUSTOM SELECT
 * - CLASSIE
 * - ANIMONSCROLL 
 * - MASONRY
 * - TOUCHSWIPE
 * - APPEAR
 * - STELLAR
 * - COUNTTO
 * - COUNTDOWN
 * - vCenter
 * - vCenterTop
 *
 */

/////////////////////////////////////////////
// MODERNIZR PLUGIN
/////////////////////////////////////////////

/* Modernizr 2.8.3 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-csstransitions-touch-shiv-mq-cssclasses-teststyles-testprop-testallprops-prefixes-domprefixes-load
 */
;window.Modernizr=function(a,b,c){function A(a){j.cssText=a}function B(a,b){return A(m.join(a+";")+(b||""))}function C(a,b){return typeof a===b}function D(a,b){return!!~(""+a).indexOf(b)}function E(a,b){for(var d in a){var e=a[d];if(!D(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function F(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:C(f,"function")?f.bind(d||b):f}return!1}function G(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+o.join(d+" ")+d).split(" ");return C(b,"string")||C(b,"undefined")?E(e,b):(e=(a+" "+p.join(d+" ")+d).split(" "),F(e,b,c))}var d="2.8.3",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k,l={}.toString,m=" -webkit- -moz- -o- -ms- ".split(" "),n="Webkit Moz O ms",o=n.split(" "),p=n.toLowerCase().split(" "),q={},r={},s={},t=[],u=t.slice,v,w=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},x=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b)&&c(b).matches||!1;var d;return w("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},y={}.hasOwnProperty,z;!C(y,"undefined")&&!C(y.call,"undefined")?z=function(a,b){return y.call(a,b)}:z=function(a,b){return b in a&&C(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=u.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(u.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(u.call(arguments)))};return e}),q.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:w(["@media (",m.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},q.csstransitions=function(){return G("transition")};for(var H in q)z(q,H)&&(v=H.toLowerCase(),e[v]=q[H](),t.push((e[v]?"":"no-")+v));return e.addTest=function(a,b){if(typeof a=="object")for(var d in a)z(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},A(""),i=k=null,function(a,b){function l(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function m(){var a=s.elements;return typeof a=="string"?a.split(" "):a}function n(a){var b=j[a[h]];return b||(b={},i++,a[h]=i,j[i]=b),b}function o(a,c,d){c||(c=b);if(k)return c.createElement(a);d||(d=n(c));var g;return d.cache[a]?g=d.cache[a].cloneNode():f.test(a)?g=(d.cache[a]=d.createElem(a)).cloneNode():g=d.createElem(a),g.canHaveChildren&&!e.test(a)&&!g.tagUrn?d.frag.appendChild(g):g}function p(a,c){a||(a=b);if(k)return a.createDocumentFragment();c=c||n(a);var d=c.frag.cloneNode(),e=0,f=m(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function q(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return s.shivMethods?o(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+m().join().replace(/[\w\-]+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(s,b.frag)}function r(a){a||(a=b);var c=n(a);return s.shivCSS&&!g&&!c.hasCSS&&(c.hasCSS=!!l(a,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),k||q(a,c),a}var c="3.7.0",d=a.html5||{},e=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,f=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,g,h="_html5shiv",i=0,j={},k;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",g="hidden"in a,k=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){g=!0,k=!0}})();var s={elements:d.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",version:c,shivCSS:d.shivCSS!==!1,supportsUnknownElements:k,shivMethods:d.shivMethods!==!1,type:"default",shivDocument:r,createElement:o,createDocumentFragment:p};a.html5=s,r(b)}(this,b),e._version=d,e._prefixes=m,e._domPrefixes=p,e._cssomPrefixes=o,e.mq=x,e.testProp=function(a){return E([a])},e.testAllProps=G,e.testStyles=w,g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+t.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};



/////////////////////////////////////////////
// HOVER INTENT PLUGIN
/////////////////////////////////////////////

/*!
 * hoverIntent r7 // 2013.03.11 // jQuery 1.9.1+
 * http://cherne.net/brian/resources/jquery.hoverIntent.html
 *
 * You may use hoverIntent under the terms of the MIT license. Basically that
 * means you are free to use hoverIntent as long as this header is left intact.
 * Copyright 2007, 2013 Brian Cherne
 */
(function($) {
    $.fn.hoverIntent = function(handlerIn,handlerOut,selector) {

        // default configuration values
        var cfg = {
            interval: 100,
            sensitivity: 7,
            timeout: 0
        };

        if ( typeof handlerIn === "object" ) {
            cfg = $.extend(cfg, handlerIn );
        } else if ($.isFunction(handlerOut)) {
            cfg = $.extend(cfg, { over: handlerIn, out: handlerOut, selector: selector } );
        } else {
            cfg = $.extend(cfg, { over: handlerIn, out: handlerIn, selector: handlerOut } );
        }

        // instantiate variables
        // cX, cY = current X and Y position of mouse, updated by mousemove event
        // pX, pY = previous X and Y position of mouse, set by mouseover and polling interval
        var cX, cY, pX, pY;

        // A private function for getting mouse position
        var track = function(ev) {
            cX = ev.pageX;
            cY = ev.pageY;
        };

        // A private function for comparing current and previous mouse position
        var compare = function(ev,ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            // compare mouse positions to see if they've crossed the threshold
            if ( ( Math.abs(pX-cX) + Math.abs(pY-cY) ) < cfg.sensitivity ) {
                $(ob).off("mousemove.hoverIntent",track);
                // set hoverIntent state to true (so mouseOut can be called)
                ob.hoverIntent_s = 1;
                return cfg.over.apply(ob,[ev]);
            } else {
                // set previous coordinates for next time
                pX = cX; pY = cY;
                // use self-calling timeout, guarantees intervals are spaced out properly (avoids JavaScript timer bugs)
                ob.hoverIntent_t = setTimeout( function(){compare(ev, ob);} , cfg.interval );
            }
        };

        // A private function for delaying the mouseOut function
        var delay = function(ev,ob) {
            ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t);
            ob.hoverIntent_s = 0;
            return cfg.out.apply(ob,[ev]);
        };

        // A private function for handling mouse 'hovering'
        var handleHover = function(e) {
            // copy objects to be passed into t (required for event object to be passed in IE)
            var ev = jQuery.extend({},e);
            var ob = this;

            // cancel hoverIntent timer if it exists
            if (ob.hoverIntent_t) { ob.hoverIntent_t = clearTimeout(ob.hoverIntent_t); }

            // if e.type == "mouseenter"
            if (e.type == "mouseenter") {
                // set "previous" X and Y position based on initial entry point
                pX = ev.pageX; pY = ev.pageY;
                // update "current" X and Y position based on mousemove
                $(ob).on("mousemove.hoverIntent",track);
                // start polling interval (self-calling timeout) to compare mouse coordinates over time
                if (ob.hoverIntent_s != 1) { ob.hoverIntent_t = setTimeout( function(){compare(ev,ob);} , cfg.interval );}

                // else e.type == "mouseleave"
            } else {
                // unbind expensive mousemove event
                $(ob).off("mousemove.hoverIntent",track);
                // if hoverIntent state is true, then call the mouseOut function after the specified delay
                if (ob.hoverIntent_s == 1) { ob.hoverIntent_t = setTimeout( function(){delay(ev,ob);} , cfg.timeout );}
            }
        };

        // listen for mouseenter and mouseleave
        return this.on({'mouseenter.hoverIntent':handleHover,'mouseleave.hoverIntent':handleHover}, cfg.selector);
    };
})(jQuery);







/////////////////////////////////////////////
// SMARTRESIZE PLUGIN
/////////////////////////////////////////////

(function($,sr){

	// USE STRICT
	"use strict";
	
	// debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function (func, threshold, execAsap) {
		var timeout;
		
		return function debounced () {
			var obj = this, args = arguments;
			function delayed () {
				if (!execAsap) {
					func.apply(obj, args);
					timeout = null;
				}
			}
			
			if (timeout) {
				clearTimeout(timeout);
			} else if (execAsap) {
				func.apply(obj, args);
			}
			
			timeout = setTimeout(delayed, threshold || 100); 
		};
	};
	
	// smartresize 
	jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
 
})(jQuery,'smartresize');






/////////////////////////////////////////////
// EQUALHEIGHTS PLUGIN
/////////////////////////////////////////////

(function($) {

	// USE STRICT
	"use strict";
	
	$.fn.equalHeights = function(px) {
		$(this).each(function(){
			var currentTallest = 0;
			$(this).children().each(function(){
				if ($(this).height() > currentTallest) { currentTallest = $(this).height(); }
			});
			if (!px && Number.prototype.pxToEm) {
				currentTallest = currentTallest.pxToEm(); //use ems unless px is specified
			}
			// for ie6, set height since min-height isn't supported
			if ($.browser.msie && $.browser.version === 6.0) {
				(this).children().css({'height': currentTallest});
			}
			$(this).children().css({'min-height': currentTallest}); 
		});
		return this;
	};
})(jQuery);







/////////////////////////////////////////////
// jQuery Parallax PLUGIN
/////////////////////////////////////////////

/*
Plugin: jQuery Parallax
Version 1.1.3
Author: Ian Lunn
Twitter: @IanLunn
Author URL: http://www.ianlunn.co.uk/
Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

(function ($) {
    var $window = $(window);
    var windowHeight = $window.height();

    $window.resize(function () {
        windowHeight = $window.height();
    });

    $.fn.parallax = function (xpos, speedFactor, outerHeight) {
        var $this = $(this);
        var getHeight;
        var firstTop;
        var paddingTop = 0;

        //get the starting position of each element to have parallax applied to it		
        $this.each(function () {
            firstTop = $this.offset().top;
        });

        if (outerHeight) {
            getHeight = function (jqo) {
                return jqo.outerHeight(true);
            };
        } else {
            getHeight = function (jqo) {
                return jqo.height();
            };
        }

        // setup defaults if arguments aren't specified
        if (arguments.length < 1 || xpos === null) xpos = "50%";
        if (arguments.length < 2 || speedFactor === null) speedFactor = 0.1;
        if (arguments.length < 3 || outerHeight === null) outerHeight = true;

        // function to be called whenever the window is scrolled or resized
        function update() {
            var pos = $window.scrollTop();

            $this.each(function () {
                var $element = $(this);
                var top = $element.offset().top;
                var height = getHeight($element);

                // Check if totally above or totally below viewport
                if (top + height < pos || top > pos + windowHeight) {
                    return;
                }

                $this.css('backgroundPosition', xpos + " " + Math.round((firstTop - pos) * speedFactor) + "px");
            });
        }

        $window.bind('scroll', update).resize(update);
        update();
    };
})(jQuery);




/////////////////////////////////////////////
// EASYPIECHART PLUGIN
/////////////////////////////////////////////

(function($) {
	// USE STRICT
	"use strict";
	$.easyPieChart = function(el, options) {
		var addScaleLine, animateLine, drawLine, easeInOutQuad, renderBackground, renderScale, renderTrack,
		_this = this;
		this.el = el;
		this.$el = $(el);
		this.$el.data("easyPieChart", this);
		this.init = function() {
			var percent;
			_this.options = $.extend({}, $.easyPieChart.defaultOptions, options);
			percent = parseInt(_this.$el.data('percent'), 10);
			_this.percentage = 0;
			_this.canvas = $("<canvas width='" + _this.options.size + "' height='" + _this.options.size + "'></canvas>").get(0);
			_this.$el.append(_this.canvas);
			if (typeof G_vmlCanvasManager !== "undefined" && G_vmlCanvasManager !== null) {
				G_vmlCanvasManager.initElement(_this.canvas);
			}
			_this.ctx = _this.canvas.getContext('2d');
			if (window.devicePixelRatio > 1.5) {
				$(_this.canvas).css({
					width: _this.options.size,
					height: _this.options.size
				});
				_this.canvas.width *= 2;
				_this.canvas.height *= 2;
				_this.ctx.scale(2, 2);
			}
			_this.ctx.translate(_this.options.size / 2, _this.options.size / 2);
			_this.$el.addClass('easyPieChart');
			_this.$el.css({
				width: _this.options.size,
				height: _this.options.size,
				lineHeight: "" + _this.options.size + "px"
			});			
			_this.update(percent);
			return _this;
		};
		this.update = function(percent) {
			if (_this.options.animate === false) {
				return drawLine(percent);
			} else {
				if (percent === 0) {
					return animateLine(0, 0);	
				} else {
					return animateLine(_this.percentage, percent);	
				}
			}
		};
		renderScale = function() {
			var i, _i, _results;
			_this.ctx.fillStyle = _this.options.scaleColor;
			_this.ctx.lineWidth = 1;
			_results = [];
			for (i = _i = 0; _i <= 24; i = ++_i) {
				_results.push(addScaleLine(i));
			}
			return _results;
		};
		addScaleLine = function(i) {
			var offset;
			offset = i % 6 === 0 ? 0 : _this.options.size * 0.017;
			_this.ctx.save();
			_this.ctx.rotate(i * Math.PI / 12);
			_this.ctx.fillRect(_this.options.size / 2 - offset, 0, -_this.options.size * 0.05 + offset, 1);
			return _this.ctx.restore();
		};
		renderTrack = function() {
			var offset;
			offset = _this.options.size / 2 - _this.options.lineWidth / 2;
			if (_this.options.scaleColor !== false) {
				offset -= _this.options.size * 0.08;
			}
			_this.ctx.beginPath();
			_this.ctx.arc(0, 0, offset, 0, Math.PI * 2, true);
			_this.ctx.closePath();
			_this.ctx.strokeStyle = _this.options.trackColor;
			_this.ctx.lineWidth = _this.options.lineWidth;
			return _this.ctx.stroke();
		};
		renderBackground = function() {
//			if (_this.options.scaleColor !== false) {
//				renderScale();
//			}
			if (_this.options.trackColor !== false) {
				return renderTrack();
			}
		};
		drawLine = function(percent) {
			var offset;
			renderBackground();
			_this.ctx.strokeStyle = $.isFunction(_this.options.barColor) ? _this.options.barColor(percent) : _this.options.barColor;
			_this.ctx.lineCap = _this.options.lineCap;
			_this.ctx.lineWidth = _this.options.lineWidth;
			offset = _this.options.size / 2 - _this.options.lineWidth / 2;
			if (_this.options.scaleColor !== false) {
				offset -= _this.options.size * 0.08;
			}
			_this.ctx.save();
			_this.ctx.rotate(-Math.PI / 2);
			_this.ctx.beginPath();
			_this.ctx.arc(0, 0, offset, 0, Math.PI * 2 * percent / 100, false);
			_this.ctx.stroke();
			return _this.ctx.restore();
		};
		animateLine = function(from, to) {
			var currentStep, fps, steps;
			fps = 30;
			steps = fps * _this.options.animate / 1000;
			currentStep = 0;
			_this.options.onStart.call(_this);
			_this.percentage = to;
			if (_this.animation) {
				clearInterval(_this.animation);
				_this.animation = false;
			}
			_this.animation = setInterval(function() {
				_this.ctx.clearRect(-_this.options.size / 2, -_this.options.size / 2, _this.options.size, _this.options.size);
				renderBackground.call(_this);
				drawLine.call(_this, [easeInOutQuad(currentStep, from, to - from, steps)]);
				currentStep++;
				if ((currentStep / steps) > 1) {
					clearInterval(_this.animation);
					_this.animation = false;
					return _this.options.onStop.call(_this);
				}
			}, 1000 / fps);
			return _this.animation;
		};
		easeInOutQuad = function(t, b, c, d) {
			var easeIn, easing;
			easeIn = function(t) {
				return Math.pow(t, 2);
			};
			easing = function(t) {
				if (t < 1) {
					return easeIn(t);
				} else {
					return 2 - easeIn((t / 2) * -2 + 2);
				}
			};
			t /= d / 2;
			return c / 2 * easing(t) + b;
		};
		return this.init();
	};
	$.easyPieChart.defaultOptions = {
		barColor: '#ef1e25',
		trackColor: '#f2f2f2',
		scaleColor: '#dfe0e0',
		lineCap: 'round',
		size: 110,
		lineWidth: 3,
		animate: false,
		onStart: $.noop,
		onStop: $.noop
	};
	$.fn.easyPieChart = function(options) {
		return $.each(this, function(i, el) {
		var $el;
		$el = $(el);
		if (!$el.data('easyPieChart')) {
			return $el.data('easyPieChart', new $.easyPieChart(el, options));
		}
		});
	};
	return void 0;
})(jQuery);








/////////////////////////////////////////////
// ANIMATE NUMBER PLUGIN
/////////////////////////////////////////////

(function($) {
	
	// USE STRICT
	"use strict";
	
    $.fn.animateNumber = function(to) {
        var $ele = $(this),
            num = parseInt($ele.html(), 10),
            up = to > num,
            num_interval = Math.abs(num - to) / 90;

        var loop = function() {
            num = up ? Math.ceil(num+num_interval) : Math.floor(num-num_interval);
            if ( (up && num > to) || (!up && num < to) ) {
                num = to;
                clearInterval(animation);
            }
            $ele.html(num);
        };
        
        var intervalTime = to <= 5 ? intervalTime = 100 : to <= 25 ? intervalTime = 50 : to <= 50 ? intervalTime = 25 : 10;

        var animation = setInterval(loop, intervalTime);
    };
})(jQuery);









/////////////////////////////////////////////
// CUSTOM SELECT PLUGIN
/////////////////////////////////////////////

/*!
 * jquery.customSelect() - v0.4.2
 * http://adam.co/lab/jquery/customselect/
 * 2013-05-22
 *
 * Copyright 2013 Adam Coulombe
 * @license http://www.opensource.org/licenses/mit-license.html MIT License
 * @license http://www.gnu.org/licenses/gpl.html GPL2 License 
 */

(function ($) {
    'use strict';

    $.fn.extend({
        customSelect: function (setOptions) {
            // filter out <= IE6
            if (typeof document.body.style.maxHeight === 'undefined') {
                return this;
            }
            var defaults = {
                    customClass: 'customSelect',
                    mapClass:    true,
                    mapStyle:    true
            },
            options = $.extend(defaults, setOptions),
            prefix = options.customClass,
            changed = function ($select,customSelectSpan) {
                var currentSelected = $select.find(':selected'),
                customSelectSpanInner = customSelectSpan.children(':first'),
                html = currentSelected.html() || '&nbsp;';

                customSelectSpanInner.html(html);
                
                if (currentSelected.attr('disabled')) {customSelectSpan.addClass(getClass('DisabledOption'));
                } else {customSelectSpan.removeClass(getClass('DisabledOption'));
                }
                
                setTimeout(function () {
                    customSelectSpan.removeClass(getClass('Open'));
                    $(document).off('mouseup.'+getClass('Open'));                  
                }, 60);
            },
            getClass = function(suffix){
                return prefix + suffix;
            };

            return this.each(function () {
                var $select = $(this),
                    customSelectInnerSpan = $('<span />').addClass(getClass('Inner')),
                    customSelectSpan = $('<span />'),
                    position = $select.position();

                $select.after(customSelectSpan.append(customSelectInnerSpan));
                
                customSelectSpan.addClass(prefix);

                if (options.mapClass) {
                    customSelectSpan.addClass($select.attr('class'));
                }
                if (options.mapStyle) {
                    customSelectSpan.attr('style', $select.attr('style'));
                }

                $select
                    .addClass('hasCustomSelect')
                    .on('update', function () {
						changed($select,customSelectSpan);

                        var selectBoxWidth = parseInt($select.outerWidth(), 10) -
                                (parseInt(customSelectSpan.outerWidth(), 10) -
                                    parseInt(customSelectSpan.width(), 10));

						// Set to inline-block before calculating outerHeight
						customSelectSpan.css({
                            display: 'inline-block'
                        });

                        var selectBoxHeight = customSelectSpan.outerHeight();

                        if ($select.attr('disabled')) {
                            customSelectSpan.addClass(getClass('Disabled'));
                        } else {
                            customSelectSpan.removeClass(getClass('Disabled'));
                        }

                        customSelectInnerSpan.css({
                            width:   selectBoxWidth,
                            display: 'inline-block'
                        });

                        $select.css({
                            '-webkit-appearance': 'menulist-button',
                            width:                customSelectSpan.outerWidth(),
                            position:             'absolute',
                            opacity:              0,
                            height:               selectBoxHeight,
                            fontSize:             customSelectSpan.css('font-size'),
                            left:                 position.left,
                            top:                  position.top
                        });
                    })
                    .on('change', function () {
                        customSelectSpan.addClass(getClass('Changed'));
                        changed($select,customSelectSpan);
                    })
                    .on('keyup', function (e) {
                        if(!customSelectSpan.hasClass(getClass('Open'))){
                            $select.blur();
                            $select.focus();
                        }else{
                            if(e.which===13||e.which===27||e.which===9){
                                changed($select,customSelectSpan);
                            }
                        }
                    })
                    .on('mousedown', function () {
                        customSelectSpan.removeClass(getClass('Changed'));
                    })
                    .on('mouseup', function (e) {
                        
                        if( !customSelectSpan.hasClass(getClass('Open'))){
                            // if FF and there are other selects open, just apply focus
                            if($('.'+getClass('Open')).not(customSelectSpan).length>0 && typeof InstallTrigger !== 'undefined'){
                                $select.focus();
                            }else{
                                customSelectSpan.addClass(getClass('Open'));
                                e.stopPropagation();
                                $(document).one('mouseup.'+getClass('Open'), function (e) {
                                    if( e.target !== $select.get(0) && $.inArray(e.target,$select.find('*').get()) < 0 ){
                                        $select.blur();
                                    }else{
                                        changed($select,customSelectSpan);
                                    }
                                });
                            }
                        }
                    })
                    .focus(function () {
                        customSelectSpan.removeClass(getClass('Changed')).addClass(getClass('Focus'));
                    })
                    .blur(function () {
                        customSelectSpan.removeClass(getClass('Focus')+' '+getClass('Open'));
                    })
                    .hover(function () {
                        customSelectSpan.addClass(getClass('Hover'));
                    }, function () {
                        customSelectSpan.removeClass(getClass('Hover'));
                    })
                    .trigger('update');
            });
        }
    });
})(jQuery);








/////////////////////////////////////////////
// CLASSIE PLUGIN
/////////////////////////////////////////////

/*!
 * classie - class helper functions
 * from bonzo https://github.com/ded/bonzo
 * 
 * classie.has( elem, 'my-class' ) -> true/false
 * classie.add( elem, 'my-new-class' )
 * classie.remove( elem, 'my-unwanted-class' )
 * classie.toggle( elem, 'my-class' )
 */

/*jshint browser: true, strict: true, undef: true */
/*global define: false */

( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

var classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

// transport
if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( classie );
} else {
  // browser global
  window.classie = classie;
}

})( window );









/////////////////////////////////////////////
// ANIMONSCROLL PLUGIN
/////////////////////////////////////////////

/**
 * animOnScroll.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
;( function( window ) {
	
	'use strict';
	
	var docElem = window.document.documentElement;

	function getViewportH() {
		var client = docElem['clientHeight'],
			inner = window['innerHeight'];
		
		if( client < inner )
			return inner;
		else
			return client;
	}

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	// http://stackoverflow.com/a/5598797/989439
	function getOffset( el ) {
		var offsetTop = 0, offsetLeft = 0;
		do {
			if ( !isNaN( el.offsetTop ) ) {
				offsetTop += el.offsetTop;
			}
			if ( !isNaN( el.offsetLeft ) ) {
				offsetLeft += el.offsetLeft;
			}
		} while( el = el.offsetParent )

		return {
			top : offsetTop,
			left : offsetLeft
		}
	}

	function inViewport( el, h ) {
		var elH = el.offsetHeight,
			scrolled = scrollY(),
			viewed = scrolled + getViewportH(),
			elTop = getOffset(el).top,
			elBottom = elTop + elH,
			// if 0, the element is considered in the viewport as soon as it enters.
			// if 1, the element is considered in the viewport only when it's fully inside
			// value in percentage (1 >= h >= 0)
			h = h || 0;

		return (elTop + elH * h) <= viewed && (elBottom - elH * h) >= scrolled;
	}

	function extend( a, b ) {
		for( var key in b ) { 
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function AnimOnScroll( el, options ) {	
		this.el = el;
		this.options = extend( this.defaults, options );
		this._init();
	}

	AnimOnScroll.prototype = {
		defaults : {
			// Minimum and a maximum duration of the animation (random value is chosen)
			minDuration : 0,
			maxDuration : 0,
			// The viewportFactor defines how much of the appearing item has to be visible in order to trigger the animation
			// if we'd use a value of 0, this would mean that it would add the animation class as soon as the item is in the viewport. 
			// If we were to use the value of 1, the animation would only be triggered when we see all of the item in the viewport (100% of it)
			viewportFactor : 0
		},
		_init : function() {
			this.items = Array.prototype.slice.call( document.querySelectorAll( '#' + this.el.id + ' > li' ) );
			this.itemsCount = this.items.length;
			this.itemsRenderedCount = 0;
			this.didScroll = false;
			
			var self = this;
			var firstLoad = false;

			imagesLoaded( this.el, function() {
											
				// initialize masonry
				new Masonry( self.el, {
					itemSelector: 'li',
					transitionDuration : 0
				} );
				
				if( Modernizr.cssanimations ) {
					// the items already shown...
					if (self.el.classList.contains('first-load')) {
						self.items.forEach( function( el, i ) {
							if( inViewport( el ) ) {
//								self._checkTotalRendered();
//								classie.add( el, 'shown' );
								self._onScrollFn();
							}
						} );
					} else {
						self.items.forEach( function( el, i ) {
							if( inViewport( el ) ) {
								self._onScrollFn();
							}
						} );
					}
					
					document.getElementById(self.el.id).className = document.getElementById(self.el.id).className.replace(/\bfirst-load\b/,'');

					// animate on scroll the items inside the viewport
					window.addEventListener( 'scroll', function() {
						self._onScrollFn();
					}, false );
					window.addEventListener( 'resize', function() {
						self._resizeHandler();
					}, false );
				}

			});
		},
		_onScrollFn : function() {
			var self = this;
			if( !this.didScroll ) {
				this.didScroll = true;
				setTimeout( function() { self._scrollPage(); }, 60 );
			}
		},
		_scrollPage : function() {
			var self = this;
			this.items.forEach( function( el, i ) {
				if( !classie.has( el, 'shown' ) && !classie.has( el, 'animate' ) && inViewport( el, self.options.viewportFactor ) ) {
					setTimeout( function() {
						var perspY = scrollY() + getViewportH() / 2;
						self.el.style.WebkitPerspectiveOrigin = '50% ' + perspY + 'px';
						self.el.style.MozPerspectiveOrigin = '50% ' + perspY + 'px';
						self.el.style.perspectiveOrigin = '50% ' + perspY + 'px';

						self._checkTotalRendered();

						if( self.options.minDuration && self.options.maxDuration ) {
							var randDuration = ( Math.random() * ( self.options.maxDuration - self.options.minDuration ) + self.options.minDuration ) + 's';
							el.style.WebkitAnimationDuration = randDuration;
							el.style.MozAnimationDuration = randDuration;
							el.style.animationDuration = randDuration;
						}
						
						classie.add( el, 'animate' );
					}, 25 );
				}
			});
			this.didScroll = false;
		},
		_resizeHandler : function() {
			var self = this;
			function delayed() {
				self._scrollPage();
				self.resizeTimeout = null;
			}
			if ( this.resizeTimeout ) {
				clearTimeout( this.resizeTimeout );
			}
			this.resizeTimeout = setTimeout( delayed, 1000 );
		},
		_checkTotalRendered : function() {
			++this.itemsRenderedCount;
			if( this.itemsRenderedCount === this.itemsCount ) {
				window.removeEventListener( 'scroll', this._onScrollFn );
			}
		}
	}

	// add to global namespace
	window.AnimOnScroll = AnimOnScroll;

} )( window );







/////////////////////////////////////////////
// MASONRY PLUGIN
/////////////////////////////////////////////

/*!
 * Masonry PACKAGED v3.0.0
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

(function(t){"use strict";function e(t){if(t){if("string"==typeof n[t])return t;t=t.charAt(0).toUpperCase()+t.slice(1);for(var e,o=0,r=i.length;r>o;o++)if(e=i[o]+t,"string"==typeof n[e])return e}}var i="Webkit Moz ms Ms O".split(" "),n=document.documentElement.style;"function"==typeof define&&define.amd?define(function(){return e}):t.getStyleProperty=e})(window),function(t){"use strict";function e(t){var e=parseFloat(t),i=-1===t.indexOf("%")&&!isNaN(e);return i&&e}function i(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0,i=s.length;i>e;e++){var n=s[e];t[n]=0}return t}function n(t){function n(t){if("string"==typeof t&&(t=document.querySelector(t)),t&&"object"==typeof t&&t.nodeType){var n=r(t);if("none"===n.display)return i();var h={};h.width=t.offsetWidth,h.height=t.offsetHeight;for(var p=h.isBorderBox=!(!a||!n[a]||"border-box"!==n[a]),u=0,f=s.length;f>u;u++){var d=s[u],c=n[d],l=parseFloat(c);h[d]=isNaN(l)?0:l}var m=h.paddingLeft+h.paddingRight,y=h.paddingTop+h.paddingBottom,g=h.marginLeft+h.marginRight,v=h.marginTop+h.marginBottom,_=h.borderLeftWidth+h.borderRightWidth,b=h.borderTopWidth+h.borderBottomWidth,L=p&&o,E=e(n.width);E!==!1&&(h.width=E+(L?0:m+_));var I=e(n.height);return I!==!1&&(h.height=I+(L?0:y+b)),h.innerWidth=h.width-(m+_),h.innerHeight=h.height-(y+b),h.outerWidth=h.width+g,h.outerHeight=h.height+v,h}}var o,a=t("boxSizing");return function(){if(a){var t=document.createElement("div");t.style.width="200px",t.style.padding="1px 2px 3px 4px",t.style.borderStyle="solid",t.style.borderWidth="1px 2px 3px 4px",t.style[a]="border-box";var i=document.body||document.documentElement;i.appendChild(t);var n=r(t);o=200===e(n.width),i.removeChild(t)}}(),n}var o=document.defaultView,r=o&&o.getComputedStyle?function(t){return o.getComputedStyle(t,null)}:function(t){return t.currentStyle},s=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"];"function"==typeof define&&define.amd?define(["get-style-property"],n):t.getSize=n(t.getStyleProperty)}(window),function(t){"use strict";var e=document.documentElement,i=function(){};e.addEventListener?i=function(t,e,i){t.addEventListener(e,i,!1)}:e.attachEvent&&(i=function(e,i,n){e[i+n]=n.handleEvent?function(){var e=t.event;e.target=e.target||e.srcElement,n.handleEvent.call(n,e)}:function(){var i=t.event;i.target=i.target||i.srcElement,n.call(e,i)},e.attachEvent("on"+i,e[i+n])});var n=function(){};e.removeEventListener?n=function(t,e,i){t.removeEventListener(e,i,!1)}:e.detachEvent&&(n=function(t,e,i){t.detachEvent("on"+e,t[e+i]);try{delete t[e+i]}catch(n){t[e+i]=void 0}});var o={bind:i,unbind:n};"function"==typeof define&&define.amd?define(o):t.eventie=o}(this),function(t){"use strict";function e(t){"function"==typeof t&&(e.isReady?t():r.push(t))}function i(t){var i="readystatechange"===t.type&&"complete"!==o.readyState;if(!e.isReady&&!i){e.isReady=!0;for(var n=0,s=r.length;s>n;n++){var a=r[n];a()}}}function n(n){return n.bind(o,"DOMContentLoaded",i),n.bind(o,"readystatechange",i),n.bind(t,"load",i),e}var o=t.document,r=[];e.isReady=!1,"function"==typeof define&&define.amd?define(["eventie"],n):t.docReady=n(t.eventie)}(this),function(t){"use strict";function e(){}function i(t,e){if(o)return e.indexOf(t);for(var i=e.length;i--;)if(e[i]===t)return i;return-1}var n=e.prototype,o=Array.prototype.indexOf?!0:!1;n._getEvents=function(){return this._events||(this._events={})},n.getListeners=function(t){var e,i,n=this._getEvents();if("object"==typeof t){e={};for(i in n)n.hasOwnProperty(i)&&t.test(i)&&(e[i]=n[i])}else e=n[t]||(n[t]=[]);return e},n.getListenersAsObject=function(t){var e,i=this.getListeners(t);return i instanceof Array&&(e={},e[t]=i),e||i},n.addListener=function(t,e){var n,o=this.getListenersAsObject(t);for(n in o)o.hasOwnProperty(n)&&-1===i(e,o[n])&&o[n].push(e);return this},n.on=n.addListener,n.defineEvent=function(t){return this.getListeners(t),this},n.defineEvents=function(t){for(var e=0;t.length>e;e+=1)this.defineEvent(t[e]);return this},n.removeListener=function(t,e){var n,o,r=this.getListenersAsObject(t);for(o in r)r.hasOwnProperty(o)&&(n=i(e,r[o]),-1!==n&&r[o].splice(n,1));return this},n.off=n.removeListener,n.addListeners=function(t,e){return this.manipulateListeners(!1,t,e)},n.removeListeners=function(t,e){return this.manipulateListeners(!0,t,e)},n.manipulateListeners=function(t,e,i){var n,o,r=t?this.removeListener:this.addListener,s=t?this.removeListeners:this.addListeners;if("object"!=typeof e||e instanceof RegExp)for(n=i.length;n--;)r.call(this,e,i[n]);else for(n in e)e.hasOwnProperty(n)&&(o=e[n])&&("function"==typeof o?r.call(this,n,o):s.call(this,n,o));return this},n.removeEvent=function(t){var e,i=typeof t,n=this._getEvents();if("string"===i)delete n[t];else if("object"===i)for(e in n)n.hasOwnProperty(e)&&t.test(e)&&delete n[e];else delete this._events;return this},n.emitEvent=function(t,e){var i,n,o,r=this.getListenersAsObject(t);for(n in r)if(r.hasOwnProperty(n))for(i=r[n].length;i--;)o=e?r[n][i].apply(null,e):r[n][i](),o===!0&&this.removeListener(t,r[n][i]);return this},n.trigger=n.emitEvent,n.emit=function(t){var e=Array.prototype.slice.call(arguments,1);return this.emitEvent(t,e)},"function"==typeof define&&define.amd?define(function(){return e}):t.EventEmitter=e}(this),function(t){"use strict";function e(){}function i(t){function i(e){e.prototype.option||(e.prototype.option=function(e){t.isPlainObject(e)&&(this.options=t.extend(!0,this.options,e))})}function o(e,i){t.fn[e]=function(o){if("string"==typeof o){for(var s=n.call(arguments,1),a=0,h=this.length;h>a;a++){var p=this[a],u=t.data(p,e);if(u)if(t.isFunction(u[o])&&"_"!==o.charAt(0)){var f=u[o].apply(u,s);if(void 0!==f)return f}else r("no such method '"+o+"' for "+e+" instance");else r("cannot call methods on "+e+" prior to initialization; "+"attempted to call '"+o+"'")}return this}return this.each(function(){var n=t.data(this,e);n?(n.option(o),n._init()):(n=new i(this,o),t.data(this,e,n))})}}if(t){var r="undefined"==typeof console?e:function(t){console.error(t)};t.bridget=function(t,e){i(e),o(t,e)}}}var n=Array.prototype.slice;"function"==typeof define&&define.amd?define(["jquery"],i):i(t.jQuery)}(window),function(t,e){"use strict";function i(t,e){return t[a](e)}function n(t){if(!t.parentNode){var e=document.createDocumentFragment();e.appendChild(t)}}function o(t,e){n(t);for(var i=t.parentNode.querySelectorAll(e),o=0,r=i.length;r>o;o++)if(i[o]===t)return!0;return!1}function r(t,e){return n(t),i(t,e)}var s,a=function(){if(e.matchesSelector)return"matchesSelector";for(var t=["webkit","moz","ms","o"],i=0,n=t.length;n>i;i++){var o=t[i],r=o+"MatchesSelector";if(e[r])return r}}();if(a){var h=document.createElement("div"),p=i(h,"div");s=p?i:r}else s=o;"function"==typeof define&&define.amd?define(function(){return s}):window.matchesSelector=s}(this,Element.prototype),function(t){"use strict";function e(t,e){for(var i in e)t[i]=e[i];return t}function i(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}var n=t.getSize,o=t.getStyleProperty,r=t.EventEmitter,s=document.defaultView,a=s&&s.getComputedStyle?function(t){return s.getComputedStyle(t,null)}:function(t){return t.currentStyle},h=o("transition"),p=o("transform"),u=h&&p,f=!!o("perspective"),d={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"otransitionend",transition:"transitionend"}[h],c=["transform","transition","transitionDuration","transitionProperty"],l=function(){for(var t={},e=0,i=c.length;i>e;e++){var n=c[e],r=o(n);r&&r!==n&&(t[n]=r)}return t}();e(i.prototype,r.prototype),i.prototype._create=function(){this.css({position:"absolute"})},i.prototype.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},i.prototype.getSize=function(){this.size=n(this.element)},i.prototype.css=function(t){var e=this.element.style;for(var i in t){var n=l[i]||i;e[n]=t[i]}},i.prototype.getPosition=function(){var t=a(this.element),e=this.layout.options,i=e.isOriginLeft,n=e.isOriginTop,o=parseInt(t[i?"left":"right"],10),r=parseInt(t[n?"top":"bottom"],10);o=isNaN(o)?0:o,r=isNaN(r)?0:r;var s=this.layout.size;o-=i?s.paddingLeft:s.paddingRight,r-=n?s.paddingTop:s.paddingBottom,this.position.x=o,this.position.y=r},i.prototype.layoutPosition=function(){var t=this.layout.size,e=this.layout.options,i={};e.isOriginLeft?(i.left=this.position.x+t.paddingLeft+"px",i.right=""):(i.right=this.position.x+t.paddingRight+"px",i.left=""),e.isOriginTop?(i.top=this.position.y+t.paddingTop+"px",i.bottom=""):(i.bottom=this.position.y+t.paddingBottom+"px",i.top=""),this.css(i),this.emitEvent("layout",[this])};var m=f?function(t,e){return"translate3d("+t+"px, "+e+"px, 0)"}:function(t,e){return"translate("+t+"px, "+e+"px)"};i.prototype._transitionTo=function(t,e){this.getPosition();var i=this.position.x,n=this.position.y,o=parseInt(t,10),r=parseInt(e,10),s=o===this.position.x&&r===this.position.y;if(this.setPosition(t,e),s&&!this.isTransitioning)return this.layoutPosition(),void 0;var a=t-i,h=e-n,p={},u=this.layout.options;a=u.isOriginLeft?a:-a,h=u.isOriginTop?h:-h,p.transform=m(a,h),this.transition({to:p,onTransitionEnd:this.layoutPosition,isCleaning:!0})},i.prototype.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},i.prototype.moveTo=u?i.prototype._transitionTo:i.prototype.goTo,i.prototype.setPosition=function(t,e){this.position.x=parseInt(t,10),this.position.y=parseInt(e,10)},i.prototype._nonTransition=function(t){this.css(t.to),t.isCleaning&&this._removeStyles(t.to),t.onTransitionEnd&&t.onTransitionEnd.call(this)},i.prototype._transition=function(t){var e=this.layout.options.transitionDuration;if(!parseFloat(e))return this._nonTransition(t),void 0;var i=t.to,n=[];for(var o in i)n.push(o);var r={};if(r.transitionProperty=n.join(","),r.transitionDuration=e,this.element.addEventListener(d,this,!1),(t.isCleaning||t.onTransitionEnd)&&this.on("transitionEnd",function(e){return t.isCleaning&&e._removeStyles(i),t.onTransitionEnd&&t.onTransitionEnd.call(e),!0}),t.from){this.css(t.from);var s=this.element.offsetHeight;s=null}this.css(r),this.css(i),this.isTransitioning=!0},i.prototype.transition=i.prototype[h?"_transition":"_nonTransition"],i.prototype.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},i.prototype.onotransitionend=function(t){this.ontransitionend(t)},i.prototype.ontransitionend=function(t){t.target===this.element&&(this.removeTransitionStyles(),this.element.removeEventListener(d,this,!1),this.isTransitioning=!1,this.emitEvent("transitionEnd",[this]))},i.prototype._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var y={transitionProperty:"",transitionDuration:""};i.prototype.removeTransitionStyles=function(){this.css(y)},i.prototype.removeElem=function(){this.element.parentNode.removeChild(this.element),this.emitEvent("remove",[this])},i.prototype.remove=h?function(){var t=this;this.on("transitionEnd",function(){return t.removeElem(),!0}),this.hide()}:i.prototype.removeElem,i.prototype.reveal=function(){this.css({display:""});var t=this.layout.options;this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0})},i.prototype.hide=function(){this.css({display:""});var t=this.layout.options;this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:function(){this.css({display:"none"})}})},i.prototype.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},t.Outlayer={Item:i}}(window),function(t){"use strict";function e(t,e){for(var i in e)t[i]=e[i];return t}function i(t){return"[object Array]"===v.call(t)}function n(t){var e=[];if(i(t))e=t;else if("number"==typeof t.length)for(var n=0,o=t.length;o>n;n++)e.push(t[n]);else e.push(t);return e}function o(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()}function r(t,i){if("string"==typeof t&&(t=l.querySelector(t)),!t||!_(t))return m&&m.error("Bad "+this.settings.namespace+" element: "+t),void 0;this.element=t,this.options=e({},this.options),e(this.options,i);var n=++L;this.element.outlayerGUID=n,E[n]=this,this._create(),this.options.isInitLayout&&this.layout()}function s(t,i){t.prototype[i]=e({},r.prototype[i])}var a=t.Outlayer,h=a.Item,p=t.docReady,u=t.EventEmitter,f=t.eventie,d=t.getSize,c=t.matchesSelector,l=t.document,m=t.console,y=t.jQuery,g=function(){},v=Object.prototype.toString,_="object"==typeof HTMLElement?function(t){return t instanceof HTMLElement}:function(t){return t&&"object"==typeof t&&1===t.nodeType&&"string"==typeof t.nodeName},b=Array.prototype.indexOf?function(t,e){return t.indexOf(e)}:function(t,e){for(var i=0,n=t.length;n>i;i++)if(t[i]===e)return i;return-1},L=0,E={};r.prototype.settings={namespace:"outlayer",item:a.Item},r.prototype.options={containerStyle:{position:"relative"},isInitLayout:!0,isOriginLeft:!0,isOriginTop:!0,isResizeBound:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}},e(r.prototype,u.prototype),r.prototype._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),e(this.element.style,this.options.containerStyle),this.options.isResizeBound&&this.bindResize()},r.prototype.reloadItems=function(){this.items=this._getItems(this.element.children)},r.prototype._getItems=function(t){for(var e=this._filterFindItemElements(t),i=this.settings.item,n=[],o=0,r=e.length;r>o;o++){var s=e[o],a=new i(s,this,this.options.itemOptions);n.push(a)}return n},r.prototype._filterFindItemElements=function(t){t=n(t);var e=this.options.itemSelector;if(!e)return t;for(var i=[],o=0,r=t.length;r>o;o++){var s=t[o];c(s,e)&&i.push(s);for(var a=s.querySelectorAll(e),h=0,p=a.length;p>h;h++)i.push(a[h])}return i},r.prototype.getItemElements=function(){for(var t=[],e=0,i=this.items.length;i>e;e++)t.push(this.items[e].element);return t},r.prototype.layout=function(){this._resetLayout(),this._manageStamps();var t=void 0!==this.options.isLayoutInstant?this.options.isLayoutInstant:!this._isLayoutInited;this.layoutItems(this.items,t),this._isLayoutInited=!0},r.prototype._init=r.prototype.layout,r.prototype._resetLayout=function(){this.getSize()},r.prototype.getSize=function(){this.size=d(this.element)},r.prototype._getMeasurement=function(t,e){var i,n=this.options[t];n?("string"==typeof n?i=this.element.querySelector(n):_(n)&&(i=n),this[t]=i?d(i)[e]:n):this[t]=0},r.prototype.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},r.prototype._getItemsForLayout=function(t){for(var e=[],i=0,n=t.length;n>i;i++){var o=t[i];o.isIgnored||e.push(o)}return e},r.prototype._layoutItems=function(t,e){if(!t||!t.length)return this.emitEvent("layoutComplete",[this,t]),void 0;this._itemsOn(t,"layout",function(){this.emitEvent("layoutComplete",[this,t])});for(var i=[],n=0,o=t.length;o>n;n++){var r=t[n],s=this._getItemLayoutPosition(r);s.item=r,s.isInstant=e,i.push(s)}this._processLayoutQueue(i)},r.prototype._getItemLayoutPosition=function(){return{x:0,y:0}},r.prototype._processLayoutQueue=function(t){for(var e=0,i=t.length;i>e;e++){var n=t[e];this._positionItem(n.item,n.x,n.y,n.isInstant)}},r.prototype._positionItem=function(t,e,i,n){n?t.goTo(e,i):t.moveTo(e,i)},r.prototype._postLayout=function(){var t=this._getContainerSize();t&&(this._setContainerMeasure(t.width,!0),this._setContainerMeasure(t.height,!1))},r.prototype._getContainerSize=g,r.prototype._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},r.prototype._itemsOn=function(t,e,i){function n(){return o++,o===r&&i.call(s),!0}for(var o=0,r=t.length,s=this,a=0,h=t.length;h>a;a++){var p=t[a];p.on(e,n)}},r.prototype.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},r.prototype.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},r.prototype.stamp=function(t){if(t=this._find(t)){this.stamps=this.stamps.concat(t);for(var e=0,i=t.length;i>e;e++){var n=t[e];this.ignore(n)}}},r.prototype.unstamp=function(t){if(t=this._find(t))for(var e=0,i=t.length;i>e;e++){var n=t[e],o=b(this.stamps,n);-1!==o&&this.stamps.splice(o,1),this.unignore(n)}},r.prototype._find=function(t){return t?("string"==typeof t&&(t=this.element.querySelectorAll(t)),t=n(t)):void 0},r.prototype._manageStamps=function(){if(this.stamps&&this.stamps.length){this._getBoundingRect();for(var t=0,e=this.stamps.length;e>t;t++){var i=this.stamps[t];this._manageStamp(i)}}},r.prototype._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},r.prototype._manageStamp=g,r.prototype._getElementOffset=function(t){var e=t.getBoundingClientRect(),i=this._boundingRect,n=d(t),o={left:e.left-i.left-n.marginLeft,top:e.top-i.top-n.marginTop,right:i.right-e.right-n.marginRight,bottom:i.bottom-e.bottom-n.marginBottom};return o},r.prototype.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},r.prototype.bindResize=function(){this.isResizeBound||(f.bind(t,"resize",this),this.isResizeBound=!0)},r.prototype.unbindResize=function(){f.unbind(t,"resize",this),this.isResizeBound=!1},r.prototype.onresize=function(){function t(){e.resize()}this.resizeTimeout&&clearTimeout(this.resizeTimeout);var e=this;this.resizeTimeout=setTimeout(t,100)},r.prototype.resize=function(){var t=d(this.element),e=this.size&&t;e&&t.innerWidth===this.size.innerWidth||(this.layout(),delete this.resizeTimeout)},r.prototype.addItems=function(t){var e=this._getItems(t);if(e.length)return this.items=this.items.concat(e),e},r.prototype.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},r.prototype.prepended=function(t){var e=this._getItems(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},r.prototype.reveal=function(t){if(t&&t.length)for(var e=0,i=t.length;i>e;e++){var n=t[e];n.reveal()}},r.prototype.hide=function(t){if(t&&t.length)for(var e=0,i=t.length;i>e;e++){var n=t[e];n.hide()}},r.prototype.getItem=function(t){for(var e=0,i=this.items.length;i>e;e++){var n=this.items[e];if(n.element===t)return n}},r.prototype.getItems=function(t){if(t&&t.length){for(var e=[],i=0,n=t.length;n>i;i++){var o=t[i],r=this.getItem(o);r&&e.push(r)}return e}},r.prototype.remove=function(t){t=n(t);var e=this.getItems(t);this._itemsOn(e,"remove",function(){this.emitEvent("removeComplete",[this,e])});for(var i=0,o=e.length;o>i;i++){var r=e[i];r.remove();var s=b(this.items,r);this.items.splice(s,1)}},r.prototype.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="";for(var e=0,i=this.items.length;i>e;e++){var n=this.items[e];n.destroy()}this.unbindResize(),delete this.element.outlayerGUID},r.data=function(t){var e=t&&t.outlayerGUID;return e&&E[e]},r.create=function(t,i){function n(){r.apply(this,arguments)}return e(n.prototype,r.prototype),s(n,"options"),s(n,"settings"),e(n.prototype.options,i),n.prototype.settings.namespace=t,n.data=r.data,n.Item=function(){h.apply(this,arguments)},n.Item.prototype=new r.Item,n.prototype.settings.item=n.Item,p(function(){for(var e=o(t),i=l.querySelectorAll(".js-"+e),r="data-"+e+"-options",s=0,a=i.length;a>s;s++){var h,p=i[s],u=p.getAttribute(r);try{h=u&&JSON.parse(u)}catch(f){m&&m.error("Error parsing "+r+" on "+p.nodeName.toLowerCase()+(p.id?"#"+p.id:"")+": "+f);continue}var d=new n(p,h);y&&y.data(p,t,d)}}),y&&y.bridget&&y.bridget(t,n),n},r.Item=h,t.Outlayer=r}(window),function(t){"use strict";function e(t,e){var n=t.create("masonry");return n.prototype._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns();var t=this.cols;for(this.colYs=[];t--;)this.colYs.push(0);this.maxY=0},n.prototype.measureColumns=function(){var t=this.items[0].element;this.columnWidth=this.columnWidth||e(t).outerWidth,this.columnWidth+=this.gutter,this.cols=Math.floor((this.size.innerWidth+this.gutter)/this.columnWidth),this.cols=Math.max(this.cols,1)},n.prototype._getItemLayoutPosition=function(t){t.getSize();var e=Math.ceil(t.size.outerWidth/this.columnWidth);e=Math.min(e,this.cols);for(var n=this._getColGroup(e),o=Math.min.apply(Math,n),r=i(n,o),s={x:this.columnWidth*r,y:o},a=o+t.size.outerHeight,h=this.cols+1-n.length,p=0;h>p;p++)this.colYs[r+p]=a;return s},n.prototype._getColGroup=function(t){if(1===t)return this.colYs;for(var e=[],i=this.cols+1-t,n=0;i>n;n++){var o=this.colYs.slice(n,n+t);e[n]=Math.max.apply(Math,o)}return e},n.prototype._manageStamp=function(t){var i=e(t),n=this._getElementOffset(t),o=this.options.isOriginLeft?n.left:n.right,r=o+i.outerWidth,s=Math.floor(o/this.columnWidth);s=Math.max(0,s);var a=Math.floor(r/this.columnWidth);a=Math.min(this.cols-1,a);for(var h=(this.options.isOriginTop?n.top:n.bottom)+i.outerHeight,p=s;a>=p;p++)this.colYs[p]=Math.max(h,this.colYs[p])},n.prototype._getContainerSize=function(){return this.maxY=Math.max.apply(Math,this.colYs),{height:this.maxY}},n}var i=Array.prototype.indexOf?function(t,e){return t.indexOf(e)}:function(t,e){for(var i=0,n=t.length;n>i;i++){var o=t[i];if(o===e)return i}return-1};"function"==typeof define&&define.amd?define(["outlayer","get-size"],e):t.Masonry=e(t.Outlayer,t.getSize)}(window);










/////////////////////////////////////////////
// TOUCHSWIPE PLUGIN
/////////////////////////////////////////////

/*
* @fileOverview TouchSwipe - jQuery Plugin
* @version 1.6.5
*
* @author Matt Bryson http://www.github.com/mattbryson
* @see https://github.com/mattbryson/TouchSwipe-Jquery-Plugin
* @see http://labs.skinkers.com/touchSwipe/
* @see http://plugins.jquery.com/project/touchSwipe
*
* Copyright (c) 2010 Matt Bryson
* Dual licensed under the MIT or GPL Version 2 licenses.
*
*/

(function(a){if(typeof define==="function"&&define.amd&&define.amd.jQuery){define(["jquery"],a)}else{a(jQuery)}}(function(e){var o="left",n="right",d="up",v="down",c="in",w="out",l="none",r="auto",k="swipe",s="pinch",x="tap",i="doubletap",b="longtap",A="horizontal",t="vertical",h="all",q=10,f="start",j="move",g="end",p="cancel",a="ontouchstart" in window,y="TouchSwipe";var m={fingers:1,threshold:75,cancelThreshold:null,pinchThreshold:20,maxTimeThreshold:null,fingerReleaseThreshold:250,longTapThreshold:500,doubleTapThreshold:200,swipe:null,swipeLeft:null,swipeRight:null,swipeUp:null,swipeDown:null,swipeStatus:null,pinchIn:null,pinchOut:null,pinchStatus:null,click:null,tap:null,doubleTap:null,longTap:null,triggerOnTouchEnd:true,triggerOnTouchLeave:false,allowPageScroll:"auto",fallbackToMouseEvents:true,excludedElements:"label, button, input, select, textarea, .noSwipe"};e.fn.swipe=function(D){var C=e(this),B=C.data(y);if(B&&typeof D==="string"){if(B[D]){return B[D].apply(this,Array.prototype.slice.call(arguments,1))}else{e.error("Method "+D+" does not exist on jQuery.swipe")}}else{if(!B&&(typeof D==="object"||!D)){return u.apply(this,arguments)}}return C};e.fn.swipe.defaults=m;e.fn.swipe.phases={PHASE_START:f,PHASE_MOVE:j,PHASE_END:g,PHASE_CANCEL:p};e.fn.swipe.directions={LEFT:o,RIGHT:n,UP:d,DOWN:v,IN:c,OUT:w};e.fn.swipe.pageScroll={NONE:l,HORIZONTAL:A,VERTICAL:t,AUTO:r};e.fn.swipe.fingers={ONE:1,TWO:2,THREE:3,ALL:h};function u(B){if(B&&(B.allowPageScroll===undefined&&(B.swipe!==undefined||B.swipeStatus!==undefined))){B.allowPageScroll=l}if(B.click!==undefined&&B.tap===undefined){B.tap=B.click}if(!B){B={}}B=e.extend({},e.fn.swipe.defaults,B);return this.each(function(){var D=e(this);var C=D.data(y);if(!C){C=new z(this,B);D.data(y,C)}})}function z(a0,aq){var av=(a||!aq.fallbackToMouseEvents),G=av?"touchstart":"mousedown",au=av?"touchmove":"mousemove",R=av?"touchend":"mouseup",P=av?null:"mouseleave",az="touchcancel";var ac=0,aL=null,Y=0,aX=0,aV=0,D=1,am=0,aF=0,J=null;var aN=e(a0);var W="start";var T=0;var aM=null;var Q=0,aY=0,a1=0,aa=0,K=0;var aS=null;try{aN.bind(G,aJ);aN.bind(az,a5)}catch(ag){e.error("events not supported "+G+","+az+" on jQuery.swipe")}this.enable=function(){aN.bind(G,aJ);aN.bind(az,a5);return aN};this.disable=function(){aG();return aN};this.destroy=function(){aG();aN.data(y,null);return aN};this.option=function(a8,a7){if(aq[a8]!==undefined){if(a7===undefined){return aq[a8]}else{aq[a8]=a7}}else{e.error("Option "+a8+" does not exist on jQuery.swipe.options")}return null};function aJ(a9){if(ax()){return}if(e(a9.target).closest(aq.excludedElements,aN).length>0){return}var ba=a9.originalEvent?a9.originalEvent:a9;var a8,a7=a?ba.touches[0]:ba;W=f;if(a){T=ba.touches.length}else{a9.preventDefault()}ac=0;aL=null;aF=null;Y=0;aX=0;aV=0;D=1;am=0;aM=af();J=X();O();if(!a||(T===aq.fingers||aq.fingers===h)||aT()){ae(0,a7);Q=ao();if(T==2){ae(1,ba.touches[1]);aX=aV=ap(aM[0].start,aM[1].start)}if(aq.swipeStatus||aq.pinchStatus){a8=L(ba,W)}}else{a8=false}if(a8===false){W=p;L(ba,W);return a8}else{ak(true)}return null}function aZ(ba){var bd=ba.originalEvent?ba.originalEvent:ba;if(W===g||W===p||ai()){return}var a9,a8=a?bd.touches[0]:bd;var bb=aD(a8);aY=ao();if(a){T=bd.touches.length}W=j;if(T==2){if(aX==0){ae(1,bd.touches[1]);aX=aV=ap(aM[0].start,aM[1].start)}else{aD(bd.touches[1]);aV=ap(aM[0].end,aM[1].end);aF=an(aM[0].end,aM[1].end)}D=a3(aX,aV);am=Math.abs(aX-aV)}if((T===aq.fingers||aq.fingers===h)||!a||aT()){aL=aH(bb.start,bb.end);ah(ba,aL);ac=aO(bb.start,bb.end);Y=aI();aE(aL,ac);if(aq.swipeStatus||aq.pinchStatus){a9=L(bd,W)}if(!aq.triggerOnTouchEnd||aq.triggerOnTouchLeave){var a7=true;if(aq.triggerOnTouchLeave){var bc=aU(this);a7=B(bb.end,bc)}if(!aq.triggerOnTouchEnd&&a7){W=ay(j)}else{if(aq.triggerOnTouchLeave&&!a7){W=ay(g)}}if(W==p||W==g){L(bd,W)}}}else{W=p;L(bd,W)}if(a9===false){W=p;L(bd,W)}}function I(a7){var a8=a7.originalEvent;if(a){if(a8.touches.length>0){C();return true}}if(ai()){T=aa}a7.preventDefault();aY=ao();Y=aI();if(a6()){W=p;L(a8,W)}else{if(aq.triggerOnTouchEnd||(aq.triggerOnTouchEnd==false&&W===j)){W=g;L(a8,W)}else{if(!aq.triggerOnTouchEnd&&a2()){W=g;aB(a8,W,x)}else{if(W===j){W=p;L(a8,W)}}}}ak(false);return null}function a5(){T=0;aY=0;Q=0;aX=0;aV=0;D=1;O();ak(false)}function H(a7){var a8=a7.originalEvent;if(aq.triggerOnTouchLeave){W=ay(g);L(a8,W)}}function aG(){aN.unbind(G,aJ);aN.unbind(az,a5);aN.unbind(au,aZ);aN.unbind(R,I);if(P){aN.unbind(P,H)}ak(false)}function ay(bb){var ba=bb;var a9=aw();var a8=aj();var a7=a6();if(!a9||a7){ba=p}else{if(a8&&bb==j&&(!aq.triggerOnTouchEnd||aq.triggerOnTouchLeave)){ba=g}else{if(!a8&&bb==g&&aq.triggerOnTouchLeave){ba=p}}}return ba}function L(a9,a7){var a8=undefined;if(F()||S()){a8=aB(a9,a7,k)}else{if((M()||aT())&&a8!==false){a8=aB(a9,a7,s)}}if(aC()&&a8!==false){a8=aB(a9,a7,i)}else{if(al()&&a8!==false){a8=aB(a9,a7,b)}else{if(ad()&&a8!==false){a8=aB(a9,a7,x)}}}if(a7===p){a5(a9)}if(a7===g){if(a){if(a9.touches.length==0){a5(a9)}}else{a5(a9)}}return a8}function aB(ba,a7,a9){var a8=undefined;if(a9==k){aN.trigger("swipeStatus",[a7,aL||null,ac||0,Y||0,T]);if(aq.swipeStatus){a8=aq.swipeStatus.call(aN,ba,a7,aL||null,ac||0,Y||0,T);if(a8===false){return false}}if(a7==g&&aR()){aN.trigger("swipe",[aL,ac,Y,T]);if(aq.swipe){a8=aq.swipe.call(aN,ba,aL,ac,Y,T);if(a8===false){return false}}switch(aL){case o:aN.trigger("swipeLeft",[aL,ac,Y,T]);if(aq.swipeLeft){a8=aq.swipeLeft.call(aN,ba,aL,ac,Y,T)}break;case n:aN.trigger("swipeRight",[aL,ac,Y,T]);if(aq.swipeRight){a8=aq.swipeRight.call(aN,ba,aL,ac,Y,T)}break;case d:aN.trigger("swipeUp",[aL,ac,Y,T]);if(aq.swipeUp){a8=aq.swipeUp.call(aN,ba,aL,ac,Y,T)}break;case v:aN.trigger("swipeDown",[aL,ac,Y,T]);if(aq.swipeDown){a8=aq.swipeDown.call(aN,ba,aL,ac,Y,T)}break}}}if(a9==s){aN.trigger("pinchStatus",[a7,aF||null,am||0,Y||0,T,D]);if(aq.pinchStatus){a8=aq.pinchStatus.call(aN,ba,a7,aF||null,am||0,Y||0,T,D);if(a8===false){return false}}if(a7==g&&a4()){switch(aF){case c:aN.trigger("pinchIn",[aF||null,am||0,Y||0,T,D]);if(aq.pinchIn){a8=aq.pinchIn.call(aN,ba,aF||null,am||0,Y||0,T,D)}break;case w:aN.trigger("pinchOut",[aF||null,am||0,Y||0,T,D]);if(aq.pinchOut){a8=aq.pinchOut.call(aN,ba,aF||null,am||0,Y||0,T,D)}break}}}if(a9==x){if(a7===p||a7===g){clearTimeout(aS);if(V()&&!E()){K=ao();aS=setTimeout(e.proxy(function(){K=null;aN.trigger("tap",[ba.target]);if(aq.tap){a8=aq.tap.call(aN,ba,ba.target)}},this),aq.doubleTapThreshold)}else{K=null;aN.trigger("tap",[ba.target]);if(aq.tap){a8=aq.tap.call(aN,ba,ba.target)}}}}else{if(a9==i){if(a7===p||a7===g){clearTimeout(aS);K=null;aN.trigger("doubletap",[ba.target]);if(aq.doubleTap){a8=aq.doubleTap.call(aN,ba,ba.target)}}}else{if(a9==b){if(a7===p||a7===g){clearTimeout(aS);K=null;aN.trigger("longtap",[ba.target]);if(aq.longTap){a8=aq.longTap.call(aN,ba,ba.target)}}}}}return a8}function aj(){var a7=true;if(aq.threshold!==null){a7=ac>=aq.threshold}return a7}function a6(){var a7=false;if(aq.cancelThreshold!==null&&aL!==null){a7=(aP(aL)-ac)>=aq.cancelThreshold}return a7}function ab(){if(aq.pinchThreshold!==null){return am>=aq.pinchThreshold}return true}function aw(){var a7;if(aq.maxTimeThreshold){if(Y>=aq.maxTimeThreshold){a7=false}else{a7=true}}else{a7=true}return a7}function ah(a7,a8){if(aq.allowPageScroll===l||aT()){a7.preventDefault()}else{var a9=aq.allowPageScroll===r;switch(a8){case o:if((aq.swipeLeft&&a9)||(!a9&&aq.allowPageScroll!=A)){a7.preventDefault()}break;case n:if((aq.swipeRight&&a9)||(!a9&&aq.allowPageScroll!=A)){a7.preventDefault()}break;case d:if((aq.swipeUp&&a9)||(!a9&&aq.allowPageScroll!=t)){a7.preventDefault()}break;case v:if((aq.swipeDown&&a9)||(!a9&&aq.allowPageScroll!=t)){a7.preventDefault()}break}}}function a4(){var a8=aK();var a7=U();var a9=ab();return a8&&a7&&a9}function aT(){return !!(aq.pinchStatus||aq.pinchIn||aq.pinchOut)}function M(){return !!(a4()&&aT())}function aR(){var ba=aw();var bc=aj();var a9=aK();var a7=U();var a8=a6();var bb=!a8&&a7&&a9&&bc&&ba;return bb}function S(){return !!(aq.swipe||aq.swipeStatus||aq.swipeLeft||aq.swipeRight||aq.swipeUp||aq.swipeDown)}function F(){return !!(aR()&&S())}function aK(){return((T===aq.fingers||aq.fingers===h)||!a)}function U(){return aM[0].end.x!==0}function a2(){return !!(aq.tap)}function V(){return !!(aq.doubleTap)}function aQ(){return !!(aq.longTap)}function N(){if(K==null){return false}var a7=ao();return(V()&&((a7-K)<=aq.doubleTapThreshold))}function E(){return N()}function at(){return((T===1||!a)&&(isNaN(ac)||ac===0))}function aW(){return((Y>aq.longTapThreshold)&&(ac<q))}function ad(){return !!(at()&&a2())}function aC(){return !!(N()&&V())}function al(){return !!(aW()&&aQ())}function C(){a1=ao();aa=event.touches.length+1}function O(){a1=0;aa=0}function ai(){var a7=false;if(a1){var a8=ao()-a1;if(a8<=aq.fingerReleaseThreshold){a7=true}}return a7}function ax(){return !!(aN.data(y+"_intouch")===true)}function ak(a7){if(a7===true){aN.bind(au,aZ);aN.bind(R,I);if(P){aN.bind(P,H)}}else{aN.unbind(au,aZ,false);aN.unbind(R,I,false);if(P){aN.unbind(P,H,false)}}aN.data(y+"_intouch",a7===true)}function ae(a8,a7){var a9=a7.identifier!==undefined?a7.identifier:0;aM[a8].identifier=a9;aM[a8].start.x=aM[a8].end.x=a7.pageX||a7.clientX;aM[a8].start.y=aM[a8].end.y=a7.pageY||a7.clientY;return aM[a8]}function aD(a7){var a9=a7.identifier!==undefined?a7.identifier:0;var a8=Z(a9);a8.end.x=a7.pageX||a7.clientX;a8.end.y=a7.pageY||a7.clientY;return a8}function Z(a8){for(var a7=0;a7<aM.length;a7++){if(aM[a7].identifier==a8){return aM[a7]}}}function af(){var a7=[];for(var a8=0;a8<=5;a8++){a7.push({start:{x:0,y:0},end:{x:0,y:0},identifier:0})}return a7}function aE(a7,a8){a8=Math.max(a8,aP(a7));J[a7].distance=a8}function aP(a7){if(J[a7]){return J[a7].distance}return undefined}function X(){var a7={};a7[o]=ar(o);a7[n]=ar(n);a7[d]=ar(d);a7[v]=ar(v);return a7}function ar(a7){return{direction:a7,distance:0}}function aI(){return aY-Q}function ap(ba,a9){var a8=Math.abs(ba.x-a9.x);var a7=Math.abs(ba.y-a9.y);return Math.round(Math.sqrt(a8*a8+a7*a7))}function a3(a7,a8){var a9=(a8/a7)*1;return a9.toFixed(2)}function an(){if(D<1){return w}else{return c}}function aO(a8,a7){return Math.round(Math.sqrt(Math.pow(a7.x-a8.x,2)+Math.pow(a7.y-a8.y,2)))}function aA(ba,a8){var a7=ba.x-a8.x;var bc=a8.y-ba.y;var a9=Math.atan2(bc,a7);var bb=Math.round(a9*180/Math.PI);if(bb<0){bb=360-Math.abs(bb)}return bb}function aH(a8,a7){var a9=aA(a8,a7);if((a9<=45)&&(a9>=0)){return o}else{if((a9<=360)&&(a9>=315)){return o}else{if((a9>=135)&&(a9<=225)){return n}else{if((a9>45)&&(a9<135)){return v}else{return d}}}}}function ao(){var a7=new Date();return a7.getTime()}function aU(a7){a7=e(a7);var a9=a7.offset();var a8={left:a9.left,right:a9.left+a7.outerWidth(),top:a9.top,bottom:a9.top+a7.outerHeight()};return a8}function B(a7,a8){return(a7.x>a8.left&&a7.x<a8.right&&a7.y>a8.top&&a7.y<a8.bottom)}}}));












/////////////////////////////////////////////
// APPEAR PLUGIN
/////////////////////////////////////////////

/*
 * jQuery.appear
 * https://github.com/bas2k/jquery.appear/
 * http://code.google.com/p/jquery-appear/
 *
 * Copyright (c) 2009 Michael Hixson
 * Copyright (c) 2012 Alexander Brovikov
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php)
 */
(function($) {
    $.fn.appear = function(fn, options) {

        var settings = $.extend({

            //arbitrary data to pass to fn
            data: undefined,

            //call fn only on the first appear?
            one: true,

            // X & Y accuracy
            accX: 0,
            accY: 0

        }, options);

        return this.each(function() {

            var t = $(this);

            //whether the element is currently visible
            t.appeared = false;

            if (!fn) {

                //trigger the custom event
                t.trigger('appear', settings.data);
                return;
            }

            var w = $(window);

            //fires the appear event when appropriate
            var check = function() {

                //is the element hidden?
                if (!t.is(':visible')) {

                    //it became hidden
                    t.appeared = false;
                    return;
                }

                //is the element inside the visible window?
                var a = w.scrollLeft();
                var b = w.scrollTop();
                var o = t.offset();
                var x = o.left;
                var y = o.top;

                var ax = settings.accX;
                var ay = settings.accY;
                var th = t.height();
                var wh = w.height();
                var tw = t.width();
                var ww = w.width();

                if (y + th + ay >= b &&
                    y <= b + wh + ay &&
                    x + tw + ax >= a &&
                    x <= a + ww + ax) {

                    //trigger the custom event
                    if (!t.appeared) t.trigger('appear', settings.data);

                } else {

                    //it scrolled out of view
                    t.appeared = false;
                }
            };

            //create a modified fn with some additional logic
            var modifiedFn = function() {

                //mark the element as visible
                t.appeared = true;

                //is this supposed to happen only once?
                if (settings.one) {

                    //remove the check
                    w.unbind('scroll', check);
                    var i = $.inArray(check, $.fn.appear.checks);
                    if (i >= 0) $.fn.appear.checks.splice(i, 1);
                }

                //trigger the original fn
                fn.apply(this, arguments);
            };

            //bind the modified fn to the element
            if (settings.one) t.one('appear', settings.data, modifiedFn);
            else t.bind('appear', settings.data, modifiedFn);

            //check whenever the window scrolls
            w.scroll(check);

            //check whenever the dom changes
            $.fn.appear.checks.push(check);

            //check now
            (check)();
        });
    };

    //keep a queue of appearance checks
    $.extend($.fn.appear, {

        checks: [],
        timeout: null,

        //process the queue
        checkAll: function() {
            var length = $.fn.appear.checks.length;
            //try {
                if (length > 0) while (length--) ($.fn.appear.checks[length])();
            //} catch (e) { } 
        },

        //check the queue asynchronously
        run: function() {
            if ($.fn.appear.timeout) clearTimeout($.fn.appear.timeout);
            $.fn.appear.timeout = setTimeout($.fn.appear.checkAll, 20);
        }
    });

    //run checks when these methods are called
    $.each(['append', 'prepend', 'after', 'before', 'attr',
        'removeAttr', 'addClass', 'removeClass', 'toggleClass',
        'remove', 'css', 'show', 'hide'], function(i, n) {
        var old = $.fn[n];
        if (old) {
            $.fn[n] = function() {
                var r = old.apply(this, arguments);
                $.fn.appear.run();
                return r;
            }
        }
    });

})(jQuery);










/////////////////////////////////////////////
// STELLAR.JS PLUGIN
/////////////////////////////////////////////

/*! Stellar.js v0.6.2 | Copyright 2013, Mark Dalgleish | http://markdalgleish.com/projects/stellar.js | http://markdalgleish.mit-license.org */
(function(e,t,n,r){function d(t,n){this.element=t,this.options=e.extend({},s,n),this._defaults=s,this._name=i,this.init()}var i="stellar",s={scrollProperty:"scroll",positionProperty:"position",horizontalScrolling:!0,verticalScrolling:!0,horizontalOffset:0,verticalOffset:0,responsive:!1,parallaxBackgrounds:!0,parallaxElements:!0,hideDistantElements:!0,hideElement:function(e){e.hide()},showElement:function(e){e.show()}},o={scroll:{getLeft:function(e){return e.scrollLeft()},setLeft:function(e,t){e.scrollLeft(t)},getTop:function(e){return e.scrollTop()},setTop:function(e,t){e.scrollTop(t)}},position:{getLeft:function(e){return parseInt(e.css("left"),10)*-1},getTop:function(e){return parseInt(e.css("top"),10)*-1}},margin:{getLeft:function(e){return parseInt(e.css("margin-left"),10)*-1},getTop:function(e){return parseInt(e.css("margin-top"),10)*-1}},transform:{getLeft:function(e){var t=getComputedStyle(e[0])[f];return t!=="none"?parseInt(t.match(/(-?[0-9]+)/g)[4],10)*-1:0},getTop:function(e){var t=getComputedStyle(e[0])[f];return t!=="none"?parseInt(t.match(/(-?[0-9]+)/g)[5],10)*-1:0}}},u={position:{setLeft:function(e,t){e.css("left",t)},setTop:function(e,t){e.css("top",t)}},transform:{setPosition:function(e,t,n,r,i){e[0].style[f]="translate3d("+(t-n)+"px, "+(r-i)+"px, 0)"}}},a=function(){var t=/^(Moz|Webkit|Khtml|O|ms|Icab)(?=[A-Z])/,n=e("script")[0].style,r="",i;for(i in n)if(t.test(i)){r=i.match(t)[0];break}return"WebkitOpacity"in n&&(r="Webkit"),"KhtmlOpacity"in n&&(r="Khtml"),function(e){return r+(r.length>0?e.charAt(0).toUpperCase()+e.slice(1):e)}}(),f=a("transform"),l=e("<div />",{style:"background:#fff"}).css("background-position-x")!==r,c=l?function(e,t,n){e.css({"background-position-x":t,"background-position-y":n})}:function(e,t,n){e.css("background-position",t+" "+n)},h=l?function(e){return[e.css("background-position-x"),e.css("background-position-y")]}:function(e){return e.css("background-position").split(" ")},p=t.requestAnimationFrame||t.webkitRequestAnimationFrame||t.mozRequestAnimationFrame||t.oRequestAnimationFrame||t.msRequestAnimationFrame||function(e){setTimeout(e,1e3/60)};d.prototype={init:function(){this.options.name=i+"_"+Math.floor(Math.random()*1e9),this._defineElements(),this._defineGetters(),this._defineSetters(),this._handleWindowLoadAndResize(),this._detectViewport(),this.refresh({firstLoad:!0}),this.options.scrollProperty==="scroll"?this._handleScrollEvent():this._startAnimationLoop()},_defineElements:function(){this.element===n.body&&(this.element=t),this.$scrollElement=e(this.element),this.$element=this.element===t?e("body"):this.$scrollElement,this.$viewportElement=this.options.viewportElement!==r?e(this.options.viewportElement):this.$scrollElement[0]===t||this.options.scrollProperty==="scroll"?this.$scrollElement:this.$scrollElement.parent()},_defineGetters:function(){var e=this,t=o[e.options.scrollProperty];this._getScrollLeft=function(){return t.getLeft(e.$scrollElement)},this._getScrollTop=function(){return t.getTop(e.$scrollElement)}},_defineSetters:function(){var t=this,n=o[t.options.scrollProperty],r=u[t.options.positionProperty],i=n.setLeft,s=n.setTop;this._setScrollLeft=typeof i=="function"?function(e){i(t.$scrollElement,e)}:e.noop,this._setScrollTop=typeof s=="function"?function(e){s(t.$scrollElement,e)}:e.noop,this._setPosition=r.setPosition||function(e,n,i,s,o){t.options.horizontalScrolling&&r.setLeft(e,n,i),t.options.verticalScrolling&&r.setTop(e,s,o)}},_handleWindowLoadAndResize:function(){var n=this,r=e(t);n.options.responsive&&r.bind("load."+this.name,function(){n.refresh()}),r.bind("resize."+this.name,function(){n._detectViewport(),n.options.responsive&&n.refresh()})},refresh:function(n){var r=this,i=r._getScrollLeft(),s=r._getScrollTop();(!n||!n.firstLoad)&&this._reset(),this._setScrollLeft(0),this._setScrollTop(0),this._setOffsets(),this._findParticles(),this._findBackgrounds(),n&&n.firstLoad&&/WebKit/.test(navigator.userAgent)&&e(t).load(function(){var e=r._getScrollLeft(),t=r._getScrollTop();r._setScrollLeft(e+1),r._setScrollTop(t+1),r._setScrollLeft(e),r._setScrollTop(t)}),this._setScrollLeft(i),this._setScrollTop(s)},_detectViewport:function(){var e=this.$viewportElement.offset(),t=e!==null&&e!==r;this.viewportWidth=this.$viewportElement.width(),this.viewportHeight=this.$viewportElement.height(),this.viewportOffsetTop=t?e.top:0,this.viewportOffsetLeft=t?e.left:0},_findParticles:function(){var t=this,n=this._getScrollLeft(),i=this._getScrollTop();if(this.particles!==r)for(var s=this.particles.length-1;s>=0;s--)this.particles[s].$element.data("stellar-elementIsActive",r);this.particles=[];if(!this.options.parallaxElements)return;this.$element.find("[data-stellar-ratio]").each(function(n){var i=e(this),s,o,u,a,f,l,c,h,p,d=0,v=0,m=0,g=0;if(!i.data("stellar-elementIsActive"))i.data("stellar-elementIsActive",this);else if(i.data("stellar-elementIsActive")!==this)return;t.options.showElement(i),i.data("stellar-startingLeft")?(i.css("left",i.data("stellar-startingLeft")),i.css("top",i.data("stellar-startingTop"))):(i.data("stellar-startingLeft",i.css("left")),i.data("stellar-startingTop",i.css("top"))),u=i.position().left,a=i.position().top,f=i.css("margin-left")==="auto"?0:parseInt(i.css("margin-left"),10),l=i.css("margin-top")==="auto"?0:parseInt(i.css("margin-top"),10),h=i.offset().left-f,p=i.offset().top-l,i.parents().each(function(){var t=e(this);if(t.data("stellar-offset-parent")===!0)return d=m,v=g,c=t,!1;m+=t.position().left,g+=t.position().top}),s=i.data("stellar-horizontal-offset")!==r?i.data("stellar-horizontal-offset"):c!==r&&c.data("stellar-horizontal-offset")!==r?c.data("stellar-horizontal-offset"):t.horizontalOffset,o=i.data("stellar-vertical-offset")!==r?i.data("stellar-vertical-offset"):c!==r&&c.data("stellar-vertical-offset")!==r?c.data("stellar-vertical-offset"):t.verticalOffset,t.particles.push({$element:i,$offsetParent:c,isFixed:i.css("position")==="fixed",horizontalOffset:s,verticalOffset:o,startingPositionLeft:u,startingPositionTop:a,startingOffsetLeft:h,startingOffsetTop:p,parentOffsetLeft:d,parentOffsetTop:v,stellarRatio:i.data("stellar-ratio")!==r?i.data("stellar-ratio"):1,width:i.outerWidth(!0),height:i.outerHeight(!0),isHidden:!1})})},_findBackgrounds:function(){var t=this,n=this._getScrollLeft(),i=this._getScrollTop(),s;this.backgrounds=[];if(!this.options.parallaxBackgrounds)return;s=this.$element.find("[data-stellar-background-ratio]"),this.$element.data("stellar-background-ratio")&&(s=s.add(this.$element)),s.each(function(){var s=e(this),o=h(s),u,a,f,l,p,d,v,m,g,y=0,b=0,w=0,E=0;if(!s.data("stellar-backgroundIsActive"))s.data("stellar-backgroundIsActive",this);else if(s.data("stellar-backgroundIsActive")!==this)return;s.data("stellar-backgroundStartingLeft")?c(s,s.data("stellar-backgroundStartingLeft"),s.data("stellar-backgroundStartingTop")):(s.data("stellar-backgroundStartingLeft",o[0]),s.data("stellar-backgroundStartingTop",o[1])),p=s.css("margin-left")==="auto"?0:parseInt(s.css("margin-left"),10),d=s.css("margin-top")==="auto"?0:parseInt(s.css("margin-top"),10),v=s.offset().left-p-n,m=s.offset().top-d-i,s.parents().each(function(){var t=e(this);if(t.data("stellar-offset-parent")===!0)return y=w,b=E,g=t,!1;w+=t.position().left,E+=t.position().top}),u=s.data("stellar-horizontal-offset")!==r?s.data("stellar-horizontal-offset"):g!==r&&g.data("stellar-horizontal-offset")!==r?g.data("stellar-horizontal-offset"):t.horizontalOffset,a=s.data("stellar-vertical-offset")!==r?s.data("stellar-vertical-offset"):g!==r&&g.data("stellar-vertical-offset")!==r?g.data("stellar-vertical-offset"):t.verticalOffset,t.backgrounds.push({$element:s,$offsetParent:g,isFixed:s.css("background-attachment")==="fixed",horizontalOffset:u,verticalOffset:a,startingValueLeft:o[0],startingValueTop:o[1],startingBackgroundPositionLeft:isNaN(parseInt(o[0],10))?0:parseInt(o[0],10),startingBackgroundPositionTop:isNaN(parseInt(o[1],10))?0:parseInt(o[1],10),startingPositionLeft:s.position().left,startingPositionTop:s.position().top,startingOffsetLeft:v,startingOffsetTop:m,parentOffsetLeft:y,parentOffsetTop:b,stellarRatio:s.data("stellar-background-ratio")===r?1:s.data("stellar-background-ratio")})})},_reset:function(){var e,t,n,r,i;for(i=this.particles.length-1;i>=0;i--)e=this.particles[i],t=e.$element.data("stellar-startingLeft"),n=e.$element.data("stellar-startingTop"),this._setPosition(e.$element,t,t,n,n),this.options.showElement(e.$element),e.$element.data("stellar-startingLeft",null).data("stellar-elementIsActive",null).data("stellar-backgroundIsActive",null);for(i=this.backgrounds.length-1;i>=0;i--)r=this.backgrounds[i],r.$element.data("stellar-backgroundStartingLeft",null).data("stellar-backgroundStartingTop",null),c(r.$element,r.startingValueLeft,r.startingValueTop)},destroy:function(){this._reset(),this.$scrollElement.unbind("resize."+this.name).unbind("scroll."+this.name),this._animationLoop=e.noop,e(t).unbind("load."+this.name).unbind("resize."+this.name)},_setOffsets:function(){var n=this,r=e(t);r.unbind("resize.horizontal-"+this.name).unbind("resize.vertical-"+this.name),typeof this.options.horizontalOffset=="function"?(this.horizontalOffset=this.options.horizontalOffset(),r.bind("resize.horizontal-"+this.name,function(){n.horizontalOffset=n.options.horizontalOffset()})):this.horizontalOffset=this.options.horizontalOffset,typeof this.options.verticalOffset=="function"?(this.verticalOffset=this.options.verticalOffset(),r.bind("resize.vertical-"+this.name,function(){n.verticalOffset=n.options.verticalOffset()})):this.verticalOffset=this.options.verticalOffset},_repositionElements:function(){var e=this._getScrollLeft(),t=this._getScrollTop(),n,r,i,s,o,u,a,f=!0,l=!0,h,p,d,v,m;if(this.currentScrollLeft===e&&this.currentScrollTop===t&&this.currentWidth===this.viewportWidth&&this.currentHeight===this.viewportHeight)return;this.currentScrollLeft=e,this.currentScrollTop=t,this.currentWidth=this.viewportWidth,this.currentHeight=this.viewportHeight;for(m=this.particles.length-1;m>=0;m--)i=this.particles[m],s=i.isFixed?1:0,this.options.horizontalScrolling?(h=(e+i.horizontalOffset+this.viewportOffsetLeft+i.startingPositionLeft-i.startingOffsetLeft+i.parentOffsetLeft)*-(i.stellarRatio+s-1)+i.startingPositionLeft,d=h-i.startingPositionLeft+i.startingOffsetLeft):(h=i.startingPositionLeft,d=i.startingOffsetLeft),this.options.verticalScrolling?(p=(t+i.verticalOffset+this.viewportOffsetTop+i.startingPositionTop-i.startingOffsetTop+i.parentOffsetTop)*-(i.stellarRatio+s-1)+i.startingPositionTop,v=p-i.startingPositionTop+i.startingOffsetTop):(p=i.startingPositionTop,v=i.startingOffsetTop),this.options.hideDistantElements&&(l=!this.options.horizontalScrolling||d+i.width>(i.isFixed?0:e)&&d<(i.isFixed?0:e)+this.viewportWidth+this.viewportOffsetLeft,f=!this.options.verticalScrolling||v+i.height>(i.isFixed?0:t)&&v<(i.isFixed?0:t)+this.viewportHeight+this.viewportOffsetTop),l&&f?(i.isHidden&&(this.options.showElement(i.$element),i.isHidden=!1),this._setPosition(i.$element,h,i.startingPositionLeft,p,i.startingPositionTop)):i.isHidden||(this.options.hideElement(i.$element),i.isHidden=!0);for(m=this.backgrounds.length-1;m>=0;m--)o=this.backgrounds[m],s=o.isFixed?0:1,u=this.options.horizontalScrolling?(e+o.horizontalOffset-this.viewportOffsetLeft-o.startingOffsetLeft+o.parentOffsetLeft-o.startingBackgroundPositionLeft)*(s-o.stellarRatio)+"px":o.startingValueLeft,a=this.options.verticalScrolling?(t+o.verticalOffset-this.viewportOffsetTop-o.startingOffsetTop+o.parentOffsetTop-o.startingBackgroundPositionTop)*(s-o.stellarRatio)+"px":o.startingValueTop,c(o.$element,u,a)},_handleScrollEvent:function(){var e=this,t=!1,n=function(){e._repositionElements(),t=!1},r=function(){t||(p(n),t=!0)};this.$scrollElement.bind("scroll."+this.name,r),r()},_startAnimationLoop:function(){var e=this;this._animationLoop=function(){p(e._animationLoop),e._repositionElements()},this._animationLoop()}},e.fn[i]=function(t){var n=arguments;if(t===r||typeof t=="object")return this.each(function(){e.data(this,"plugin_"+i)||e.data(this,"plugin_"+i,new d(this,t))});if(typeof t=="string"&&t[0]!=="_"&&t!=="init")return this.each(function(){var r=e.data(this,"plugin_"+i);r instanceof d&&typeof r[t]=="function"&&r[t].apply(r,Array.prototype.slice.call(n,1)),t==="destroy"&&e.data(this,"plugin_"+i,null)})},e[i]=function(n){var r=e(t);return r.stellar.apply(r,Array.prototype.slice.call(arguments,0))},e[i].scrollProperty=o,e[i].positionProperty=u,t.Stellar=d})(jQuery,this,document);













/////////////////////////////////////////////
// COUNTTO PLUGIN
/////////////////////////////////////////////

(function ($) {
	$.fn.countTo = function (options) {
		options = options || {};

		return $(this).each(function () {
			// set options for current element
			var settings = $.extend({}, $.fn.countTo.defaults, {
				from:            $(this).data('from'),
				to:              $(this).data('to'),
				speed:           $(this).data('speed'),
				refreshInterval: $(this).data('refresh-interval'),
				decimals:        $(this).data('decimals')
			}, options);

			// how many times to update the value, and how much to increment the value on each update
			var loops = Math.ceil(settings.speed / settings.refreshInterval),
				increment = (settings.to - settings.from) / loops;

			// references & variables that will change with each update
			var self = this,
				$self = $(this),
				loopCount = 0,
				value = settings.from,
				data = $self.data('countTo') || {};

			$self.data('countTo', data);

			// if an existing interval can be found, clear it first
			if (data.interval) {
				clearInterval(data.interval);
			}
			data.interval = setInterval(updateTimer, settings.refreshInterval);

			// initialize the element with the starting value
			render(value);

			function updateTimer() {
				value += increment;
				loopCount++;

				render(value);

				if (typeof(settings.onUpdate) == 'function') {
					settings.onUpdate.call(self, value);
				}

				if (loopCount >= loops) {
					// remove the interval
					$self.removeData('countTo');
					clearInterval(data.interval);
					value = settings.to;

					if (typeof(settings.onComplete) == 'function') {
						settings.onComplete.call(self, value);
					}
				}
			}

			function render(value) {
				var formattedValue = settings.formatter.call(self, value, settings);
				$self.html(formattedValue);
			}
		});
	};

	$.fn.countTo.defaults = {
		from: 0,               // the number the element should start at
		to: 0,                 // the number the element should end at
		speed: 1000,           // how long it should take to count between the target numbers
		refreshInterval: 100,  // how often the element should be updated
		decimals: 0,           // the number of decimal places to show
		formatter: formatter,  // handler for formatting the value before rendering
		onUpdate: null,        // callback method for every time the element is updated
		onComplete: null       // callback method for when the element finishes updating
	};

	function formatter(value, settings) {
		return value.toFixed(settings.decimals);
	}
}(jQuery));











/////////////////////////////////////////////
// COUNTDOWN PLUGIN
/////////////////////////////////////////////

/* http://keith-wood.name/countdown.html
   Countdown for jQuery v1.6.3.
   Written by Keith Wood (kbwood{at}iinet.com.au) January 2008.
   Available under the MIT (https://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt) license. 
   Please attribute the author if you use it. */
(function($){function Countdown(){this.regional=[];this.regional['']={labels:['Years','Months','Weeks','Days','Hours','Minutes','Seconds'],labels1:['Year','Month','Week','Day','Hour','Minute','Second'],compactLabels:['y','m','w','d'],whichLabels:null,digits:['0','1','2','3','4','5','6','7','8','9'],timeSeparator:':',isRTL:false};this._defaults={until:null,since:null,timezone:null,serverSync:null,format:'dHMS',layout:'',compact:false,significant:0,description:'',expiryUrl:'',expiryText:'',alwaysExpire:false,onExpiry:null,onTick:null,tickInterval:1};$.extend(this._defaults,this.regional['']);this._serverSyncs=[];var c=(typeof Date.now=='function'?Date.now:function(){return new Date().getTime()});var d=(window.performance&&typeof window.performance.now=='function');function timerCallBack(a){var b=(a<1e12?(d?(performance.now()+performance.timing.navigationStart):c()):a||c());if(b-f>=1000){x._updateTargets();f=b}e(timerCallBack)}var e=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.oRequestAnimationFrame||window.msRequestAnimationFrame||null;var f=0;if(!e||$.noRequestAnimationFrame){$.noRequestAnimationFrame=null;setInterval(function(){x._updateTargets()},980)}else{f=window.animationStartTime||window.webkitAnimationStartTime||window.mozAnimationStartTime||window.oAnimationStartTime||window.msAnimationStartTime||c();e(timerCallBack)}}var Y=0;var O=1;var W=2;var D=3;var H=4;var M=5;var S=6;$.extend(Countdown.prototype,{markerClassName:'hasCountdown',propertyName:'countdown',_rtlClass:'countdown_rtl',_sectionClass:'countdown_section',_amountClass:'countdown_amount',_rowClass:'countdown_row',_holdingClass:'countdown_holding',_showClass:'countdown_show',_descrClass:'countdown_descr',_timerTargets:[],setDefaults:function(a){this._resetExtraLabels(this._defaults,a);$.extend(this._defaults,a||{})},UTCDate:function(a,b,c,e,f,g,h,i){if(typeof b=='object'&&b.constructor==Date){i=b.getMilliseconds();h=b.getSeconds();g=b.getMinutes();f=b.getHours();e=b.getDate();c=b.getMonth();b=b.getFullYear()}var d=new Date();d.setUTCFullYear(b);d.setUTCDate(1);d.setUTCMonth(c||0);d.setUTCDate(e||1);d.setUTCHours(f||0);d.setUTCMinutes((g||0)-(Math.abs(a)<30?a*60:a));d.setUTCSeconds(h||0);d.setUTCMilliseconds(i||0);return d},periodsToSeconds:function(a){return a[0]*31557600+a[1]*2629800+a[2]*604800+a[3]*86400+a[4]*3600+a[5]*60+a[6]},_attachPlugin:function(a,b){a=$(a);if(a.hasClass(this.markerClassName)){return}var c={options:$.extend({},this._defaults),_periods:[0,0,0,0,0,0,0]};a.addClass(this.markerClassName).data(this.propertyName,c);this._optionPlugin(a,b)},_addTarget:function(a){if(!this._hasTarget(a)){this._timerTargets.push(a)}},_hasTarget:function(a){return($.inArray(a,this._timerTargets)>-1)},_removeTarget:function(b){this._timerTargets=$.map(this._timerTargets,function(a){return(a==b?null:a)})},_updateTargets:function(){for(var i=this._timerTargets.length-1;i>=0;i--){this._updateCountdown(this._timerTargets[i])}},_optionPlugin:function(a,b,c){a=$(a);var d=a.data(this.propertyName);if(!b||(typeof b=='string'&&c==null)){var e=b;b=(d||{}).options;return(b&&e?b[e]:b)}if(!a.hasClass(this.markerClassName)){return}b=b||{};if(typeof b=='string'){var e=b;b={};b[e]=c}if(b.layout){b.layout=b.layout.replace(/&lt;/g,'<').replace(/&gt;/g,'>')}this._resetExtraLabels(d.options,b);var f=(d.options.timezone!=b.timezone);$.extend(d.options,b);this._adjustSettings(a,d,b.until!=null||b.since!=null||f);var g=new Date();if((d._since&&d._since<g)||(d._until&&d._until>g)){this._addTarget(a[0])}this._updateCountdown(a,d)},_updateCountdown:function(a,b){var c=$(a);b=b||c.data(this.propertyName);if(!b){return}c.html(this._generateHTML(b)).toggleClass(this._rtlClass,b.options.isRTL);if($.isFunction(b.options.onTick)){var d=b._hold!='lap'?b._periods:this._calculatePeriods(b,b._show,b.options.significant,new Date());if(b.options.tickInterval==1||this.periodsToSeconds(d)%b.options.tickInterval==0){b.options.onTick.apply(a,[d])}}var e=b._hold!='pause'&&(b._since?b._now.getTime()<b._since.getTime():b._now.getTime()>=b._until.getTime());if(e&&!b._expiring){b._expiring=true;if(this._hasTarget(a)||b.options.alwaysExpire){this._removeTarget(a);if($.isFunction(b.options.onExpiry)){b.options.onExpiry.apply(a,[])}if(b.options.expiryText){var f=b.options.layout;b.options.layout=b.options.expiryText;this._updateCountdown(a,b);b.options.layout=f}if(b.options.expiryUrl){window.location=b.options.expiryUrl}}b._expiring=false}else if(b._hold=='pause'){this._removeTarget(a)}c.data(this.propertyName,b)},_resetExtraLabels:function(a,b){var c=false;for(var n in b){if(n!='whichLabels'&&n.match(/[Ll]abels/)){c=true;break}}if(c){for(var n in a){if(n.match(/[Ll]abels[02-9]|compactLabels1/)){a[n]=null}}}},_adjustSettings:function(a,b,c){var d;var e=0;var f=null;for(var i=0;i<this._serverSyncs.length;i++){if(this._serverSyncs[i][0]==b.options.serverSync){f=this._serverSyncs[i][1];break}}if(f!=null){e=(b.options.serverSync?f:0);d=new Date()}else{var g=($.isFunction(b.options.serverSync)?b.options.serverSync.apply(a,[]):null);d=new Date();e=(g?d.getTime()-g.getTime():0);this._serverSyncs.push([b.options.serverSync,e])}var h=b.options.timezone;h=(h==null?-d.getTimezoneOffset():h);if(c||(!c&&b._until==null&&b._since==null)){b._since=b.options.since;if(b._since!=null){b._since=this.UTCDate(h,this._determineTime(b._since,null));if(b._since&&e){b._since.setMilliseconds(b._since.getMilliseconds()+e)}}b._until=this.UTCDate(h,this._determineTime(b.options.until,d));if(e){b._until.setMilliseconds(b._until.getMilliseconds()+e)}}b._show=this._determineShow(b)},_destroyPlugin:function(a){a=$(a);if(!a.hasClass(this.markerClassName)){return}this._removeTarget(a[0]);a.removeClass(this.markerClassName).empty().removeData(this.propertyName)},_pausePlugin:function(a){this._hold(a,'pause')},_lapPlugin:function(a){this._hold(a,'lap')},_resumePlugin:function(a){this._hold(a,null)},_hold:function(a,b){var c=$.data(a,this.propertyName);if(c){if(c._hold=='pause'&&!b){c._periods=c._savePeriods;var d=(c._since?'-':'+');c[c._since?'_since':'_until']=this._determineTime(d+c._periods[0]+'y'+d+c._periods[1]+'o'+d+c._periods[2]+'w'+d+c._periods[3]+'d'+d+c._periods[4]+'h'+d+c._periods[5]+'m'+d+c._periods[6]+'s');this._addTarget(a)}c._hold=b;c._savePeriods=(b=='pause'?c._periods:null);$.data(a,this.propertyName,c);this._updateCountdown(a,c)}},_getTimesPlugin:function(a){var b=$.data(a,this.propertyName);return(!b?null:(b._hold=='pause'?b._savePeriods:(!b._hold?b._periods:this._calculatePeriods(b,b._show,b.options.significant,new Date()))))},_determineTime:function(k,l){var m=function(a){var b=new Date();b.setTime(b.getTime()+a*1000);return b};var n=function(a){a=a.toLowerCase();var b=new Date();var c=b.getFullYear();var d=b.getMonth();var e=b.getDate();var f=b.getHours();var g=b.getMinutes();var h=b.getSeconds();var i=/([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;var j=i.exec(a);while(j){switch(j[2]||'s'){case's':h+=parseInt(j[1],10);break;case'm':g+=parseInt(j[1],10);break;case'h':f+=parseInt(j[1],10);break;case'd':e+=parseInt(j[1],10);break;case'w':e+=parseInt(j[1],10)*7;break;case'o':d+=parseInt(j[1],10);e=Math.min(e,x._getDaysInMonth(c,d));break;case'y':c+=parseInt(j[1],10);e=Math.min(e,x._getDaysInMonth(c,d));break}j=i.exec(a)}return new Date(c,d,e,f,g,h,0)};var o=(k==null?l:(typeof k=='string'?n(k):(typeof k=='number'?m(k):k)));if(o)o.setMilliseconds(0);return o},_getDaysInMonth:function(a,b){return 32-new Date(a,b,32).getDate()},_normalLabels:function(a){return a},_generateHTML:function(c){var d=this;c._periods=(c._hold?c._periods:this._calculatePeriods(c,c._show,c.options.significant,new Date()));var e=false;var f=0;var g=c.options.significant;var h=$.extend({},c._show);for(var i=Y;i<=S;i++){e|=(c._show[i]=='?'&&c._periods[i]>0);h[i]=(c._show[i]=='?'&&!e?null:c._show[i]);f+=(h[i]?1:0);g-=(c._periods[i]>0?1:0)}var j=[false,false,false,false,false,false,false];for(var i=S;i>=Y;i--){if(c._show[i]){if(c._periods[i]){j[i]=true}else{j[i]=g>0;g--}}}var k=(c.options.compact?c.options.compactLabels:c.options.labels);var l=c.options.whichLabels||this._normalLabels;var m=function(a){var b=c.options['compactLabels'+l(c._periods[a])];return(h[a]?d._translateDigits(c,c._periods[a])+(b?b[a]:k[a])+' ':'')};var n=function(a){var b=c.options['labels'+l(c._periods[a])];return((!c.options.significant&&h[a])||(c.options.significant&&j[a])?'<span class="'+x._sectionClass+'">'+'<span class="'+x._amountClass+'">'+d._translateDigits(c,c._periods[a])+'</span><span class="amount_label">'+(b?b[a]:k[a])+'</span></span>':'')};return(c.options.layout?this._buildLayout(c,h,c.options.layout,c.options.compact,c.options.significant,j):((c.options.compact?'<span class="'+this._rowClass+' '+this._amountClass+(c._hold?' '+this._holdingClass:'')+'">'+m(Y)+m(O)+m(W)+m(D)+(h[H]?this._minDigits(c,c._periods[H],2):'')+(h[M]?(h[H]?c.options.timeSeparator:'')+this._minDigits(c,c._periods[M],2):'')+(h[S]?(h[H]||h[M]?c.options.timeSeparator:'')+this._minDigits(c,c._periods[S],2):''):'<span class="'+this._rowClass+' '+this._showClass+(c.options.significant||f)+(c._hold?' '+this._holdingClass:'')+'">'+n(Y)+n(O)+n(W)+n(D)+n(H)+n(M)+n(S))+'</span>'+(c.options.description?'<span class="'+this._rowClass+' '+this._descrClass+'">'+c.options.description+'</span>':'')))},_buildLayout:function(c,d,e,f,g,h){var j=c.options[f?'compactLabels':'labels'];var k=c.options.whichLabels||this._normalLabels;var l=function(a){return(c.options[(f?'compactLabels':'labels')+k(c._periods[a])]||j)[a]};var m=function(a,b){return c.options.digits[Math.floor(a/b)%10]};var o={desc:c.options.description,sep:c.options.timeSeparator,yl:l(Y),yn:this._minDigits(c,c._periods[Y],1),ynn:this._minDigits(c,c._periods[Y],2),ynnn:this._minDigits(c,c._periods[Y],3),y1:m(c._periods[Y],1),y10:m(c._periods[Y],10),y100:m(c._periods[Y],100),y1000:m(c._periods[Y],1000),ol:l(O),on:this._minDigits(c,c._periods[O],1),onn:this._minDigits(c,c._periods[O],2),onnn:this._minDigits(c,c._periods[O],3),o1:m(c._periods[O],1),o10:m(c._periods[O],10),o100:m(c._periods[O],100),o1000:m(c._periods[O],1000),wl:l(W),wn:this._minDigits(c,c._periods[W],1),wnn:this._minDigits(c,c._periods[W],2),wnnn:this._minDigits(c,c._periods[W],3),w1:m(c._periods[W],1),w10:m(c._periods[W],10),w100:m(c._periods[W],100),w1000:m(c._periods[W],1000),dl:l(D),dn:this._minDigits(c,c._periods[D],1),dnn:this._minDigits(c,c._periods[D],2),dnnn:this._minDigits(c,c._periods[D],3),d1:m(c._periods[D],1),d10:m(c._periods[D],10),d100:m(c._periods[D],100),d1000:m(c._periods[D],1000),hl:l(H),hn:this._minDigits(c,c._periods[H],1),hnn:this._minDigits(c,c._periods[H],2),hnnn:this._minDigits(c,c._periods[H],3),h1:m(c._periods[H],1),h10:m(c._periods[H],10),h100:m(c._periods[H],100),h1000:m(c._periods[H],1000),ml:l(M),mn:this._minDigits(c,c._periods[M],1),mnn:this._minDigits(c,c._periods[M],2),mnnn:this._minDigits(c,c._periods[M],3),m1:m(c._periods[M],1),m10:m(c._periods[M],10),m100:m(c._periods[M],100),m1000:m(c._periods[M],1000),sl:l(S),sn:this._minDigits(c,c._periods[S],1),snn:this._minDigits(c,c._periods[S],2),snnn:this._minDigits(c,c._periods[S],3),s1:m(c._periods[S],1),s10:m(c._periods[S],10),s100:m(c._periods[S],100),s1000:m(c._periods[S],1000)};var p=e;for(var i=Y;i<=S;i++){var q='yowdhms'.charAt(i);var r=new RegExp('\\{'+q+'<\\}([\\s\\S]*)\\{'+q+'>\\}','g');p=p.replace(r,((!g&&d[i])||(g&&h[i])?'$1':''))}$.each(o,function(n,v){var a=new RegExp('\\{'+n+'\\}','g');p=p.replace(a,v)});return p},_minDigits:function(a,b,c){b=''+b;if(b.length>=c){return this._translateDigits(a,b)}b='0000000000'+b;return this._translateDigits(a,b.substr(b.length-c))},_translateDigits:function(b,c){return(''+c).replace(/[0-9]/g,function(a){return b.options.digits[a]})},_determineShow:function(a){var b=a.options.format;var c=[];c[Y]=(b.match('y')?'?':(b.match('Y')?'!':null));c[O]=(b.match('o')?'?':(b.match('O')?'!':null));c[W]=(b.match('w')?'?':(b.match('W')?'!':null));c[D]=(b.match('d')?'?':(b.match('D')?'!':null));c[H]=(b.match('h')?'?':(b.match('H')?'!':null));c[M]=(b.match('m')?'?':(b.match('M')?'!':null));c[S]=(b.match('s')?'?':(b.match('S')?'!':null));return c},_calculatePeriods:function(c,d,e,f){c._now=f;c._now.setMilliseconds(0);var g=new Date(c._now.getTime());if(c._since){if(f.getTime()<c._since.getTime()){c._now=f=g}else{f=c._since}}else{g.setTime(c._until.getTime());if(f.getTime()>c._until.getTime()){c._now=f=g}}var h=[0,0,0,0,0,0,0];if(d[Y]||d[O]){var i=x._getDaysInMonth(f.getFullYear(),f.getMonth());var j=x._getDaysInMonth(g.getFullYear(),g.getMonth());var k=(g.getDate()==f.getDate()||(g.getDate()>=Math.min(i,j)&&f.getDate()>=Math.min(i,j)));var l=function(a){return(a.getHours()*60+a.getMinutes())*60+a.getSeconds()};var m=Math.max(0,(g.getFullYear()-f.getFullYear())*12+g.getMonth()-f.getMonth()+((g.getDate()<f.getDate()&&!k)||(k&&l(g)<l(f))?-1:0));h[Y]=(d[Y]?Math.floor(m/12):0);h[O]=(d[O]?m-h[Y]*12:0);f=new Date(f.getTime());var n=(f.getDate()==i);var o=x._getDaysInMonth(f.getFullYear()+h[Y],f.getMonth()+h[O]);if(f.getDate()>o){f.setDate(o)}f.setFullYear(f.getFullYear()+h[Y]);f.setMonth(f.getMonth()+h[O]);if(n){f.setDate(o)}}var p=Math.floor((g.getTime()-f.getTime())/1000);var q=function(a,b){h[a]=(d[a]?Math.floor(p/b):0);p-=h[a]*b};q(W,604800);q(D,86400);q(H,3600);q(M,60);q(S,1);if(p>0&&!c._since){var r=[1,12,4.3482,7,24,60,60];var s=S;var t=1;for(var u=S;u>=Y;u--){if(d[u]){if(h[s]>=t){h[s]=0;p=1}if(p>0){h[u]++;p=0;s=u;t=1}}t*=r[u]}}if(e){for(var u=Y;u<=S;u++){if(e&&h[u]){e--}else if(!e){h[u]=0}}}return h}});var w=['getTimes'];function isNotChained(a,b){if(a=='option'&&(b.length==0||(b.length==1&&typeof b[0]=='string'))){return true}return $.inArray(a,w)>-1}$.fn.countdown=function(a){var b=Array.prototype.slice.call(arguments,1);if(isNotChained(a,b)){return x['_'+a+'Plugin'].apply(x,[this[0]].concat(b))}return this.each(function(){if(typeof a=='string'){if(!x['_'+a+'Plugin']){throw'Unknown command: '+a;}x['_'+a+'Plugin'].apply(x,[this].concat(b))}else{x._attachPlugin(this,a||{})}})};var x=$.countdown=new Countdown()})(jQuery);










/////////////////////////////////////////////
// vCenter PLUGIN
/////////////////////////////////////////////

(function($) {$.fn.vCenter = function() {return this.each(function(){var height = $(this).outerHeight();$(this).css('margin-bottom',-height/2);});};})(jQuery);
(function($) {$.fn.vCenterTop = function() {return this.each(function(){var height = $(this).outerHeight();$(this).css('margin-top',-height/2);});};})(jQuery);






/**************************************************************************
*	@name		    Zozo UI Tabs
*	@descripton	    Create awesome tabbed content area
*	@version	    6.1
*   @Licenses: 	    http://codecanyon.net/licenses/
*   @requires       jQuery v1.7 or later
*	@copyright      Copyright (c) 2013 Zozo UI
*   @author         Zozo UI
*   @URL:           http://www.zozoui.com
*   
***************************************************************************/
;; (function (_0x5adcx0) { (jQuery["browser"] = jQuery["browser"] || {})["mobile"] = /(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i["test"](_0x5adcx0) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i["test"](_0x5adcx0["substr"](0, 4)); })(navigator["userAgent"] || navigator["vendor"] || window["opera"]);;; (function (_0x5adcx1, _0x5adcx2, _0x5adcx3, _0x5adcx4) { if (!_0x5adcx2["console"]) { _0x5adcx2["console"] = {}; }; if (!_0x5adcx2["console"]["log"]) { _0x5adcx2["console"]["log"] = function () { }; }; _0x5adcx1["fn"]["extend"]({ hasClasses: function (_0x5adcx5) { var _0x5adcx6 = this; for (i in _0x5adcx5) { if (_0x5adcx1(_0x5adcx6)["hasClass"](_0x5adcx5[i])) { return true; }; }; return false; } }); _0x5adcx1["zozo"] = {}; _0x5adcx1["zozo"]["core"] = {}; _0x5adcx1["zozo"]["core"]["console"] = { debug: false, log: function (_0x5adcx7) { if (_0x5adcx1("#zozo-console")["length"] != 0) { _0x5adcx1("\x3Cdiv/\x3E")["css"]({ marginTop: -24 })["html"](_0x5adcx7)["prependTo"]("#zozo-console")["animate"]({ marginTop: 0 }, 300)["animate"]({ backgroundColor: "#ffffff" }, 800); } else { if (console && this["debug"] === true) { console["log"](_0x5adcx7); }; }; } }; _0x5adcx1["zozo"]["core"]["content"] = { debug: false, video: function (_0x5adcx8) { if (_0x5adcx8) { _0x5adcx8["find"]("iframe")["each"](function () { var _0x5adcx9 = _0x5adcx1(this)["attr"]("src"); var _0x5adcxa = "wmode=transparent"; if (_0x5adcx9 && _0x5adcx9["indexOf"](_0x5adcxa) === -1) { if (_0x5adcx9["indexOf"]("?") != -1) { _0x5adcx1(this)["attr"]("src", _0x5adcx9 + "\x26" + _0x5adcxa); } else { _0x5adcx1(this)["attr"]("src", _0x5adcx9 + "?" + _0x5adcxa); }; }; }); }; }, check: function (_0x5adcx8) { this["video"](_0x5adcx8); } }; _0x5adcx1["zozo"]["core"]["keyCodes"] = { tab: 9, enter: 13, esc: 27, space: 32, pageup: 33, pagedown: 34, end: 35, home: 36, left: 37, up: 38, right: 39, down: 40 }; _0x5adcx1["zozo"]["core"]["debug"] = { startTime: new Date(), log: function (_0x5adcxb) { if (console) { console["log"](_0x5adcxb); }; }, start: function () { this["startTime"] = +new Date(); this["log"]("start: " + this["startTime"]); }, stop: function () { var _0x5adcxc = +new Date(); var _0x5adcxd = _0x5adcxc - this["startTime"]; this["log"]("end: " + _0x5adcxc); this["log"]("diff: " + _0x5adcxd); var _0x5adcxe = _0x5adcxd / 1000; var _0x5adcxf = Math["abs"](_0x5adcxe); } }; _0x5adcx1["zozo"]["core"]["support"] = { is_mouse_present: function () { return (("onmousedown" in _0x5adcx2) && ("onmouseup" in _0x5adcx2) && ("onmousemove" in _0x5adcx2) && ("onclick" in _0x5adcx2) && ("ondblclick" in _0x5adcx2) && ("onmousemove" in _0x5adcx2) && ("onmouseover" in _0x5adcx2) && ("onmouseout" in _0x5adcx2) && ("oncontextmenu" in _0x5adcx2)); }, is_touch_device: function () { return (("ontouchstart" in _0x5adcx2) || (navigator["maxTouchPoints"] > 0) || (navigator["msMaxTouchPoints"] > 0)) && (jQuery["browser"]["mobile"]); }, supportsCss: (function () { var _0x5adcx10 = _0x5adcx3["createElement"]("div"), _0x5adcx11 = "khtml ms o moz webkit"["split"](" "), _0x5adcx12 = false; return function (_0x5adcx13) { (_0x5adcx13 in _0x5adcx10["style"]) && (_0x5adcx12 = _0x5adcx13); var _0x5adcx14 = _0x5adcx13["replace"](/^[a-z]/, function (_0x5adcx15) { return _0x5adcx15["toUpperCase"](); }); _0x5adcx1["each"](_0x5adcx11, function (_0x5adcx16, _0x5adcx17) { (_0x5adcx17 + _0x5adcx14 in _0x5adcx10["style"]) && (_0x5adcx12 = "-" + _0x5adcx17 + "-" + _0x5adcx13); }); return _0x5adcx12; }; })(), css: { transition: false } }; _0x5adcx1["zozo"]["core"]["utils"] = { toArray: function (_0x5adcx18) { return _0x5adcx1["map"](_0x5adcx18, function (_0x5adcx17, _0x5adcx19) { return _0x5adcx17; }); }, createHeader: function (_0x5adcx1a, _0x5adcx1b) { var _0x5adcx1c = _0x5adcx1("\x3Cli\x3E\x3Ca\x3E" + _0x5adcx1a + "\x3C/a\x3E\x3C/li\x3E"); var _0x5adcx8 = _0x5adcx1("\x3Cdiv\x3E" + _0x5adcx1b + "\x3C/div\x3E"); return { tab: _0x5adcx1c, content: _0x5adcx8 }; }, isEmpty: function (_0x5adcx1d) { return (!_0x5adcx1d || 0 === _0x5adcx1d["length"]); }, isNumber: function (_0x5adcx1e) { return typeof _0x5adcx1e === "number" && isFinite(_0x5adcx1e); }, isEven: function (_0x5adcx1f) { return _0x5adcx1f % 2 === 0; }, isOdd: function (_0x5adcx1e) { return !(_number % 2 === 0); }, animate: function (_0x5adcx6, _0x5adcx20, _0x5adcx21, _0x5adcx22, _0x5adcx23, _0x5adcx24) { var _0x5adcx25 = (_0x5adcx6["settings"]["animation"]["effects"] === "none") ? 0 : _0x5adcx6["settings"]["animation"]["duration"]; var _0x5adcx26 = _0x5adcx6["settings"]["animation"]["easing"]; var _0x5adcx27 = _0x5adcx1["zozo"]["core"]["support"]["css"]["transition"]; if (_0x5adcx20 && _0x5adcx22) { if (_0x5adcx21) { _0x5adcx20["css"](_0x5adcx21); }; var _0x5adcx28 = _0x5adcx20["css"]("left"); var _0x5adcx29 = _0x5adcx20["css"]("top"); if (_0x5adcx6["settings"]["animation"]["type"] === "css") { _0x5adcx22[_0x5adcx27] = "all " + _0x5adcx25 + "ms ease-in-out"; setTimeout(function () { _0x5adcx20["css"](_0x5adcx22); }); setTimeout(function () { if (_0x5adcx23) { _0x5adcx20["css"](_0x5adcx23); }; _0x5adcx20["css"](_0x5adcx27, ""); }, _0x5adcx25); } else { _0x5adcx20["animate"](_0x5adcx22, { duration: _0x5adcx25, easing: _0x5adcx26, complete: function () { if (_0x5adcx23) { _0x5adcx20["css"](_0x5adcx23); }; if (_0x5adcx24) { _0x5adcx20["hide"](); }; } }); }; }; return _0x5adcx6; } }; _0x5adcx1["zozo"]["core"]["plugins"] = { easing: function (_0x5adcx6) { var _0x5adcx2a = false; if (_0x5adcx6) { if (_0x5adcx6["settings"]) { var _0x5adcx2b = "swing"; if (_0x5adcx1["easing"]["def"]) { _0x5adcx2a = true; } else { if (_0x5adcx6["settings"]["animation"]["easing"] != "swing" && _0x5adcx6["settings"]["animation"]["easing"] != "linear") { _0x5adcx6["settings"]["animation"]["easing"] = _0x5adcx2b; }; }; }; }; return _0x5adcx2a; } }; _0x5adcx1["zozo"]["core"]["browser"] = { init: function () { this["browser"] = this["searchString"](this["dataBrowser"]) || "An unknown browser"; this["version"] = this["searchVersion"](navigator["userAgent"]) || this["searchVersion"](navigator["appVersion"]) || "an unknown version"; _0x5adcx1["zozo"]["core"]["console"]["log"]("init: " + this["browser"] + " : " + this["version"]); if (this["browser"] === "Explorer") { var _0x5adcx2c = _0x5adcx1("html"); var _0x5adcx2d = parseInt(this["version"]); if (_0x5adcx2d === 6) { _0x5adcx2c["addClass"]("ie ie7"); } else { if (_0x5adcx2d === 7) { _0x5adcx2c["addClass"]("ie ie7"); } else { if (_0x5adcx2d === 8) { _0x5adcx2c["addClass"]("ie ie8"); } else { if (_0x5adcx2d === 9) { _0x5adcx2c["addClass"]("ie ie9"); }; }; }; }; }; }, isIE: function (_0x5adcx2e) { if (_0x5adcx1["zozo"]["core"]["utils"]["isNumber"](_0x5adcx2e)) { return (this["browser"] === "Explorer" && this["version"] <= _0x5adcx2e); } else { return (this["browser"] === "Explorer"); }; }, isChrome: function (_0x5adcx2e) { if (_0x5adcx1["zozo"]["core"]["utils"]["isNumber"](_0x5adcx2e)) { return (this["browser"] === "Chrome" && this["version"] <= _0x5adcx2e); } else { return (this["browser"] === "Chrome"); }; }, searchString: function (_0x5adcx2f) { for (var _0x5adcx30 = 0; _0x5adcx30 < _0x5adcx2f["length"]; _0x5adcx30++) { var _0x5adcx31 = _0x5adcx2f[_0x5adcx30]["string"]; var _0x5adcx32 = _0x5adcx2f[_0x5adcx30]["prop"]; this["versionSearchString"] = _0x5adcx2f[_0x5adcx30]["versionSearch"] || _0x5adcx2f[_0x5adcx30]["identity"]; if (_0x5adcx31) { if (_0x5adcx31["indexOf"](_0x5adcx2f[_0x5adcx30]["subString"]) != -1) { return _0x5adcx2f[_0x5adcx30]["identity"]; }; } else { if (_0x5adcx32) { return _0x5adcx2f[_0x5adcx30]["identity"]; }; }; }; }, searchVersion: function (_0x5adcx31) { var _0x5adcx16 = _0x5adcx31["indexOf"](this["versionSearchString"]); if (_0x5adcx16 == -1) { return; }; return parseFloat(_0x5adcx31["substring"](_0x5adcx16 + this["versionSearchString"]["length"] + 1)); }, dataBrowser: [{ string: navigator["userAgent"], subString: "Chrome", identity: "Chrome" }, { string: navigator["vendor"], subString: "Apple", identity: "Safari", versionSearch: "Version" }, { prop: _0x5adcx2["opera"], identity: "Opera" }, { string: navigator["userAgent"], subString: "Firefox", identity: "Firefox" }, { string: navigator["userAgent"], subString: "MSIE", identity: "Explorer", versionSearch: "MSIE" }] }; _0x5adcx1["zozo"]["core"]["hashHelper"] = { mode: "single", separator: null, all: function (_0x5adcx33) { var _0x5adcx34 = []; var _0x5adcx35 = _0x5adcx3["location"]["hash"]; if (!this["hasHash"]()) { return _0x5adcx34; }; if (this["isSimple"](_0x5adcx33)) { return _0x5adcx35["substring"](1); } else { _0x5adcx35 = _0x5adcx35["substring"](1)["split"]("\x26"); for (var _0x5adcx30 = 0; _0x5adcx30 < _0x5adcx35["length"]; _0x5adcx30++) { var _0x5adcx36 = _0x5adcx35[_0x5adcx30]["split"](_0x5adcx33); if (_0x5adcx36["length"] != 2 || _0x5adcx36[0] in _0x5adcx34) { _0x5adcx36[1] = "none"; }; _0x5adcx34[_0x5adcx36[0]] = _0x5adcx36[1]; }; }; return _0x5adcx34; }, get: function (_0x5adcx19, _0x5adcx33) { var _0x5adcx37 = this["all"](_0x5adcx33); if (this["isSimple"](_0x5adcx33)) { return _0x5adcx37; } else { if (typeof _0x5adcx37 === "undefined" || typeof _0x5adcx37["length"] < 0) { return null; } else { if (typeof _0x5adcx37[_0x5adcx19] !== "undefined" && _0x5adcx37[_0x5adcx19] !== null) { return _0x5adcx37[_0x5adcx19]; } else { return null; }; }; }; }, set: function (_0x5adcx19, _0x5adcx17, _0x5adcx33, _0x5adcx38) { if (this["isSimple"](_0x5adcx33)) { _0x5adcx3["location"]["hash"] = _0x5adcx17; } else { if (_0x5adcx38 === "multiple") { var _0x5adcx37 = this["all"](_0x5adcx33); var _0x5adcx35 = []; _0x5adcx37[_0x5adcx19] = _0x5adcx17; for (var _0x5adcx19 in _0x5adcx37) { _0x5adcx35["push"](_0x5adcx19 + _0x5adcx33 + _0x5adcx37[_0x5adcx19]); }; _0x5adcx3["location"]["hash"] = _0x5adcx35["join"]("\x26"); } else { _0x5adcx3["location"]["hash"] = _0x5adcx19 + _0x5adcx33 + _0x5adcx17; }; }; }, isSimple: function (_0x5adcx33) { if (!_0x5adcx33 || _0x5adcx33 === "none") { return true; } else { return false; }; }, hasHash: function () { var _0x5adcx35 = _0x5adcx3["location"]["hash"]; if (_0x5adcx35["length"] > 0) { return true; } else { return false; }; } }; _0x5adcx1["zozo"]["core"]["support"]["css"]["transition"] = _0x5adcx1["zozo"]["core"]["support"]["supportsCss"]("transition"); _0x5adcx1["zozo"]["core"]["browser"]["init"](); })(jQuery, window, document);;; (function (_0x5adcx1) { _0x5adcx1["event"]["special"]["ztap"] = { distanceThreshold: 10, timeThreshold: 500, isTouchSupported: jQuery["zozo"]["core"]["support"]["is_touch_device"](), setup: function (_0x5adcx39) { var _0x5adcx3a = this, _0x5adcx6 = _0x5adcx1(_0x5adcx3a); var _0x5adcx3b = "click"; if (_0x5adcx39) { if (_0x5adcx39["data"]) { _0x5adcx3b = _0x5adcx39["data"]; }; }; if (_0x5adcx1["event"]["special"]["ztap"]["isTouchSupported"]) { _0x5adcx6["on"]("touchstart", function (_0x5adcx3c) { var _0x5adcx3d = _0x5adcx3c["target"], _0x5adcx3e = _0x5adcx3c["originalEvent"]["touches"][0], _0x5adcx3f = _0x5adcx3e["pageX"], _0x5adcx40 = _0x5adcx3e["pageY"], _0x5adcx41 = _0x5adcx1["event"]["special"]["ztap"]["distanceThreshold"], _0x5adcx42; function _0x5adcx43() { clearTimeout(_0x5adcx42); _0x5adcx6["off"]("touchmove", _0x5adcx46)["off"]("touchend", _0x5adcx44); }; function _0x5adcx44(_0x5adcx45) { _0x5adcx43(); if (_0x5adcx3d == _0x5adcx45["target"]) { _0x5adcx1["event"]["simulate"]("ztap", _0x5adcx3a, _0x5adcx45); }; }; function _0x5adcx46(_0x5adcx47) { var _0x5adcx48 = _0x5adcx47["originalEvent"]["touches"][0], _0x5adcx49 = _0x5adcx48["pageX"], _0x5adcx4a = _0x5adcx48["pageY"]; if (Math["abs"](_0x5adcx49 - _0x5adcx3f) > _0x5adcx41 || Math["abs"](_0x5adcx4a - _0x5adcx40) > _0x5adcx41) { _0x5adcx43(); }; }; _0x5adcx42 = setTimeout(_0x5adcx43, _0x5adcx1["event"]["special"]["ztap"]["timeThreshold"]); _0x5adcx6["on"]("touchmove", _0x5adcx46)["on"]("touchend", _0x5adcx44); }); } else { _0x5adcx6["on"](_0x5adcx3b, function (_0x5adcx45) { _0x5adcx1["event"]["simulate"]("ztap", _0x5adcx3a, _0x5adcx45); }); }; } }; })(jQuery);;; (function (_0x5adcx1, _0x5adcx2, _0x5adcx3, _0x5adcx4) { if (_0x5adcx2["zozo"] == null) { _0x5adcx2["zozo"] = {}; }; var _0x5adcx4b = function (_0x5adcx4c, _0x5adcx4d) { this["elem"] = _0x5adcx4c; this["$elem"] = _0x5adcx1(_0x5adcx4c); this["options"] = _0x5adcx4d; this["metadata"] = (this["$elem"]["data"]("options")) ? this["$elem"]["data"]("options") : {}; this["attrdata"] = (this["$elem"]["data"]()) ? this["$elem"]["data"]() : {}; this["tabID"]; this["$tabGroup"]; this["$mobileNav"]; this["$mobileDropdownArrow"]; this["$tabs"]; this["$container"]; this["$contents"]; this["autoplayIntervalId"]; this["resizeWindowIntervalId"]; this["currentTab"]; this["BrowserDetection"] = _0x5adcx1["zozo"]["core"]["browser"]; this["Deeplinking"] = _0x5adcx1["zozo"]["core"]["hashHelper"]; this["lastWindowHeight"]; this["lastWindowWidth"]; this["responsive"]; }; var _0x5adcx4e = { pluginName: "zozoTabs", elementSpacer: "\x3Cspan class=\x27z-tab-spacer\x27 style=\x27clear: both;display: block;\x27\x3E\x3C/span\x3E", commaRegExp: /,/g, space: " ", responsive: { largeDesktop: 1200, desktop: 960, tablet: 720, phone: 480 }, modes: { tabs: "tabs", stacked: "stacked", menu: "menu", slider: "slider" }, states: { closed: "z-state-closed", open: "z-state-open", active: "z-state-active" }, events: { click: "click", mousover: "mouseover", touchend: "touchend", touchstart: "touchstart", touchmove: "touchmove" }, animation: { effects: { fade: "fade", none: "none", slideH: "slideH", slideV: "slideV", slideLeft: "slideLeft", slideRight: "slideRight", slideTop: "slideTop", slideDown: "slideDown" }, types: { css: "css", jquery: "jquery" } }, classes: { prefix: "z-", wrapper: "z-tabs", tabGroup: "z-tabs-nav", tab: "z-tab", first: "z-first", last: "z-last", left: "z-left", right: "z-right", firstCol: "z-first-col", lastCol: "z-last-col", firstRow: "z-first-row", lastRow: "z-last-row", active: "z-active", link: "z-link", container: "z-container", content: "z-content", shadows: "z-shadows", bordered: "z-bordered", dark: "z-dark", spaced: "z-spaced", rounded: "z-rounded", themes: ["gray", "black", "blue", "crystal", "green", "silver", "red", "orange", "deepblue", "white"], flatThemes: ["flat-turquoise", "flat-emerald", "flat-peter-river", "flat-amethyst", "flat-wet-asphalt", "flat-green-sea", "flat-nephritis", "flat-belize-hole", "flat-wisteria", "flat-midnight-blue", "flat-sun-flower", "flat-carrot", "flat-alizarin", "flat-graphite", "flat-concrete", "flat-orange", "flat-pumpkin", "flat-pomegranate", "flat-silver", "flat-asbestos", "flat-zozo-red"], styles: ["contained", "pills", "underlined", "clean", "minimal"], orientations: ["vertical", "horizontal"], sizes: ["mini", "small", "medium", "large", "xlarge", "xxlarge"], positions: { top: "top", topLeft: "top-left", topCenter: "top-center", topRight: "top-right", topCompact: "top-compact", bottom: "bottom", bottomLeft: "bottom-left", bottomCenter: "bottom-center", bottomRight: "bottom-right", bottomCompact: "bottom-compact" } } }, _0x5adcx4f = "flat", _0x5adcx50 = "ready", _0x5adcx51 = "error", _0x5adcx52 = "select", _0x5adcx53 = "activate", _0x5adcx54 = "deactivate", _0x5adcx55 = "hover", _0x5adcx56 = "beforeSend", _0x5adcx57 = "contentLoad", _0x5adcx58 = "contentUrl", _0x5adcx59 = "contentType", _0x5adcx5a = "disabled", _0x5adcx5b = "z-icon-menu", _0x5adcx5c = "z-disabled", _0x5adcx5d = "z-stacked", _0x5adcx5e = "z-icons-light", _0x5adcx5f = "z-icons-dark", _0x5adcx60 = "z-spinner", _0x5adcx61 = "underlined", _0x5adcx62 = "contained", _0x5adcx63 = "clean", _0x5adcx64 = "pills", _0x5adcx65 = "vertical", _0x5adcx66 = "horizontal", _0x5adcx67 = "top-left", _0x5adcx68 = "top-right", _0x5adcx69 = "top", _0x5adcx6a = "bottom", _0x5adcx6b = "bottom-right", _0x5adcx6c = "bottom-left", _0x5adcx6d = "mobile", _0x5adcx6e = "z-multiline", _0x5adcx6f = "transition", _0x5adcx70 = "z-animating", _0x5adcx71 = "z-dropdown-arrow", _0x5adcx72 = "responsive", _0x5adcx73 = "z-content-inner"; _0x5adcx4b["prototype"] = { defaults: { delayAjax: 50, animation: { duration: 600, effects: "slideH", easing: "easeInQuad", type: "css", mobileDuration: 0 }, autoContentHeight: true, autoplay: { interval: 0, smart: true }, bordered: true, dark: false, cacheAjax: true, contentUrls: null, deeplinking: false, deeplinkingMode: "single", deeplinkingPrefix: null, deeplinkingSeparator: "", defaultTab: "tab1", event: _0x5adcx4e["events"]["click"], maxRows: 3, minWidth: 200, minWindowWidth: 480, manualTabId: false, mobileAutoScrolling: null, mobileNav: true, mobileMenuIcon: null, mode: _0x5adcx4e["modes"]["tabs"], multiline: false, hashAttribute: "data-link", position: _0x5adcx4e["classes"]["positions"]["topLeft"], orientation: _0x5adcx66, ready: function () { }, responsive: true, responsiveDelay: 0, rounded: false, shadows: true, theme: "silver", urlBased: false, scrollToContent: false, select: function () { }, spaced: false, deactivate: function () { }, beforeSend: function () { }, contentLoad: function () { }, next: null, prev: null, error: function () { }, noTabs: false, size: "medium", style: _0x5adcx62, tabRatio: 1.03, tabRatioCompact: 1.031, original: { itemWidth: 0, itemMinWidth: null, itemMaxWidth: null, groupWidth: 0, initGroupWidth: 0, itemD: 0, itemM: 0, firstRowWidth: 0, lastRowItems: 0, count: 0, contentMaxHeight: null, contentMaxWidth: null, navHeight: null, position: null, bottomLeft: null, tabGroupWidth: 0 }, animating: false }, init: function () { var _0x5adcx6 = this; _0x5adcx6["settings"] = _0x5adcx1["extend"](true, {}, _0x5adcx6["defaults"], _0x5adcx6["options"], _0x5adcx6["metadata"], _0x5adcx6["attrdata"]); if (_0x5adcx6["settings"]["contentUrls"] != null) { _0x5adcx6["$elem"]["find"]("\x3E div \x3E div")["each"](function (_0x5adcx16, _0x5adcx74) { _0x5adcx1(_0x5adcx74)["data"](_0x5adcx58, _0x5adcx6["settings"]["contentUrls"][_0x5adcx16]); }); }; _0x5adcx85["initAnimation"](_0x5adcx6, true); _0x5adcx85["updateClasses"](_0x5adcx6); _0x5adcx85["checkWidth"](_0x5adcx6, true); _0x5adcx85["bindEvents"](_0x5adcx6); _0x5adcx85["initAutoPlay"](_0x5adcx6); _0x5adcx1["zozo"]["core"]["plugins"]["easing"](_0x5adcx6); var _0x5adcx75 = (_0x5adcx6["settings"]["deeplinkingPrefix"]) ? _0x5adcx6["settings"]["deeplinkingPrefix"] : _0x5adcx6["tabID"]; if (_0x5adcx6["settings"]["deeplinking"] === true) { if (_0x5adcx3["location"]["hash"]) { var _0x5adcx76 = _0x5adcx6["Deeplinking"]["get"](_0x5adcx75, _0x5adcx6["settings"]["deeplinkingSeparator"]); var _0x5adcx77 = _0x5adcx6["$tabs"]["filter"]("li[" + _0x5adcx6["settings"]["hashAttribute"] + "=\x27" + _0x5adcx76 + "\x27]")["length"]; if (_0x5adcx77) { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx76); } else { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx6["settings"]["defaultTab"]); }; } else { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx6["settings"]["defaultTab"]); }; if (typeof (_0x5adcx1(_0x5adcx2)["hashchange"]) != "undefined") { _0x5adcx1(_0x5adcx2)["hashchange"](function () { var _0x5adcx78 = _0x5adcx6["Deeplinking"]["get"](_0x5adcx75, _0x5adcx6["settings"]["deeplinkingSeparator"]); if (!_0x5adcx6["currentTab"] || _0x5adcx6["currentTab"]["attr"](_0x5adcx6["settings"]["hashAttribute"]) !== _0x5adcx78) { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx78); }; }); } else { _0x5adcx1(_0x5adcx2)["bind"]("hashchange", function () { var _0x5adcx78 = _0x5adcx6["Deeplinking"]["get"](_0x5adcx75, _0x5adcx6["settings"]["deeplinkingSeparator"]); if (!_0x5adcx6["currentTab"] || _0x5adcx6["currentTab"]["attr"](_0x5adcx6["settings"]["hashAttribute"]) !== _0x5adcx78) { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx78); }; }); }; } else { if (_0x5adcx6["settings"]["noTabs"] === true) { _0x5adcx85["showContent"](_0x5adcx6, _0x5adcx85["getActive"](_0x5adcx6, 0)); } else { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx6["settings"]["defaultTab"]); }; }; _0x5adcx6["$elem"]["find"]("\x3E." + _0x5adcx60)["remove"](); _0x5adcx6["$elem"]["removeClass"]("z-tabs-loading"); _0x5adcx85["checkWidth"](_0x5adcx6); _0x5adcx6["$elem"]["trigger"](_0x5adcx50, _0x5adcx6.$elem); return _0x5adcx6; }, setOptions: function (_0x5adcx79) { var _0x5adcx6 = this; _0x5adcx6["settings"] = _0x5adcx1["extend"](true, _0x5adcx6["settings"], _0x5adcx79); _0x5adcx85["initAnimation"](_0x5adcx6); _0x5adcx85["updateClasses"](_0x5adcx6, true); _0x5adcx85["checkWidth"](_0x5adcx6, false, true); _0x5adcx85["initAutoPlay"](_0x5adcx6); return _0x5adcx6; }, add: function (_0x5adcx1a, _0x5adcx1b, _0x5adcx7a) { var _0x5adcx6 = this; var _0x5adcx7b = _0x5adcx85["create"](_0x5adcx1a, _0x5adcx1b); _0x5adcx7b["tab"]["appendTo"](_0x5adcx6.$tabGroup)["hide"]()["fadeIn"](300)["css"]("display", ""); _0x5adcx7b["content"]["appendTo"](_0x5adcx6.$container); _0x5adcx85["updateClasses"](_0x5adcx6); (_0x5adcx7a && _0x5adcx6["settings"]["deeplinking"] === true) && (_0x5adcx7b["tab"]["attr"](_0x5adcx6["settings"]["hashAttribute"], _0x5adcx7a)); _0x5adcx85["bindEvent"](_0x5adcx6, _0x5adcx7b["tab"]); setTimeout(function () { _0x5adcx85["checkWidth"](_0x5adcx6, false, true); }, 350); return _0x5adcx6; }, insertAfter: function (_0x5adcx1a, _0x5adcx1b, _0x5adcx7a) { var _0x5adcx6 = this; return _0x5adcx6; }, insertBefore: function (_0x5adcx1a, _0x5adcx1b, _0x5adcx7a) { var _0x5adcx6 = this; return _0x5adcx6; }, remove: function (_0x5adcx7c) { var _0x5adcx6 = this; var _0x5adcx7d = (_0x5adcx7c - 1); var _0x5adcx7e = _0x5adcx6["$tabs"]["eq"](_0x5adcx7d); var _0x5adcx7f = _0x5adcx6["$contents"]["eq"](_0x5adcx7d); _0x5adcx7f["remove"](); _0x5adcx7e["fadeOut"](300, function () { _0x5adcx1(this)["remove"](); _0x5adcx85["updateClasses"](_0x5adcx6); }); setTimeout(function () { _0x5adcx85["checkWidth"](_0x5adcx6, false, true); }, 350); return _0x5adcx6; }, enable: function (_0x5adcx7c) { var _0x5adcx6 = this; var _0x5adcx80 = _0x5adcx6["$tabs"]["eq"](_0x5adcx7c); if (_0x5adcx80["length"]) { _0x5adcx80["removeClass"](_0x5adcx5c); _0x5adcx80["data"](_0x5adcx5a, false); }; return _0x5adcx6; }, disable: function (_0x5adcx7c) { var _0x5adcx6 = this; var _0x5adcx81 = _0x5adcx6["$tabs"]["eq"](_0x5adcx7c); if (_0x5adcx81["length"]) { _0x5adcx81["addClass"](_0x5adcx5c); _0x5adcx81["data"](_0x5adcx5a, true); }; return _0x5adcx6; }, select: function (_0x5adcx7c) { var _0x5adcx6 = this; if (_0x5adcx6["settings"]["animating"] !== true) { if (_0x5adcx6["settings"]["noTabs"] === true) { _0x5adcx85["showContent"](_0x5adcx6, _0x5adcx85["getActive"](_0x5adcx6, _0x5adcx7c)); } else { _0x5adcx85["changeHash"](_0x5adcx6, _0x5adcx6["$tabs"]["eq"](_0x5adcx7c)["attr"](_0x5adcx6["settings"]["hashAttribute"])); }; }; return _0x5adcx6; }, first: function () { var _0x5adcx6 = this; _0x5adcx6["select"](_0x5adcx85["getFirst"]()); return _0x5adcx6; }, prev: function () { var _0x5adcx6 = this; var _0x5adcx82 = _0x5adcx85["getActiveIndex"](_0x5adcx6); if (_0x5adcx82 <= _0x5adcx85["getFirst"](_0x5adcx6)) { _0x5adcx6["select"](_0x5adcx85["getLast"](_0x5adcx6)); } else { _0x5adcx6["select"](_0x5adcx82 - 1); _0x5adcx1["zozo"]["core"]["debug"]["log"]("prev tab : " + (_0x5adcx82 - 1)); }; return _0x5adcx6; }, next: function (_0x5adcx6) { _0x5adcx6 = (_0x5adcx6) ? _0x5adcx6 : this; var _0x5adcx82 = _0x5adcx85["getActiveIndex"](_0x5adcx6); var _0x5adcx83 = parseInt(_0x5adcx85["getLast"](_0x5adcx6)); if (_0x5adcx82 >= _0x5adcx83) { _0x5adcx6["select"](_0x5adcx85["getFirst"]()); } else { _0x5adcx6["select"](_0x5adcx82 + 1); _0x5adcx1["zozo"]["core"]["debug"]["log"]("next tab : " + (_0x5adcx82 + 1)); }; return _0x5adcx6; }, last: function () { var _0x5adcx6 = this; _0x5adcx6["select"](_0x5adcx85["getLast"](_0x5adcx6)); return _0x5adcx6; }, play: function (_0x5adcx84) { var _0x5adcx6 = this; if (_0x5adcx84 == null || _0x5adcx84 < 0) { _0x5adcx84 = 2000; }; _0x5adcx6["settings"]["autoplay"]["interval"] = _0x5adcx84; _0x5adcx6["stop"](); _0x5adcx6["autoplayIntervalId"] = setInterval(function () { _0x5adcx6["next"](_0x5adcx6); }, _0x5adcx6["settings"]["autoplay"]["interval"]); return _0x5adcx6; }, stop: function (_0x5adcx6) { _0x5adcx6 = (_0x5adcx6) ? _0x5adcx6 : this; clearInterval(_0x5adcx6["autoplayIntervalId"]); return _0x5adcx6; }, refresh: function () { var _0x5adcx6 = this; _0x5adcx6["$contents"]["filter"](".z-active")["css"]({ "display": "block" })["show"](); _0x5adcx85["checkWidth"](_0x5adcx6); return _0x5adcx6; } }; var _0x5adcx85 = { initAnimation: function (_0x5adcx6, _0x5adcx86) { var _0x5adcx87 = _0x5adcx1["zozo"]["core"]["utils"]["toArray"](_0x5adcx4e["animation"]["effects"]); if (_0x5adcx1["inArray"](_0x5adcx6["settings"]["animation"]["effects"], _0x5adcx87) < 0) { _0x5adcx6["settings"]["animation"]["effects"] = _0x5adcx4e["animation"]["effects"]["slideH"]; }; if (jQuery["browser"]["mobile"]) { _0x5adcx6["settings"]["shadows"] = false; }; if (_0x5adcx1["zozo"]["core"]["support"]["css"]["transition"] === false) { _0x5adcx6["settings"]["animation"]["type"] = _0x5adcx4e["animation"]["types"]["jquery"]; if (jQuery["browser"]["mobile"]) { _0x5adcx6["settings"]["animation"]["duration"] = 0; }; }; if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["none"] && _0x5adcx86 === true) { _0x5adcx6["settings"]["animation"]["duration"] = 0; }; }, updateClasses: function (_0x5adcx6, _0x5adcx88) { _0x5adcx6["$elem"]["find"]("*")["stop"](true, true); _0x5adcx6["tabID"] = _0x5adcx6["$elem"]["attr"]("id"); _0x5adcx6["$tabGroup"] = _0x5adcx6["$elem"]["find"]("\x3E ul")["addClass"](_0x5adcx4e["classes"]["tabGroup"])["not"](".z-tabs-mobile")["addClass"]("z-tabs-desktop"); _0x5adcx6["$tabs"] = _0x5adcx6["$tabGroup"]["find"]("\x3E li"); _0x5adcx6["$container"] = _0x5adcx6["$elem"]["find"]("\x3E div"); _0x5adcx6["$contents"] = _0x5adcx6["$container"]["find"]("\x3E div"); if (_0x5adcx6["$tabGroup"]["length"] <= 0) { _0x5adcx6["settings"]["noTabs"] = true; }; var _0x5adcx27 = _0x5adcx1["zozo"]["core"]["support"]["css"]["transition"]; var _0x5adcx89 = _0x5adcx6["settings"]["noTabs"]; _0x5adcx6["$container"]["addClass"](_0x5adcx4e["classes"]["container"])["css"]({ _transition: "" }); _0x5adcx6["$contents"]["addClass"](_0x5adcx4e["classes"]["content"]); _0x5adcx6["$contents"]["each"](function (_0x5adcx16, _0x5adcx74) { var _0x5adcx8a = _0x5adcx1(_0x5adcx74); _0x5adcx8a["css"]({ "left": "", "top": "", "opacity": "", "display": "", _transition: "" }); (_0x5adcx8a["hasClass"](_0x5adcx4e["classes"]["active"])) && _0x5adcx8a["show"]()["css"]({ "display": "block", _transition: "" }); }); if (_0x5adcx89 != true) { _0x5adcx6["$tabs"]["each"](function (_0x5adcx16, _0x5adcx74) { var _0x5adcx1c = _0x5adcx1(_0x5adcx74); _0x5adcx1c["removeClass"](_0x5adcx4e["classes"]["first"])["removeClass"](_0x5adcx4e["classes"]["last"])["removeClass"](_0x5adcx4e["classes"]["left"])["removeClass"](_0x5adcx4e["classes"]["right"])["removeClass"](_0x5adcx4e["classes"]["firstCol"])["removeClass"](_0x5adcx4e["classes"]["lastCol"])["removeClass"](_0x5adcx4e["classes"]["firstRow"])["removeClass"](_0x5adcx4e["classes"]["lastRow"])["css"]({ "width": "", "float": "" })["addClass"](_0x5adcx4e["classes"]["tab"])["find"]("a")["addClass"](_0x5adcx4e["classes"]["link"]); (_0x5adcx85["isTabDisabled"](_0x5adcx1c)) && (_0x5adcx6["disable"](_0x5adcx16)); (_0x5adcx6["settings"]["deeplinking"] === false) && _0x5adcx1(_0x5adcx74)["attr"](_0x5adcx6["settings"]["hashAttribute"], "tab" + (_0x5adcx16 + 1)); }); _0x5adcx6["$tabs"]["filter"]("li:first-child")["addClass"](_0x5adcx4e["classes"]["first"]); _0x5adcx6["$tabs"]["filter"]("li:last-child")["addClass"](_0x5adcx4e["classes"]["last"]); }; var _0x5adcx8b = _0x5adcx1["zozo"]["core"]["utils"]["toArray"](_0x5adcx4e["classes"]["positions"]); _0x5adcx6["$elem"]["removeClass"](_0x5adcx4e["classes"]["wrapper"])["removeClass"](_0x5adcx4e["classes"]["rounded"])["removeClass"](_0x5adcx4e["classes"]["shadows"])["removeClass"](_0x5adcx4e["classes"]["spaced"])["removeClass"](_0x5adcx4e["classes"]["bordered"])["removeClass"](_0x5adcx4e["classes"]["dark"])["removeClass"](_0x5adcx6e)["removeClass"](_0x5adcx5e)["removeClass"](_0x5adcx5f)["removeClass"](_0x5adcx5d)["removeClass"](_0x5adcx4f)["removeClass"](_0x5adcx4e["classes"]["styles"]["join"](_0x5adcx4e["space"]))["removeClass"](_0x5adcx4e["classes"]["orientations"]["join"](_0x5adcx4e["space"]))["removeClass"](_0x5adcx8b["join"]()["replace"](_0x5adcx4e["commaRegExp"], _0x5adcx4e["space"]))["removeClass"](_0x5adcx4e["classes"]["sizes"]["join"](_0x5adcx4e["space"]))["removeClass"](_0x5adcx4e["classes"]["themes"]["join"](_0x5adcx4e["space"]))["removeClass"](_0x5adcx4e["classes"]["flatThemes"]["join"](_0x5adcx4e["space"]))["addClass"](_0x5adcx55)["addClass"](_0x5adcx6["settings"]["style"])["addClass"](_0x5adcx6["settings"]["size"])["addClass"](_0x5adcx6["settings"]["theme"]); (_0x5adcx85["isFlatTheme"](_0x5adcx6)) && _0x5adcx6["$elem"]["addClass"](_0x5adcx4f); (_0x5adcx85["isLightTheme"](_0x5adcx6)) ? _0x5adcx6["$elem"]["addClass"](_0x5adcx5f) : _0x5adcx6["$elem"]["addClass"](_0x5adcx5e); (_0x5adcx6["settings"]["rounded"] === true) && _0x5adcx6["$elem"]["addClass"](_0x5adcx4e["classes"]["rounded"]); (_0x5adcx6["settings"]["shadows"] === true) && _0x5adcx6["$elem"]["addClass"](_0x5adcx4e["classes"]["shadows"]); (_0x5adcx6["settings"]["bordered"] === true) && _0x5adcx6["$elem"]["addClass"](_0x5adcx4e["classes"]["bordered"]); (_0x5adcx6["settings"]["dark"] === true) && _0x5adcx6["$elem"]["addClass"](_0x5adcx4e["classes"]["dark"]); (_0x5adcx6["settings"]["spaced"] === true) && _0x5adcx6["$elem"]["addClass"](_0x5adcx4e["classes"]["spaced"]); (_0x5adcx6["settings"]["multiline"] === true) && _0x5adcx6["$elem"]["addClass"](_0x5adcx6e); _0x5adcx85["checkPosition"](_0x5adcx6); if (_0x5adcx6["$elem"]["find"]("\x3E ul." + "z-tabs-mobile")["length"]) { _0x5adcx6["$mobileNav"] = _0x5adcx6["$elem"]["find"]("\x3E ul." + "z-tabs-mobile"); } else { _0x5adcx6["$mobileNav"] = _0x5adcx1("\x3Cul class=\x27z-tabs-nav z-tabs-mobile\x27\x3E\x3Cli\x3E\x3Ca class=\x27z-link\x27 style=\x27text-align: left;\x27\x3E\x3Cspan class=\x27z-title\x27\x3EOverview\x3C/span\x3E\x3Cspan class=\x27z-arrow\x27\x3E\x3C/span\x3E\x3C/a\x3E\x3C/li\x3E\x3C/ul\x3E"); }; if (_0x5adcx6["$mobileNav"]) { _0x5adcx6["$tabGroup"]["before"](_0x5adcx6.$mobileNav); if (_0x5adcx6["$elem"]["find"]("\x3E i." + _0x5adcx71)["length"]) { _0x5adcx6["$mobileDropdownArrow"] = _0x5adcx6["$elem"]["find"]("\x3E i." + _0x5adcx71); } else { _0x5adcx6["$mobileDropdownArrow"] = _0x5adcx1("\x3Ci class=\x27z-dropdown-arrow\x27\x3E\x3C/i\x3E"); }; _0x5adcx6["$tabGroup"]["before"](_0x5adcx6.$mobileDropdownArrow); }; (jQuery["browser"]["mobile"]) && (_0x5adcx6["$elem"]["removeClass"](_0x5adcx55)); }, checkPosition: function (_0x5adcx6) { _0x5adcx6["$container"]["appendTo"](_0x5adcx6.$elem); _0x5adcx6["$tabGroup"]["prependTo"](_0x5adcx6.$elem); _0x5adcx6["$elem"]["find"]("\x3E span.z-tab-spacer")["remove"](); _0x5adcx6["$elem"]["addClass"](_0x5adcx4e["classes"]["wrapper"]); var _0x5adcx8c = _0x5adcx85["isTop"](_0x5adcx6); _0x5adcx6["$contents"]["each"](function (_0x5adcx16, _0x5adcx74) { var _0x5adcx8 = _0x5adcx1(_0x5adcx74); var _0x5adcx8d = _0x5adcx73; if (!_0x5adcx8["find"]("\x3E div." + _0x5adcx73)["length"]) { if (_0x5adcx8["hasClass"]("z-row")) { _0x5adcx8["removeClass"]("z-row"); _0x5adcx8d = "z-row " + _0x5adcx73; }; _0x5adcx8["wrapInner"]("\x3Cdiv class=\x27" + _0x5adcx8d + "\x27\x3E\x3C/div\x3E"); _0x5adcx1["zozo"]["core"]["content"]["check"](_0x5adcx8); }; }); if (_0x5adcx6["settings"]["orientation"] === _0x5adcx65) { if (_0x5adcx6["settings"]["position"] !== _0x5adcx68) { _0x5adcx6["settings"]["position"] = _0x5adcx67; }; } else { _0x5adcx6["settings"]["orientation"] = _0x5adcx66; if (_0x5adcx8c === false) { _0x5adcx6["$tabGroup"]["appendTo"](_0x5adcx6.$elem); _0x5adcx1(_0x5adcx4e["elementSpacer"])["appendTo"](_0x5adcx6.$elem); _0x5adcx6["$container"]["prependTo"](_0x5adcx6.$elem); }; }; _0x5adcx6["$elem"]["addClass"](_0x5adcx6["settings"]["orientation"]); _0x5adcx6["$elem"]["addClass"](_0x5adcx6["settings"]["position"]); if (_0x5adcx8c) { _0x5adcx6["$elem"]["addClass"](_0x5adcx69); } else { _0x5adcx6["$elem"]["addClass"](_0x5adcx6a); }; }, bindEvents: function (_0x5adcx6) { var _0x5adcx25 = (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["none"]) ? 0 : _0x5adcx6["settings"]["animation"]["duration"]; _0x5adcx6["$tabs"]["each"](function () { var _0x5adcx1c = _0x5adcx1(this); var _0x5adcx8e = _0x5adcx1c["find"]("a")["attr"]("href"); var _0x5adcx3d = _0x5adcx1c["find"]("a")["attr"]("target"); if (!_0x5adcx1["trim"](_0x5adcx8e)["length"]) { _0x5adcx85["bindEvent"](_0x5adcx6, _0x5adcx1c); } else { _0x5adcx1c["on"]("ztap", { data: _0x5adcx6["settings"]["event"] }, function (_0x5adcx39) { (_0x5adcx1["trim"](_0x5adcx3d)["length"]) ? _0x5adcx2["open"](_0x5adcx8e, _0x5adcx3d) : _0x5adcx2["location"] = _0x5adcx8e; _0x5adcx39["preventDefault"](); }); }; }); _0x5adcx1(_0x5adcx2)["resize"](function () { if (_0x5adcx6["lastWindowWidth"] !== _0x5adcx1(_0x5adcx2)["width"]()) { clearInterval(_0x5adcx6["resizeWindowIntervalId"]); _0x5adcx6["resizeWindowIntervalId"] = setTimeout(function () { _0x5adcx6["lastWindowHeight"] = _0x5adcx1(_0x5adcx2)["height"](); _0x5adcx6["lastWindowWidth"] = _0x5adcx1(_0x5adcx2)["width"](); _0x5adcx85["checkWidth"](_0x5adcx6); }, _0x5adcx6["settings"]["responsiveDelay"]); }; }); var _0x5adcx8f = _0x5adcx6["settings"]["next"]; if (_0x5adcx8f != null) { _0x5adcx1(_0x5adcx8f)["on"](_0x5adcx4e["events"]["click"], function (_0x5adcx39) { _0x5adcx39["preventDefault"](); _0x5adcx6["next"](); }); }; var _0x5adcx90 = _0x5adcx6["settings"]["prev"]; if (_0x5adcx90 != null) { _0x5adcx1(_0x5adcx90)["on"](_0x5adcx4e["events"]["click"], function (_0x5adcx39) { _0x5adcx39["preventDefault"](); _0x5adcx6["prev"](); }); }; if (_0x5adcx6["$mobileNav"]) { _0x5adcx6["$mobileNav"]["find"]("li")["on"]("ztap", { data: _0x5adcx6["settings"]["event"] }, function (_0x5adcx39) { _0x5adcx39["preventDefault"](); if (_0x5adcx6["$mobileNav"]["hasClass"](_0x5adcx4e["states"]["closed"])) { _0x5adcx6["$mobileNav"]["removeClass"](_0x5adcx4e["states"]["closed"]); _0x5adcx6["$tabGroup"]["removeClass"]("z-hide-menu"); _0x5adcx85["mobileNavAutoScroll"](_0x5adcx6); } else { _0x5adcx6["$mobileNav"]["addClass"](_0x5adcx4e["states"]["closed"]); _0x5adcx6["$tabGroup"]["addClass"]("z-hide-menu"); }; _0x5adcx85["refreshParents"](_0x5adcx6, _0x5adcx25); }); }; _0x5adcx6["lastWindowHeight"] = _0x5adcx1(_0x5adcx2)["height"](); _0x5adcx6["lastWindowWidth"] = _0x5adcx1(_0x5adcx2)["width"](); _0x5adcx6["$elem"]["bind"](_0x5adcx50, _0x5adcx6["settings"]["ready"]); _0x5adcx6["$elem"]["bind"](_0x5adcx52, _0x5adcx6["settings"]["select"]); _0x5adcx6["$elem"]["bind"](_0x5adcx54, _0x5adcx6["settings"]["deactivate"]); _0x5adcx6["$elem"]["bind"](_0x5adcx51, _0x5adcx6["settings"]["error"]); _0x5adcx6["$elem"]["bind"](_0x5adcx57, _0x5adcx6["settings"]["contentLoad"]); }, bindEvent: function (_0x5adcx6, _0x5adcx1c) { _0x5adcx1c["on"]("ztap", { data: _0x5adcx6["settings"]["event"] }, function (_0x5adcx39) { _0x5adcx39["preventDefault"](); if (_0x5adcx6["settings"]["autoplay"] !== false && _0x5adcx6["settings"]["autoplay"] != null) { if (_0x5adcx6["settings"]["autoplay"]["smart"] === true) { _0x5adcx6["stop"](); }; }; _0x5adcx85["changeHash"](_0x5adcx6, _0x5adcx1c["attr"](_0x5adcx6["settings"]["hashAttribute"])); if (_0x5adcx85["allowAutoScrolling"](_0x5adcx6) === true && _0x5adcx85["isMobile"](_0x5adcx6)) { _0x5adcx1(_0x5adcx2["opera"] ? "html" : "html, body")["animate"]({ scrollTop: _0x5adcx6["$elem"]["offset"]()["top"] + _0x5adcx6["settings"]["mobileAutoScrolling"]["contentTopOffset"] }, 0); }; }); }, mobileNavAutoScroll: function (_0x5adcx6) { if (_0x5adcx85["allowAutoScrolling"](_0x5adcx6) === true) { _0x5adcx1(_0x5adcx2["opera"] ? "html" : "html, body")["animate"]({ scrollTop: _0x5adcx6["$mobileNav"]["offset"]()["top"] + _0x5adcx6["settings"]["mobileAutoScrolling"]["navTopOffset"] }, 0); }; return _0x5adcx6; }, showTab: function (_0x5adcx6, _0x5adcx76) { var _0x5adcx77 = _0x5adcx6["$tabs"]["filter"]("li[" + _0x5adcx6["settings"]["hashAttribute"] + "=\x27" + _0x5adcx76 + "\x27]")["length"]; if (_0x5adcx77 && _0x5adcx76 != null && _0x5adcx6["settings"]["animating"] !== true) { var _0x5adcx91 = _0x5adcx6["$tabs"]["filter"]("li[" + _0x5adcx6["settings"]["hashAttribute"] + "=\x27" + _0x5adcx76 + "\x27]"); var _0x5adcx92 = _0x5adcx6["$tabs"]["index"](_0x5adcx91); var _0x5adcx93 = _0x5adcx85["getActive"](_0x5adcx6, _0x5adcx92); if (_0x5adcx93["enabled"] && _0x5adcx93["preIndex"] !== _0x5adcx93["index"] && _0x5adcx6["settings"]["noTabs"] !== true) { _0x5adcx6["currentTab"] = _0x5adcx91; _0x5adcx6["$tabs"]["removeClass"](_0x5adcx4e["classes"]["active"]); _0x5adcx6["currentTab"]["addClass"](_0x5adcx4e["classes"]["active"]); _0x5adcx85["mobileNav"](_0x5adcx6, false, _0x5adcx93["index"]); if (_0x5adcx93["contentUrl"]) { if (_0x5adcx93["preIndex"] === -1) { _0x5adcx93["content"]["css"]({ "opacity": "", "left": "", "top": "", "position": "relative" })["show"](); }; if (_0x5adcx93["contentType"] === "iframe") { _0x5adcx85["iframeContent"](_0x5adcx6, _0x5adcx93); } else { _0x5adcx85["ajaxRequest"](_0x5adcx6, _0x5adcx93); }; } else { _0x5adcx85["showContent"](_0x5adcx6, _0x5adcx93); }; }; }; }, getActiveIndex: function (_0x5adcx6) { var _0x5adcx7d; if (_0x5adcx6["settings"]["noTabs"] === true) { _0x5adcx7d = _0x5adcx6["$container"]["find"]("\x3Ediv." + _0x5adcx4e["classes"]["active"])["index"](); } else { if (_0x5adcx6["currentTab"]) { _0x5adcx7d = parseInt(_0x5adcx6["currentTab"]["index"]()); } else { _0x5adcx7d = _0x5adcx6["$tabGroup"]["find"]("li." + _0x5adcx4e["classes"]["active"])["index"](); }; }; return _0x5adcx7d; }, getActive: function (_0x5adcx6, _0x5adcx7d) { var _0x5adcx94 = _0x5adcx85["getActiveIndex"](_0x5adcx6); var _0x5adcx95 = _0x5adcx6["$contents"]["eq"](_0x5adcx7d); var _0x5adcx96 = _0x5adcx6["$tabs"]["eq"](_0x5adcx7d); var _0x5adcx97 = _0x5adcx6["$tabs"]["eq"](_0x5adcx94); var _0x5adcx27 = _0x5adcx1["zozo"]["core"]["support"]["css"]["transition"]; var _0x5adcx25 = (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["none"]) ? 0 : _0x5adcx6["settings"]["animation"]["duration"]; var _0x5adcx93 = { index: _0x5adcx7d, tab: _0x5adcx96, content: _0x5adcx95, contentInner: _0x5adcx95["find"]("\x3E .z-content-inner"), enabled: _0x5adcx85["isTabDisabled"](_0x5adcx96) === false, contentUrl: _0x5adcx95["data"](_0x5adcx58), contentType: _0x5adcx95["data"](_0x5adcx59), noAnimation: false, transition: _0x5adcx27, duration: _0x5adcx25, preIndex: _0x5adcx94, preTab: _0x5adcx97, preContent: _0x5adcx6["$contents"]["eq"](_0x5adcx94) }; return _0x5adcx93; }, iframeContent: function (_0x5adcx6, _0x5adcx93) { var _0x5adcx98 = _0x5adcx93["contentInner"]["find"]("\x3E div \x3E.z-iframe"); if (!_0x5adcx98["length"]) { _0x5adcx85["showLoading"](_0x5adcx6); _0x5adcx93["contentInner"]["append"]("\x3Cdiv class=\x22z-video\x22\x3E\x3Ciframe src=\x22" + _0x5adcx93["contentUrl"] + "\x22 frameborder=\x220\x22 scrolling=\x22auto\x22 height=\x221400\x22 class=\x22z-iframe\x22\x3E\x3C/iframe\x3E\x3C/div\x3E"); console["log"]("add iframe"); } else { _0x5adcx85["hideLoading"](_0x5adcx6); }; _0x5adcx85["showContent"](_0x5adcx6, _0x5adcx93); _0x5adcx93["contentInner"]["find"](".z-iframe")["load"](function () { _0x5adcx85["hideLoading"](_0x5adcx6); }); return _0x5adcx6; }, showLoading: function (_0x5adcx6) { _0x5adcx6["$container"]["append"]("\x3Cspan class=\x22" + _0x5adcx60 + "\x22\x3E\x3C/span\x3E"); return _0x5adcx6; }, hideLoading: function (_0x5adcx6) { _0x5adcx6["$container"]["find"]("\x3E." + _0x5adcx60)["remove"](); return _0x5adcx6; }, ajaxRequest: function (_0x5adcx6, _0x5adcx93) { var _0x5adcx2f = {}; _0x5adcx1["ajax"]({ type: "GET", cache: (_0x5adcx6["settings"]["cacheAjax"] === true), url: _0x5adcx93["contentUrl"], dataType: "html", data: _0x5adcx2f, beforeSend: function (_0x5adcx99, _0x5adcx9a) { _0x5adcx85["showLoading"](_0x5adcx6); }, error: function (_0x5adcx99, _0x5adcx9b, _0x5adcx9c) { if (_0x5adcx99["status"] == 404) { _0x5adcx93["contentInner"]["html"]("\x3Ch4 style=\x27color:red;\x27\x3ESorry, error: 404 - the requested content could not be found.\x3C/h4\x3E"); } else { _0x5adcx93["contentInner"]["html"]("\x3Ch4 style=\x27color:red;\x27\x3EAn error occurred: " + _0x5adcx9b + "\x0AError: " + _0x5adcx99 + " code: " + _0x5adcx99["status"] + "\x3C/h4\x3E"); }; (_0x5adcx6["settings"]["error"] && typeof (_0x5adcx6["settings"]["error"]) == typeof (Function)) && _0x5adcx6["$elem"]["trigger"](_0x5adcx51, _0x5adcx99); }, complete: function (_0x5adcx99, _0x5adcx9b) { setTimeout(function () { _0x5adcx85["showContent"](_0x5adcx6, _0x5adcx93); _0x5adcx85["hideLoading"](_0x5adcx6); }, _0x5adcx6["settings"]["delayAjax"]); }, success: function (_0x5adcx2f, _0x5adcx9b, _0x5adcx99) { setTimeout(function () { _0x5adcx93["contentInner"]["html"](_0x5adcx2f); _0x5adcx6["$elem"]["trigger"](_0x5adcx57, { tab: _0x5adcx93["tab"], content: _0x5adcx93["content"], index: _0x5adcx93["index"] }); }, _0x5adcx6["settings"]["delayAjax"]); } }); return _0x5adcx6; }, showContent: function (_0x5adcx6, _0x5adcx93) { if (_0x5adcx93["preIndex"] !== _0x5adcx93["index"] && _0x5adcx6["settings"]["animating"] !== true) { _0x5adcx6["settings"]["animating"] = true; _0x5adcx6["$contents"]["removeClass"](_0x5adcx4e["classes"]["active"]); _0x5adcx93["content"]["addClass"](_0x5adcx4e["classes"]["active"]); if (_0x5adcx93["preIndex"] === -1) { _0x5adcxd3["init"](_0x5adcx6, _0x5adcx93); } else { var _0x5adcx9d = _0x5adcx85["getElementSize"](_0x5adcx93["preContent"])["height"]; var _0x5adcx9e = _0x5adcx85["getElementSize"](_0x5adcx93["content"])["height"]; var _0x5adcx9f = _0x5adcx85["getContentHeight"](_0x5adcx6, null, true)["height"]; if (_0x5adcx6["settings"]["orientation"] === _0x5adcx66 && _0x5adcx6["settings"]["autoContentHeight"] === true) { _0x5adcx9f = (_0x5adcx9d > _0x5adcx9e) ? _0x5adcx9d : _0x5adcx9e; }; var _0x5adcxa0 = (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideH"] || _0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideRight"]) ? _0x5adcx6["$container"]["width"]() : _0x5adcxa0 = _0x5adcx9f; if (_0x5adcx93["preIndex"] < _0x5adcx93["index"] && _0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideV"]) { var _0x5adcxa1 = _0x5adcx85["isLarger"](_0x5adcx9d, _0x5adcx9e); (_0x5adcxa1 > _0x5adcxa0) && (_0x5adcxa0 = _0x5adcxa1); }; var _0x5adcxa2 = -_0x5adcxa0; var _0x5adcxa3 = _0x5adcxa0; if (_0x5adcx93["preIndex"] > _0x5adcx93["index"]) { _0x5adcxa2 = _0x5adcxa0; _0x5adcxa3 = -_0x5adcxa0; }; _0x5adcxd3["before"](_0x5adcx6, _0x5adcx93); if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideH"]) { _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["preContent"], null, { "left": _0x5adcxa2 + "px" }); _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["content"], { "left": _0x5adcxa3 + "px" }, { "left": 0 + "px" }); } else { if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideV"]) { _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["preContent"], null, { "left": 0, "top": _0x5adcxa2 + "px" }); _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["content"], { "left": 0, "top": _0x5adcxa3 + "px" }, { "top": 0 + "px" }); } else { if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideRight"]) { } else { if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["slideTop"]) { _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["preContent"], { "opacity": 1 }, { "left": 0, "top": (-_0x5adcxa0) + "px", "opacity": 0 }); _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["content"], { "left": 0, "top": (-_0x5adcxa0) + "px", "opacity": 0 }, { "top": 0 + "px", "opacity": 1 }); } else { if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["fade"]) { _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["preContent"], { "display": "block" }, { "opacity": 0 }); _0x5adcx85["animate"](_0x5adcx6, _0x5adcx93["content"], { "display": "block", "opacity": 0 }, { "opacity": 1 }); } else { if (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["none"]) { _0x5adcx6["$contents"]["css"]({ "position": "absolute", "left": 0, "top": 0 })["removeClass"](_0x5adcx4e["classes"]["active"])["hide"]()["eq"](_0x5adcx93["index"])["addClass"](_0x5adcx4e["classes"]["active"])["css"]({ "position": "relative" })["show"](); }; }; }; }; }; }; _0x5adcxd3["after"](_0x5adcx6, _0x5adcx93); }; }; }, refreshParents: function (_0x5adcx6, _0x5adcx25) { setTimeout(function () { _0x5adcx6["$elem"]["parents"](".z-tabs")["each"](function (_0x5adcx16, _0x5adcx74) { _0x5adcx1(_0x5adcx74)["data"]("zozoTabs")["refresh"](); }); }, _0x5adcx25); }, animate: function (_0x5adcx6, _0x5adcx20, _0x5adcx21, _0x5adcx22, _0x5adcx23, _0x5adcx24) { _0x5adcx1["zozo"]["core"]["utils"]["animate"](_0x5adcx6, _0x5adcx20, _0x5adcx21, _0x5adcx22, _0x5adcx23, _0x5adcx24); }, mobileNav: function (_0x5adcx6, _0x5adcxa4, _0x5adcx92) { if (_0x5adcx92 !== null && _0x5adcx6["$mobileNav"]) { _0x5adcx6["$mobileNav"]["find"]("\x3E li \x3E a \x3E span.z-title")["html"](_0x5adcx6["$tabs"]["eq"](_0x5adcx92)["find"]("a")["html"]()); }; if (_0x5adcxa4 === true) { setTimeout(function () { _0x5adcx6["$mobileNav"]["removeClass"](_0x5adcx4e["states"]["closed"]); }, _0x5adcx6["settings"]["animation"]["mobileDuration"]); _0x5adcx6["$tabGroup"]["removeClass"]("z-hide-menu");;; } else { (_0x5adcx6["$mobileNav"]) && _0x5adcx6["$mobileNav"]["addClass"](_0x5adcx4e["states"]["closed"]); _0x5adcx6["$tabGroup"]["addClass"]("z-hide-menu"); }; }, setResponsiveDimension: function (_0x5adcx6, _0x5adcxa5, _0x5adcxa6) { var _0x5adcxa7 = _0x5adcx6["$container"]; _0x5adcx6["settings"]["original"]["count"] = parseInt(_0x5adcx6["$tabs"]["size"]()); if (!_0x5adcxa6) { _0x5adcx6["settings"]["original"]["itemD"] = parseInt(_0x5adcxa7["width"]() / _0x5adcx6["settings"]["original"]["itemWidth"]); _0x5adcx6["settings"]["original"]["itemM"] = _0x5adcx6["settings"]["original"]["itemWidth"] + _0x5adcx6["settings"]["original"]["itemM"]; }; _0x5adcx6["settings"]["original"]["firstRowWidth"] = (_0x5adcx6["settings"]["original"]["itemWidth"] / (parseInt(_0x5adcx6["settings"]["original"]["itemD"]) * _0x5adcx6["settings"]["original"]["itemWidth"]) * 100); _0x5adcx6["settings"]["original"]["itemCount"] = parseInt(_0x5adcx6["settings"]["original"]["itemD"]) * parseInt(_0x5adcx6["settings"]["original"]["count"] / (parseInt(_0x5adcx6["settings"]["original"]["itemD"]))); _0x5adcx6["settings"]["original"]["lastItem"] = 100 / (parseInt(_0x5adcx6["settings"]["original"]["count"]) - parseInt(_0x5adcx6["settings"]["original"]["itemCount"])); _0x5adcx6["settings"]["original"]["navHeight"] = _0x5adcx6["settings"]["original"]["itemD"] * (parseInt(_0x5adcx6["$tabs"]["eq"](0)["innerHeight"]())) + ((_0x5adcx6["settings"]["original"]["itemM"] > 0 ? _0x5adcx6["$tabs"]["eq"](0)["innerHeight"]() : 0)); _0x5adcx6["settings"]["original"]["bottomLeft"] = _0x5adcx6["settings"]["original"]["count"] - (_0x5adcx6["settings"]["original"]["count"] - _0x5adcx6["settings"]["original"]["itemCount"]); _0x5adcx6["settings"]["original"]["rows"] = _0x5adcx6["settings"]["original"]["count"] > _0x5adcx6["settings"]["original"]["itemCount"] ? parseInt(_0x5adcx6["settings"]["original"]["itemCount"] / _0x5adcx6["settings"]["original"]["itemD"]) + 1 : parseInt(_0x5adcx6["settings"]["original"]["itemCount"] / _0x5adcx6["settings"]["original"]["itemD"]); _0x5adcx6["settings"]["original"]["lastRowItems"] = _0x5adcx6["settings"]["original"]["count"] - (_0x5adcx6["settings"]["original"]["itemCount"] * (_0x5adcx6["settings"]["original"]["rows"] - 1)); _0x5adcx6["settings"]["original"]["itemsPerRow"] = _0x5adcx6["settings"]["original"]["itemCount"] / _0x5adcx6["settings"]["original"]["rows"]; if (_0x5adcxa7["width"]() > _0x5adcxa5 && !_0x5adcxa6) { _0x5adcx6["settings"]["original"]["itemD"] = _0x5adcx6["settings"]["original"]["count"]; _0x5adcx6["settings"]["original"]["itemM"] = 0; _0x5adcx6["settings"]["original"]["rows"] = 1; _0x5adcx6["settings"]["original"]["itemCount"] = _0x5adcx6["settings"]["original"]["count"]; }; return _0x5adcx6; }, checkWidth: function (_0x5adcx6, _0x5adcx86, _0x5adcx88) { var _0x5adcxa5 = 0; var _0x5adcxa7 = _0x5adcx6["$container"]; var _0x5adcxa8 = _0x5adcx85["isCompact"](_0x5adcx6); var _0x5adcxa9 = 0; var _0x5adcxaa = _0x5adcx6["settings"]["tabRatio"]; var _0x5adcxab = _0x5adcx6["settings"]["tabRatioCompact"]; _0x5adcx6["$tabs"]["each"](function (_0x5adcx16) { var _0x5adcxac = _0x5adcx1(this)["outerWidth"](true) * _0x5adcxaa; (_0x5adcxa8) && (_0x5adcxac = (_0x5adcxac * _0x5adcxab)); if (_0x5adcx86 === true) { if (_0x5adcxac > _0x5adcx6["settings"]["original"]["itemWidth"]) { _0x5adcx6["settings"]["original"]["itemWidth"] = _0x5adcxac; _0x5adcx6["settings"]["original"]["itemMaxWidth"] = _0x5adcxac; }; if (_0x5adcxac < _0x5adcx6["settings"]["original"]["itemMinWidth"]) { _0x5adcx6["settings"]["original"]["itemMinWidth"] = _0x5adcxac; }; }; _0x5adcxa9 = _0x5adcxa9 + _0x5adcx1(this)["innerHeight"](); _0x5adcxa5 = _0x5adcxa5 + _0x5adcxac; }); if (_0x5adcx86 === true) { _0x5adcxa5 = _0x5adcxa5 + (_0x5adcx6["settings"]["original"]["itemWidth"] * 0); }; _0x5adcx6["settings"]["original"]["count"] = parseInt(_0x5adcx6["$tabs"]["size"]()); _0x5adcx6["settings"]["original"]["groupWidth"] = _0x5adcxa5; _0x5adcx85["setResponsiveDimension"](_0x5adcx6, _0x5adcx6["settings"]["original"]["groupWidth"]); if (_0x5adcx6["settings"]["original"]["count"] > 3 && _0x5adcx6["settings"]["original"]["lastRowItems"] === 1) { _0x5adcx6["settings"]["original"]["itemD"] = _0x5adcx6["settings"]["original"]["itemD"] - 1; _0x5adcx6["settings"]["original"]["itemM"] = _0x5adcxa7["width"]() % _0x5adcx6["settings"]["original"]["itemWidth"]; _0x5adcx85["setResponsiveDimension"](_0x5adcx6, _0x5adcx6["settings"]["original"]["groupWidth"], true); }; if (_0x5adcx86 === true || _0x5adcx88 === true) { _0x5adcx6["settings"]["original"]["initGroupWidth"] = _0x5adcx6["settings"]["original"]["groupWidth"]; if (_0x5adcx85["isCompact"](_0x5adcx6)) { var _0x5adcxad = 100 / _0x5adcx6["settings"]["original"]["count"]; _0x5adcx6["$tabs"]["each"](function () { _0x5adcx1(this)["css"]({ "width": _0x5adcxad + "%" }); }); }; _0x5adcx6["settings"]["original"]["position"] = _0x5adcx6["settings"]["position"]; }; if (_0x5adcx6["settings"]["responsive"] === true) { _0x5adcx85["responsive"](_0x5adcx6, _0x5adcx86); }; var _0x5adcxae = ((_0x5adcx85["isCompact"](_0x5adcx6) && !_0x5adcx85["isMobile"](_0x5adcx6))); var _0x5adcxaf = (_0x5adcx85["isResponsive"](_0x5adcx6) && _0x5adcx6["BrowserDetection"]["isIE"](7)) ? { "float": "none", "width": "auto" } : { "float": "" }; var _0x5adcxb0 = _0x5adcx6["$elem"]["hasClass"](_0x5adcx72); _0x5adcx6["$tabs"]["each"](function (_0x5adcx16) { if (((_0x5adcxb0 === true && (_0x5adcx16 + 1) === _0x5adcx6["settings"]["original"]["itemD"]) || (_0x5adcx16 + 1) === _0x5adcx6["settings"]["original"]["count"]) && _0x5adcxae) { _0x5adcx1(this)["css"](_0x5adcxaf); } else { _0x5adcx1(this)["css"]({ "float": "" }); }; }); if (_0x5adcx6["settings"]["orientation"] === _0x5adcx65) { _0x5adcx85["setContentHeight"](_0x5adcx6, null, true); }; }, checkModes: function (_0x5adcx6) { var _0x5adcxa8 = _0x5adcx85["isCompact"](_0x5adcx6); if (_0x5adcx6["settings"]["mode"] === _0x5adcx4e["modes"]["stacked"]) { _0x5adcx6["$elem"]["addClass"](_0x5adcx5d); _0x5adcx6["$elem"]["addClass"](_0x5adcx72); _0x5adcx6["$tabs"]["css"]({ "width": "" }); (_0x5adcx6["$mobileNav"]) && _0x5adcx6["$mobileNav"]["hide"](); } else { if (_0x5adcxa8) { var _0x5adcxad = 100 / _0x5adcx6["settings"]["original"]["count"]; _0x5adcx6["$tabs"]["each"](function () { _0x5adcx1(this)["css"]({ "float": "", "width": _0x5adcxad + "%" }); }); } else { _0x5adcx6["$tabs"]["each"](function () { _0x5adcx1(this)["css"]({ "float": "", "width": "" }); }); }; }; }, getContentHeight: function (_0x5adcx6, _0x5adcxb1, _0x5adcxb2) { var _0x5adcxb3 = _0x5adcx6["settings"]["autoContentHeight"]; var _0x5adcxb4 = { width: 0, height: 0 }; if (_0x5adcxb3 != true) { _0x5adcx6["$contents"]["each"](function (_0x5adcx16, _0x5adcx74) { var _0x5adcx8 = _0x5adcx1(_0x5adcx74); var _0x5adcxb5 = _0x5adcx85["getElementSize"](_0x5adcx8); (_0x5adcxb5["height"] > _0x5adcxb4["height"]) && (_0x5adcxb4["height"] = _0x5adcxb5["height"]); (_0x5adcxb5["width"] > _0x5adcxb4["width"]) && (_0x5adcxb4["width"] = _0x5adcxb5["width"]); }); } else { var _0x5adcxb6 = _0x5adcx6["$elem"]["find"]("\x3E .z-container \x3E .z-content.z-active"); if (_0x5adcxb1 != null) { _0x5adcxb6 = _0x5adcxb1; }; _0x5adcxb4["height"] = _0x5adcx85["getElementSize"](_0x5adcxb6)["height"]; }; if (_0x5adcx6["settings"]["orientation"] === _0x5adcx65 && !_0x5adcx85["isMobile"](_0x5adcx6)) { var _0x5adcxb7 = 0; _0x5adcx6["$tabs"]["each"](function (_0x5adcx16) { _0x5adcxb7 = _0x5adcxb7 + parseInt(_0x5adcx1(this)["height"]()) + parseInt(_0x5adcx1(this)["css"]("border-top-width")) + parseInt(_0x5adcx1(this)["css"]("border-bottom-width")); }); _0x5adcxb4["height"] = _0x5adcx85["isLarger"](_0x5adcxb4["height"], _0x5adcx6["$tabGroup"]["innerHeight"]()); _0x5adcxb4["height"] = _0x5adcx85["isLarger"](_0x5adcxb4["height"], _0x5adcxb7); }; return _0x5adcxb4; }, setContentHeight: function (_0x5adcx6, _0x5adcxb1, _0x5adcxb2) { var _0x5adcxb4 = _0x5adcx85["getContentHeight"](_0x5adcx6, _0x5adcxb1, _0x5adcxb2); _0x5adcx6["settings"]["original"]["contentMaxHeight"] = _0x5adcxb4["height"]; _0x5adcx6["settings"]["original"]["contentMaxWidth"] = _0x5adcxb4["width"]; var _0x5adcx25 = (_0x5adcx6["settings"]["animation"]["effects"] === _0x5adcx4e["animation"]["effects"]["none"] || _0x5adcxb2 === true) ? 0 : _0x5adcx6["settings"]["animation"]["duration"]; var _0x5adcxb3 = _0x5adcx6["settings"]["autoContentHeight"]; var _0x5adcx27 = _0x5adcx1["zozo"]["core"]["support"]["css"]["transition"]; var _0x5adcxb8 = { _transition: "none", "min-height": _0x5adcx6["settings"]["original"]["contentMaxHeight"] + "px" }; if (_0x5adcxb2 === true) { _0x5adcx6["$container"]["css"](_0x5adcxb8); } else { _0x5adcx85["animate"](_0x5adcx6, _0x5adcx6.$container, null, _0x5adcxb8, {}); }; return _0x5adcx6; }, responsive: function (_0x5adcx6, _0x5adcx86) { var _0x5adcxb9 = _0x5adcx1(_0x5adcx2)["width"](); var _0x5adcx8c = _0x5adcx85["isTop"](_0x5adcx6); var _0x5adcxa8 = _0x5adcx85["isCompact"](_0x5adcx6); var _0x5adcxba = _0x5adcx6["settings"]["original"]["initGroupWidth"] >= _0x5adcx6["$container"]["width"](); var _0x5adcxbb = _0x5adcx6["settings"]["original"]["rows"] > _0x5adcx6["settings"]["maxRows"]; var _0x5adcxbc = _0x5adcxb9 <= _0x5adcx6["settings"]["minWindowWidth"]; var _0x5adcxbd = (!_0x5adcx6["BrowserDetection"]["isIE"](8) && _0x5adcx6["settings"]["mobileNav"] === true && _0x5adcx6["$mobileNav"] != null); var _0x5adcx83 = _0x5adcx6["settings"]["original"]["count"]; var _0x5adcxbe = _0x5adcx6["settings"]["original"]["itemCount"]; var _0x5adcxbf = _0x5adcx6["settings"]["original"]["itemD"]; var _0x5adcxc0 = _0x5adcx6["settings"]["original"]["rows"]; _0x5adcx6["$elem"]["removeClass"](_0x5adcx5d); _0x5adcx6["$tabs"]["removeClass"](_0x5adcx4e["classes"]["left"])["removeClass"](_0x5adcx4e["classes"]["right"])["removeClass"](_0x5adcx4e["classes"]["firstCol"])["removeClass"](_0x5adcx4e["classes"]["lastCol"])["removeClass"](_0x5adcx4e["classes"]["firstRow"])["removeClass"](_0x5adcx4e["classes"]["lastRow"]); if (_0x5adcx6["settings"]["orientation"] === _0x5adcx66) { var _0x5adcxc1 = (_0x5adcxa8 && (parseInt(_0x5adcx6["settings"]["original"]["count"] * _0x5adcx6["settings"]["original"]["itemWidth"]) >= _0x5adcx6["$container"]["width"]())); var _0x5adcxc2 = (!_0x5adcxa8 && _0x5adcxba); var _0x5adcxc3 = _0x5adcxc1 || _0x5adcxc2; if (_0x5adcxc3) { (_0x5adcxc0 === _0x5adcx83 || (_0x5adcx6["settings"]["mode"] === _0x5adcx4e["modes"]["stacked"])) && (_0x5adcx6["$elem"]["addClass"](_0x5adcx5d)); _0x5adcx6["$tabs"]["each"](function (_0x5adcx16) { var _0x5adcxc4 = _0x5adcx1(this); var _0x5adcx82 = (_0x5adcx16 + 1); if (_0x5adcx6["settings"]["original"]["itemM"] > 0) { if (_0x5adcx82 <= _0x5adcxbe) { _0x5adcxc4["css"]({ "float": "", "width": _0x5adcx6["settings"]["original"]["firstRowWidth"] + "%" }); } else { _0x5adcxc4["css"]({ "float": "", "width": _0x5adcx6["settings"]["original"]["lastItem"] + "%" }); }; if (_0x5adcx8c === true) { (_0x5adcx16 === (_0x5adcxbf - 1)) ? _0x5adcxc4["addClass"](_0x5adcx4e["classes"]["right"]) : _0x5adcxc4["removeClass"](_0x5adcx4e["classes"]["right"]); } else { (_0x5adcx82 === _0x5adcx83) && (_0x5adcxc4["addClass"](_0x5adcx4e["classes"]["right"])); (_0x5adcx16 === _0x5adcx6["settings"]["original"]["bottomLeft"]) && (_0x5adcxc4["addClass"](_0x5adcx4e["classes"]["left"])); }; if (_0x5adcxc0 > 1 && _0x5adcxbf !== 1) { (_0x5adcx82 === 1 || (_0x5adcx82 > _0x5adcxbf && (_0x5adcx82 % _0x5adcxbf === 1))) && (_0x5adcxc4["addClass"](_0x5adcx4e["classes"]["firstCol"])); (_0x5adcx82 === _0x5adcx83 || (_0x5adcx82 >= _0x5adcxbf && (_0x5adcx82 % _0x5adcxbf === 0))) && (_0x5adcxc4["addClass"](_0x5adcx4e["classes"]["lastCol"])); (_0x5adcx82 <= _0x5adcxbf) && (_0x5adcxc4["addClass"](_0x5adcx4e["classes"]["firstRow"])); (_0x5adcx82 > (_0x5adcxbf * (_0x5adcxc0 - 1))) && (_0x5adcxc4["addClass"](_0x5adcx4e["classes"]["lastRow"])); }; }; }); _0x5adcx85["switchResponsiveClasses"](_0x5adcx6, true); } else { if (_0x5adcxa8) { var _0x5adcxad = 100 / _0x5adcx6["settings"]["original"]["count"]; _0x5adcx6["$tabs"]["each"](function () { _0x5adcx1(this)["css"]({ "float": "", "width": _0x5adcxad + "%" }); }); } else { _0x5adcx6["$tabs"]["each"](function () { _0x5adcx1(this)["css"]({ "float": "", "width": "" }); }); }; _0x5adcx85["switchResponsiveClasses"](_0x5adcx6, false); }; if (_0x5adcxb9 >= 1200 && _0x5adcx6["responsive"] != _0x5adcx4e["responsive"]["largeDesktop"]) { _0x5adcx6["responsive"] = _0x5adcx4e["responsive"]["largeDesktop"]; _0x5adcx85["switchMenu"](_0x5adcx6, false); }; if (_0x5adcx6["responsive"] != _0x5adcx4e["responsive"]["phone"] && _0x5adcxbd && ((_0x5adcxbc) || ((_0x5adcxbb)))) { _0x5adcx6["responsive"] = "auto"; _0x5adcx6["$elem"]["removeClass"](_0x5adcx72); _0x5adcx6["$tabs"]["each"](function () { _0x5adcx1(this)["css"]({ "width": "" }); }); _0x5adcx6["$tabs"]["filter"]("li:first-child")["addClass"](_0x5adcx4e["classes"]["first"]); _0x5adcx6["$tabs"]["filter"]("li:last-child")["addClass"](_0x5adcx4e["classes"]["last"]); _0x5adcx85["switchMenu"](_0x5adcx6, true); }; } else { if (_0x5adcxbd === true && (_0x5adcxbc || parseInt(_0x5adcx6["$elem"]["width"]() - _0x5adcx6["settings"]["original"]["itemWidth"]) < _0x5adcx6["settings"]["minWidth"])) { _0x5adcx85["switchMenu"](_0x5adcx6, true); } else { _0x5adcx85["switchMenu"](_0x5adcx6, false); }; }; _0x5adcx85["refreshParents"](_0x5adcx6, 0); }, switchResponsiveClasses: function (_0x5adcx6, _0x5adcxc5) { var _0x5adcx8c = _0x5adcx85["isTop"](_0x5adcx6); var _0x5adcxc6 = _0x5adcx6["settings"]["original"]["position"]; var _0x5adcxc7 = _0x5adcx4e["classes"]["positions"]["topLeft"]; var _0x5adcxc8 = _0x5adcx4e["classes"]["positions"]["bottomLeft"]; if (_0x5adcxc5 === true) { _0x5adcx6["$elem"]["addClass"](_0x5adcx72); _0x5adcx85["switchMenu"](_0x5adcx6, false); _0x5adcx6["$elem"]["removeClass"](_0x5adcxc6); } else { (_0x5adcx8c === true) ? _0x5adcx6["$elem"]["removeClass"](_0x5adcxc7)["addClass"](_0x5adcxc6) : _0x5adcx6["$elem"]["removeClass"](_0x5adcxc8)["addClass"](_0x5adcxc6); _0x5adcx85["switchMenu"](_0x5adcx6, false); _0x5adcx6["$elem"]["removeClass"](_0x5adcx72); _0x5adcx6["$tabs"]["removeClass"](_0x5adcx4e["classes"]["last"])["filter"]("li:last-child")["addClass"](_0x5adcx4e["classes"]["last"]); }; }, switchMenu: function (_0x5adcx6, _0x5adcxc9) { var _0x5adcxca = _0x5adcx4e["classes"]["themes"]; var _0x5adcxcb = _0x5adcx4e["classes"]["sizes"]; var _0x5adcx8b = _0x5adcx1["zozo"]["core"]["utils"]["toArray"](_0x5adcx4e["classes"]["positions"]); _0x5adcx6["$elem"]["removeClass"](_0x5adcxca["join"](_0x5adcx4e["space"])); if (_0x5adcxc9 === true) { (_0x5adcx6["$mobileNav"]) && _0x5adcx6["$mobileNav"]["addClass"](_0x5adcx4e["states"]["closed"])["show"](); _0x5adcx6["$tabGroup"]["addClass"]("z-hide-menu"); _0x5adcx6["$elem"]["addClass"](_0x5adcx6d); _0x5adcx6["$elem"]["removeClass"](_0x5adcx6["settings"]["orientation"]); _0x5adcx6["$elem"]["removeClass"](_0x5adcx6["settings"]["position"]); (_0x5adcx6["settings"]["style"] === _0x5adcx61) ? _0x5adcx6["$elem"]["addClass"]("m-" + _0x5adcx6["settings"]["theme"]) : _0x5adcx6["$elem"]["addClass"](_0x5adcx6["settings"]["theme"]); } else { _0x5adcx6["$elem"]["addClass"](_0x5adcx6["settings"]["orientation"]); _0x5adcx6["$elem"]["addClass"](_0x5adcx6["settings"]["theme"]); _0x5adcx6["$elem"]["addClass"](_0x5adcx6["settings"]["position"]); (_0x5adcx6["$mobileNav"]) && _0x5adcx6["$mobileNav"]["removeClass"](_0x5adcx4e["states"]["closed"]); _0x5adcx6["$tabGroup"]["removeClass"]("z-hide-menu"); _0x5adcx6["$tabs"]["filter"]("li:first-child")["addClass"](_0x5adcx4e["classes"]["first"]); _0x5adcx6["$elem"]["removeClass"](_0x5adcx6d); (_0x5adcx6["$mobileNav"]) && _0x5adcx6["$mobileNav"]["hide"](); }; }, initAutoPlay: function (_0x5adcx6) { if (_0x5adcx6["settings"]["autoplay"] !== false && _0x5adcx6["settings"]["autoplay"] != null) { if (_0x5adcx6["settings"]["autoplay"]["interval"] > 0) { _0x5adcx6["stop"](); _0x5adcx6["autoplayIntervalId"] = setInterval(function () { _0x5adcx6["next"](_0x5adcx6); }, _0x5adcx6["settings"]["autoplay"]["interval"]); } else { _0x5adcx6["stop"](); }; } else { _0x5adcx6["stop"](); }; }, changeHash: function (_0x5adcx6, _0x5adcx76) { var _0x5adcx75 = (_0x5adcx6["settings"]["deeplinkingPrefix"]) ? _0x5adcx6["settings"]["deeplinkingPrefix"] : _0x5adcx6["tabID"]; if (_0x5adcx6["settings"]["animating"] !== true) { if (_0x5adcx6["settings"]["deeplinking"] === true) { if (typeof (_0x5adcx1(_0x5adcx2)["hashchange"]) != "undefined") { _0x5adcx6["Deeplinking"]["set"](_0x5adcx75, _0x5adcx76, _0x5adcx6["settings"]["deeplinkingSeparator"], _0x5adcx6["settings"]["deeplinkingMode"]); } else { if (_0x5adcx6["BrowserDetection"]["isIE"](7)) { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx76); } else { _0x5adcx6["Deeplinking"]["set"](_0x5adcx75, _0x5adcx76, _0x5adcx6["settings"]["deeplinkingSeparator"], _0x5adcx6["settings"]["deeplinkingMode"]); }; }; } else { _0x5adcx85["showTab"](_0x5adcx6, _0x5adcx76); }; }; }, getFirst: function (_0x5adcx6) { return 0; }, getLast: function (_0x5adcx6) { if (_0x5adcx6["settings"]["noTabs"] === true) { return parseInt(_0x5adcx6["$container"]["children"]("div")["size"]() - 1); }; return parseInt(_0x5adcx6["$tabGroup"]["children"]("li")["size"]() - 1); }, create: function (_0x5adcx1a, _0x5adcx1b) { var _0x5adcx1c = _0x5adcx1("\x3Cli\x3E\x3Ca\x3E" + _0x5adcx1a + "\x3C/a\x3E\x3C/li\x3E"); var _0x5adcx8 = _0x5adcx1("\x3Cdiv\x3E" + _0x5adcx1b + "\x3C/div\x3E"); return { tab: _0x5adcx1c, content: _0x5adcx8 }; }, isCompact: function (_0x5adcx6) { return (_0x5adcx6["settings"]["position"] === _0x5adcx4e["classes"]["positions"]["topCompact"] || _0x5adcx6["settings"]["position"] === _0x5adcx4e["classes"]["positions"]["bottomCompact"]); }, isTop: function (_0x5adcx6) { if (_0x5adcx6["settings"]["original"]["position"] === null) { _0x5adcx6["settings"]["original"]["position"] = _0x5adcx6["settings"]["position"]; }; return (_0x5adcx6["settings"]["original"]["position"]["indexOf"]("top") >= 0); }, isLightTheme: function (_0x5adcx6) { var _0x5adcxcc = ["red", "deepblue", "blue", "green", "orange", "black"]; var _0x5adcxcd = true; var _0x5adcxce = _0x5adcx85["isFlatTheme"](_0x5adcx6); if (_0x5adcx6["settings"]["style"] !== _0x5adcx61) { (_0x5adcx1["inArray"](_0x5adcx6["settings"]["theme"], _0x5adcxcc) > -1) && (_0x5adcxcd = false); (_0x5adcxce) && (_0x5adcxcd = false); }; return _0x5adcxcd; }, isFlatTheme: function (_0x5adcx6) { return (_0x5adcx6["settings"]["theme"]["indexOf"]("flat") >= 0); }, isResponsive: function (_0x5adcx6) { return (_0x5adcx6["$elem"]["hasClass"](_0x5adcx72) === true); }, isMobile: function (_0x5adcx6) { return (_0x5adcx6["$elem"]["hasClass"](_0x5adcx6d) === true); }, isTabDisabled: function (_0x5adcx1c) { return (_0x5adcx1c["hasClass"](_0x5adcx5c) || _0x5adcx1c["data"](_0x5adcx5a) === true); }, allowAutoScrolling: function (_0x5adcx6) { return (_0x5adcx6["settings"]["mobileAutoScrolling"] != null && _0x5adcx6["settings"]["mobileAutoScrolling"] != false); }, getElementSize: function (_0x5adcx8) { var _0x5adcxb4 = { width: 0, height: 0 }; if (_0x5adcx8 == null || _0x5adcx8["length"] == 0) { return _0x5adcxb4; }; if (_0x5adcx8["is"](":visible") === false) { _0x5adcxb4["height"] = _0x5adcx8["show"]()["find"]("\x3E.z-content-inner")["innerHeight"](); _0x5adcxb4["width"] = _0x5adcx8["show"]()["find"]("\x3E.z-content-inner")["outerWidth"](); if (_0x5adcxb4["height"] >= 0) { }; _0x5adcx8["hide"](); } else { _0x5adcxb4["height"] = _0x5adcx8["find"]("\x3E.z-content-inner")["innerHeight"](); _0x5adcxb4["width"] = _0x5adcx8["find"]("\x3E.z-content-inner")["outerWidth"](); if (_0x5adcxb4["height"] >= 0) { }; }; (_0x5adcx8["hasClass"]("z-video") && (_0x5adcxb4["height"] = _0x5adcx8["innerHeight"]())); return _0x5adcxb4; }, getWidth: function (_0x5adcxcf) { if (_0x5adcxcf == null || _0x5adcxcf["length"] == 0) { return 0; }; _0x5adcxcf = _0x5adcxcf["find"]("a"); var _0x5adcx17 = _0x5adcxcf["outerWidth"](); _0x5adcx17 += parseInt(_0x5adcxcf["css"]("margin-left"), 10) + parseInt(_0x5adcxcf["css"]("margin-right"), 10); _0x5adcx17 += parseInt(_0x5adcxcf["css"]("borderLeftWidth"), 10) + parseInt(_0x5adcxcf["css"]("borderRightWidth"), 10); return _0x5adcx17; }, isLarger: function (_0x5adcxd0, _0x5adcxd1) { var _0x5adcxd2 = _0x5adcxd0; if (_0x5adcxd0 < _0x5adcxd1) { _0x5adcxd2 = _0x5adcxd1; }; return _0x5adcxd2; } }; var _0x5adcxd3 = { init: function (_0x5adcx6, _0x5adcx93) { _0x5adcx6["$contents"]["hide"](); _0x5adcx93["content"]["css"]({ "opacity": "", "left": "", "top": "", "position": "relative" })["show"](); setTimeout(function () { _0x5adcx6["$container"]["find"](".z-tabs")["each"](function (_0x5adcx16, _0x5adcx74) { _0x5adcx1(_0x5adcx74)["data"]("zozoTabs")["refresh"](); }); _0x5adcx6["$elem"]["trigger"](_0x5adcx52, { tab: _0x5adcx93["tab"], content: _0x5adcx93["content"], index: _0x5adcx93["index"] }); _0x5adcx6["settings"]["animating"] = false; }, _0x5adcx93["duration"] >= 0 ? 200 : _0x5adcx93["duration"]); if (_0x5adcx6["settings"]["orientation"] === _0x5adcx65) { _0x5adcx85["setContentHeight"](_0x5adcx6, _0x5adcx93["content"], true); }; return _0x5adcx6; }, before: function (_0x5adcx6, _0x5adcx93) { setTimeout(function () { _0x5adcx93["content"]["find"](".z-tabs")["each"](function (_0x5adcx16, _0x5adcx74) { _0x5adcx1(_0x5adcx74)["data"]("zozoTabs")["refresh"](); }); }, 50); if (_0x5adcx6["settings"]["animation"]["effects"] !== _0x5adcx4e["animation"]["effects"]["none"]) { _0x5adcx85["setContentHeight"](_0x5adcx6, _0x5adcx93["preContent"], true); _0x5adcx85["setContentHeight"](_0x5adcx6, _0x5adcx93["content"]); }; _0x5adcx6["$container"]["addClass"](_0x5adcx70); _0x5adcx93["preContent"]["css"]({ "position": "absolute", "display": "block", "left": 0, "top": 0 }); _0x5adcx93["content"]["css"]({ "position": "absolute", "display": "block" }); return _0x5adcx6; }, after: function (_0x5adcx6, _0x5adcx93) { setTimeout(function () { _0x5adcx93["content"]["css"]({ "position": "relative" }); _0x5adcx93["preContent"]["css"]({ "display": "none" }); }, _0x5adcx93["duration"]); setTimeout(function () { _0x5adcx6["$elem"]["trigger"](_0x5adcx52, { tab: _0x5adcx93["tab"], content: _0x5adcx93["content"], index: _0x5adcx93["index"] }); _0x5adcx6["$elem"]["trigger"](_0x5adcx54, { tab: _0x5adcx93["preTab"], content: _0x5adcx93["preContent"], index: _0x5adcx93["preIndex"] }); var _0x5adcxb8 = (_0x5adcx6["settings"]["orientation"] === _0x5adcx65) ? { "height": "" } : { "height": "", "min-height": "", "overflow": "" }; _0x5adcx6["$container"]["css"](_0x5adcxb8); _0x5adcx6["$container"]["removeClass"](_0x5adcx70); setTimeout(function () { _0x5adcx6["$contents"]["removeClass"](_0x5adcx4e["classes"]["active"])["eq"](_0x5adcx93["index"])["addClass"](_0x5adcx4e["classes"]["active"]); _0x5adcx6["settings"]["animating"] = false; _0x5adcx6["$contents"]["stop"](true, true); }); }, _0x5adcx93["duration"] + 50); return _0x5adcx6; } }; _0x5adcx4b["defaults"] = _0x5adcx4b["prototype"]["defaults"]; _0x5adcx1["fn"]["zozoTabs"] = function (_0x5adcx4d) { return this["each"](function () { if (_0x5adcx4 == _0x5adcx1(this)["data"](_0x5adcx4e["pluginName"])) { var _0x5adcxd4 = new _0x5adcx4b(this, _0x5adcx4d)["init"](); _0x5adcx1(this)["data"](_0x5adcx4e["pluginName"], _0x5adcxd4); }; }); }; _0x5adcx2["zozo"]["tabs"] = _0x5adcx4b; _0x5adcx1(_0x5adcx3)["ready"](function () { _0x5adcx1("[data-role=\x27z-tabs\x27]")["each"](function (_0x5adcx16, _0x5adcx74) { if (!_0x5adcx1(_0x5adcx74)["zozoTabs"]()) { _0x5adcx1(_0x5adcx74)["zozoTabs"](); }; }); }); })(jQuery, window, document);









 
/////////////////////////////////////////////
// SmoothScroll PLUGIN
/////////////////////////////////////////////

(function ($)
{
    $.extend({
        smoothScroll: function ()
        {
            // Scroll Variables (tweakable)
            var defaultOptions = {
                // Scrolling Core
                frameRate: 150, // [Hz]
                animationTime: 700, // [px]
                stepSize: 80, // [px]

                // Pulse (less tweakable)
                // ratio of "tail" to "acceleration"
                pulseAlgorithm: true,
                pulseScale: 8,
                pulseNormalize: 1,

                // Acceleration
                accelerationDelta: 20,  // 20
                accelerationMax: 1,   // 1

                // Keyboard Settings
                keyboardSupport: true,  // option
                arrowScroll: 50,     // [px]

                // Other
                touchpadSupport: true,
                fixedBackground: true,
                excluded: ""
            };

            var options = defaultOptions;

            // Other Variables
            var isExcluded = false;
            var isFrame = false;
            var direction = { x: 0, y: 0 };
            var initDone = false;
            var root = document.documentElement;
            var activeElement;
            var observer;
            var deltaBuffer = [120, 120, 120];

            var key = {
                left: 37, up: 38, right: 39, down: 40, spacebar: 32,
                pageup: 33, pagedown: 34, end: 35, home: 36
            };

            /***********************************************
			 * INITIALIZE
			 ***********************************************/

            /**
			 * Sets up scrolls array, determines if frames are involved.
			 */
            function init()
            {
                if (!document.body) return;

                var body = document.body;
                var html = document.documentElement;
                var windowHeight = window.innerHeight;
                var scrollHeight = body.scrollHeight;

                // check compat mode for root element
                root = (document.compatMode.indexOf('CSS') >= 0) ? html : body;
                activeElement = body;

                initDone = true;

                // Checks if this script is running in a frame
                if (top != self)
                {
                    isFrame = true;
                }

                    /**
                     * This fixes a bug where the areas left and right to
                     * the content does not trigger the onmousewheel event
                     * on some pages. e.g.: html, body { height: 100% }
                     */
                else if (scrollHeight > windowHeight &&
						(body.offsetHeight <= windowHeight ||
						 html.offsetHeight <= windowHeight))
                {
                    // DOMChange (throttle): fix height
                    var pending = false;
                    var refresh = function ()
                    {
                        if (!pending && html.scrollHeight != document.height)
                        {
                            pending = true; // add a new pending action
                            setTimeout(function ()
                            {
                                html.style.height = document.height + 'px';
                                pending = false;
                            }, 500); // act rarely to stay fast
                        }
                    };
                    html.style.height = 'auto';
                    setTimeout(refresh, 10);

                    var config = {
                        attributes: true,
                        childList: true,
                        characterData: false
                    };

                    //observer = new MutationObserver(refresh);
                    //observer.observe(body, config);

                    // clearfix
                    if (root.offsetHeight <= windowHeight)
                    {
                        var underlay = document.createElement("div");
                        underlay.style.clear = "both";
                        body.appendChild(underlay);
                    }
                }

                // gmail performance fix
                if (document.URL.indexOf("mail.google.com") > -1)
                {
                    var s = document.createElement("style");
                    s.innerHTML = ".iu { visibility: hidden }";
                    (document.getElementsByTagName("head")[0] || html).appendChild(s);
                }
                    // facebook better home timeline performance
                    // all the HTML resized images make rendering CPU intensive
                else if (document.URL.indexOf("www.facebook.com") > -1)
                {
                    var home_stream = document.getElementById("home_stream");
                    home_stream && (home_stream.style.webkitTransform = "translateZ(0)");
                }
                // disable fixed background
                if (!options.fixedBackground && !isExcluded)
                {
                    body.style.backgroundAttachment = "scroll";
                    html.style.backgroundAttachment = "scroll";
                }
            }

            /************************************************
			 * SCROLLING
			 ************************************************/

            var que = [];
            var pending = false;
            var lastScroll = +new Date;

            /**
			 * Pushes scroll actions to the scrolling queue.
			 */
            function scrollArray(elem, left, top, delay)
            {
                delay || (delay = 1000);
                directionCheck(left, top);

                if (options.accelerationMax != 1)
                {
                    var now = +new Date;
                    var elapsed = now - lastScroll;
                    if (elapsed < options.accelerationDelta)
                    {
                        var factor = (1 + (30 / elapsed)) / 2;
                        if (factor > 1)
                        {
                            factor = Math.min(factor, options.accelerationMax);
                            left *= factor;
                            top *= factor;
                        }
                    }
                    lastScroll = +new Date;
                }

                // push a scroll command
                que.push({
                    x: left,
                    y: top,
                    lastX: (left < 0) ? 0.99 : -0.99,
                    lastY: (top < 0) ? 0.99 : -0.99,
                    start: +new Date
                });

                // don't act if there's a pending queue
                if (pending)
                {
                    return;
                }

                var scrollWindow = (elem === document.body);

                var step = function (time)
                {
                    var now = +new Date;
                    var scrollX = 0;
                    var scrollY = 0;

                    for (var i = 0; i < que.length; i++)
                    {
                        var item = que[i];
                        var elapsed = now - item.start;
                        var finished = (elapsed >= options.animationTime);

                        // scroll position: [0, 1]
                        var position = (finished) ? 1 : elapsed / options.animationTime;

                        // easing [optional]
                        if (options.pulseAlgorithm)
                        {
                            position = pulse(position);
                        }

                        // only need the difference
                        var x = (item.x * position - item.lastX) >> 0;
                        var y = (item.y * position - item.lastY) >> 0;

                        // add this to the total scrolling
                        scrollX += x;
                        scrollY += y;

                        // update last values
                        item.lastX += x;
                        item.lastY += y;

                        // delete and step back if it's over
                        if (finished)
                        {
                            que.splice(i, 1); i--;
                        }
                    }

                    // scroll left and top
                    if (scrollWindow)
                    {
                        window.scrollBy(scrollX, scrollY);
                    }
                    else
                    {
                        if (scrollX) elem.scrollLeft += scrollX;
                        if (scrollY) elem.scrollTop += scrollY;
                    }

                    // clean up if there's nothing left to do
                    if (!left && !top)
                    {
                        que = [];
                    }

                    if (que.length)
                    {
                        requestFrame(step, elem, (delay / options.frameRate + 1));
                    } else
                    {
                        pending = false;
                    }
                };

                // start a new queue of actions
                requestFrame(step, elem, 0);
                pending = true;
            }

            /***********************************************
			 * EVENTS
			 ***********************************************/

            /**
			 * Mouse wheel handler.
			 * @param {Object} event
			 */
            function wheel(event)
            {
                if (!initDone)
                {
                    init();
                }

                var target = event.target;
                var overflowing = overflowingAncestor(target);

                // use default if there's no overflowing
                // element or default action is prevented
                if (!overflowing || event.defaultPrevented ||
					isNodeName(activeElement, "embed") ||
				   (isNodeName(target, "embed") && /\.pdf/i.test(target.src)))
                {
                    return true;
                }

                var deltaX = event.wheelDeltaX || 0;
                var deltaY = event.wheelDeltaY || 0;

                // use wheelDelta if deltaX/Y is not available
                if (!deltaX && !deltaY)
                {
                    deltaY = event.wheelDelta || 0;
                }

                // check if it's a touchpad scroll that should be ignored
                if (!options.touchpadSupport && isTouchpad(deltaY))
                {
                    return true;
                }

                // scale by step size
                // delta is 120 most of the time
                // synaptics seems to send 1 sometimes
                if (Math.abs(deltaX) > 1.2)
                {
                    deltaX *= options.stepSize / 120;
                }
                if (Math.abs(deltaY) > 1.2)
                {
                    deltaY *= options.stepSize / 120;
                }

                scrollArray(overflowing, -deltaX, -deltaY);
                event.preventDefault();
            }

            /**
			 * Keydown event handler.
			 * @param {Object} event
			 */
            function keydown(event)
            {
                var target = event.target;
                var modifier = event.ctrlKey || event.altKey || event.metaKey ||
							  (event.shiftKey && event.keyCode !== key.spacebar);

                // do nothing if user is editing text
                // or using a modifier key (except shift)
                // or in a dropdown
                if (/input|textarea|select|embed/i.test(target.nodeName) ||
					 target.isContentEditable ||
					 event.defaultPrevented ||
					 modifier)
                {
                    return true;
                }
                // spacebar should trigger button press
                if (isNodeName(target, "button") &&
					event.keyCode === key.spacebar)
                {
                    return true;
                }

                var shift, x = 0, y = 0;
                var elem = overflowingAncestor(activeElement);
                var clientHeight = elem.clientHeight;

                if (elem == document.body)
                {
                    clientHeight = window.innerHeight;
                }

                switch (event.keyCode)
                {
                    case key.up:
                        y = -options.arrowScroll;
                        break;
                    case key.down:
                        y = options.arrowScroll;
                        break;
                    case key.spacebar: // (+ shift)
                        shift = event.shiftKey ? 1 : -1;
                        y = -shift * clientHeight * 0.9;
                        break;
                    case key.pageup:
                        y = -clientHeight * 0.9;
                        break;
                    case key.pagedown:
                        y = clientHeight * 0.9;
                        break;
                    case key.home:
                        y = -elem.scrollTop;
                        break;
                    case key.end:
                        var damt = elem.scrollHeight - elem.scrollTop - clientHeight;
                        y = (damt > 0) ? damt + 10 : 0;
                        break;
                    case key.left:
                        x = -options.arrowScroll;
                        break;
                    case key.right:
                        x = options.arrowScroll;
                        break;
                    default:
                        return true; // a key we don't care about
                }

                scrollArray(elem, x, y);
                event.preventDefault();
            }

            /**
			 * Mousedown event only for updating activeElement
			 */
            function mousedown(event)
            {
                activeElement = event.target;
            }

            /***********************************************
			 * OVERFLOW
			 ***********************************************/

            var cache = {}; // cleared out every once in while
            setInterval(function () { cache = {}; }, 10 * 1000);

            var uniqueID = (function ()
            {
                var i = 0;
                return function (el)
                {
                    return el.uniqueID || (el.uniqueID = i++);
                };
            })();

            function setCache(elems, overflowing)
            {
                for (var i = elems.length; i--;)
                    cache[uniqueID(elems[i])] = overflowing;
                return overflowing;
            }

            function overflowingAncestor(el)
            {
                var elems = [];
                var rootScrollHeight = root.scrollHeight;
                do
                {
                    var cached = cache[uniqueID(el)];
                    if (cached)
                    {
                        return setCache(elems, cached);
                    }
                    elems.push(el);
                    if (rootScrollHeight === el.scrollHeight)
                    {
                        if (!isFrame || root.clientHeight + 10 < rootScrollHeight)
                        {
                            return setCache(elems, document.body); // scrolling root in WebKit
                        }
                    } else if (el.clientHeight + 10 < el.scrollHeight)
                    {
                        overflow = getComputedStyle(el, "").getPropertyValue("overflow-y");
                        if (overflow === "scroll" || overflow === "auto")
                        {
                            return setCache(elems, el);
                        }
                    }
                } while (el = el.parentNode);
            }

            /***********************************************
			 * HELPERS
			 ***********************************************/

            function addEvent(type, fn, bubble)
            {
                window.addEventListener(type, fn, (bubble || false));
            }

            function removeEvent(type, fn, bubble)
            {
                window.removeEventListener(type, fn, (bubble || false));
            }

            function isNodeName(el, tag)
            {
                return (el.nodeName || "").toLowerCase() === tag.toLowerCase();
            }

            function directionCheck(x, y)
            {
                x = (x > 0) ? 1 : -1;
                y = (y > 0) ? 1 : -1;
                if (direction.x !== x || direction.y !== y)
                {
                    direction.x = x;
                    direction.y = y;
                    que = [];
                    lastScroll = 0;
                }
            }

            var deltaBufferTimer;

            function isTouchpad(deltaY)
            {
                if (!deltaY) return;
                deltaY = Math.abs(deltaY)
                deltaBuffer.push(deltaY);
                deltaBuffer.shift();
                clearTimeout(deltaBufferTimer);
                var allEquals = (deltaBuffer[0] == deltaBuffer[1] &&
									deltaBuffer[1] == deltaBuffer[2]);
                var allDivisable = (isDivisible(deltaBuffer[0], 120) &&
									isDivisible(deltaBuffer[1], 120) &&
									isDivisible(deltaBuffer[2], 120));
                return !(allEquals || allDivisable);
            }

            function isDivisible(n, divisor)
            {
                return (Math.floor(n / divisor) == n / divisor);
            }

            var requestFrame = (function ()
            {
                return window.requestAnimationFrame ||
                        window.webkitRequestAnimationFrame ||
                        function (callback, element, delay)
                        {
                            window.setTimeout(callback, delay || (1000 / 60));
                        };
            })();

            var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

            /***********************************************
			 * PULSE
			 ***********************************************/

            /**
			 * Viscous fluid with a pulse for part and decay for the rest.
			 * - Applies a fixed force over an interval (a damped acceleration), and
			 * - Lets the exponential bleed away the velocity over a longer interval
			 * - Michael Herf, http://stereopsis.com/stopping/
			 */
            function pulse_(x)
            {
                var val, start, expx;
                // test
                x = x * options.pulseScale;
                if (x < 1)
                { // acceleartion
                    val = x - (1 - Math.exp(-x));
                } else
                {     // tail
                    // the previous animation ended here:
                    start = Math.exp(-1);
                    // simple viscous drag
                    x -= 1;
                    expx = 1 - Math.exp(-x);
                    val = start + (expx * (1 - start));
                }
                return val * options.pulseNormalize;
            }

            function pulse(x)
            {
                if (x >= 1) return 1;
                if (x <= 0) return 0;

                if (options.pulseNormalize == 1)
                {
                    options.pulseNormalize /= pulse_(1);
                }
                return pulse_(x);
            }

            addEvent("mousedown", mousedown);
            addEvent("mousewheel", wheel);
            addEvent("load", init);
        }
    });
})(jQuery);



(function ()
{
    "use strict";

    var Core = {
        initialized: false,

        initialize: function ()
        {
            if (this.initialized) return;
            this.initialized = true;

            this.build();
        },

        build: function ()
        {
            //$.smoothScroll();

        },
    };

    Core.initialize();
 
})();













/*
 * QueryLoader v2 - A simple script to create a preloader for images
 *
 * For instructions read the original post:
 * http://www.gayadesign.com/diy/queryloader2-preload-your-images-with-ease/
 *
 * Copyright (c) 2011 - Gaya Kessler
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Version:  2.9.0
 * Last update: 2014-01-31
 */
(function($){/*!
 * eventie v1.0.5
 * event binding helper
 *   eventie.bind( elem, 'click', myFn )
 *   eventie.unbind( elem, 'click', myFn )
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true */
/*global define: false, module: false */

( function( window ) {

'use strict';

var docElem = document.documentElement;

var bind = function() {};

function getIEEvent( obj ) {
  var event = window.event;
  // add event.target
  event.target = event.target || event.srcElement || obj;
  return event;
}

if ( docElem.addEventListener ) {
  bind = function( obj, type, fn ) {
    obj.addEventListener( type, fn, false );
  };
} else if ( docElem.attachEvent ) {
  bind = function( obj, type, fn ) {
    obj[ type + fn ] = fn.handleEvent ?
      function() {
        var event = getIEEvent( obj );
        fn.handleEvent.call( fn, event );
      } :
      function() {
        var event = getIEEvent( obj );
        fn.call( obj, event );
      };
    obj.attachEvent( "on" + type, obj[ type + fn ] );
  };
}

var unbind = function() {};

if ( docElem.removeEventListener ) {
  unbind = function( obj, type, fn ) {
    obj.removeEventListener( type, fn, false );
  };
} else if ( docElem.detachEvent ) {
  unbind = function( obj, type, fn ) {
    obj.detachEvent( "on" + type, obj[ type + fn ] );
    try {
      delete obj[ type + fn ];
    } catch ( err ) {
      // can't delete window object properties
      obj[ type + fn ] = undefined;
    }
  };
}

var eventie = {
  bind: bind,
  unbind: unbind
};

// ----- module definition ----- //

if ( typeof define === 'function' && define.amd ) {
  // AMD
  define( eventie );
} else if ( typeof exports === 'object' ) {
  // CommonJS
  module.exports = eventie;
} else {
  // browser global
  window.eventie = eventie;
}

})( this );

/*!
 * EventEmitter v4.2.7 - git.io/ee
 * Oliver Caldwell
 * MIT license
 * @preserve
 */

(function () {
	'use strict';

	/**
	 * Class for managing events.
	 * Can be extended to provide event functionality in other classes.
	 *
	 * @class EventEmitter Manages event registering and emitting.
	 */
	function EventEmitter() {}

	// Shortcuts to improve speed and size
	var proto = EventEmitter.prototype;
	var exports = this;
	var originalGlobalValue = exports.EventEmitter;

	/**
	 * Finds the index of the listener for the event in it's storage array.
	 *
	 * @param {Function[]} listeners Array of listeners to search through.
	 * @param {Function} listener Method to look for.
	 * @return {Number} Index of the specified listener, -1 if not found
	 * @api private
	 */
	function indexOfListener(listeners, listener) {
		var i = listeners.length;
		while (i--) {
			if (listeners[i].listener === listener) {
				return i;
			}
		}

		return -1;
	}

	/**
	 * Alias a method while keeping the context correct, to allow for overwriting of target method.
	 *
	 * @param {String} name The name of the target method.
	 * @return {Function} The aliased method
	 * @api private
	 */
	function alias(name) {
		return function aliasClosure() {
			return this[name].apply(this, arguments);
		};
	}

	/**
	 * Returns the listener array for the specified event.
	 * Will initialise the event object and listener arrays if required.
	 * Will return an object if you use a regex search. The object contains keys for each matched event. So /ba[rz]/ might return an object containing bar and baz. But only if you have either defined them with defineEvent or added some listeners to them.
	 * Each property in the object response is an array of listener functions.
	 *
	 * @param {String|RegExp} evt Name of the event to return the listeners from.
	 * @return {Function[]|Object} All listener functions for the event.
	 */
	proto.getListeners = function getListeners(evt) {
		var events = this._getEvents();
		var response;
		var key;

		// Return a concatenated array of all matching events if
		// the selector is a regular expression.
		if (evt instanceof RegExp) {
			response = {};
			for (key in events) {
				if (events.hasOwnProperty(key) && evt.test(key)) {
					response[key] = events[key];
				}
			}
		}
		else {
			response = events[evt] || (events[evt] = []);
		}

		return response;
	};

	/**
	 * Takes a list of listener objects and flattens it into a list of listener functions.
	 *
	 * @param {Object[]} listeners Raw listener objects.
	 * @return {Function[]} Just the listener functions.
	 */
	proto.flattenListeners = function flattenListeners(listeners) {
		var flatListeners = [];
		var i;

		for (i = 0; i < listeners.length; i += 1) {
			flatListeners.push(listeners[i].listener);
		}

		return flatListeners;
	};

	/**
	 * Fetches the requested listeners via getListeners but will always return the results inside an object. This is mainly for internal use but others may find it useful.
	 *
	 * @param {String|RegExp} evt Name of the event to return the listeners from.
	 * @return {Object} All listener functions for an event in an object.
	 */
	proto.getListenersAsObject = function getListenersAsObject(evt) {
		var listeners = this.getListeners(evt);
		var response;

		if (listeners instanceof Array) {
			response = {};
			response[evt] = listeners;
		}

		return response || listeners;
	};

	/**
	 * Adds a listener function to the specified event.
	 * The listener will not be added if it is a duplicate.
	 * If the listener returns true then it will be removed after it is called.
	 * If you pass a regular expression as the event name then the listener will be added to all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to attach the listener to.
	 * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.addListener = function addListener(evt, listener) {
		var listeners = this.getListenersAsObject(evt);
		var listenerIsWrapped = typeof listener === 'object';
		var key;

		for (key in listeners) {
			if (listeners.hasOwnProperty(key) && indexOfListener(listeners[key], listener) === -1) {
				listeners[key].push(listenerIsWrapped ? listener : {
					listener: listener,
					once: false
				});
			}
		}

		return this;
	};

	/**
	 * Alias of addListener
	 */
	proto.on = alias('addListener');

	/**
	 * Semi-alias of addListener. It will add a listener that will be
	 * automatically removed after it's first execution.
	 *
	 * @param {String|RegExp} evt Name of the event to attach the listener to.
	 * @param {Function} listener Method to be called when the event is emitted. If the function returns true then it will be removed after calling.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.addOnceListener = function addOnceListener(evt, listener) {
		return this.addListener(evt, {
			listener: listener,
			once: true
		});
	};

	/**
	 * Alias of addOnceListener.
	 */
	proto.once = alias('addOnceListener');

	/**
	 * Defines an event name. This is required if you want to use a regex to add a listener to multiple events at once. If you don't do this then how do you expect it to know what event to add to? Should it just add to every possible match for a regex? No. That is scary and bad.
	 * You need to tell it what event names should be matched by a regex.
	 *
	 * @param {String} evt Name of the event to create.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.defineEvent = function defineEvent(evt) {
		this.getListeners(evt);
		return this;
	};

	/**
	 * Uses defineEvent to define multiple events.
	 *
	 * @param {String[]} evts An array of event names to define.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.defineEvents = function defineEvents(evts) {
		for (var i = 0; i < evts.length; i += 1) {
			this.defineEvent(evts[i]);
		}
		return this;
	};

	/**
	 * Removes a listener function from the specified event.
	 * When passed a regular expression as the event name, it will remove the listener from all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to remove the listener from.
	 * @param {Function} listener Method to remove from the event.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.removeListener = function removeListener(evt, listener) {
		var listeners = this.getListenersAsObject(evt);
		var index;
		var key;

		for (key in listeners) {
			if (listeners.hasOwnProperty(key)) {
				index = indexOfListener(listeners[key], listener);

				if (index !== -1) {
					listeners[key].splice(index, 1);
				}
			}
		}

		return this;
	};

	/**
	 * Alias of removeListener
	 */
	proto.off = alias('removeListener');

	/**
	 * Adds listeners in bulk using the manipulateListeners method.
	 * If you pass an object as the second argument you can add to multiple events at once. The object should contain key value pairs of events and listeners or listener arrays. You can also pass it an event name and an array of listeners to be added.
	 * You can also pass it a regular expression to add the array of listeners to all events that match it.
	 * Yeah, this function does quite a bit. That's probably a bad thing.
	 *
	 * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add to multiple events at once.
	 * @param {Function[]} [listeners] An optional array of listener functions to add.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.addListeners = function addListeners(evt, listeners) {
		// Pass through to manipulateListeners
		return this.manipulateListeners(false, evt, listeners);
	};

	/**
	 * Removes listeners in bulk using the manipulateListeners method.
	 * If you pass an object as the second argument you can remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
	 * You can also pass it an event name and an array of listeners to be removed.
	 * You can also pass it a regular expression to remove the listeners from all events that match it.
	 *
	 * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to remove from multiple events at once.
	 * @param {Function[]} [listeners] An optional array of listener functions to remove.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.removeListeners = function removeListeners(evt, listeners) {
		// Pass through to manipulateListeners
		return this.manipulateListeners(true, evt, listeners);
	};

	/**
	 * Edits listeners in bulk. The addListeners and removeListeners methods both use this to do their job. You should really use those instead, this is a little lower level.
	 * The first argument will determine if the listeners are removed (true) or added (false).
	 * If you pass an object as the second argument you can add/remove from multiple events at once. The object should contain key value pairs of events and listeners or listener arrays.
	 * You can also pass it an event name and an array of listeners to be added/removed.
	 * You can also pass it a regular expression to manipulate the listeners of all events that match it.
	 *
	 * @param {Boolean} remove True if you want to remove listeners, false if you want to add.
	 * @param {String|Object|RegExp} evt An event name if you will pass an array of listeners next. An object if you wish to add/remove from multiple events at once.
	 * @param {Function[]} [listeners] An optional array of listener functions to add/remove.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.manipulateListeners = function manipulateListeners(remove, evt, listeners) {
		var i;
		var value;
		var single = remove ? this.removeListener : this.addListener;
		var multiple = remove ? this.removeListeners : this.addListeners;

		// If evt is an object then pass each of it's properties to this method
		if (typeof evt === 'object' && !(evt instanceof RegExp)) {
			for (i in evt) {
				if (evt.hasOwnProperty(i) && (value = evt[i])) {
					// Pass the single listener straight through to the singular method
					if (typeof value === 'function') {
						single.call(this, i, value);
					}
					else {
						// Otherwise pass back to the multiple function
						multiple.call(this, i, value);
					}
				}
			}
		}
		else {
			// So evt must be a string
			// And listeners must be an array of listeners
			// Loop over it and pass each one to the multiple method
			i = listeners.length;
			while (i--) {
				single.call(this, evt, listeners[i]);
			}
		}

		return this;
	};

	/**
	 * Removes all listeners from a specified event.
	 * If you do not specify an event then all listeners will be removed.
	 * That means every event will be emptied.
	 * You can also pass a regex to remove all events that match it.
	 *
	 * @param {String|RegExp} [evt] Optional name of the event to remove all listeners for. Will remove from every event if not passed.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.removeEvent = function removeEvent(evt) {
		var type = typeof evt;
		var events = this._getEvents();
		var key;

		// Remove different things depending on the state of evt
		if (type === 'string') {
			// Remove all listeners for the specified event
			delete events[evt];
		}
		else if (evt instanceof RegExp) {
			// Remove all events matching the regex.
			for (key in events) {
				if (events.hasOwnProperty(key) && evt.test(key)) {
					delete events[key];
				}
			}
		}
		else {
			// Remove all listeners in all events
			delete this._events;
		}

		return this;
	};

	/**
	 * Alias of removeEvent.
	 *
	 * Added to mirror the node API.
	 */
	proto.removeAllListeners = alias('removeEvent');

	/**
	 * Emits an event of your choice.
	 * When emitted, every listener attached to that event will be executed.
	 * If you pass the optional argument array then those arguments will be passed to every listener upon execution.
	 * Because it uses `apply`, your array of arguments will be passed as if you wrote them out separately.
	 * So they will not arrive within the array on the other side, they will be separate.
	 * You can also pass a regular expression to emit to all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
	 * @param {Array} [args] Optional array of arguments to be passed to each listener.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.emitEvent = function emitEvent(evt, args) {
		var listeners = this.getListenersAsObject(evt);
		var listener;
		var i;
		var key;
		var response;

		for (key in listeners) {
			if (listeners.hasOwnProperty(key)) {
				i = listeners[key].length;

				while (i--) {
					// If the listener returns true then it shall be removed from the event
					// The function is executed either with a basic call or an apply if there is an args array
					listener = listeners[key][i];

					if (listener.once === true) {
						this.removeListener(evt, listener.listener);
					}

					response = listener.listener.apply(this, args || []);

					if (response === this._getOnceReturnValue()) {
						this.removeListener(evt, listener.listener);
					}
				}
			}
		}

		return this;
	};

	/**
	 * Alias of emitEvent
	 */
	proto.trigger = alias('emitEvent');

	/**
	 * Subtly different from emitEvent in that it will pass its arguments on to the listeners, as opposed to taking a single array of arguments to pass on.
	 * As with emitEvent, you can pass a regex in place of the event name to emit to all events that match it.
	 *
	 * @param {String|RegExp} evt Name of the event to emit and execute listeners for.
	 * @param {...*} Optional additional arguments to be passed to each listener.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.emit = function emit(evt) {
		var args = Array.prototype.slice.call(arguments, 1);
		return this.emitEvent(evt, args);
	};

	/**
	 * Sets the current value to check against when executing listeners. If a
	 * listeners return value matches the one set here then it will be removed
	 * after execution. This value defaults to true.
	 *
	 * @param {*} value The new value to check for when executing listeners.
	 * @return {Object} Current instance of EventEmitter for chaining.
	 */
	proto.setOnceReturnValue = function setOnceReturnValue(value) {
		this._onceReturnValue = value;
		return this;
	};

	/**
	 * Fetches the current value to check against when executing listeners. If
	 * the listeners return value matches this one then it should be removed
	 * automatically. It will return true by default.
	 *
	 * @return {*|Boolean} The current value to check for or the default, true.
	 * @api private
	 */
	proto._getOnceReturnValue = function _getOnceReturnValue() {
		if (this.hasOwnProperty('_onceReturnValue')) {
			return this._onceReturnValue;
		}
		else {
			return true;
		}
	};

	/**
	 * Fetches the events object and creates one if required.
	 *
	 * @return {Object} The events storage object.
	 * @api private
	 */
	proto._getEvents = function _getEvents() {
		return this._events || (this._events = {});
	};

	/**
	 * Reverts the global {@link EventEmitter} to its previous value and returns a reference to this version.
	 *
	 * @return {Function} Non conflicting EventEmitter class.
	 */
	EventEmitter.noConflict = function noConflict() {
		exports.EventEmitter = originalGlobalValue;
		return EventEmitter;
	};

	// Expose the class either via AMD, CommonJS or the global object
	if (typeof define === 'function' && define.amd) {
		define(function () {
			return EventEmitter;
		});
	}
	else if (typeof module === 'object' && module.exports){
		module.exports = EventEmitter;
	}
	else {
		this.EventEmitter = EventEmitter;
	}
}.call(this));

/*!
 * imagesLoaded v3.1.4
 * JavaScript is all like "You images are done yet or what?"
 * MIT License
 */

( function( window, factory ) { 'use strict';
  // universal module definition

  /*global define: false, module: false, require: false */

  if ( typeof define === 'function' && define.amd ) {
    // AMD
    define( [
      'eventEmitter/EventEmitter',
      'eventie/eventie'
    ], function( EventEmitter, eventie ) {
      return factory( window, EventEmitter, eventie );
    });
  } else if ( typeof exports === 'object' ) {
    // CommonJS
    module.exports = factory(
      window,
      require('eventEmitter'),
      require('eventie')
    );
  } else {
    // browser global
    window.imagesLoaded = factory(
      window,
      window.EventEmitter,
      window.eventie
    );
  }

})( this,

// --------------------------  factory -------------------------- //

function factory( window, EventEmitter, eventie ) {

'use strict';

var $ = window.jQuery;
var console = window.console;
var hasConsole = typeof console !== 'undefined';

// -------------------------- helpers -------------------------- //

// extend objects
function extend( a, b ) {
  for ( var prop in b ) {
    a[ prop ] = b[ prop ];
  }
  return a;
}

var objToString = Object.prototype.toString;
function isArray( obj ) {
  return objToString.call( obj ) === '[object Array]';
}

// turn element or nodeList into an array
function makeArray( obj ) {
  var ary = [];
  if ( isArray( obj ) ) {
    // use object if already an array
    ary = obj;
  } else if ( typeof obj.length === 'number' ) {
    // convert nodeList to array
    for ( var i=0, len = obj.length; i < len; i++ ) {
      ary.push( obj[i] );
    }
  } else {
    // array of single index
    ary.push( obj );
  }
  return ary;
}

  // -------------------------- imagesLoaded -------------------------- //

  /**
   * @param {Array, Element, NodeList, String} elem
   * @param {Object or Function} options - if function, use as callback
   * @param {Function} onAlways - callback function
   */
  function ImagesLoaded( elem, options, onAlways ) {
    // coerce ImagesLoaded() without new, to be new ImagesLoaded()
    if ( !( this instanceof ImagesLoaded ) ) {
      return new ImagesLoaded( elem, options );
    }
    // use elem as selector string
    if ( typeof elem === 'string' ) {
      elem = document.querySelectorAll( elem );
    }

    this.elements = makeArray( elem );
    this.options = extend( {}, this.options );

    if ( typeof options === 'function' ) {
      onAlways = options;
    } else {
      extend( this.options, options );
    }

    if ( onAlways ) {
      this.on( 'always', onAlways );
    }

    this.getImages();

    if ( $ ) {
      // add jQuery Deferred object
      this.jqDeferred = new $.Deferred();
    }

    // HACK check async to allow time to bind listeners
    var _this = this;
    setTimeout( function() {
      _this.check();
    });
  }

  ImagesLoaded.prototype = new EventEmitter();

  ImagesLoaded.prototype.options = {};

  ImagesLoaded.prototype.getImages = function() {
    this.images = [];

    // filter & find items if we have an item selector
    for ( var i=0, len = this.elements.length; i < len; i++ ) {
      var elem = this.elements[i];
      // filter siblings
      if ( elem.nodeName === 'IMG' ) {
        this.addImage( elem );
      }
      // find children
      var childElems = elem.querySelectorAll('img');
      // concat childElems to filterFound array
      for ( var j=0, jLen = childElems.length; j < jLen; j++ ) {
        var img = childElems[j];
        this.addImage( img );
      }
    }
  };

  /**
   * @param {Image} img
   */
  ImagesLoaded.prototype.addImage = function( img ) {
    var loadingImage = new LoadingImage( img );
    this.images.push( loadingImage );
  };

  ImagesLoaded.prototype.check = function() {
    var _this = this;
    var checkedCount = 0;
    var length = this.images.length;
    this.hasAnyBroken = false;
    // complete if no images
    if ( !length ) {
      this.complete();
      return;
    }

    function onConfirm( image, message ) {
      if ( _this.options.debug && hasConsole ) {
        
      }

      _this.progress( image );
      checkedCount++;
      if ( checkedCount === length ) {
        _this.complete();
      }
      return true; // bind once
    }

    for ( var i=0; i < length; i++ ) {
      var loadingImage = this.images[i];
      loadingImage.on( 'confirm', onConfirm );
      loadingImage.check();
    }
  };

  ImagesLoaded.prototype.progress = function( image ) {
    this.hasAnyBroken = this.hasAnyBroken || !image.isLoaded;
    // HACK - Chrome triggers event before object properties have changed. #83
    var _this = this;
    setTimeout( function() {
      _this.emit( 'progress', _this, image );
      if ( _this.jqDeferred && _this.jqDeferred.notify ) {
        _this.jqDeferred.notify( _this, image );
      }
    });
  };

  ImagesLoaded.prototype.complete = function() {
    var eventName = this.hasAnyBroken ? 'fail' : 'done';
    this.isComplete = true;
    var _this = this;
    // HACK - another setTimeout so that confirm happens after progress
    setTimeout( function() {
      _this.emit( eventName, _this );
      _this.emit( 'always', _this );
      if ( _this.jqDeferred ) {
        var jqMethod = _this.hasAnyBroken ? 'reject' : 'resolve';
        _this.jqDeferred[ jqMethod ]( _this );
      }
    });
  };

  // -------------------------- jquery -------------------------- //

  if ( $ ) {
    $.fn.imagesLoaded = function( options, callback ) {
      var instance = new ImagesLoaded( this, options, callback );
      return instance.jqDeferred.promise( $(this) );
    };
  }


  // --------------------------  -------------------------- //

  function LoadingImage( img ) {
    this.img = img;
  }

  LoadingImage.prototype = new EventEmitter();

  LoadingImage.prototype.check = function() {
    // first check cached any previous images that have same src
    var resource = cache[ this.img.src ] || new Resource( this.img.src );
    if ( resource.isConfirmed ) {
      this.confirm( resource.isLoaded, 'cached was confirmed' );
      return;
    }

    // If complete is true and browser supports natural sizes,
    // try to check for image status manually.
    if ( this.img.complete && this.img.naturalWidth !== undefined ) {
      // report based on naturalWidth
      this.confirm( this.img.naturalWidth !== 0, 'naturalWidth' );
      return;
    }

    // If none of the checks above matched, simulate loading on detached element.
    var _this = this;
    resource.on( 'confirm', function( resrc, message ) {
      _this.confirm( resrc.isLoaded, message );
      return true;
    });

    resource.check();
  };

  LoadingImage.prototype.confirm = function( isLoaded, message ) {
    this.isLoaded = isLoaded;
    this.emit( 'confirm', this, message );
  };

  // -------------------------- Resource -------------------------- //

  // Resource checks each src, only once
  // separate class from LoadingImage to prevent memory leaks. See #115

  var cache = {};

  function Resource( src ) {
    this.src = src;
    // add to cache
    cache[ src ] = this;
  }

  Resource.prototype = new EventEmitter();

  Resource.prototype.check = function() {
    // only trigger checking once
    if ( this.isChecked ) {
      return;
    }
    // simulate loading on detached element
    var proxyImage = new Image();
    eventie.bind( proxyImage, 'load', this );
    eventie.bind( proxyImage, 'error', this );
    proxyImage.src = this.src;
    // set flag
    this.isChecked = true;
  };

  // ----- events ----- //

  // trigger specified handler for event type
  Resource.prototype.handleEvent = function( event ) {
    var method = 'on' + event.type;
    if ( this[ method ] ) {
      this[ method ]( event );
    }
  };

  Resource.prototype.onload = function( event ) {
    this.confirm( true, 'onload' );
    this.unbindProxyEvents( event );
  };

  Resource.prototype.onerror = function( event ) {
    this.confirm( false, 'onerror' );
    this.unbindProxyEvents( event );
  };

  // ----- confirm ----- //

  Resource.prototype.confirm = function( isLoaded, message ) {
    this.isConfirmed = true;
    this.isLoaded = isLoaded;
    this.emit( 'confirm', this, message );
  };

  Resource.prototype.unbindProxyEvents = function( event ) {
    eventie.unbind( event.target, 'load', this );
    eventie.unbind( event.target, 'error', this );
  };

  // -----  ----- //

  return ImagesLoaded;

});

function OverlayLoader(parent) {
	this.parent = parent;
	this.container;
	this.loadbar;
	this.percentageContainer;
};

OverlayLoader.prototype.createOverlay = function () {
	

	//determine postion of overlay and set parent position
	var overlayPosition = "absolute";

	if (this.parent.element.tagName.toLowerCase() == "body") {
		overlayPosition = "fixed";
	} else {
		var pos = this.parent.$element.css("position");
		if (pos != "fixed" || pos != "absolute") {
			this.parent.$element.css("position", "relative");
		}
	}

	//create the overlay container
	this.container = $("<div id='" + this.parent.options.overlayId + "'></div>").css({
		width: "100%",
		height: "100%",
		backgroundColor: this.parent.options.backgroundColor,
		backgroundPosition: "fixed",
		position: overlayPosition,
		zIndex: 666999, //very high!
		top: 0,
		left: 0
	}).appendTo("body");

	//create the loading bar
	this.loadbar = $("<div id='qLbar'></div>").css({
		height: this.parent.options.barHeight + "px",
		marginTop: "-" + (this.parent.options.barHeight / 2) + "px",
		backgroundColor: this.parent.options.barColor,
		width: "0%",
		position: "absolute",
		top: "50%"
	}).appendTo(this.container);

	//if percentage is on
	if (this.parent.options.percentage == true) {
		this.percentageContainer = $("<div id='qLpercentage'></div>").text("0%").css({
			height: "40px",
			width: "100px",
			position: "absolute",
			fontSize: "3em",
			top: "50%",
			left: "50%",
			marginTop: "-" + (59 + this.parent.options.barHeight) + "px",
			textAlign: "center",
			marginLeft: "-50px",
			color: this.parent.options.barColor
		}).appendTo(this.container);
	}

	//if no images... destroy
	if (!this.parent.preloadContainer.toPreload.length || this.parent.alreadyLoaded == true) {
		this.parent.destroyContainers();
	}
};

OverlayLoader.prototype.updatePercentage = function (percentage) {
	this.loadbar.stop().animate({
		width: percentage + "%",
		minWidth: percentage + "%"
	}, 200);

	//update textual percentage
	if (this.parent.options.percentage == true) {
		this.percentageContainer.text(Math.ceil(percentage) + "%");
	}
};
function PreloadContainer(parent) {
    this.toPreload = [];
    this.parent = parent;
    this.container;
};

PreloadContainer.prototype.create = function () {
    this.container = $("<div></div>").appendTo("body").css({
        display: "none",
        width: 0,
        height: 0,
        overflow: "hidden"
    });

    //process the image queue
    this.processQueue();
};

PreloadContainer.prototype.processQueue = function () {
    //add background images for loading
    for (var i = 0; this.toPreload.length > i; i++) {
		if (!this.parent.destroyed) {
			this.preloadImage(this.toPreload[i]);
		}
    }
};

PreloadContainer.prototype.addImage = function (src) {
    
    this.toPreload.push(src);
};

PreloadContainer.prototype.preloadImage = function (url) {
    
    var image = new PreloadImage();
    image.addToPreloader(this, url);
    image.bindLoadEvent();
};
function PreloadImage(parent) {
    this.element;
    this.parent = parent;
};

PreloadImage.prototype.addToPreloader = function (preloader, url) {
	
    this.element = $("<img />").attr("src", url);
    this.element.appendTo(preloader.container);
    this.parent = preloader.parent;
};

PreloadImage.prototype.bindLoadEvent = function () {
    this.parent.imageCounter++;

    //binding
    this.element[0].ref = this;

    new imagesLoaded(this.element, function (e) {
        e.elements[0].ref.completeLoading();
    });
};

PreloadImage.prototype.completeLoading = function () {
    this.parent.imageDone++;

    var percentage = (this.parent.imageDone / this.parent.imageCounter) * 100;

    

	//update the percentage of the loader
	this.parent.overlayLoader.updatePercentage(percentage);

	//all images done!
    if (this.parent.imageDone == this.parent.imageCounter || percentage >= 100) {
		this.parent.endLoader();
    }
};
function QueryLoader2(element, options) {
	this.element = element;
    this.$element = $(element);
	this.options = options;
    this.foundUrls = [];
    this.destroyed = false;
    this.imageCounter = 0;
    this.imageDone = 0;
	this.alreadyLoaded = false;

	//create objects
    this.preloadContainer = new PreloadContainer(this);
	this.overlayLoader = new OverlayLoader(this);

	//The default options
    this.defaultOptions = {
        onComplete: function() {},
		onLoadComplete: function() {},
        backgroundColor: "#000",
        barColor: "#fff",
        overlayId: 'qLoverlay',
        barHeight: 1,
        percentage: false,
        deepSearch: true,
        completeAnimation: "fade",
        minimumTime: 500
    };

	//run the init
	this.init();
};

QueryLoader2.prototype.init = function() {
	

	
	this.options = $.extend({}, this.defaultOptions, this.options);

    
    var images = this.findImageInElement(this.element);
    if (this.options.deepSearch == true) {
        
        var elements = this.$element.find("*:not(script)");
        for (var i = 0; i < elements.length; i++) {
            this.findImageInElement(elements[i]);
        }
    }

    //create containers
    this.preloadContainer.create();
	this.overlayLoader.createOverlay();
};

QueryLoader2.prototype.findImageInElement = function (element) {
    var url = "";
    var obj = $(element);
    var type = "normal";

    if (obj.css("background-image") != "none") {
        //if object has background image
        url = obj.css("background-image");
        type = "background";
    } else if (typeof(obj.attr("src")) != "undefined" && element.nodeName.toLowerCase() == "img") {
        //if is img and has src
        url = obj.attr("src");
    }

    //skip if gradient
    if (!this.hasGradient(url)) {
        //remove unwanted chars
        url = this.stripUrl(url);

        //split urls
        var urls = url.split(", ");

        for (var i = 0; i < urls.length; i++) {
            if (this.validUrl(urls[i]) && this.urlIsNew(urls[i])) {
                
                var extra = "";

                if (this.isIE() || this.isOpera()){
                    //filthy always no cache for IE, sorry peeps!
                    extra = "?rand=" + Math.random();

                    //add to preloader
                    this.preloadContainer.addImage(urls[i] + extra);
                } else {
                    if (type == "background") {
                        //add to preloader
                        this.preloadContainer.addImage(urls[i] + extra);
                    } else {
                        var image = new PreloadImage(this);
                        image.element = obj;
                        image.bindLoadEvent();
                    }
                }

                //add image to found list
                this.foundUrls.push(urls[i]);
            }
        }
    }
};

QueryLoader2.prototype.hasGradient = function (url) {
    if (url.indexOf("gradient") == -1) {
        return false;
    } else {
        return true;
    }
};

QueryLoader2.prototype.stripUrl = function (url) {
    url = url.replace(/url\(\"/g, "");
    url = url.replace(/url\(/g, "");
    url = url.replace(/\"\)/g, "");
    url = url.replace(/\)/g, "");

    return url;
};

QueryLoader2.prototype.isIE = function () {
    return navigator.userAgent.match(/msie/i);
};

QueryLoader2.prototype.isOpera = function () {
    return navigator.userAgent.match(/Opera/i);
};

QueryLoader2.prototype.validUrl = function (url) {
    if (url.length > 0 && !url.match(/^(data:)/i)) {
        return true;
    } else {
        return false;
    }
};

QueryLoader2.prototype.urlIsNew = function (url) {
    if (this.foundUrls.indexOf(url) == -1) {
        return true;
    } else {
        return false;
    }
};

QueryLoader2.prototype.destroyContainers = function () {
	this.destroyed = true;
	this.preloadContainer.container.remove();
	this.overlayLoader.container.remove();
};

QueryLoader2.prototype.endLoader = function () {
	

	this.destroyed = true;
	this.onLoadComplete();
};

QueryLoader2.prototype.onLoadComplete = function() {
	//fire the event before end animation
	this.options.onLoadComplete();

	if (this.options.completeAnimation == "grow") {
		var animationTime = this.options.minimumTime;

		this.overlayLoader.loadbar[0].parent = this; //put object in DOM element
		this.overlayLoader.loadbar.stop().animate({
			"width": "100%"
		}, animationTime, function () {
			$(this).animate({
				top: "0%",
				width: "100%",
				height: "100%"
			}, 500, function () {
				this.parent.overlayLoader.container[0].parent = this.parent; //once again...
				this.parent.overlayLoader.container.fadeOut(500, function () {
					this.parent.destroyContainers();
					this.parent.options.onComplete();
				});
			});
		});
	} else {
        var animationTime = this.options.minimumTime;

		this.overlayLoader.container[0].parent = this;
		this.overlayLoader.container.fadeOut(animationTime, function () {
			this.parent.destroyContainers();
			this.parent.options.onComplete();
		});
	}
};
//HERE COMES THE IE SHITSTORM
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function (elt /*, from*/) {
		var len = this.length >>> 0;
		var from = Number(arguments[1]) || 0;
		from = (from < 0)
			? Math.ceil(from)
			: Math.floor(from);
		if (from < 0)
			from += len;

		for (; from < len; from++) {
			if (from in this &&
				this[from] === elt)
				return from;
		}
		return -1;
	};
}
//function binder
$.fn.queryLoader2 = function(options){
    return this.each(function(){
        (new QueryLoader2(this, options));
    });
};
})(jQuery);











/*
 *  jQuery OwlCarousel v1.3.3
 *
 *  Copyright (c) 2013 Bartosz Wojciechowski
 *  http://www.owlgraphic.com/owlcarousel/
 *
 *  Licensed under MIT
 *
 */

/*JS Lint helpers: */
/*global dragMove: false, dragEnd: false, $, jQuery, alert, window, document */
/*jslint nomen: true, continue:true */

if (typeof Object.create !== "function") {
    Object.create = function (obj) {
        function F() {}
        F.prototype = obj;
        return new F();
    };
}
(function ($, window, document) {

    var Carousel = {
        init : function (options, el) {
            var base = this;

            base.$elem = $(el);
            base.options = $.extend({}, $.fn.owlCarousel.options, base.$elem.data(), options);

            base.userOptions = options;
            base.loadContent();
        },

        loadContent : function () {
            var base = this, url;

            function getData(data) {
                var i, content = "";
                if (typeof base.options.jsonSuccess === "function") {
                    base.options.jsonSuccess.apply(this, [data]);
                } else {
                    for (i in data.owl) {
                        if (data.owl.hasOwnProperty(i)) {
                            content += data.owl[i].item;
                        }
                    }
                    base.$elem.html(content);
                }
                base.logIn();
            }

            if (typeof base.options.beforeInit === "function") {
                base.options.beforeInit.apply(this, [base.$elem]);
            }

            if (typeof base.options.jsonPath === "string") {
                url = base.options.jsonPath;
                $.getJSON(url, getData);
            } else {
                base.logIn();
            }
        },

        logIn : function () {
            var base = this;

            base.$elem.data("owl-originalStyles", base.$elem.attr("style"));
            base.$elem.data("owl-originalClasses", base.$elem.attr("class"));

            base.$elem.css({opacity: 0});
            base.orignalItems = base.options.items;
            base.checkBrowser();
            base.wrapperWidth = 0;
            base.checkVisible = null;
            base.setVars();
        },

        setVars : function () {
            var base = this;
            if (base.$elem.children().length === 0) {return false; }
            base.baseClass();
            base.eventTypes();
            base.$userItems = base.$elem.children();
            base.itemsAmount = base.$userItems.length;
            base.wrapItems();
            base.$owlItems = base.$elem.find(".owl-item");
            base.$owlWrapper = base.$elem.find(".owl-wrapper");
            base.playDirection = "next";
            base.prevItem = 0;
            base.prevArr = [0];
            base.currentItem = 0;
            base.customEvents();
            base.onStartup();
        },

        onStartup : function () {
            var base = this;
            base.updateItems();
            base.calculateAll();
            base.buildControls();
            base.updateControls();
            base.response();
            base.moveEvents();
            base.stopOnHover();
            base.owlStatus();

            if (base.options.transitionStyle !== false) {
                base.transitionTypes(base.options.transitionStyle);
            }
            if (base.options.autoPlay === true) {
                base.options.autoPlay = 5000;
            }
            base.play();

            base.$elem.find(".owl-wrapper").css("display", "block");

            if (!base.$elem.is(":visible")) {
                base.watchVisibility();
            } else {
                base.$elem.css("opacity", 1);
            }
            base.onstartup = false;
            base.eachMoveUpdate();
            if (typeof base.options.afterInit === "function") {
                base.options.afterInit.apply(this, [base.$elem]);
            }
        },

        eachMoveUpdate : function () {
            var base = this;

            if (base.options.lazyLoad === true) {
                base.lazyLoad();
            }
            if (base.options.autoHeight === true) {
                base.autoHeight();
            }
            base.onVisibleItems();

            if (typeof base.options.afterAction === "function") {
                base.options.afterAction.apply(this, [base.$elem]);
            }
        },

        updateVars : function () {
            var base = this;
            if (typeof base.options.beforeUpdate === "function") {
                base.options.beforeUpdate.apply(this, [base.$elem]);
            }
            base.watchVisibility();
            base.updateItems();
            base.calculateAll();
            base.updatePosition();
            base.updateControls();
            base.eachMoveUpdate();
            if (typeof base.options.afterUpdate === "function") {
                base.options.afterUpdate.apply(this, [base.$elem]);
            }
        },

        reload : function () {
            var base = this;
            window.setTimeout(function () {
                base.updateVars();
            }, 0);
        },

        watchVisibility : function () {
            var base = this;

            if (base.$elem.is(":visible") === false) {
                base.$elem.css({opacity: 0});
                window.clearInterval(base.autoPlayInterval);
                window.clearInterval(base.checkVisible);
            } else {
                return false;
            }
            base.checkVisible = window.setInterval(function () {
                if (base.$elem.is(":visible")) {
                    base.reload();
                    base.$elem.animate({opacity: 1}, 200);
                    window.clearInterval(base.checkVisible);
                }
            }, 500);
        },

        wrapItems : function () {
            var base = this;
            base.$userItems.wrapAll("<div class=\"owl-wrapper\">").wrap("<div class=\"owl-item\"></div>");
            base.$elem.find(".owl-wrapper").wrap("<div class=\"owl-wrapper-outer\">");
            base.wrapperOuter = base.$elem.find(".owl-wrapper-outer");
            base.$elem.css("display", "block");
        },

        baseClass : function () {
            var base = this,
                hasBaseClass = base.$elem.hasClass(base.options.baseClass),
                hasThemeClass = base.$elem.hasClass(base.options.theme);

            if (!hasBaseClass) {
                base.$elem.addClass(base.options.baseClass);
            }

            if (!hasThemeClass) {
                base.$elem.addClass(base.options.theme);
            }
        },

        updateItems : function () {
            var base = this, width, i;

            if (base.options.responsive === false) {
                return false;
            }
            if (base.options.singleItem === true) {
                base.options.items = base.orignalItems = 1;
                base.options.itemsCustom = false;
                base.options.itemsDesktop = false;
                base.options.itemsDesktopSmall = false;
                base.options.itemsTablet = false;
                base.options.itemsTabletSmall = false;
                base.options.itemsMobile = false;
                return false;
            }

            width = $(base.options.responsiveBaseWidth).width();

            if (width > (base.options.itemsDesktop[0] || base.orignalItems)) {
                base.options.items = base.orignalItems;
            }
            if (base.options.itemsCustom !== false) {
                //Reorder array by screen size
                base.options.itemsCustom.sort(function (a, b) {return a[0] - b[0]; });

                for (i = 0; i < base.options.itemsCustom.length; i += 1) {
                    if (base.options.itemsCustom[i][0] <= width) {
                        base.options.items = base.options.itemsCustom[i][1];
                    }
                }

            } else {

                if (width <= base.options.itemsDesktop[0] && base.options.itemsDesktop !== false) {
                    base.options.items = base.options.itemsDesktop[1];
                }

                if (width <= base.options.itemsDesktopSmall[0] && base.options.itemsDesktopSmall !== false) {
                    base.options.items = base.options.itemsDesktopSmall[1];
                }

                if (width <= base.options.itemsTablet[0] && base.options.itemsTablet !== false) {
                    base.options.items = base.options.itemsTablet[1];
                }

                if (width <= base.options.itemsTabletSmall[0] && base.options.itemsTabletSmall !== false) {
                    base.options.items = base.options.itemsTabletSmall[1];
                }

                if (width <= base.options.itemsMobile[0] && base.options.itemsMobile !== false) {
                    base.options.items = base.options.itemsMobile[1];
                }
            }

            //if number of items is less than declared
            if (base.options.items > base.itemsAmount && base.options.itemsScaleUp === true) {
                base.options.items = base.itemsAmount;
            }
        },

        response : function () {
            var base = this,
                smallDelay,
                lastWindowWidth;

            if (base.options.responsive !== true) {
                return false;
            }
            lastWindowWidth = $(window).width();

            base.resizer = function () {
                if ($(window).width() !== lastWindowWidth) {
                    if (base.options.autoPlay !== false) {
                        window.clearInterval(base.autoPlayInterval);
                    }
                    window.clearTimeout(smallDelay);
                    smallDelay = window.setTimeout(function () {
                        lastWindowWidth = $(window).width();
                        base.updateVars();
                    }, base.options.responsiveRefreshRate);
                }
            };
            $(window).resize(base.resizer);
        },

        updatePosition : function () {
            var base = this;
            base.jumpTo(base.currentItem);
            if (base.options.autoPlay !== false) {
                base.checkAp();
            }
        },

        appendItemsSizes : function () {
            var base = this,
                roundPages = 0,
                lastItem = base.itemsAmount - base.options.items;

            base.$owlItems.each(function (index) {
                var $this = $(this);
                $this
                    .css({"width": base.itemWidth})
                    .data("owl-item", Number(index));

                if (index % base.options.items === 0 || index === lastItem) {
                    if (!(index > lastItem)) {
                        roundPages += 1;
                    }
                }
                $this.data("owl-roundPages", roundPages);
            });
        },

        appendWrapperSizes : function () {
            var base = this,
                width = base.$owlItems.length * base.itemWidth;

            base.$owlWrapper.css({
                "width": width * 2,
                "left": 0
            });
            base.appendItemsSizes();
        },

        calculateAll : function () {
            var base = this;
            base.calculateWidth();
            base.appendWrapperSizes();
            base.loops();
            base.max();
        },

        calculateWidth : function () {
            var base = this;
            base.itemWidth = Math.round(base.$elem.width() / base.options.items);
        },

        max : function () {
            var base = this,
                maximum = ((base.itemsAmount * base.itemWidth) - base.options.items * base.itemWidth) * -1;
            if (base.options.items > base.itemsAmount) {
                base.maximumItem = 0;
                maximum = 0;
                base.maximumPixels = 0;
            } else {
                base.maximumItem = base.itemsAmount - base.options.items;
                base.maximumPixels = maximum;
            }
            return maximum;
        },

        min : function () {
            return 0;
        },

        loops : function () {
            var base = this,
                prev = 0,
                elWidth = 0,
                i,
                item,
                roundPageNum;

            base.positionsInArray = [0];
            base.pagesInArray = [];

            for (i = 0; i < base.itemsAmount; i += 1) {
                elWidth += base.itemWidth;
                base.positionsInArray.push(-elWidth);

                if (base.options.scrollPerPage === true) {
                    item = $(base.$owlItems[i]);
                    roundPageNum = item.data("owl-roundPages");
                    if (roundPageNum !== prev) {
                        base.pagesInArray[prev] = base.positionsInArray[i];
                        prev = roundPageNum;
                    }
                }
            }
        },

        buildControls : function () {
            var base = this;
            if (base.options.navigation === true || base.options.pagination === true) {
                base.owlControls = $("<div class=\"owl-controls\"/>").toggleClass("clickable", !base.browser.isTouch).appendTo(base.$elem);
            }
            if (base.options.pagination === true) {
                base.buildPagination();
            }
            if (base.options.navigation === true) {
                base.buildButtons();
            }
        },

        buildButtons : function () {
            var base = this,
                buttonsWrapper = $("<div class=\"owl-buttons\"/>");
            base.owlControls.append(buttonsWrapper);

            base.buttonPrev = $("<div/>", {
                "class" : "owl-prev",
                "html" : base.options.navigationText[0] || ""
            });

            base.buttonNext = $("<div/>", {
                "class" : "owl-next",
                "html" : base.options.navigationText[1] || ""
            });

            buttonsWrapper
                .append(base.buttonPrev)
                .append(base.buttonNext);

            buttonsWrapper.on("touchstart.owlControls mousedown.owlControls", "div[class^=\"owl\"]", function (event) {
                event.preventDefault();
            });

            buttonsWrapper.on("touchend.owlControls mouseup.owlControls", "div[class^=\"owl\"]", function (event) {
                event.preventDefault();
                if ($(this).hasClass("owl-next")) {
                    base.next();
                } else {
                    base.prev();
                }
            });
        },

        buildPagination : function () {
            var base = this;

            base.paginationWrapper = $("<div class=\"owl-pagination\"/>");
            base.owlControls.append(base.paginationWrapper);

            base.paginationWrapper.on("touchend.owlControls mouseup.owlControls", ".owl-page", function (event) {
                event.preventDefault();
                if (Number($(this).data("owl-page")) !== base.currentItem) {
                    base.goTo(Number($(this).data("owl-page")), true);
                }
            });
        },

        updatePagination : function () {
            var base = this,
                counter,
                lastPage,
                lastItem,
                i,
                paginationButton,
                paginationButtonInner;

            if (base.options.pagination === false) {
                return false;
            }

            base.paginationWrapper.html("");

            counter = 0;
            lastPage = base.itemsAmount - base.itemsAmount % base.options.items;

            for (i = 0; i < base.itemsAmount; i += 1) {
                if (i % base.options.items === 0) {
                    counter += 1;
                    if (lastPage === i) {
                        lastItem = base.itemsAmount - base.options.items;
                    }
                    paginationButton = $("<div/>", {
                        "class" : "owl-page"
                    });
                    paginationButtonInner = $("<span></span>", {
                        "text": base.options.paginationNumbers === true ? counter : "",
                        "class": base.options.paginationNumbers === true ? "owl-numbers" : ""
                    });
                    paginationButton.append(paginationButtonInner);

                    paginationButton.data("owl-page", lastPage === i ? lastItem : i);
                    paginationButton.data("owl-roundPages", counter);

                    base.paginationWrapper.append(paginationButton);
                }
            }
            base.checkPagination();
        },
        checkPagination : function () {
            var base = this;
            if (base.options.pagination === false) {
                return false;
            }
            base.paginationWrapper.find(".owl-page").each(function () {
                if ($(this).data("owl-roundPages") === $(base.$owlItems[base.currentItem]).data("owl-roundPages")) {
                    base.paginationWrapper
                        .find(".owl-page")
                        .removeClass("active");
                    $(this).addClass("active");
                }
            });
        },

        checkNavigation : function () {
            var base = this;

            if (base.options.navigation === false) {
                return false;
            }
            if (base.options.rewindNav === false) {
                if (base.currentItem === 0 && base.maximumItem === 0) {
                    base.buttonPrev.addClass("disabled");
                    base.buttonNext.addClass("disabled");
                } else if (base.currentItem === 0 && base.maximumItem !== 0) {
                    base.buttonPrev.addClass("disabled");
                    base.buttonNext.removeClass("disabled");
                } else if (base.currentItem === base.maximumItem) {
                    base.buttonPrev.removeClass("disabled");
                    base.buttonNext.addClass("disabled");
                } else if (base.currentItem !== 0 && base.currentItem !== base.maximumItem) {
                    base.buttonPrev.removeClass("disabled");
                    base.buttonNext.removeClass("disabled");
                }
            }
        },

        updateControls : function () {
            var base = this;
            base.updatePagination();
            base.checkNavigation();
            if (base.owlControls) {
                if (base.options.items >= base.itemsAmount) {
                    base.owlControls.hide();
                } else {
                    base.owlControls.show();
                }
            }
        },

        destroyControls : function () {
            var base = this;
            if (base.owlControls) {
                base.owlControls.remove();
            }
        },

        next : function (speed) {
            var base = this;

            if (base.isTransition) {
                return false;
            }

            base.currentItem += base.options.scrollPerPage === true ? base.options.items : 1;
            if (base.currentItem > base.maximumItem + (base.options.scrollPerPage === true ? (base.options.items - 1) : 0)) {
                if (base.options.rewindNav === true) {
                    base.currentItem = 0;
                    speed = "rewind";
                } else {
                    base.currentItem = base.maximumItem;
                    return false;
                }
            }
            base.goTo(base.currentItem, speed);
        },

        prev : function (speed) {
            var base = this;

            if (base.isTransition) {
                return false;
            }

            if (base.options.scrollPerPage === true && base.currentItem > 0 && base.currentItem < base.options.items) {
                base.currentItem = 0;
            } else {
                base.currentItem -= base.options.scrollPerPage === true ? base.options.items : 1;
            }
            if (base.currentItem < 0) {
                if (base.options.rewindNav === true) {
                    base.currentItem = base.maximumItem;
                    speed = "rewind";
                } else {
                    base.currentItem = 0;
                    return false;
                }
            }
            base.goTo(base.currentItem, speed);
        },

        goTo : function (position, speed, drag) {
            var base = this,
                goToPixel;

            if (base.isTransition) {
                return false;
            }
            if (typeof base.options.beforeMove === "function") {
                base.options.beforeMove.apply(this, [base.$elem]);
            }
            if (position >= base.maximumItem) {
                position = base.maximumItem;
            } else if (position <= 0) {
                position = 0;
            }

            base.currentItem = base.owl.currentItem = position;
            if (base.options.transitionStyle !== false && drag !== "drag" && base.options.items === 1 && base.browser.support3d === true) {
                base.swapSpeed(0);
                if (base.browser.support3d === true) {
                    base.transition3d(base.positionsInArray[position]);
                } else {
                    base.css2slide(base.positionsInArray[position], 1);
                }
                base.afterGo();
                base.singleItemTransition();
                return false;
            }
            goToPixel = base.positionsInArray[position];

            if (base.browser.support3d === true) {
                base.isCss3Finish = false;

                if (speed === true) {
                    base.swapSpeed("paginationSpeed");
                    window.setTimeout(function () {
                        base.isCss3Finish = true;
                    }, base.options.paginationSpeed);

                } else if (speed === "rewind") {
                    base.swapSpeed(base.options.rewindSpeed);
                    window.setTimeout(function () {
                        base.isCss3Finish = true;
                    }, base.options.rewindSpeed);

                } else {
                    base.swapSpeed("slideSpeed");
                    window.setTimeout(function () {
                        base.isCss3Finish = true;
                    }, base.options.slideSpeed);
                }
                base.transition3d(goToPixel);
            } else {
                if (speed === true) {
                    base.css2slide(goToPixel, base.options.paginationSpeed);
                } else if (speed === "rewind") {
                    base.css2slide(goToPixel, base.options.rewindSpeed);
                } else {
                    base.css2slide(goToPixel, base.options.slideSpeed);
                }
            }
            base.afterGo();
        },

        jumpTo : function (position) {
            var base = this;
            if (typeof base.options.beforeMove === "function") {
                base.options.beforeMove.apply(this, [base.$elem]);
            }
            if (position >= base.maximumItem || position === -1) {
                position = base.maximumItem;
            } else if (position <= 0) {
                position = 0;
            }
            base.swapSpeed(0);
            if (base.browser.support3d === true) {
                base.transition3d(base.positionsInArray[position]);
            } else {
                base.css2slide(base.positionsInArray[position], 1);
            }
            base.currentItem = base.owl.currentItem = position;
            base.afterGo();
        },

        afterGo : function () {
            var base = this;

            base.prevArr.push(base.currentItem);
            base.prevItem = base.owl.prevItem = base.prevArr[base.prevArr.length - 2];
            base.prevArr.shift(0);

            if (base.prevItem !== base.currentItem) {
                base.checkPagination();
                base.checkNavigation();
                base.eachMoveUpdate();

                if (base.options.autoPlay !== false) {
                    base.checkAp();
                }
            }
            if (typeof base.options.afterMove === "function" && base.prevItem !== base.currentItem) {
                base.options.afterMove.apply(this, [base.$elem]);
            }
        },

        stop : function () {
            var base = this;
            base.apStatus = "stop";
            window.clearInterval(base.autoPlayInterval);
        },

        checkAp : function () {
            var base = this;
            if (base.apStatus !== "stop") {
                base.play();
            }
        },

        play : function () {
            var base = this;
            base.apStatus = "play";
            if (base.options.autoPlay === false) {
                return false;
            }
            window.clearInterval(base.autoPlayInterval);
            base.autoPlayInterval = window.setInterval(function () {
                base.next(true);
            }, base.options.autoPlay);
        },

        swapSpeed : function (action) {
            var base = this;
            if (action === "slideSpeed") {
                base.$owlWrapper.css(base.addCssSpeed(base.options.slideSpeed));
            } else if (action === "paginationSpeed") {
                base.$owlWrapper.css(base.addCssSpeed(base.options.paginationSpeed));
            } else if (typeof action !== "string") {
                base.$owlWrapper.css(base.addCssSpeed(action));
            }
        },

        addCssSpeed : function (speed) {
            return {
                "-webkit-transition": "all " + speed + "ms ease",
                "-moz-transition": "all " + speed + "ms ease",
                "-o-transition": "all " + speed + "ms ease",
                "transition": "all " + speed + "ms ease"
            };
        },

        removeTransition : function () {
            return {
                "-webkit-transition": "",
                "-moz-transition": "",
                "-o-transition": "",
                "transition": ""
            };
        },

        doTranslate : function (pixels) {
            return {
                "-webkit-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "-moz-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "-o-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "-ms-transform": "translate3d(" + pixels + "px, 0px, 0px)",
                "transform": "translate3d(" + pixels + "px, 0px,0px)"
            };
        },

        transition3d : function (value) {
            var base = this;
            base.$owlWrapper.css(base.doTranslate(value));
        },

        css2move : function (value) {
            var base = this;
            base.$owlWrapper.css({"left" : value});
        },

        css2slide : function (value, speed) {
            var base = this;

            base.isCssFinish = false;
            base.$owlWrapper.stop(true, true).animate({
                "left" : value
            }, {
                duration : speed || base.options.slideSpeed,
                complete : function () {
                    base.isCssFinish = true;
                }
            });
        },

        checkBrowser : function () {
            var base = this,
                translate3D = "translate3d(0px, 0px, 0px)",
                tempElem = document.createElement("div"),
                regex,
                asSupport,
                support3d,
                isTouch;

            tempElem.style.cssText = "  -moz-transform:" + translate3D +
                                  "; -ms-transform:"     + translate3D +
                                  "; -o-transform:"      + translate3D +
                                  "; -webkit-transform:" + translate3D +
                                  "; transform:"         + translate3D;
            regex = /translate3d\(0px, 0px, 0px\)/g;
            asSupport = tempElem.style.cssText.match(regex);
            support3d = (asSupport !== null && asSupport.length === 1);

            isTouch = "ontouchstart" in window || window.navigator.msMaxTouchPoints;

            base.browser = {
                "support3d" : support3d,
                "isTouch" : isTouch
            };
        },

        moveEvents : function () {
            var base = this;
            if (base.options.mouseDrag !== false || base.options.touchDrag !== false) {
                base.gestures();
                base.disabledEvents();
            }
        },

        eventTypes : function () {
            var base = this,
                types = ["s", "e", "x"];

            base.ev_types = {};

            if (base.options.mouseDrag === true && base.options.touchDrag === true) {
                types = [
                    "touchstart.owl mousedown.owl",
                    "touchmove.owl mousemove.owl",
                    "touchend.owl touchcancel.owl mouseup.owl"
                ];
            } else if (base.options.mouseDrag === false && base.options.touchDrag === true) {
                types = [
                    "touchstart.owl",
                    "touchmove.owl",
                    "touchend.owl touchcancel.owl"
                ];
            } else if (base.options.mouseDrag === true && base.options.touchDrag === false) {
                types = [
                    "mousedown.owl",
                    "mousemove.owl",
                    "mouseup.owl"
                ];
            }

            base.ev_types.start = types[0];
            base.ev_types.move = types[1];
            base.ev_types.end = types[2];
        },

        disabledEvents :  function () {
            var base = this;
            base.$elem.on("dragstart.owl", function (event) { event.preventDefault(); });
            base.$elem.on("mousedown.disableTextSelect", function (e) {
                return $(e.target).is('input, textarea, select, option');
            });
        },

        gestures : function () {
            /*jslint unparam: true*/
            var base = this,
                locals = {
                    offsetX : 0,
                    offsetY : 0,
                    baseElWidth : 0,
                    relativePos : 0,
                    position: null,
                    minSwipe : null,
                    maxSwipe: null,
                    sliding : null,
                    dargging: null,
                    targetElement : null
                };

            base.isCssFinish = true;

            function getTouches(event) {
                if (event.touches !== undefined) {
                    return {
                        x : event.touches[0].pageX,
                        y : event.touches[0].pageY
                    };
                }

                if (event.touches === undefined) {
                    if (event.pageX !== undefined) {
                        return {
                            x : event.pageX,
                            y : event.pageY
                        };
                    }
                    if (event.pageX === undefined) {
                        return {
                            x : event.clientX,
                            y : event.clientY
                        };
                    }
                }
            }

            function swapEvents(type) {
                if (type === "on") {
                    $(document).on(base.ev_types.move, dragMove);
                    $(document).on(base.ev_types.end, dragEnd);
                } else if (type === "off") {
                    $(document).off(base.ev_types.move);
                    $(document).off(base.ev_types.end);
                }
            }

            function dragStart(event) {
                var ev = event.originalEvent || event || window.event,
                    position;

                if (ev.which === 3) {
                    return false;
                }
                if (base.itemsAmount <= base.options.items) {
                    return;
                }
                if (base.isCssFinish === false && !base.options.dragBeforeAnimFinish) {
                    return false;
                }
                if (base.isCss3Finish === false && !base.options.dragBeforeAnimFinish) {
                    return false;
                }

                if (base.options.autoPlay !== false) {
                    window.clearInterval(base.autoPlayInterval);
                }

                if (base.browser.isTouch !== true && !base.$owlWrapper.hasClass("grabbing")) {
                    base.$owlWrapper.addClass("grabbing");
                }

                base.newPosX = 0;
                base.newRelativeX = 0;

                $(this).css(base.removeTransition());

                position = $(this).position();
                locals.relativePos = position.left;

                locals.offsetX = getTouches(ev).x - position.left;
                locals.offsetY = getTouches(ev).y - position.top;

                swapEvents("on");

                locals.sliding = false;
                locals.targetElement = ev.target || ev.srcElement;
            }

            function dragMove(event) {
                var ev = event.originalEvent || event || window.event,
                    minSwipe,
                    maxSwipe;

                base.newPosX = getTouches(ev).x - locals.offsetX;
                base.newPosY = getTouches(ev).y - locals.offsetY;
                base.newRelativeX = base.newPosX - locals.relativePos;

                if (typeof base.options.startDragging === "function" && locals.dragging !== true && base.newRelativeX !== 0) {
                    locals.dragging = true;
                    base.options.startDragging.apply(base, [base.$elem]);
                }

                if ((base.newRelativeX > 8 || base.newRelativeX < -8) && (base.browser.isTouch === true)) {
                    if (ev.preventDefault !== undefined) {
                        ev.preventDefault();
                    } else {
                        ev.returnValue = false;
                    }
                    locals.sliding = true;
                }

                if ((base.newPosY > 10 || base.newPosY < -10) && locals.sliding === false) {
                    $(document).off("touchmove.owl");
                }

                minSwipe = function () {
                    return base.newRelativeX / 5;
                };

                maxSwipe = function () {
                    return base.maximumPixels + base.newRelativeX / 5;
                };

                base.newPosX = Math.max(Math.min(base.newPosX, minSwipe()), maxSwipe());
                if (base.browser.support3d === true) {
                    base.transition3d(base.newPosX);
                } else {
                    base.css2move(base.newPosX);
                }
            }

            function dragEnd(event) {
                var ev = event.originalEvent || event || window.event,
                    newPosition,
                    handlers,
                    owlStopEvent;

                ev.target = ev.target || ev.srcElement;

                locals.dragging = false;

                if (base.browser.isTouch !== true) {
                    base.$owlWrapper.removeClass("grabbing");
                }

                if (base.newRelativeX < 0) {
                    base.dragDirection = base.owl.dragDirection = "left";
                } else {
                    base.dragDirection = base.owl.dragDirection = "right";
                }

                if (base.newRelativeX !== 0) {
                    newPosition = base.getNewPosition();
                    base.goTo(newPosition, false, "drag");
                    if (locals.targetElement === ev.target && base.browser.isTouch !== true) {
                        $(ev.target).on("click.disable", function (ev) {
                            ev.stopImmediatePropagation();
                            ev.stopPropagation();
                            ev.preventDefault();
                            $(ev.target).off("click.disable");
                        });
                        handlers = $._data(ev.target, "events").click;
                        owlStopEvent = handlers.pop();
                        handlers.splice(0, 0, owlStopEvent);
                    }
                }
                swapEvents("off");
            }
            base.$elem.on(base.ev_types.start, ".owl-wrapper", dragStart);
        },

        getNewPosition : function () {
            var base = this,
                newPosition = base.closestItem();

            if (newPosition > base.maximumItem) {
                base.currentItem = base.maximumItem;
                newPosition  = base.maximumItem;
            } else if (base.newPosX >= 0) {
                newPosition = 0;
                base.currentItem = 0;
            }
            return newPosition;
        },
        closestItem : function () {
            var base = this,
                array = base.options.scrollPerPage === true ? base.pagesInArray : base.positionsInArray,
                goal = base.newPosX,
                closest = null;

            $.each(array, function (i, v) {
                if (goal - (base.itemWidth / 20) > array[i + 1] && goal - (base.itemWidth / 20) < v && base.moveDirection() === "left") {
                    closest = v;
                    if (base.options.scrollPerPage === true) {
                        base.currentItem = $.inArray(closest, base.positionsInArray);
                    } else {
                        base.currentItem = i;
                    }
                } else if (goal + (base.itemWidth / 20) < v && goal + (base.itemWidth / 20) > (array[i + 1] || array[i] - base.itemWidth) && base.moveDirection() === "right") {
                    if (base.options.scrollPerPage === true) {
                        closest = array[i + 1] || array[array.length - 1];
                        base.currentItem = $.inArray(closest, base.positionsInArray);
                    } else {
                        closest = array[i + 1];
                        base.currentItem = i + 1;
                    }
                }
            });
            return base.currentItem;
        },

        moveDirection : function () {
            var base = this,
                direction;
            if (base.newRelativeX < 0) {
                direction = "right";
                base.playDirection = "next";
            } else {
                direction = "left";
                base.playDirection = "prev";
            }
            return direction;
        },

        customEvents : function () {
            /*jslint unparam: true*/
            var base = this;
            base.$elem.on("owl.next", function () {
                base.next();
            });
            base.$elem.on("owl.prev", function () {
                base.prev();
            });
            base.$elem.on("owl.play", function (event, speed) {
                base.options.autoPlay = speed;
                base.play();
                base.hoverStatus = "play";
            });
            base.$elem.on("owl.stop", function () {
                base.stop();
                base.hoverStatus = "stop";
            });
            base.$elem.on("owl.goTo", function (event, item) {
                base.goTo(item);
            });
            base.$elem.on("owl.jumpTo", function (event, item) {
                base.jumpTo(item);
            });
        },

        stopOnHover : function () {
            var base = this;
            if (base.options.stopOnHover === true && base.browser.isTouch !== true && base.options.autoPlay !== false) {
                base.$elem.on("mouseover", function () {
                    base.stop();
                });
                base.$elem.on("mouseout", function () {
                    if (base.hoverStatus !== "stop") {
                        base.play();
                    }
                });
            }
        },

        lazyLoad : function () {
            var base = this,
                i,
                $item,
                itemNumber,
                $lazyImg,
                follow;

            if (base.options.lazyLoad === false) {
                return false;
            }
            for (i = 0; i < base.itemsAmount; i += 1) {
                $item = $(base.$owlItems[i]);

                if ($item.data("owl-loaded") === "loaded") {
                    continue;
                }

                itemNumber = $item.data("owl-item");
                $lazyImg = $item.find(".lazyOwl");

                if (typeof $lazyImg.data("src") !== "string") {
                    $item.data("owl-loaded", "loaded");
                    continue;
                }
                if ($item.data("owl-loaded") === undefined) {
                    $lazyImg.hide();
                    $item.addClass("loading").data("owl-loaded", "checked");
                }
                if (base.options.lazyFollow === true) {
                    follow = itemNumber >= base.currentItem;
                } else {
                    follow = true;
                }
                if (follow && itemNumber < base.currentItem + base.options.items && $lazyImg.length) {
                    base.lazyPreload($item, $lazyImg);
                }
            }
        },

        lazyPreload : function ($item, $lazyImg) {
            var base = this,
                iterations = 0,
                isBackgroundImg;

            if ($lazyImg.prop("tagName") === "DIV") {
                $lazyImg.css("background-image", "url(" + $lazyImg.data("src") + ")");
                isBackgroundImg = true;
            } else {
                $lazyImg[0].src = $lazyImg.data("src");
            }

            function showImage() {
                $item.data("owl-loaded", "loaded").removeClass("loading");
                $lazyImg.removeAttr("data-src");
                if (base.options.lazyEffect === "fade") {
                    $lazyImg.fadeIn(400);
                } else {
                    $lazyImg.show();
                }
                if (typeof base.options.afterLazyLoad === "function") {
                    base.options.afterLazyLoad.apply(this, [base.$elem]);
                }
            }

            function checkLazyImage() {
                iterations += 1;
                if (base.completeImg($lazyImg.get(0)) || isBackgroundImg === true) {
                    showImage();
                } else if (iterations <= 100) {//if image loads in less than 10 seconds 
                    window.setTimeout(checkLazyImage, 100);
                } else {
                    showImage();
                }
            }

            checkLazyImage();
        },

        autoHeight : function () {
            var base = this,
                $currentimg = $(base.$owlItems[base.currentItem]).find("img"),
                iterations;

            function addHeight() {
                var $currentItem = $(base.$owlItems[base.currentItem]).height();
                base.wrapperOuter.css("height", $currentItem + "px");
                if (!base.wrapperOuter.hasClass("autoHeight")) {
                    window.setTimeout(function () {
                        base.wrapperOuter.addClass("autoHeight");
                    }, 0);
                }
            }

            function checkImage() {
                iterations += 1;
                if (base.completeImg($currentimg.get(0))) {
                    addHeight();
                } else if (iterations <= 100) { //if image loads in less than 10 seconds 
                    window.setTimeout(checkImage, 100);
                } else {
                    base.wrapperOuter.css("height", ""); //Else remove height attribute
                }
            }

            if ($currentimg.get(0) !== undefined) {
                iterations = 0;
                checkImage();
            } else {
                addHeight();
            }
        },

        completeImg : function (img) {
            var naturalWidthType;

            if (!img.complete) {
                return false;
            }
            naturalWidthType = typeof img.naturalWidth;
            if (naturalWidthType !== "undefined" && img.naturalWidth === 0) {
                return false;
            }
            return true;
        },

        onVisibleItems : function () {
            var base = this,
                i;

            if (base.options.addClassActive === true) {
                base.$owlItems.removeClass("active");
            }
            base.visibleItems = [];
            for (i = base.currentItem; i < base.currentItem + base.options.items; i += 1) {
                base.visibleItems.push(i);

                if (base.options.addClassActive === true) {
                    $(base.$owlItems[i]).addClass("active");
                }
            }
            base.owl.visibleItems = base.visibleItems;
        },

        transitionTypes : function (className) {
            var base = this;
            //Currently available: "fade", "backSlide", "goDown", "fadeUp"
            base.outClass = "owl-" + className + "-out";
            base.inClass = "owl-" + className + "-in";
        },

        singleItemTransition : function () {
            var base = this,
                outClass = base.outClass,
                inClass = base.inClass,
                $currentItem = base.$owlItems.eq(base.currentItem),
                $prevItem = base.$owlItems.eq(base.prevItem),
                prevPos = Math.abs(base.positionsInArray[base.currentItem]) + base.positionsInArray[base.prevItem],
                origin = Math.abs(base.positionsInArray[base.currentItem]) + base.itemWidth / 2,
                animEnd = 'webkitAnimationEnd oAnimationEnd MSAnimationEnd animationend';

            base.isTransition = true;

            base.$owlWrapper
                .addClass('owl-origin')
                .css({
                    "-webkit-transform-origin" : origin + "px",
                    "-moz-perspective-origin" : origin + "px",
                    "perspective-origin" : origin + "px"
                });
            function transStyles(prevPos) {
                return {
                    "position" : "relative",
                    "left" : prevPos + "px"
                };
            }

            $prevItem
                .css(transStyles(prevPos, 10))
                .addClass(outClass)
                .on(animEnd, function () {
                    base.endPrev = true;
                    $prevItem.off(animEnd);
                    base.clearTransStyle($prevItem, outClass);
                });

            $currentItem
                .addClass(inClass)
                .on(animEnd, function () {
                    base.endCurrent = true;
                    $currentItem.off(animEnd);
                    base.clearTransStyle($currentItem, inClass);
                });
        },

        clearTransStyle : function (item, classToRemove) {
            var base = this;
            item.css({
                "position" : "",
                "left" : ""
            }).removeClass(classToRemove);

            if (base.endPrev && base.endCurrent) {
                base.$owlWrapper.removeClass('owl-origin');
                base.endPrev = false;
                base.endCurrent = false;
                base.isTransition = false;
            }
        },

        owlStatus : function () {
            var base = this;
            base.owl = {
                "userOptions"   : base.userOptions,
                "baseElement"   : base.$elem,
                "userItems"     : base.$userItems,
                "owlItems"      : base.$owlItems,
                "currentItem"   : base.currentItem,
                "prevItem"      : base.prevItem,
                "visibleItems"  : base.visibleItems,
                "isTouch"       : base.browser.isTouch,
                "browser"       : base.browser,
                "dragDirection" : base.dragDirection
            };
        },

        clearEvents : function () {
            var base = this;
            base.$elem.off(".owl owl mousedown.disableTextSelect");
            $(document).off(".owl owl");
            $(window).off("resize", base.resizer);
        },

        unWrap : function () {
            var base = this;
            if (base.$elem.children().length !== 0) {
                base.$owlWrapper.unwrap();
                base.$userItems.unwrap().unwrap();
                if (base.owlControls) {
                    base.owlControls.remove();
                }
            }
            base.clearEvents();
            base.$elem
                .attr("style", base.$elem.data("owl-originalStyles") || "")
                .attr("class", base.$elem.data("owl-originalClasses"));
        },

        destroy : function () {
            var base = this;
            base.stop();
            window.clearInterval(base.checkVisible);
            base.unWrap();
            base.$elem.removeData();
        },

        reinit : function (newOptions) {
            var base = this,
                options = $.extend({}, base.userOptions, newOptions);
            base.unWrap();
            base.init(options, base.$elem);
        },

        addItem : function (htmlString, targetPosition) {
            var base = this,
                position;

            if (!htmlString) {return false; }

            if (base.$elem.children().length === 0) {
                base.$elem.append(htmlString);
                base.setVars();
                return false;
            }
            base.unWrap();
            if (targetPosition === undefined || targetPosition === -1) {
                position = -1;
            } else {
                position = targetPosition;
            }
            if (position >= base.$userItems.length || position === -1) {
                base.$userItems.eq(-1).after(htmlString);
            } else {
                base.$userItems.eq(position).before(htmlString);
            }

            base.setVars();
        },

        removeItem : function (targetPosition) {
            var base = this,
                position;

            if (base.$elem.children().length === 0) {
                return false;
            }
            if (targetPosition === undefined || targetPosition === -1) {
                position = -1;
            } else {
                position = targetPosition;
            }

            base.unWrap();
            base.$userItems.eq(position).remove();
            base.setVars();
        }

    };

    $.fn.owlCarousel = function (options) {
        return this.each(function () {
            if ($(this).data("owl-init") === true) {
                return false;
            }
            $(this).data("owl-init", true);
            var carousel = Object.create(Carousel);
            carousel.init(options, this);
            $.data(this, "owlCarousel", carousel);
        });
    };

    $.fn.owlCarousel.options = {

        items : 5,
        itemsCustom : false,
        itemsDesktop : [1199, 4],
        itemsDesktopSmall : [979, 3],
        itemsTablet : [768, 2],
        itemsTabletSmall : false,
        itemsMobile : [479, 1],
        singleItem : false,
        itemsScaleUp : false,

        slideSpeed : 200,
        paginationSpeed : 800,
        rewindSpeed : 1000,

        autoPlay : false,
        stopOnHover : false,

        navigation : false,
        navigationText : ["prev", "next"],
        rewindNav : true,
        scrollPerPage : false,

        pagination : true,
        paginationNumbers : false,

        responsive : true,
        responsiveRefreshRate : 200,
        responsiveBaseWidth : window,

        baseClass : "owl-carousel",
        theme : "owl-theme",

        lazyLoad : false,
        lazyFollow : true,
        lazyEffect : "fade",

        autoHeight : false,

        jsonPath : false,
        jsonSuccess : false,

        dragBeforeAnimFinish : true,
        mouseDrag : true,
        touchDrag : true,

        addClassActive : false,
        transitionStyle : false,

        beforeUpdate : false,
        afterUpdate : false,
        beforeInit : false,
        afterInit : false,
        beforeMove : false,
        afterMove : false,
        afterAction : false,
        startDragging : false,
        afterLazyLoad: false
    };
}(jQuery, window, document));
