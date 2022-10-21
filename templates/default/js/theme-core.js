
(function () {
    "use strict";

    var Core = {

        initialized: false,

        initialize: function () {
            if (this.initialized) return;
            this.initialized = true;


            this.v_Common();
            this.v_NavMenu();
            this.v_HeaderSearch();
            this.v_FancyHeading();
            this.v_Counter();
            this.v_IconBoxes();
            this.v_Map.init();
            this.v_Parallax.init();
            this.v_FlexSlider.init();
            this.v_Portfolio.init();
            this.v_Blog.init();
            this.v_ProgressBar();
            this.v_owlCarousel();
            this.v_AnchorsPosition();
            this.v_FigureHover();
        },

        //FigureHover
        v_FigureHover: function () {

            var $this = jQuery("figure.animated-overlay");

            $this.hover(
				function () {
				    jQuery(this).find("figcaption .thumb-info i").css(
                        {
                            visibility: "visible",
                            opacity: "1",
                            transitionDuration: "0.3s",
                            transform: "scale(1) rotate(0deg)"
                        });
				}
			);

            $this.mouseleave(
				function () {
				    jQuery(this).find("figcaption .thumb-info i").css(
                        {
                            transitionDuration: "0.3s",
                            transform: "scale(0.5) rotate(-90deg)"
                        });
				}
			);
        },
        //End FigureHover


        //NavMenu
        v_NavMenu: function () {

            // Responsive Menu Events
            var addActiveClass = false;

            $("#mainMenu li.dropdown > a, #mainMenu li.dropdown-submenu > a").on("click", function (e) {

                if ($(window).width() > 979) return;

                e.preventDefault();

                addActiveClass = $(this).parent().hasClass("resp-active");

                $("#mainMenu").find(".resp-active").removeClass("resp-active");

                if (!addActiveClass) {
                    $(this).parents("li").addClass("resp-active");
                }

                return;

            });

            // Submenu Check Visible Space
            $("#mainMenu li.dropdown-submenu").hover(function () {

                if ($(window).width() < 767) return;

                var subMenu = $(this).find("ul.dropdown-menu");

                if (!subMenu.get(0)) return;

                var screenWidth = $(window).width(),
					subMenuOffset = subMenu.offset(),
					subMenuWidth = subMenu.width(),
					subMenuParentWidth = subMenu.parents("ul.dropdown-menu").width(),
					subMenuPosRight = subMenu.offset().left + subMenu.width();

                if (subMenuPosRight > screenWidth) {
                    subMenu.css("margin-left", "-" + (subMenuParentWidth + subMenuWidth + 10) + "px");
                } else {
                    subMenu.css("margin-left", 0);
                }

            });

            // Mega Menu
            $(document).on("click", ".mega-menu .dropdown-menu", function (e) {
                e.stopPropagation()
            });

        },
        //End NavMenu


        //HeaderSearch
        v_HeaderSearch: function () {

            var searchEl = $("#headerSearch .search-input"),
				searchSubmit = searchEl.find("button");

            $(document).on("click", function (e) {
                if ($(e.target).closest("#headerSearch").length === 0) {
                    searchEl.removeClass("active");
                    setTimeout(function () {
                        searchEl.hide();
                    }, 250);
                }
            });

            $("#headerSearchOpen").on("click", function (e) {
                e.preventDefault();

                searchEl.show();

                setTimeout(function () {
                    searchEl.addClass("active");
                }, 50);

                setTimeout(function () {
                    searchEl.find("input").focus();
                }, 250);
            });

            searchSubmit.on("click", function (e) {
                $("#headerSearchForm").submit();
            });

        },
        //End HeaderSearch


        //Anchors Position
        v_AnchorsPosition: function () {
            $("a[data-hash]").on("click", function (e) {
                e.preventDefault();
                var header = $("body header:first"),
					headerHeight = header.height(),
					target = $(this).attr("href"),
					$this = $(this);

                var stickyHeight = $(window).scrollTop() > 60 ? 0: 30; // for the sticky menu            

                if ($(window).width() > 991) {
                    $("html,body").animate({ scrollTop: $(target).offset().top - (headerHeight) + stickyHeight }, 600, "easeOutQuad");
                } else {
                    $("html,body").animate({ scrollTop: $(target).offset().top }, 600, "easeOutQuad");
                }

                return false;
            });
        },
        //End Anchors Position


        //owlCarousel
        v_owlCarousel: function (options) {

            var total = $("div.owl-carousel:not(.manual)").length,
				count = 0;

            $("div.owl-carousel:not(.manual)").each(function () {

                var slider = $(this);

                var defaults = {
                    // Most important owl features
                    items: 5,
                    itemsCustom: false,
                    itemsDesktop: [1199, 4],
                    itemsDesktopSmall: [980, 3],
                    itemsTablet: [768, 2],
                    itemsTabletSmall: false,
                    itemsMobile: [479, 1],
                    singleItem: false,
                    itemsScaleUp: false,

                    //Basic Speeds
                    slideSpeed: 200,
                    paginationSpeed: 800,
                    rewindSpeed: 1000,

                    //Autoplay
                    autoPlay: false,
                    stopOnHover: false,

                    // Navigation
                    navigation: false,
                    navigationText: ["<i class=\"fa fa-chevron-left\"></i>", "<i class=\"fa fa-chevron-right\"></i>"],
                    rewindNav: true,
                    scrollPerPage: false,

                    //Pagination
                    pagination: false,
                    paginationNumbers: false,

                    // Responsive 
                    responsive: true,
                    responsiveRefreshRate: 200,
                    responsiveBaseWidth: window,

                    // CSS Styles
                    baseClass: "owl-carousel",
                    theme: "owl-theme",

                    //Lazy load
                    lazyLoad: false,
                    lazyFollow: true,
                    lazyEffect: "fade",

                    //Auto height
                    autoHeight: false,

                    //JSON 
                    jsonPath: false,
                    jsonSuccess: false,

                    //Mouse Events
                    dragBeforeAnimFinish: true,
                    mouseDrag: true,
                    touchDrag: true,

                    //Transitions
                    transitionStyle: false,

                    // Other
                    addClassActive: false,

                    //Callbacks
                    beforeUpdate: false,
                    afterUpdate: false,
                    beforeInit: false,
                    afterInit: false,
                    beforeMove: false,
                    afterMove: false,
                    afterAction: false,
                    startDragging: false,
                    afterLazyLoad: false
                }

                var config = $.extend({}, defaults, options, slider.data("plugin-options"));

                // Initialize Slider
                slider.owlCarousel(config).addClass("owl-carousel-init");

            });


            $("div.carousel-wrap").each(function () {
                
                var slider = $(this);

                slider.find(".customNavigation .next").click(function () {
                    slider.find("div.owl-carousel:not(.manual)").trigger('owl.next');
                })
                slider.find(".customNavigation .prev").click(function () {
                    slider.find("div.owl-carousel:not(.manual)").trigger('owl.prev');
                })

            });

        },
        //owlCarousel


        //FlexSlider
        v_FlexSlider: {
            init: function () {

                if (jQuery('.recent-posts').length > 0) {
                    Core.v_FlexSlider.thumb();
                }

                jQuery('.item-slider').flexslider({
                    animation: "slide",             //String: Select your animation type, "fade" or "slide"
                    slideDirection: "horizontal",   //String: Select the sliding direction, "horizontal" or "vertical"
                    slideshow: true,	            //Boolean: Animate slider automatically
                    slideshowSpeed: 6000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
                    animationDuration: 500,			//Integer: Set the speed of animations, in milliseconds
                    smoothHeight: true,
                    directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
                    controlNav: true,               //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
                    keyboardNav: false,             //Boolean: Allow slider navigating via keyboard left/right keys
                    mousewheel: false,              //Boolean: Allow slider navigating via mousewheel
                    prevText: "Prev",               //String: Set the text for the "previous" directionNav item
                    nextText: "Next",               //String: Set the text for the "next" directionNav item
                    pausePlay: true,                //Boolean: Create pause/play dynamic element
                    pauseText: '',                  //String: Set the text for the "pause" pausePlay item
                    playText: '',                   //String: Set the text for the "play" pausePlay item
                    randomize: false,               //Boolean: Randomize slide order
                    slideToStart: 0,                //Integer: The slide that the slider should start on. Array notation (0 = first slide)
                    animationLoop: true,            //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
                    pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
                    pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
                    controlsContainer: "",          //Selector: Declare which container the navigation elements should be appended too. Default container is the flexSlider element. Example use would be ".flexslider-container", "#container", etc. If the given element is not found, the default action will be taken.
                    manualControls: "",             //Selector: Declare custom control navigation. Example would be ".flex-control-nav li" or "#tabs-nav li img", etc. The number of elements in your controlNav should match the number of slides/tabs.
                    start: function () { },         //Callback: function(slider) - Fires when the slider loads the first slide
                    before: function () { },        //Callback: function(slider) - Fires asynchronously with each slider animation
                    after: function () { },         //Callback: function(slider) - Fires after each slider animation completes
                    end: function () { }            //Callback: function(slider) - Fires when the slider reaches the last slide (asynchronous)
                });
                jQuery('.content-slider').each(function () {
                    var slider = jQuery(this),
                        autoplay = ((slider.attr('data-autoplay') === "yes") ? true : false);

                    slider.flexslider({
                        animation: "fade",              //String: Select your animation type, "fade" or "slide"
                        slideshow: autoplay,	        //Boolean: Animate slider automatically
                        slideshowSpeed: 6000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
                        animationDuration: 1000,		//Integer: Set the speed of animations, in milliseconds
                        smoothHeight: true,
                        directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
                        controlNav: false,              //Boolean: Create navigation for paging control of each clide? Note: Leave true for manualControls usage
                        start: function () { }
                    });
                });

            },
            thumb: function () {
                jQuery('.thumb-slider').flexslider({
                    animation: "fade",              //String: Select your animation type, "fade" or "slide"
                    slideDirection: "horizontal",   //String: Select the sliding direction, "horizontal" or "vertical"
                    slideshow: false,	            //Boolean: Animate slider automatically
                    slideshowSpeed: 6000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
                    animationDuration: 600,         //Integer: Set the speed of animations, in milliseconds
                    directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
                    controlNav: false,              //Boolean: Create navigation for paging control of each slide? Note: Leave true for manualControls usage
                    keyboardNav: false,             //Boolean: Allow slider navigating via keyboard left/right keys
                    smoothHeight: true
                });
            },

            gallery: function () {
                jQuery('.v-gallery-widget').each(function () {

                    var gallerySlider = jQuery(this).find('.gallery-slider');

                    gallerySlider.flexslider({
                        animation: gallerySlider.data('transition'),
                        controlNav: false,
                        animationLoop: false,
                        slideshow: false
                    });

                });

            }
        },
        //End FlexSlider


        //Blog
        v_Blog: {
            init: function () {
                if (blogItems.hasClass('masonry-items')) {
                    Core.v_Blog.masonryBlog();
                }
            },
            masonryBlog: function () {
                if (!(IEVersion && IEVersion < 9)) {
                    var scrollAnimateElement = new AnimOnScroll(document.getElementById('blogGrid'), {
                        minDuration: 0.4,
                        maxDuration: 0.7,
                        viewportFactor: 0.2
                    });
                }
                blogItems.imagesLoaded(function () {
                    Core.v_FlexSlider.thumb();
                });
                blogItems.fitVids();
            }
        },
        //End Blog


        //Parallax
        v_Parallax: {
            init: function () {

                jQuery('.v-parallax').each(function () {

                    var parallaxAsset = jQuery(this);

                    if (parallaxAsset.hasClass('v-parallax-video')) {

                        if (!isMobileAlt) {

                            var parallaxVideo = parallaxAsset.find('video'),
								parallaxContent = parallaxAsset.find('.v-content-wrapper'),
								parallaxVideoTop = 0;

                            parallaxVideo.css('top', -parallaxVideoTop);
                            parallaxVideo.attr('data-top-default', parallaxVideoTop);

                            if (parallaxAsset.hasClass('parallax-video-height')) {
                                parallaxAsset.animate({
                                    'height': parallaxVideo.height() / 2
                                }, 400);
                                setTimeout(function () {
                                    parallaxAsset.find('.video-overlay').animate({
                                        'opacity': 0.8
                                    }, 200);
                                }, 100);
                                parallaxContent.vCenterTop();
                                setTimeout(function () {
                                    parallaxContent.animate({
                                        'opacity': 1,
                                        'top': '50%'
                                    }, 600, 'easeOutExpo');
                                }, 600);
                                parallaxAsset.attr('data-height-default', parallaxVideo.height() / 2);
                                $window.smartresize(function () {
                                    parallaxAsset.animate({
                                        height: parallaxVideo.height() / 2
                                    }, 400);
                                    parallaxVideo.css('top', -parallaxVideo.height() / 4);
                                    parallaxVideo.attr('data-top-default', parallaxVideo.height() / 4);
                                    parallaxAsset.attr('data-height-default', parallaxVideo.height() / 2);
                                });
                            }

                            $window.scroll(function () {
                                if ($window.width() > 1024) {
                                    Core.v_Parallax.videoScroll(parallaxAsset);
                                }
                            });
                        } else {
                            parallaxAsset.find('video').remove();
                        }

                    } else if (parallaxAsset.hasClass('parallax-window-height')) { 
                        jQuery(this).height($window.height() - (parseInt(jQuery(this).css('padding-top'), 10) * 2) - 30);
                        jQuery(this).find('.v-content-wrapper').vCenterTop();
                        $window.scroll(function () {
                            if ($window.width() > 1024) {
                                //Core.v_Parallax.windowImageScroll(parallaxAsset);
                            }
                        });
                    }
                });
            },
            videoScroll: function (asset) {

                var offsetTop = asset.offset().top,
                    windowTop = $window.scrollTop(),
                    defaultHeight = parseInt(asset.data('height-default'), 10),
                    diff = windowTop - offsetTop,
                    currentTop = asset.find('.v-content-wrapper').css('top'),
                    heightDifference = defaultHeight - diff * 1.5;

                if (windowTop > offsetTop) {
                    asset.css('height', heightDifference);
                    asset.find('.v-content-wrapper').css('opacity', 1 - (diff / 300));
                    if (asset.hasClass('parallax-video-height')) {
                        asset.find('.v-content-wrapper').css('top', currentTop + (diff / 4));
                    } else {
                        asset.find('.v-content-wrapper').css('top', (diff / 3));
                    }
                } else {
                    asset.css('height', defaultHeight);
                    asset.find('.v-content-wrapper').css('opacity', 1);
                    if (asset.hasClass('parallax-video-height')) {
                        asset.find('.v-content-wrapper').css('top', '50%');
                    } else {
                        asset.find('.v-content-wrapper').css('top', 0);
                    }
                }

            },
            //windowImageScroll: function (asset) {
            //    asset.height($window.height() - jQuery(this).css('padding-top') / 2);
            //    asset.find('.v-content-wrapper').vCenterTop();
            //}
        },
        //End Parallax


        //Map
        v_Map: {
            init: function () {

                var maps = jQuery('.map-canvas');
                maps.each(function (index, element) {
                    var mapContainer = element,
                        mapAddress = mapContainer.getAttribute('data-address'),
                        mapZoom = mapContainer.getAttribute('data-zoom'),
                        mapType = mapContainer.getAttribute('data-maptype'),
                        mapColor = mapContainer.getAttribute('data-mapcolor'),
                        mapSaturation = mapContainer.getAttribute('data-mapsaturation'),
                        pinLogoURL = mapContainer.getAttribute('data-pinimage');

                    Core.v_Map.getCoordinates(mapAddress, mapContainer, mapZoom, mapType, mapColor, mapSaturation, pinLogoURL);

                });

                Core.v_Map.fullscreenMap();
                $window.smartresize(function () {
                    Core.v_Map.fullscreenMap();
                });

            },
            getCoordinates: function (address, mapContainer, mapZoom, mapType, mapColor, mapSaturation, pinLogoURL) {
                var geocoder;
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'address': address
                }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {

                        if (mapSaturation == "mono") {
                            mapSaturation = -100;
                        } else {
                            mapSaturation = -20;
                        }

                        var styles = [
                            {
                                stylers: [
                                    { hue: mapColor },
                                    { saturation: mapSaturation }
                                ]
                            }
                        ];

                        var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" });

                        var mapTypeIdentifier = "",
                            companyPos = "",
                            isDraggable = true,
                            mapCoordinates = results[0].geometry.location,
                            latitude = results[0].geometry.location.lat(),
                            longitude = results[0].geometry.location.lng();

                        if (isMobileAlt) {
                            isDraggable = false;
                        }

                        if (mapType === "satellite") {
                            mapTypeIdentifier = google.maps.MapTypeId.SATELLITE;
                        } else if (mapType === "terrain") {
                            mapTypeIdentifier = google.maps.MapTypeId.TERRAIN;
                        } else if (mapType === "hybrid") {
                            mapTypeIdentifier = google.maps.MapTypeId.HYBRID;
                        } else {
                            mapTypeIdentifier = google.maps.MapTypeId.ROADMAP;
                        }

                        var latlng = new google.maps.LatLng(latitude, longitude);
                        var settings = {
                            zoom: parseInt(mapZoom, 10),
                            scrollwheel: false,
                            center: latlng,
                            draggable: isDraggable,
                            mapTypeControl: true,
                            mapTypeControlOptions: { style: google.maps.MapTypeControlStyle.DROPDOWN_MENU },
                            navigationControl: true,
                            navigationControlOptions: { style: google.maps.NavigationControlStyle.SMALL },
                            mapTypeId: mapTypeIdentifier
                        };
                        var mapInstance = new google.maps.Map(mapContainer, settings);
                        var companyMarker = "";
                                                
                        jQuery(mapContainer).appear(function () {
                            setTimeout(function () {
                                if (pinLogoURL) {
                                    var companyLogo = new google.maps.MarkerImage(pinLogoURL,
                                        new google.maps.Size(150, 75),
                                        new google.maps.Point(0, 0),
                                        new google.maps.Point(75, 75)
                                    );
                                    companyPos = new google.maps.LatLng(latitude, longitude);
                                    companyMarker = new google.maps.Marker({
                                        position: mapCoordinates,
                                        map: mapInstance,
                                        icon: companyLogo,
                                        animation: google.maps.Animation.DROP
                                    });
                                } else {
                                    companyPos = new google.maps.LatLng(latitude, longitude);
                                    companyMarker = new google.maps.Marker({
                                        position: mapCoordinates,
                                        map: mapInstance,
                                        animation: google.maps.Animation.DROP
                                    });
                                }

                                google.maps.event.addListener(companyMarker, 'click', function () {
                                    window.location.href = '//maps.google.com/maps?q=' + companyPos;
                                });

                                google.maps.event.addDomListener(window, 'resize', function () {
                                    mapInstance.setCenter(companyPos);
                                });
                            }, 1000);
                        });
                                                
                        if (mapColor !== "") {
                            mapInstance.mapTypes.set('map_style', styledMap);
                            mapInstance.setMapTypeId('map_style');
                        }

                    } else {
                        console.log(status);
                    }
                });
            },
            fullscreenMap: function () {
                var fullscreenMap = jQuery('.fullscreen-map'),
                    container = jQuery('#container'),
                    mapOffset = container.offset() != undefined ? container.offset().left : 0,
                    windowWidth = $window.width();

                if (windowWidth > 768) {
                    mapOffset = mapOffset;
                } else {
                    mapOffset = 20;
                }

                if (jQuery('body').hasClass('boxed-layout')) {
                    windowWidth = jQuery('body').width();

                    if (windowWidth > 1024) {
                        mapOffset = 45;
                    } else if (windowWidth > 768) {
                        mapOffset = 30;
                    } else if (windowWidth > 480) {
                        mapOffset = 24;
                    } else {
                        mapOffset = 20;
                    }
                }

                fullscreenMap.find('.map-canvas').css('width', windowWidth);
                fullscreenMap.css('margin-left', '-' + mapOffset + 'px');

            }
        },
        //End Map

        //Common
        v_Common: function () {
            if (isMobile) {
                body.addClass("mobile-browser");
            } else {
                body.addClass("standard-browser");
            }
            if (isAppleDevice) {
                body.addClass("apple-mobile-browser");
            }
            // ADD IE CLASS
            if (IEVersion && IEVersion < 10) {
                body.addClass('browser-ie');
            }

            // ADD IE10 CLASS
            var pattern = /MSIE\s([\d]+)/,
				ua = navigator.userAgent,
				matched = ua.match(pattern);
            if (matched) {
                body.addClass('browser-ie10');
            }

            jQuery(".modal").each(function () {
                jQuery(this).appendTo("body");
            });
        },
        //End Common
        

        //IconBoxes
        v_IconBoxes: function () {
            jQuery('.v-icon-box').hover(
				function () {
				    jQuery(this).addClass('sf-hover');
				}, function () {
				    jQuery(this).removeClass('sf-hover');
				}
			);
        },
        //End IconBoxes


        //Counter
        v_Counter: function () {
            jQuery('.v-counter').each(function () {

                var countAsset = jQuery(this),
					countNumber = countAsset.find('.count-number'),
					countDivider = countAsset.find('.count-divider').find('span'),
					countSubject = countAsset.find('.v-counter-text');

                if (!isMobileAlt) {
                    countAsset.appear(function () {

                        countNumber.countTo({
                            onComplete: function () {
                                countDivider.animate({
                                    'width': 50
                                }, 400, 'easeOutCubic');
                                countSubject.delay(100).animate({
                                    'opacity': 1,
                                    'bottom': '0px'
                                }, 600, 'easeOutCubic');
                            }
                        });

                    }, { accX: 0, accY: -150 }, 'easeInCubic');
                } else {
                    countNumber.countTo({
                        onComplete: function () {
                            countDivider.animate({
                                'width': 50
                            }, 400, 'easeOutCubic');
                            countSubject.delay(100).animate({
                                'opacity': 1,
                                'bottom': '0px'
                            }, 600, 'easeOutCubic');
                        }
                    });
                }

            });
        },
        //End Counter


        //FancyHeading
        v_FancyHeading: function () {
            if (jQuery('.v-fancy-heading').hasClass('v-fancy-image')) {
                $window.stellar();
            }
            setTimeout(function () {
                jQuery('.v-fancy-heading').slideDown({
                    duration: 600,
                    easing: "easeInOutQuart"
                });
            }, 200);
        },
        //End FancyHeading


        //ProgressBar
        v_ProgressBar: function () {
            $("[data-appear-progress-animation]").each(function () {
                var $this = $(this);

                $this.appear(function () {
                    var delay = ($this.attr("data-appear-animation-delay") ? $this.attr("data-appear-animation-delay") : 1);

                    if (delay > 1) $this.css("animation-delay", delay + "ms");
                    $this.addClass($this.attr("data-appear-animation"));

                    setTimeout(function () {
                        $this.animate({
                            width: $this.attr("data-appear-progress-animation")
                        }, 1500, "easeOutQuad", function () {
                            $this.find(".progress-bar-tooltip").animate({
                                opacity: 1
                            }, 500, "easeOutQuad");
                        });
                    }, delay);
                }, { accX: 0, accY: -50 });
            });
        },
        //End ProgressBar
 

        //BackToTop
        v_BackToTop: function () {
            var scrollPosition = $window.scrollTop();

            if (scrollPosition > 500) {
                jQuery('#back-to-top').stop().animate({
                    'bottom': '62px',
                    'opacity': 1
                }, 300, "easeOutQuart");
            } else if (scrollPosition < 500) {
                jQuery('#back-to-top').stop().animate({
                    'bottom': '-40px',
                    'opacity': 0
                }, 300, "easeInQuart");
            }
        },
        //End BackToTop


        //ReloadFunctions
        v_ReloadFunctions: {
            init: function () {

                // Remove title attributes from images to avoid showing on hover 
                jQuery('img[title]').each(function () {
                    jQuery(this).removeAttr('title');
                });

                if (!isAppleDevice) {
                    jQuery('embed').show();
                }

                // Animate Top Links
                jQuery('.animate-top').on('click', function (e) {
                    e.preventDefault();
                    jQuery('body,html').animate({ scrollTop: 0 }, 800, 'easeOutCubic');
                });
            },
            load: function () {
                if (!isMobile) {

                    // Button hover tooltips
                    jQuery('.tooltip').each(function () {
                        jQuery(this).css('marginLeft', '-' + Math.round((jQuery(this).outerWidth(true) / 2)) + 'px');
                    });

                    jQuery('.comment-avatar').hover(function () {
                        jQuery(this).find('.tooltip').stop().animate({
                            bottom: '44px',
                            opacity: 1
                        }, 500, 'easeInOutExpo');
                    }, function () {
                        jQuery(this).find('.tooltip').stop().animate({
                            bottom: '25px',
                            opacity: 0
                        }, 400, 'easeInOutExpo');
                    });
                }
            }
        },
        //End ReloadFunctions


        //Portfolio
        v_Portfolio: {
            init: function () {
                if (portfolioContainer.hasClass('masonry-items')) {
                    Core.v_Portfolio.masonrySetup();
                } else {
                    Core.v_Portfolio.standardSetup();
                }

                // PORTFOLIO WINDOW RESIZE
                $window.smartresize(function () {
                    Core.v_Portfolio.windowResized();
                });

                // Enable filter options on when there are items from that skill
                jQuery('.filtering li').each(function () {
                    var itemCount = 0;
                    var filter = jQuery(this),
                        filterName = jQuery(this).find('a').attr('class'),
                        portfolioItems = jQuery(this).parent().parent().parent().find('.filterable-items');

                    portfolioItems.find('.v-portfolio-item').each(function () {
                        if (jQuery(this).hasClass(filterName)) {
                            filter.addClass('has-items');
                            itemCount++;
                        }
                    });

                    if (jQuery(this).hasClass('all')) {
                        itemCount = portfolioItems.children('li').length;
                        jQuery(this).find('.item-count').text(itemCount);
                    } else {
                        jQuery(this).find('.item-count').text(itemCount);
                    }
                }).parents('.v-portfolio-filter-nav').animate({
                    opacity: 1
                }, 400);

                // filter items when filter link is clicked
                jQuery('.filtering li').on('click', 'a', function (e) {
                    e.preventDefault();
                    jQuery(this).parent().parent().find('li').removeClass('selected');
                    jQuery(this).parent().addClass('selected');
                    var selector = jQuery(this).data('filter');
                    var portfolioItems = jQuery(this).parent().parent().parent().parent().find('.filterable-items');
                    portfolioItems.isotope({ filter: selector });
                });

                jQuery('.v-portfolio-filter-wrap > a').on('click', function (e) {
                    e.preventDefault();
                    jQuery(this).parent().find('.filter-slide-wrap').slideToggle();
                });
            },
            standardSetup: function () {
                // SET ITEM HEIGHTS
                portfolioContainer.imagesLoaded(function () {
                    Core.v_Portfolio.setItemHeight();
                    Core.v_FlexSlider.thumb();
                    portfolioContainer.animate({ opacity: 1 }, 800);
                    portfolioContainer.isotope({
                        animationEngine: 'best-available',
                        animationOptions: {
                            duration: 300,
                            easing: 'easeInOutQuad',
                            queue: false
                        },
                        resizable: true,
                        layoutMode: 'fitRows'
                    });
                    portfolioContainer.isotope("reLayout");
                });
            },
            masonrySetup: function () {
                portfolioContainer.fitVids();
                portfolioContainer.imagesLoaded(function () {
                    Core.v_FlexSlider.thumb();
                    portfolioContainer.animate({ opacity: 1 }, 800);
                    portfolioContainer.isotope({
                        itemSelector: '.v-portfolio-item',
                        animationEngine: 'best-available',
                        animationOptions: {
                            duration: 300,
                            easing: 'easeInOutQuad',
                            queue: false
                        },
                        resizable: true
                    });

                });
            },
            setItemHeight: function () {
                if (!portfolioContainer.hasClass('masonry-items')) {
                    portfolioContainer.children().css('min-height', '0');
                    portfolioContainer.equalHeights();
                }
            },
            windowResized: function () {
                if (!portfolioContainer.hasClass('masonry-items')) {
                    Core.v_Portfolio.setItemHeight();
                }
                portfolioContainer.isotope("reLayout");
            },
        },
        //End Portfolio


        //GetIEVersion
        v_GetIEVersion: function () {

            var undef,
				v = 3,
				div = document.createElement('div'),
				all = div.getElementsByTagName('i');

            while (
				div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
				all[0]
			);

            return v > 4 ? v : undef;
        },
        //End GetIEVersion


        //FitVids
        v_FitVids: function () {
            jQuery('.v-portfolio-items,.v-blog-items,article.type-portfolio,article.type-post,article.type-team,.v-video-widget,.infocus-item,.recent-posts,.full-width-detail').fitVids();
        },
        //End FitVids


        //GetWindowHeight
        v_GetWindowHeight: function () {
            var height = "innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight;
            return height;
        },
        //End GetWindowHeight
    };
    //End Core


    var $window = jQuery(window),
    body = jQuery('body'),
    deviceAgent = navigator.userAgent.toLowerCase(),
    isMobile = deviceAgent.match(/(iphone|ipod|android|iemobile)/),
    isMobileAlt = deviceAgent.match(/(iphone|ipod|ipad|android|iemobile)/),
    isAppleDevice = deviceAgent.match(/(iphone|ipod|ipad)/),
    portfolioContainer = jQuery('.v-portfolio-wrap').find('.filterable-items'),
    blogItems = jQuery('.v-blog-wrap').find('.v-blog-items'),
    IEVersion = Core.v_GetIEVersion(),
    windowheight = Core.v_GetWindowHeight();

    //$("body").queryLoader2({
    //    percentage: true,
    //    onLoadComplete: Core.initialize()
    //}).parent().css({ 'overflow': 'hidden' });

    //$("body").queryLoader2({
    //    percentage: true,
    //    onLoadComplete: Core.initialize()
    //});

    Core.initialize();
 

    $(window).load(function () {
        jQuery('[rel=tooltip]').tooltip();

        $('.popover-dismiss').popover({
            trigger: 'focus'
        })


        Core.v_FlexSlider.init();

        Core.v_Map.init();

        Core.v_FitVids();

        Core.v_ReloadFunctions.load();
        Core.v_ReloadFunctions.init();

        Core.v_FlexSlider.gallery();
    });

    $window.scroll(function () {
        Core.v_BackToTop();
    });

})();



