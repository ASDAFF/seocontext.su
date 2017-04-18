;(function ($, undefined) {

    /**
     *******************************************************************************************************************
     * Скроллинг вверх
     *******************************************************************************************************************
     */

    // опции по умолчанию
    var defaults = { breakpoint : '#back_to_top', offset : 0 };
    // конструктор
    function backToTop(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);

        this.init();
    }
    // глобальные переменные

    // методы
    backToTop.prototype = {

        // публичные методы

        init : function () {
            var self               = $(this.element),
                BreakPointSelector = $(this.options.breakpoint),
                breakPointPosition = BreakPointSelector.position().top + this.options.offset,
                windowHeight       = $(window).height(),
                docHeight          = $(document).height();

            $(window).scroll(function() {
                var topOffset = $(this).scrollTop();

                if ($(window).width() < 991) {
                    self.fadeOut();
                    return;
                }

                if ( (topOffset > breakPointPosition) && (breakPointPosition < windowHeight || breakPointPosition < docHeight) ) {
                    self.fadeIn();
                } else {
                    self.fadeOut();
                }
            });

            self.click(function() {
                $('html,body').animate({
                    scrollTop: $('body').position().top
                }, 'slow');
                return false;
            });

        }
    };

    $.fn['backToTop'] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'liberty_backToTop')) {
                $.data(this, 'liberty_backToTop', new backToTop(this, options));
            }
            else if ($.isFunction(backToTop.prototype[options])) {
                $.data(this, 'liberty_backToTop')[options]();
            }
        });
    };

    /**
     * =================================================================================================================
     */

    /**
     *******************************************************************************************************************
     * Липкая шапка
     *******************************************************************************************************************
     */

    // опции по умолчанию
    var defaults = {};
    // конструктор
    function stickyHeader(element, options) {
        this.element = element;
        this.options = $.extend({}, defaults, options);

        this.init();
    }
    // глобальные переменные

    // методы
    stickyHeader.prototype = {

        // публичные методы

        init : function () {
            var header            = $(this.element),
                headerHeight      = header.height(),
                topHeaderHeight   = $('.top-header').height();

            $(window).on('scroll resize', function() {
                var topOffset         = $(this).scrollTop();

                if (Modernizr.mq('only screen and (max-width: 767px)')) {
                    header.removeClass('sticky');
                    $('body').css('paddingTop', 0);
                    return;
                }

                //if ( topOffset > (headerHeight+topHeaderHeight) ) {
                if ( topOffset > 190 ) {

                    if (header.hasClass('sticky')) return;

                    header.addClass('sticky');
                    $('.logo img', header).addClass('transition');

                    $('body').css('paddingTop', headerHeight);

                    /*setTimeout(function() {
                        fortis_more_navs();
                    }, 100);*/

                } else {

                    if ( ! header.hasClass('sticky')) return;

                    header.removeClass('sticky');
                    $('.logo img', header).removeClass('transition');

                    $('body').css('paddingTop', 0);

                    /*fortis_more_navs();
                    setTimeout(function() {
                        fortis_more_navs();
                    }, 100);*/

                }

                fortis_more_navs();
                setTimeout(function() {
                    fortis_more_navs();
                }, 100);
            });
        }
    };

    $.fn['stickyHeader'] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'liberty_stickyHeader')) {
                $.data(this, 'liberty_stickyHeader', new stickyHeader(this, options));
            }
            else if ($.isFunction(stickyHeader.prototype[options])) {
                $.data(this, 'liberty_stickyHeader')[options]();
            }
        });
    };

    /**
     * =================================================================================================================
     */

})(jQuery);