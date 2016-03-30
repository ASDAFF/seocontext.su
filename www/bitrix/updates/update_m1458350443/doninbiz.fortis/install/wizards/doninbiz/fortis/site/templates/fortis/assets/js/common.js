$(function() {

    $('html').removeClass('no-js');

    if ($('.header a.logo img').length) {
        $('.header a.logo img').removeAttr('width').removeAttr('height');
    }

    var Core = {

        /**
         * Инициализация
         */
        init : function() {
            var self = this;

            // Кнопка плавного перехода снизу вверх
            if ($('.header-type-1').length) {
                $('header .navigation-outer-color').stickyHeader();
            } else if ($('.header-type-2').length) {
                $('header.header').stickyHeader();
            }

            // верхняя часть
            self.top_header();

            // Главная
            self.home();

            // Слайдер на главной
            self.home_slider();

            // Слайдер в контенте
            self.content_slider();

            // Каталог продукции
            self.catalog();

            // Наша команда
            self.our_team();

            // Портфолио
            self.portfolio();

            // Разное
            self.misc();

            // Модальные окна
            self.ajax_forms();

            // Ajax обновление каптчи
            self.captcha_update();

            // Контакты
            self.contacts();

            // Кнопка плавного перехода снизу вверх
            $('#back_to_top').backToTop({ breakpoint : 'header.header', offset : 100 });

            self.resize();

            setTimeout(function() {
                //$(window).resize();
            }, 350);
        },

        /**
         * События ресайза
         */
        resize : function() {
            var self = this;

            // моментальный ресайз
            $(window).resize(function() {
                self.top_header();
            });
        },

        top_header : function() {
            var right = $('.top-header .right'),
                right_width = 240;

            var li_count = $('li', $('.top-social-icons', right)).size(),
                li_width = $('li', $('.top-social-icons', right)).outerWidth(true);

            right_width = li_width * li_count;

            //if ( (window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth) <= 767) {
            if ( Modernizr.mq('only screen and (max-width: 767px)') ) {
                right.removeAttr('style');
            } else {
                right.css('width', right_width);
            }
        },

        /**
         * Главная страница
         */
        home : function() {

            // Преимущества 1
            if ($('.home-features-v1').length) {
                var homeFeatures = function() {
                    $('.home-features-v1 > .row').eqHeight(".item", {
                        break_point: 750
                    });
                };
                homeFeatures();
                /*setTimeout(function() {
                    homeFeatures();
                    $(window).resize();
                }, 100);*/
            }

            // Зебра
            if ($('.full-width-block').length) {
                $('.full-width-block').filter(":even").addClass('even');
                $('.full-width-block').filter(":odd").addClass('odd');
            }

            // Услуги с фотографиями на главной
            if ($('.home-services-photos .item.grid').length) {
                var homeServicesPhotos = function() {
                    $('.list-catalog.home-services-photos > .row').eqHeight(".item.grid .text", {
                        break_point: 750
                    });
                };
                homeServicesPhotos();
                /*setTimeout(function() {
                    homeServicesPhotos();
                    $(window).resize();
                }, 100);*/
            }

            // портфолио на главной
            if ($('.home-portfolio-categories').length && $('.home-portfolio-list').length) {

                var homePortfolioPhotos = function() {

                    $('.home-portfolio-list > .row .item-grid .right-col').css('height', 'auto');

                    if ( Modernizr.mq('only screen and (max-width: 767px)') ) {
                        return;
                    }

                    var maxHeight = 0;
                    $('.home-portfolio-list > .row .item-grid .right-col').each(function() {
                        var selfHeight = $(this).height();
                        maxHeight = maxHeight > selfHeight ? maxHeight : selfHeight;
                    });
                    if (maxHeight > 0)
                        $('.home-portfolio-list > .row .item-grid .right-col').css('height', maxHeight);
                };
                homePortfolioPhotos();
                /*setTimeout(function() {
                    homePortfolioPhotos();
                    $(window).resize();
                }, 100);*/

                var portfolio_list,
                    isotope_init = function() {
                        portfolio_list = $('.home-portfolio-list').isotope({
                            itemSelector: '.iso-item'
                        });

                        portfolio_list.isotope('on', 'arrangeComplete', function() {
                            homePortfolioPhotos();
                        });
                        portfolio_list.isotope('on', 'layoutComplete', function() {
                            homePortfolioPhotos();
                        });
                    };

                setTimeout(function() {
                    isotope_init();
                }, 1000);

                $('.home-portfolio-categories').on('click', 'a', function() {
                    var filterValue = $( this ).attr('data-filter');

                    $('li', $(this).closest('ul')).removeClass('active');
                    $(this).parent().addClass('active');

                    portfolio_list.isotope({ filter: filterValue });

                    return false;
                });
            }

            // Акции на главной
            $('.home-offers-slider').slick({
                dots: true,
                infinite: true,
                speed: 200,
                slidesToShow: 1,
                autoplay: true,
                autoplaySpeed: 7000,
                //adaptiveHeight: true,
                pauseOnDotsHover: true,
                prevArrow: '<div class="fa fa-chevron-left prev"></div>',
                nextArrow: '<div class="fa fa-chevron-right next"></div>',
                customPaging: function (a, b) {
                    return '<i class="arrow"></i>'
                }
            });


            // Лучшие предложения

            // Высота слайдов
            var eqHeightProductsSlides = function(slider) {
                var slide  = $('.item', slider),
                    outer_image  = $('.outer-image', slide),
                    name         = $('.name', slide),
                    price_status = $('.price-status', slide);

                outer_image.height('auto');
                name.height('auto');
                price_status.height('auto');

                if (slide.length <= 1)
                    return;

                var maxHeightOuterImage = 0, maxHeightName = 0, maxHeightPriceStatus = 0;
                slide.each(function() {
                    var heightOuterImage  = $('.outer-image', this).height(),
                        heightName        = $('.name', this).height(),
                        heightPriceStatus = $('.price-status', this).height();

                    maxHeightOuterImage = maxHeightOuterImage > heightOuterImage ? maxHeightOuterImage : heightOuterImage;
                    maxHeightName = maxHeightName > heightName ? maxHeightName : heightName;
                    maxHeightPriceStatus = maxHeightPriceStatus > heightPriceStatus ? maxHeightPriceStatus : heightPriceStatus;
                });
                outer_image.height(maxHeightOuterImage);
                name.height(maxHeightName);
                price_status.height(maxHeightPriceStatus);
            };
            // Слайдер
            $('.home-best-products-slider').owlCarousel({
                items : 4,
                itemsDesktop      : [1199, 4],
                itemsDesktopSmall : [991, 3],
                itemsTablet       : [767, 2],
                itemsMobile       : [479, 1],
                stopOnHover : true,
                pagination: false,
                responsiveRefreshRate : 100,
                navigation: true,
                navigationText : ['<div class="fa fa-angle-left prev"></div>', '<div class="fa fa-angle-right next"></div>'],
                autoPlay : 5000,
                afterInit : function(e) {
                    if ($(e).closest('.tab-pane').hasClass('active')) {
                        eqHeightProductsSlides(e);
                    }
                },
                afterUpdate : function(e) {
                    if ($(e).closest('.tab-pane').hasClass('active')) {
                        eqHeightProductsSlides(e);
                    }
                }
            });
            // Вкладки
            $('.home-best-products-tabs .nav-tabs a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                var slider = $('#' + $(this).data('to')).data('owlCarousel');
                slider.goTo(0);
            });


            // Слайдер новостей на главной
            var hNewsText = $('.home-news-slider .item > .text');
            hNewsText.collapser(hNewsText.data());

            $('.home-news-slider').owlCarousel({
                stopOnHover : true,
                singleItem : true,
                autoPlay : 7000,
                navigation: true,
                navigationText : ['<div class="fa fa-angle-left prev"></div>', '<div class="fa fa-angle-right next"></div>']
            });

            // Слайдер отзывов на главной
            var hReviewsText = $('.home-reviews-slider .review > .text');
            hReviewsText.collapser($.extend({
                speed : 'fast',
                controlBtn : 'control-class'
            }, hReviewsText.data()));

            $('.home-reviews-slider').owlCarousel({
                stopOnHover : true,
                singleItem : true,
                autoPlay : 9000,
                navigation: true,
                navigationText : ['<div class="fa fa-angle-left prev"></div>', '<div class="fa fa-angle-right next"></div>']
            });

            // Партнеры на главной
            // Высота слайдов
            var eqHeightPartnersSlides = function(slider) {
                var slide  = $('li', slider),
                    outer_image  = $('a', slide);

                outer_image.height('auto');

                if (slide.length <= 1)
                    return;

                var maxHeightOuterImage = 0;
                slide.each(function() {
                    var heightOuterImage  = $('a', this).height();

                    maxHeightOuterImage = maxHeightOuterImage > heightOuterImage ? maxHeightOuterImage : heightOuterImage;
                });
                outer_image.height(maxHeightOuterImage);
            };
            // Слайды
            $('.home-partners-slider').owlCarousel({
                autoPlay : 10000,
                afterInit : function(e) {
                    eqHeightPartnersSlides(e);
                },
                afterUpdate : function(e) {
                    eqHeightPartnersSlides(e);
                }
            });
        },

        /**
         * Слайдер на главной
         */
        home_slider : function() {
            $('.home-slider-init').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                autoplay: true,
                autoplaySpeed: 7000,
                adaptiveHeight: true,
                pauseOnDotsHover: true,
                prevArrow: '<div class="fa fa-chevron-left prev"></div>',
                nextArrow: '<div class="fa fa-chevron-right next"></div>',
                customPaging: function (a, b) {
                    return '<i class="arrow"></i>'
                }
            });

            $('.home-slider').eqHeight(".slide");
        },

        /**
         * Разное
         */
        misc : function() {
            var self = this;
            // tooltips
            $('.ss a, .ssm a, .has-tooltip').tooltip();

            // multiselect
            $('.bootstrap-select').multiselect({
                disableIfEmpty: true
            });

            //
            self.aimg_hover();
            setTimeout(function() {
                self.aimg_hover();
            }, 500);

            $('.magnific-image').magnificPopup({
                type: 'image'
            });
            $('.magnific-images-gallery').magnificPopup({
                delegate: '.thumbnail',
                type: 'image',
                gallery: magnific_gallery
            });

            $('.aimg-hover:not(.magnific-gallery)').magnificPopup({
                delegate: '.aimg-fullscreen',
                type: 'image'
            });

            $('.aimg-hover.magnific-gallery').magnificPopup({
                delegate: '.aimg-fullscreen',
                type: 'image',
                gallery: magnific_gallery
            });

            $('.partner-detail-toggle').click(function() {
                var full_text = $(this).closest('.full-text'),
                    text      = $('.text', full_text);
                if (text.is(':visible')) {
                    full_text.removeClass('opened');
                    text.slideUp('slow');
                } else {
                    full_text.addClass('opened');
                    text.slideDown('slow');
                    self.aimg_hover();
                }

                return false;
            });

            // Блок заказа услуги и портфолио
            $('blockquote:not(.not-quote-block)').each(function() {
                if ( ! $(this).find('.quote-block').length) {
                    $(this).append('<span class="quote-block"><i class="fa fa-quote-right"></i></span>');
                }
            });

            // Прайс-лист
            $('.footable').footable();

            // Адаптация под всю ширину экрана со скрытием, дабы глаз не увидел этой адаптации)))
            if ($('#wrapper').hasClass('rubber')) {
                $('#wrapper').removeClass('hidden');
                $('.wrapper-container.container').removeClass('container').addClass('container-fluid');
            }

            // Номера телефонов для мобильных устройств
            if ($('.tel-mobile').length) {
                $('.tel-mobile').each(function() {
                    var number = $(this).text(),
                        classes = $(this).attr('class').replace('tel-mobile', '');
                    $(this).replaceWith('<a href="tel:'+number+'"'+(classes ? ' class="'+classes+'"' : '')+'>'+number+'</a>');
                });
            }
        },

        aimg_hover : function () {
            $('.aimg-hover').each(function() {
                var self        = $(this),
                    overlay     = $('.aimg-overlay', this),
                    row         = $('.aimg-row', this),
                    item        = $('a:eq(0)', row),
                    count_items = $('a', row).size(),
                    o_height    = parseInt(overlay.height()) < 100 ? 100 : parseInt(overlay.height()),
                    o_width     = parseInt(overlay.width()) < 100 ? 100 : parseInt(overlay.width()),
                    a_size      = parseInt(Math.min.apply(null, [o_height, o_width]) / 5);

                if (count_items) {
                    if (count_items == 1) {
                        $('.aimg-link, .aimg-fullscreen', row).css({
                            padding: a_size + 'px ' + (a_size+parseInt(a_size*0.2)) + 'px', fontSize: a_size + 'px'
                        });
                    } else if(count_items == 2) {
                        a_size = parseInt(a_size / 1.5);
                        $('.aimg-link, .aimg-fullscreen', row).css({
                            padding: a_size + 'px ' + (a_size+parseInt(a_size*0.2)) + 'px', fontSize: a_size + 'px'
                        });
                    }

                    row.show();
                    var item_height = parseInt(item.css('height')),
                        item_width  = parseInt(item.css('width'));
                    row.hide();

                    row.css({
                        left : '50%', top: '100%',
                        marginLeft : -item_width*count_items/2,
                        marginTop : -item_height/2
                    });

                    self.hover(function() {
                        row.show().stop().animate({ top: '50%', opacity: 1 }, "fast");
                    }, function() {
                        row.stop().animate({ top: '100%', opacity: 0 }, "fast", function() {
                            $(this).hide();
                        });
                    });
                }
            });
        },

        content_slider : function() {
            var sync1 = $(".slider-medium-images");
            var sync2 = $(".slider-small-images");

            if (sync1.length && sync2.length) {

                if ($.browser.msie && $.browser.version == 8.0) {

                    /*sync1.owlCarousel({
                        singleItem : true,
                        slideSpeed : 1000,
                        pagination : false
                    });*/

                    return;

                } else {
                    sync1.owlCarousel({
                        singleItem : true,
                        slideSpeed : 100,
                        pagination : false,
                        afterAction : function(el){
                            var current = this.currentItem;
                            $(".slider-small-images")
                                .find(".owl-item")
                                .removeClass("synced")
                                .eq(current)
                                .addClass("synced");
                            if($(".slider-small-images").data("owlCarousel") !== undefined){
                                center(current);
                            }
                        },
                        responsiveRefreshRate : 200,
                        transitionStyle : "fade"
                    });
                }

                sync2.owlCarousel({
                    items : 3,
                    itemsDesktop      : [1199, 3],
                    itemsDesktopSmall : [991, 5],
                    itemsTablet       : [768, 5],
                    itemsMobile       : [480, 3],
                    pagination: false,
                    responsiveRefreshRate : 100,
                    navigation: true,
                    navigationText : ['<div class="fa fa-angle-left prev"></div>', '<div class="fa fa-angle-right next"></div>'],
                    afterInit : function(el){
                        el.find(".owl-item").eq(0).addClass("synced");
                    }
                });

                $(".slider-small-images").on("click", ".owl-item", function(e){
                    e.preventDefault();
                    var number = $(this).data("owlItem");
                    sync1.trigger("owl.goTo",number);
                });

                var center = function(number){
                    var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
                    var num = number;
                    var found = false;
                    for(var i in sync2visible){
                        if(num === sync2visible[i]){
                            var found = true;
                        }
                    }

                    if(found===false){
                        if(num>sync2visible[sync2visible.length-1]){
                            sync2.trigger("owl.goTo", num - sync2visible.length+2)
                        }else{
                            if(num - 1 === -1){
                                num = 0;
                            }
                            sync2.trigger("owl.goTo", num);
                        }
                    } else if(num === sync2visible[sync2visible.length-1]){
                        sync2.trigger("owl.goTo", sync2visible[1])
                    } else if(num === sync2visible[0]){
                        sync2.trigger("owl.goTo", num-1)
                    }
                };

                $('.slider-medium-images').eqHeight(".detail_picture .outer");
                $('.slider-small-images').eqHeight(".outer");
            }
        },

        catalog : function() {
            // Комментарии
            $.each(['md-lg', 'xs-sm'], function(k, v) {
                if ($('#catalog-comments-tab'+v).size()) {
                    $('#catalog-comments-tab'+v+' a:first').tab('show');
                    $('#catalog-comments-tab'+v+' a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        var id = $(this).attr('href');
                        if (id == '#fb_comments_tab' + v) {
                            $('.fb-comments > span, .fb-comments > span > iframe', id).css('width', '100%');
                        }
                    });
                }
            });
            $(window).resize(function() {
                $('.fb-comments > span, .fb-comments > span > iframe').css('width', '100%');
            });

            // Выравнивание высоты в сетке
            if ($('.list-catalog .item.grid').length) {
                var catalogListEqHeight = function() {
                    $('.list-catalog > .row').eqHeight(".item.grid .outer-image", {
                        break_point: 750
                    });
                    $('.list-catalog > .row').eqHeight(".item.grid .name", {
                        break_point: 750
                    });
                    $('.list-catalog > .row').eqHeight(".item.grid .price-status", {
                        break_point: 750
                    });
                };
                catalogListEqHeight();
                setTimeout(function() {
                    catalogListEqHeight();
                    $(window).resize();
                }, 100);
            }

            if ($("#price-range-slider").length) {
                var params = {};

                if ($('#wrapper.boxed').length) {
                    params.force_edges = true;
                }

                $("#price-range-slider").ionRangeSlider($.extend({
                    type: "double",
                    grid: true,
                    onChange: function (data) {
                        var outer_inputs = $('.filter-price-range'),
                            from = $('.min-price', outer_inputs),
                            to   = $('.max-price', outer_inputs);

                        if (data.from == data.min) {
                            from.val(null);
                        } else {
                            from.val(data.from);
                        }

                        if (data.to == data.max) {
                            to.val(null);
                        } else {
                            to.val(data.to);
                        }
                        from.keyup();
                        to.keyup();
                    }
                },
                    params));
            }
        },

        our_team: function() {
            // Выравнивание высоты в сетке
            if ($('.our-team-list .items').length) {
                var listEqHeight = function() {
                    $('.our-team-list .items > .row').eqHeight(".outer-image", {
                        break_point: 750
                    });
                    $('.our-team-list .items > .row').eqHeight(".text", {
                        break_point: 750
                    });
                };
                listEqHeight();
                setTimeout(function() {
                    listEqHeight();
                    $(window).resize();
                }, 100);
            }
        },

        portfolio: function() {
            // Выравнивание высоты в сетке
            if ($('.portfolio-list:not(.home-portfolio-list) .grid.items').length) {
                var listEqHeight = function() {
                    $('.portfolio-list .grid.items').eqHeight(".right-col", {
                        break_point: 750
                    });
                };
                listEqHeight();
                setTimeout(function() {
                    listEqHeight();
                    $(window).resize();
                }, 100);
            }
        },

        ajax_forms: function() {
            var modals = [
                {
                    clickClass:  '.get-buy-form',
                    modalFormId: '#orderProductModal',
                    outerFormId: '#orderProduct',
                    actionUrl:   site_dir + 'order/product.php'
                },
                {
                    clickClass:  '.get-service-form',
                    modalFormId: '#orderServiceModal',
                    outerFormId: '#orderService',
                    actionUrl:   site_dir + 'order/service.php'
                },
                {
                    clickClass:  '.get-portfolio-form',
                    modalFormId: '#orderPortfolioModal',
                    outerFormId: '#orderPortfolio',
                    actionUrl:   site_dir + 'order/portfolio.php'
                },
                {
                    clickClass:  '.get-question-form',
                    modalFormId: '#orderQuestionModal',
                    outerFormId: '#orderQuestion',
                    actionUrl:   site_dir + 'order/question.php'
                },
                {
                    clickClass:  '.get-call-form',
                    modalFormId: '#orderCallModal',
                    outerFormId: '#orderCall',
                    actionUrl:   site_dir + 'order/call.php'
                }
            ];
            $.each(modals, function(k, v) {

                clear_errors($(v.outerFormId));

                $(v.clickClass).click(function() {
                    var modal = $(v.modalFormId),
                        outer = $(v.outerFormId),
                        data  = $(this).data();
                    modal.modal('show');

                    var product = $('input[name=PRODUCT]', outer);
                    if (data.name) {
                        product.closest('.form-group').removeClass('hidden');
                        product.val(data.name);
                        product.prop('disabled', true);
                        if ( ! $('input[name=product_disabled]', $('form', outer)).length) {
                            $('form', outer).append($('<input />', { type: 'hidden', 'name': 'product_disabled', value: 1 }));
                        }
                    } else {
                        //product.closest('.form-group').addClass('hidden');
                        //product.val(null);
                        product.prop('disabled', false);
                        if ($('input[name=product_disabled]', $('form', outer)).length) {
                            $('input[name=product_disabled]', $('form', outer)).remove();
                        }
                    }

                    return false;
                });

                $(v.modalFormId).on('shown.bs.modal', function () {
                    input_focus(v.modalFormId);
                    input_mask($(v.outerFormId));
                });

                $('body').delegate(v.modalFormId + ' form', 'submit', function() {
                    var url = $(this).attr('action'),
                        data;

                    var disabled = $(':input:disabled', this).removeAttr('disabled');
                    data = $(this).serializeArray();
                    disabled.attr('disabled','disabled');

                    $('.btn-submit', this).button('loading');
                    $.post(url, data, function(html) {
                        $('.modal-body', v.modalFormId).html(html);
                        input_mask($('.modal-body', v.modalFormId));
                        clear_errors($('.modal-body', v.modalFormId));
                        input_focus(v.modalFormId);
                    });
                    return false;
                });

            });

            $('.fortis-form').each(function() {
                clear_errors(this);
                //input_focus(this);
            });

            function input_mask(self) {

                if ($.browser.mobile) {
                    return;
                }

                $("input[name=MOBILE]", self).inputmask('phone',
                    {
                        oncleared: function () {
                            $(this).closest('.form-group').find('.phone').text('');
                            $(this).closest('.form-group').find('input[name=MOBILE_COUNTRY]').val(null);
                        },
                        onKeyValidation: function (result, opts) {
                            $(this).trigger('post_update');
                        }
                    }
                ).on('post_update', function() {
                        var metadata = $(this).inputmask("getmetadata"),
                            country_name = '';
                        if (metadata) {
                            country_name = metadata["name_ru"];
                        }
                        //$(this).closest('.form-group').find('.phone').text(country_name);
                        $(this).closest('.form-group').find('input[name=MOBILE_COUNTRY]').val(country_name);
                }).trigger('post_update');

                $("input[name=EMAIL]", self).inputmask('email');

                $("input[name=MOBILE]", self).on('focusin', function() {
                    var self     = $(this),
                        firstVal = self.val();
                    setTimeout(function() {
                        if ( ! firstVal.length) {
                            self.val('7');
                            self[0].setSelectionRange(2, 2);
                        }
                    }, 100);
                 }).on('focusout', function() {
                    var firstVal = $.trim($(this).val().replace(/[()_+-]/g,''));
                    if (firstVal == 7) {
                        $(this).val(null);
                    }
                 });
            }

            function clear_errors(self) {
                $('input, textarea', self).each(function() {
                    var group = $(this).closest('.form-group'),
                        input = $(this);
                    input.on('input', function() {
                        if (group.hasClass('has-error')) {
                            group.removeClass('has-error');
                            $('.help-block', group).remove();
                        }
                    });
                });
            }

            function input_focus(id) {

                if ($.browser.mobile) {
                    return;
                }

                $('input[type="text"], textarea', $(id)).each(function() {
                    if (this.value === '') {
                        this.focus();
                        return false;
                    }
                });
            }
        },

        captcha_update : function() {
            $('body').delegate('.update-captcha-img', 'click', function() {
                var outer = $(this).closest('.captcha-img');
                $.getJSON(site_dir + 'ajax/reload_captcha.php', function(obj) {
                    $('img', outer).attr('src',obj.src);
                    $('input[name=captcha_sid]').val(obj.sid);
                });
                return false;
            })
        },

        contacts : function() {
            if (window.google && window.google.maps) {
                var map = window.GLOBAL_arMapObjects[document.querySelector('.bx-google-map').id.replace('BX_GMAP_', '')];

                var center = map.getCenter();
                window.google.maps.event.addDomListener(window, 'resize', function() {
                    google.maps.event.trigger(map, "resize");
                    map.setCenter(center);
                    if (document.documentElement.clientWidth <= 767) {
                        if (map.draggable) map.set('draggable', false);
                    } else {
                        if (!map.draggable) map.set('draggable', true);
                    }
                });
            }
        }

    };

    Core.init();

});