function print_window() {
    var e = window;
    e.document.close(),
    e.focus(),
    e.print(),
    e.close()
}
!function (e) {
    e(document).ready(function () {
        e(".boxed .fullscreen-bg").length > 0 && e("body").addClass("transparent-page-wrapper"),
        e(window).load(function () {
            e("body").removeClass("no-trans")
        }
        );
        var o = navigator.platform.toLowerCase();
        0 != o.indexOf("win") && 0 != o.indexOf("linux") || Modernizr.touch || e.browser.webkit && ( 
        console.log("hello webkit"));
        var t, a = 0;
        (Modernizr.mq("only all and (min-width: 768px)") && !Modernizr.touch || e("html.ie8").length > 0) && e(".main-navigation:not(.onclick) .navbar-nav>li.dropdown, .main-navigation:not(.onclick) li.dropdown>ul>li.dropdown").hover(function () {
            var o = e(this);
            t = setTimeout(function () {
                o.addClass("open").slideDown(),
                o.find(".dropdown-toggle").addClass("disabled")
            }
            , a)
        }
        , function () {
            clearTimeout(t),
            e(this).removeClass("open"),
            e(this).find(".dropdown-toggle").removeClass("disabled")
        }
        ),
        (Modernizr.mq("only all and (max-width: 767px)") || Modernizr.touch || e(".main-navigation.onclick").length > 0) && e(".main-navigation [data-toggle=dropdown], .header-top [data-toggle=dropdown]").on("click", function (o) {
            o.preventDefault(),
            o.stopPropagation(),
            e(this).parent().siblings().removeClass("open"),
            e(this).parent().siblings().find("[data-toggle=dropdown]").parent().removeClass("open"),
            e(this).parent().toggleClass("open")
        }
        );


        var i;
        if (e(".transparent-header").length > 0 && (e(window).load(function () {
            trHeaderHeight = e("header.header").outerHeight(),
            e(".transparent-header .tp-bannertimer").css("marginTop", trHeaderHeight + "px")
        }),

        e(window).resize(function () {
            e(this).scrollTop() < headerTopHeight + headerHeight - 5 && (trHeaderHeight = e("header.header").outerHeight(),
            e(".transparent-header .tp-bannertimer").css("marginTop", trHeaderHeight + "px"))
        }),

        e(window).scroll(function () {
            0 == e(this).scrollTop() && (i && window.clearTimeout(i),
            i = window.setTimeout(function () {
                trHeaderHeight = e("header.header").outerHeight(),
                e(".transparent-header .tp-bannertimer").css("marginTop", trHeaderHeight + "px")
        }, 300))
        })),

        e(".transparent-header .slideshow").length > 0 ?
            e(".header-container header.header").addClass("transparent-header-on") : e(".header-container header.header").removeClass("transparent-header-on"),


        e(".floating-header .slideshow").length > 0 ?
            e(".header-container header.header").addClass("floating-header-on") : e(".header-container header.header").removeClass("floating-header-on"),
     
        headerTopHeight = e(".header-top").outerHeight(),
        headerHeight = e("header.header.fixed").outerHeight(),
        e(window).resize(function () {
            e(this).scrollTop() < headerTopHeight + headerHeight - 5 && e(window).width() > 767 && (headerTopHeight = e(".header-top").outerHeight(),
            headerHeight = e("header.header.fixed").outerHeight())
        }),


        e(window).scroll(function () {
            e(".header.fixed").length > 0 && !(e(".transparent-header .slideshow, .floating-header .slideshow").length > 0) ? e(this).scrollTop() > headerTopHeight + headerHeight && e(window).width() > 767 ? (e("body").addClass("fixed-header-on"),
            e(".header.fixed").addClass("animated fadeInDown"),
            e(".header-container").css("paddingBottom", headerHeight + "px")) : (e("body").removeClass("fixed-header-on"),
            e(".header-container").css("paddingBottom", "0px"),
            e(".header.fixed").removeClass("animated fadeInDown")) : e(".header.fixed").length > 0 && (e(this).scrollTop() > headerTopHeight + headerHeight && e(window).width() > 767 ? (e("body").addClass("fixed-header-on"),
            e(".header.fixed").addClass("animated fadeInDown")) : (e("body").removeClass("fixed-header-on"),
            e(".header.fixed").removeClass("animated fadeInDown")))
        }),


        e(".graph").length > 0) {
            var n = function () {
                return Math.round(500 * Math.random())
            }
            ;
        }
        e(".header-top .dropdown-menu input").click(function (e) {
            e.stopPropagation()
        }
        )
    }
    )
}
(this.jQuery),
jQuery(".btn-print").length > 0,
function (e, o, t, a, i, n, r) {
    e.GoogleAnalyticsObject = i,
    e[i] = e[i] || function () {
        (e[i].q = e[i].q || []).push(arguments)
    }
    ,
    e[i].l = 1 * new Date,
    n = o.createElement(t),
    r = o.getElementsByTagName(t)[0],
    n.async = 1,
    n.src = a,
    r.parentNode.insertBefore(n, r)
};


// SEARCH
jQuery(document).ready(function () {
    jQuery('#headerSearchsMobile').on('click',    function(){
        jQuery('header div.search-input').css({'opacity'   :'1', 'right':'auto', 'left':'10px', 'top':'40px'}).toggle();
        jQuery('.v-arrow-wrap').css('right', 'auto');
        jQuery('header div.search-input #keywords').focus();
	});
});
