;(function ($, undefined) {

    $.loadScript = function( url, options ) {
        options = $.extend( options || {}, {
            dataType: "script",
            cache: true,
            url: url
        });
        return $.ajax(options);
    };

    /**
     *******************************************************************************************************************
     * Настройки стиля
     *******************************************************************************************************************
     */

    var pluginName  = 'themeSwitcher',
        assets_path = template_path + '/assets/';

    function Plugin(element, options) {
        var el = element,
            $el = $(element),
            imagesPath       = assets_path + 'img/bg/images/',
            texturesPath      = assets_path + 'img/bg/textures/',
            lessJs           = assets_path + "js/vendor/less.min.js",
            cookieJs         = assets_path + "js/vendor/jquery.cookie.min.js",
            styleLess        = assets_path + "css/common.less",
            themeSwitcherCss = assets_path + "css/theme-switcher.css",
            colorpickerCss   = assets_path + "css/vendor/bootstrap-colorpicker.min.css",
            colorpickerJs    = assets_path + "js/vendor/bootstrap-colorpicker.min.js";

        options = $.extend({}, $.fn[pluginName].defaults, options);

        function init() {
            $(document).ready(function() {
                $(el).removeClass('hidden');
                // Переключатель
                initToggleSwitcher();
            });
            // Основной функционал
            initGeneralFunctions();
            // Обработка шаблона по умолчанию
            processing();

            // Свое событие при инициализации
            hook('onInit');
        }

        /**
         * Обработка данных
         */

        function processing() {

            $('.change-set-default').on('click', function() {

                //ajaxLoader.show();

                // Широкий макет
                $('.item.change-layout label:first', el).click();

                // Шапка светлая
                $('.item.change-header-color label:first', el).click();

                // Тип шапки
                $('.item.change-header-type label:first', el).click();

                // Зебра
                $('.item.change-home-zebra label:first', el).click();

                // Первый в списке цветов
                $('.item.change-color .def-colors label:first', el).click();
                // Очищаем кастомный
                $('.item.change-color .custom-color input', el).css({ background : '#fff' }).val(null);

                // Сайдбар слева
                $('.item.change-sidebar-align label:first', el).click();

                // Убираем фон и текстуру из формы
                $('.change-textures input:checked, .change-images input:checked').removeAttr('checked').closest('label').removeClass('active');
                // Убираем фон и текстуру со страницы
                $('body').removeAttr('style');
                if ($('body').data('backstretch')) {
                    $.backstretch("destroy");
                }

                return false;
            });

            $('form.theme-switcher-form', el).on('submit', function(e) {
                var data = $(this).serializeArray();
                ajaxLoader.show();

                data.push({ name: "switcher[type]", value: 'save' });

                var css_code = $('style[id^="less"]').html();
                if (css_code) {
                    data.push({ name: "switcher[css]", value: Base64.encode(css_code) });
                }

                $.post(template_path + '/style-switcher.php', data, function() {
                    ajaxLoader.hide();
                }).error(function() {
                    alert('There was an error, refresh the page and try again! If the problem persists, please email support@donin.biz');
                    ajaxLoader.hide();
                });
                return false;
            });

            $('form.theme-switcher-form input[type=radio]', el).on('change', function() {
                formSave();
            });
        }

        function formSave() {
            ajaxLoader.show();
            setTimeout(function() {
                $('form.theme-switcher-form', el).submit();
            }, 500);
        }

        /**
         * Инициализация основного функционала
         */

        function initGeneralFunctions() {
            // узкий, широкий
            layoutType.init();
            // светлый, темный
            headerColor.init();
            // тип шапки
            headerType.init();
            // зебра
            homeZebra.init();
            // сайдбар
            sidebarAlign.init();
            // изменение цвета
            primaryColor.init();
            // установка фона
            imageBackground.init();

            // подключение lessJs, colorpicker
            // стили
            $("<link/>", { rel: "stylesheet", type: "text/css", href: themeSwitcherCss }).appendTo("head");
            $("<link/>", { rel: "stylesheet", type: "text/css", href: colorpickerCss }).appendTo("head");
        }


        function initLess() {
            // less файл не загружен
            if ( ! $('body').hasClass('less-loaded')) {
                // стиль
                $("<link/>", { rel: "stylesheet/less", type: "text/css", href: styleLess }).appendTo("head");
                if (options.debug) {
                    less = {
                        env : 'development'
                    }
                }
                // скрипт
                $('<script/>', { type : 'text/javascript', src : lessJs }).appendTo('head');
                $('body').addClass('less-loaded');
            }
        }



        /**
         * Фон шаблона
         */
        var imageBackground = {
            init: function() {
                $('.item.change-images label > input, .item.change-textures label > input', el).each(function() {
                    var s_image = ($(this).closest('.item').hasClass('change-images') ? imagesPath : texturesPath) + 's_' + $(this).val(),
                        label = $(this).parent();
                    label.css('backgroundImage', 'url('+s_image+')');
                });

                $('.item.change-images input, .item.change-textures input', el).change(function() {
                    var self = $(this),
                        label = $(this).parent(),
                        item  = $(this).closest('.item');

                    if (item.hasClass('change-images')) {
                        $('.item.change-textures input[type=radio]', el).removeAttr('checked');
                        $('.item.change-textures label', el).removeClass('active');
                    } else {
                        $('.item.change-images input[type=radio]', el).removeAttr('checked');
                        $('.item.change-images label', el).removeClass('active');
                    }
                });

                this.setImages();
                this.setTextures();
            },
            setImages: function() {
                $('.item.change-images input', el).change(function() {
                    var image   = imagesPath + $(this).val(),
                        label = $(this).parent();

                    if ($('body').css('backgroundImage') != 'none') {

                    }

                    $.backstretch(image);

                    layoutType.setBoxed();
                });
            },
            setTextures: function() {
                $('.item.change-textures input', el).change(function() {
                    var image   = texturesPath + $(this).val(),
                        label = $(this).parent();

                    if ($('body').data('backstretch')) {
                        $.backstretch("destroy");
                    }

                    $('body').css('backgroundImage', 'url('+image+')');

                    layoutType.setBoxed();
                });
            }
        };



        /**
         * Основной цвет шаблона
         */

        var primaryColor = {
            init: function() {
                // установка фона для предустановленных цветов
                $('.change-color .btn-group.def-colors label > input', el).each(function() {
                    var color = $(this).val();
                    $(this).parent().css('backgroundColor', color);
                });

                // клик по предустановленному цвету
                $('.change-color .btn-group.def-colors input', el).change(function() {
                    var color = $('.change-color .btn-group.def-colors input:checked', el).val(),
                        label = $(this).parent();

                    initLess();
                    setTimeout(function() {
                        less.modifyVars({ '@primaryColor' : color });
                    }, 100);

                    $('.item.change-color .custom-color input', el).css({ background : '#fff' }).val(null);
                });

                $('.item.change-color .change-custom-color', el).click(function() {
                    var color = $('.item.change-color .field-custom-color', el).val();

                    if ( ! color.match(/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i)) {
                        return alert('Wrong Hex code');
                    }

                    $('.item.change-color .field-custom-color', el).css({ background : color });

                    initLess();
                    setTimeout(function() {
                        less.modifyVars({ '@primaryColor' : color });
                    }, 100);

                    formSave();

                    return false;
                });

                // инициализируем colorpicker
                $.loadScript(colorpickerJs).done(function(script) {
                    $('.item.change-color .field-custom-color', el).colorpicker().on('changeColor', function(ev){
                        var color = ev.color.toHex(),
                            field = $(this);

                        $('.item.change-color .def-colors label', el).removeClass('active');

                        field.css('backgroundColor', color);
                        field.val(color);
                    });
                    $('.item.change-color .field-custom-color', el)
                });
            }
        };



        /**
         * Светлый или темный
         */

        var headerColor = {
            init : function() {
                $('.item.change-header-color input', el).change(function() {
                    var layout = $(this).val();
                    switch(layout) {
                        case 'light':
                            headerColor.setLight();
                            break;
                        case 'dark':
                            headerColor.setDark();
                            break;
                        case 'colored':
                            headerColor.setColored();
                            break;
                    }
                });
            },
            setLight : function() {
                $('#wrapper, body').removeClass('dark').removeClass('colored').addClass('light');
            },
            setDark : function() {
                $('#wrapper, body').removeClass('light').removeClass('colored').addClass('dark');
            },
            setColored : function() {
                $('#wrapper, body').removeClass('light').removeClass('dark').addClass('colored');
            }
        };



        /**
         * Тип шапки
         */

        var headerType = {
            init : function() {
                $('.item.change-header-type input', el).change(function() {
                    $(document).ajaxComplete(function() {
                        ajaxLoader.show();
                        location.reload();
                    });
                });
            }
        };



        /**
         * узкий, широкий, по ширине экрана
         */

        var homeZebra = {
            init : function() {
                $('.item.change-home-zebra input', el).change(function() {
                    var use = $(this).val();

                    switch(use) {
                        case '1':
                            homeZebra.setUse();
                            break;
                        case '0':
                            homeZebra.setNotUse();
                            break;
                    }
                });
            },
            setUse : function() {
                $('#wrapper').addClass('use-zebra');
                if ($('.item.change-home-zebra input:checked', el).val() != '1') {
                    $('.item.change-home-zebra input[value=1]', el).click();
                }
            },
            setNotUse : function() {
                $('#wrapper').removeClass('use-zebra');
                if ($('.item.change-home-zebra input:checked', el).val() != '0') {
                    $('.item.change-home-zebra input[value=0]', el).click();
                }
            }
        };



        /**
         * узкий, широкий, по ширине экрана
         */

        var layoutType = {
            init : function() {
                $('.item.change-layout input', el).change(function() {
                    var layout = $(this).val();
                    switch(layout) {
                        case 'wide':
                            layoutType.setWide();
                            break;
                        case 'boxed':
                            layoutType.setBoxed();
                            break;
                        case 'rubber':
                            layoutType.setRubber();
                            break;
                    }
                });
            },
            setBoxed : function() {
                $('#wrapper').removeClass('wide rubber').addClass('boxed');
                if ($('.item.change-layout input:checked', el).val() != 'boxed') {
                    $('.item.change-layout input[value=boxed]', el).click();
                }
                this.setContainer('container');
                $(window).resize();
            },
            setWide : function() {
                $('#wrapper').removeClass('boxed rubber').addClass('wide');
                if ($('.item.change-layout input:checked', el).val() != 'wide') {
                    $('.item.change-layout input[value=wide]', el).click();
                }
                this.setContainer('container');

                // Убираем фон
                $('.change-textures input:checked, .change-images input:checked').removeAttr('checked').closest('label').removeClass('active');
                $('body').removeAttr('style');
                if ($('body').data('backstretch')) {
                    $.backstretch("destroy");
                }

                $(window).resize();
            },
            setRubber : function() {
                $('#wrapper').removeClass('wide boxed').addClass('rubber');
                this.setContainer('container-fluid');

                // Убираем фон
                $('.change-textures input:checked, .change-images input:checked').removeAttr('checked').closest('label').removeClass('active');
                $('body').removeAttr('style');
                if ($('body').data('backstretch')) {
                    $.backstretch("destroy");
                }

                $(window).resize();
            },
            setContainer : function(add_class) {
                if (typeof add_class == 'undefined') {
                    add_class = 'container';
                }
                var remove_class = add_class == 'container' ? 'container-fluid' : 'container';

                $('#wrapper').find('.wrapper-container').removeClass(remove_class).addClass(add_class);
                $(window).resize();
            }
        };


        /**
         * Положение сайдбара
         */
        var sidebarAlign = {
            init : function() {
                $('.item.change-sidebar-align input', el).change(function() {
                    var align = $(this).val();

                    if ( ! $('.col-sidebar').length) {
                        return;
                    }

                    switch(align) {
                        case 'left':
                            sidebarAlign.setLeft();
                            break;
                        case 'right':
                            sidebarAlign.setRight();
                            break;
                    }
                });
            },
            setLeft : function() {
                $('.col-sidebar').attr('class', 'col-sidebar col-sidebar-left col-md-3 hidden-sm hidden-xs').addClass('.col-sidebar');
                $('.col-content').attr('class', 'col-content col-content-left col-md-9 col-sm-12 col-xs-12').addClass('.col-content');
            },
            setRight : function() {
                $('.col-sidebar').attr('class', 'col-sidebar col-sidebar-right col-md-3 col-md-push-9 hidden-sm hidden-xs').addClass('.col-sidebar');
                $('.col-content').attr('class', 'col-content col-content-right col-md-9 col-md-pull-3 col-sm-12 col-xs-12').addClass('.col-content');
            }
        };


        /**
         * Клик по значку шестеренки
         */

        function initToggleSwitcher() {

            $.loadScript(cookieJs).done(function() {
                if ($.cookie(pluginName)) {
                    openSwitcher(true);
                }
            });

            $('#switcher-toggle')
                .click(function() {
                    $el.hasClass('opened') ? closeSwitcher() : openSwitcher();
                    setPositionSwitcher();
                    return false;
                });
            // закрытие по клику вне области
            if (options.closeDoc) {
                $(document).mouseup(function (e) {
                    var container = $el;
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        closeSwitcher();
                    }
                });
            }
            // закрытие по кнопке Esc
            if (options.closeEsc) {
                $(document).keyup(function(e) {
                    if ($el.hasClass('opened') && e.keyCode == 27) {
                        closeSwitcher();
                    }
                });
            }

            setPositionSwitcher();

            $(window).afterresize(function() {
                if ( Modernizr.mq('only screen and (max-width: 480px)') ) {
                    $el.hide();
                } else {
                    $el.show();
                    setPositionSwitcher();

                    if ( ! $el.hasClass('opened') && $el.width() > 0) {
                        $el.css('left', -$el.width());
                    }
                }
            });
        }

        function setPositionSwitcher() {
            /*if ($el.hasClass('opened')) {
                $el.css({
                    position: 'absolute',
                    top: window.pageYOffset+($(window).height() < 200 ? 50 : 150)
                });
            } else {
                $el.css({
                    position: 'fixed',
                    top: ($(window).height() < 200 ? 50 : 150)
                });
            }
            if (window.innerWidth < 480) {
                $el.hide();
            }*/

            if ($el.hasClass('opened')) {
                if ( ! $el.data('orheight')) {
                    $el.data('orheight', $el.height());
                }

                if ( $el.data('orheight') > 0 && $(window).height() < $el.data('orheight') ) {

                    $('form', $el).height($(window).height() - $('> h4', $el).height() - 20);
                    $el.css('top', 10);

                } else {
                    $('form', $el).height('auto');
                    $el.css('top', ($(window).height() - $el.height()) / 2 );
                }
            } else {
                $('form', $el).height('auto');
                $el.css('top', 150);
            }

            if (window.innerWidth < 480) {
                $el.hide();
            }
        }


        /**
         * Открытие редактора темы
         */

        function openSwitcher(load_show) {
            load_show = typeof load_show != 'undefined' ? true : false;

            if (load_show) {
                setTimeout(function() {
                    $el.css('left', 0).addClass('opened');
                    setPositionSwitcher();
                }, 1000);
            } else {
                $el.css('left', 0).addClass('opened');
                setPositionSwitcher();
            }

            $.cookie(pluginName, 1, { path: '/' });
        }

        /**
         * Закрытие редактора темы
         */

        function closeSwitcher() {
            $el.css('left', '-' + $el.width() + 'px').removeClass('opened');
            setPositionSwitcher();

            $.removeCookie(pluginName, { path: '/' });
        }

        /**
         * Ajax индикатор
         * @type {{init: init, show: show, hide: hide, ajax: ajax}}
         */

        var ajaxLoader = {
            init : function() {
                var loader = '<div class="fortis-ajax-loader" style="position: absolute; left: 0; top: 0; background-color: rgba(47, 56, 61, 0.8); z-index: 99999; width: 100%; height: 100%; display: none;">' +
                    '<div style="background-position: center 40%;background-repeat: no-repeat;height:100%;width:100%;background-color: transparent; background-image: url(data:image/gif;base64,R0lGODlhKAApAKUAAAQCBISChERCRMTCxCQiJGRiZOTi5KSipBQSFFRSVNTS1DQyNHRydPTy9LSytJSSlAwKDIyKjExKTMzKzCwqLGxqbOzq7KyqrBwaHFxaXNza3Dw6PHx6fPz6/Ly6vJyanAQGBISGhERGRMTGxCQmJGRmZOTm5KSmpBQWFFRWVNTW1DQ2NHR2dPT29LS2tAwODIyOjExOTMzOzCwuLGxubOzu7KyurBweHFxeXNze3Dw+PHx+fPz+/Ly+vJyenAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQJAwA/ACwAAAAAKAApAAAG/sCfcEgsGo/IpHJZfK1KFRFk+UK9mEXA6mPgeXkyEvIFMzQMjhIKu7B1vnAXwAixeWgZmqthiqyRCBENcIQdCEY0DCBDACQPNQYFc0UiKoSXPARFCCWTRSRuF38/ICFvmIQYRSCeRiAZJjkbPy82cCYfDA8thBZTWEQEIx07Cl8TCZ4ZhB6twD8QDl8WBYtECLxfDM9FOjVeFppGL4NeHTfcQwQmPB0OHT7WRDNwDs5YANItOAAhPBHyhFSAIyCdkBsNFKwQAuFCi22MpHnpEfAZCAG/hKCQ0ULCEAzZOswymISCBQPofvjzcuIeSSIpOlC8kS3cSyUADvDgoNNL+IWbS27UaHFqQEWgRnZ8abAA6RIY2pwqiemlRkapRVZYgEMDqxEMlniYGMDDwCGvQl6Q5VFjAw6faKHZ4tFAxw8KXhS4fFnLSwMRQgDwEvkSwo4DOK6g8OA3BZGtPGBguVGRwRcVJTSAi1GknIajRhJcISKRUI6mREh86TCDSYDRQ0ZgepAysA+uTFzAFnICVQMPLDKUcHGKJROFQ0AkkIGquZcRTC7IECGAQ1jnzRUwEVHc7wHI2OFAZ5JBhYUcaVQJ+Bb+ywFgAF44W2GsPY8SSBE86I7KgiqnG6yFSgc/YQVCCvUR0gANe90EgAAPyNBAQhGIY0QQACH5BAkDAD8ALAAAAAAoACkAhQQCBISChERCRMTCxCQiJGRiZOTi5KSipBQSFFRSVNTS1DQyNHRydPTy9LSytJSWlAwKDIyKjExKTMzKzCwqLGxqbOzq7KyqrBwaHFxaXNza3Dw6PHx6fPz6/Ly6vJyenAQGBISGhERGRMTGxCQmJGRmZOTm5KSmpBQWFFRWVNTW1DQ2NHR2dPT29LS2tJyanAwODIyOjExOTMzOzCwuLGxubOzu7KyurBweHFxeXNze3Dw+PHx+fPz+/Ly+vAAAAAb+wJ9wSCwaj8ikclkEISgoEFMonRIxjEmr13HglLiAb+aqYKa4l63HbnuqRlJiRSDJXprIFwnjWNqAbAtWPxgxOiUARiszgY45hEKMNyhDICwNjo4lkUIIBxorPwgOHW0jHDkhmYASnUIALC0cKm0ONEQVgB17rz8hbQYJikQogDNwnSJ/PQYERzCmbBG+PxtrLQctEclCBG0doq80Bj0WAj8ls8RDGW0T3VMUGs24PwABLRlFJ20FrxhqWRhkyYMFe6PW9NAAoxOICz1aiDCCQ4eChj8YtOGkrEcDGUgSdHgAAAazCRBeSXBA8AiADx1kRGDTYUM1JigMmNjSg9r+TSYl2ihI+XMJBzYNEBZFsoPVBHZWVpy4MWwJCnptJhKiwarDDQRJQLhgQ6aHg0gsAql4ZgTECzYqMOhqQYKQxkAazhABEYONsx8U2IQgtEHTBLBUZpqzB8KUDqJMACgwXBeAYhM2hzBDZyWFph4mUjxgo0HpD2YxCAGA+JnNhF5CMLSZAXUJjBGte3BAPCQtUr1KMBaakNvGARkUKHDgySazEgaVPBHPzUUaoH1LZByQAgDHaOqfOSqBMEPBAR+ZGkwGH0i8EhI+bFiYEYAAiBDW2WuNjAEFOwAZMANeA8BVQ4IH7B2w1D0yTPeZBrAVBYIIJygEiANsLUgEBCsLMPDCCxzQUNsQQQAAIfkECQMAPwAsAAAAACgAKQCFBAIEhIKExMLEREJEJCIkpKKk5OLkZGJkFBIUlJKU1NLUVFJUNDI0tLK09PL0dHJ0DAoMjIqMzMrMTEpMLCosrKqs7OrsHBocnJqc3NrcXFpcPDo8vLq8/Pr8fHp8bG5sBAYEhIaExMbEREZEJCYkpKak5ObkZGZkFBYUlJaU1NbUVFZUNDY0tLa09Pb0dHZ0DA4MjI6MzM7MTE5MLC4srK6s7O7sHB4cnJ6c3N7cXF5cPD48vL68/P78fH58AAAABv7An3BILBqPyKRyyWw6ibBdoIJZgZoDnY4BaSJemZ5YXLgyYZtQK0RIgnS5sby3eP4gC14AZiQ0OnNyFXZCED4iNEMAKyaBcxIAhEI7Cis/MDhjHS0eJziAY5CSQhcyHhJiLgkXRCdyLaNDG2McFEYIDmMhsT80cT0KfEYQFmMjsTuNLh4ZIZFFKKAGwoQTNj0Oxyw2J0YTYwmjO8UOA0Mn2UUpqbaEN3Hpijgm7XfFPSXPTxAC2BNGMBRI6PJDhxgH9YxQSFDDx40hIDCIOYBkh4sAP0CE6REhCQoDYxzoEPJCDAZ9RkJ02OCqRw4ESRgEejACkARqR2DIyACyh/4GJQRczOnQyECbJTNooRymwFGGhElq9rBwVMkHRzzMKEGxsUATGKgCfVgCocIYFw+ZsBA6xwELJR7EUOkRw4kPRznSGpkBSAAMDVNxJoEgwpECvUMYFJMAk4CYkU0oXAsko+oPEnEOCwEgFJaTA45cvv1xQUWPDJaL2UDhBEAmRzZW3GiqgAQRzmLqOIHAI3QHkBJYESExpu4TFKZ9/ysSYgwPQgQ2OnJRYQKfN6BcLmUyO7QmCy6yB26CksQ9744swGSS5oOHAgZcZBCPXowBrUtITCnhgQYID2zVJ4YIvAgxgAwCioFRgZd4MJl3NlhW4AUhnDfUTwwWgYAGDRLkEoo5GR4BAAwMTDACAdsNEQQAIfkECQMAPQAsAAAAACgAKQCFBAIEhIKExMLEREJEJCIk5OLkpKKkZGJkFBIUlJKU1NLUNDI09PL0dHJ0tLK0VFZUDAoMjIqMzMrMTEpMLCos7OrsrKqsbGpsHBocnJqc3NrcPDo8/Pr8fHp8vLq8BAYEhIaExMbEREZEJCYk5ObkpKakZGZkFBYUlJaU1NbUNDY09Pb0dHZ0tLa0XF5cDA4MjI6MzM7MTE5MLC4s7O7srK6sbG5sHB4cnJ6c3N7cPD48/P78fH58AAAAAAAAAAAABv7AnnBILBqPyKRyyWw6i58TBvIcvgDNzyaRYqxygVcV8xh8kgCdYLVru3HnJ0AFe2CLpwzbzecsqkIQFxYjRBM5fIltF4BDIy0yPR8gHG0cHgE2BpVuHY1DEBkBDm0MIBhED3w2n0M3FW01BEYfiG0iRBs1NRcITBQaOxwxVEYAEqUnQx8KbiQud0czBTsrLjAJ0aC2HtEfnJY82o6IDJEQLQ1GN5wHRB8kiRwPRhgpOwy4QgQ5kUQmbUgoI1JDEQlfQxAIwDegyIEChYQAWLgDhBFVingsI7XCHxEAFkIUm1GpAqoiEKglqiAGQAZhLpC88tSjRBt1Rzoo2tEAAP6PNgGUmFixgEIlBcWMnICVqMCDSg7iIIEgIERBDg2TBNjJYAeJWUs27EGxBEO8ne6YQIClQcySBjtJJFUCog2MJi+aKcKpRKwwlk1EgHOzYoKSvDssRODp5KWiChFp2YyBQMSOFFIP602kAOHHnzlmIRC2wYmKroo8uB3igoMGCkNgWXTSemcLzzoY5IA9JFiMKix27pBQiEKBHH+IwGJwsgmAxTsrNCigYUYRAm4MP/lgQDg+FUZguOHrBIJN4TQS8IbAAlyERh8SeBdGQgKNRLMBQbAwX7iJKh9g4EILAqBwX3986OPECA9sMBAFyCBYTWStvADCHvN100oRKiR4MB8N1m0IxQQODNZGBVmJaAwBNjigQQ4S8NCcimh8MI4RQQAAIfkECQMAPgAsAAAAACgAKQCFBAIEhIKEREJExMLEJCIk5OLkpKKkZGJkFBIUlJKU1NLUNDI09PL0dHJ0VFJUtLa0DAoMjIqMTEpMzMrMLCos7OrsrKqsbGpsHBocnJqc3NrcPDo8/Pr8fHp8XF5cvL68BAYEhIaEREZExMbEJCYk5ObkpKakZGZkFBYUlJaU1NbUNDY09Pb0dHZ0VFZUvLq8DA4MjI6MTE5MzM7MLC4s7O7srK6sbG5sHB4cnJ6c3N7cPD48/P78fH58AAAAAAAABv5An3BILBqPyKRyyWw6n1AmaHFJpVqEqBN2mXB4YF5pRSTYXieYEkA6gg6asJw3Ag0H4Rk5SUkTKS9fc3I0QyVyDA5KCB0UPgAHNWAcLxEHARVyEkMFcywCaxceOWAMCVlDFAxhoEIzgxoQSihxPBaoRTaTOEO6gwdJOCo8LCOyRylgCnZCAYM8NkgECjwVIhIRAEcWYBFEEs8qzEQ41BqOPjctRiDUHHtCMKtzHGpEGOa4IAYyRQRfE8YJ8TVn0xAEE3gUaHNvBDohN8BcMOLimYEhIB7wqAGPiIsJxwC8KmCPCAQdg1igeJSABwcXSADYCCFkhUQkLZ4F8HGgm/4SAhXIcFuGBMWhORUESBpwLEmAGSQ4cBChpMEzFtVwJcFQA2WOJRCoPfPgJIPClUtkCJJTQGASEHi8SSEo50QTqxwqlFRCQdKcGg+RbGDwIiKwJheejdhbBIOGGSho8PjgBMCHZzncCoHwoIQjACxYaE1CwO+gENqI7KuwY8iwiUySEXvWYxyIBNaIJLTABEYnAwLmzUlgB0KCAq2GxNGhuQgEExfsSMA66MMGCzMKEUHxhQGGKB7WyuGgg1eRDpMCN4FEfZCOECuKn2i/QIsPF+IHMajQ3mV9+xII94x+32kBggsSjDDgMwqkZp8PMCSQ34DrPEiECGItqAICFh86d8Iw4ajX4WYSGKACdRp0wNiIRYCAAAEYNMUii0EAACH5BAkDAD8ALAAAAAAoACkAhQQCBISChMTCxERGRCQiJKSipOTi5GRmZBQSFJSSlNTS1FRWVDQyNLSytPTy9HR2dAwKDIyKjMzKzExOTCwqLKyqrOzq7GxubBwaHJyanNza3FxeXDw6PLy6vPz6/Hx+fAQGBISGhMTGxExKTCQmJKSmpOTm5GxqbBQWFJSWlNTW1FxaXDQ2NLS2tPT29Hx6fAwODIyOjMzOzFRSVCwuLKyurOzu7HRydBweHJyenNze3GRiZDw+PLy+vPz+/AAAAAb+wJ9wSCwaj8giAAYDJJ9Q48NlKxCi2CTB5euaRlkkiDJZMEDFV3ftWoWJuIfM0/VUYMTOeu1gvQkJNntrAUQ6g10dTlEQL4KIXToQQ1yQGFEUInUNDwMzBWseOEMOkD4USSgMG6UOEaNDACVrV0IakDaTYhVdNbBFI10OKEO8iB1JMA0+dmhHDF0Szj8HkAKLRSg9PhYjPDvYRBxdEUQkkBbTQwgCPho0Qgs7RxtdHEQAEpAk2e0qtT8AvJhgJIIPBbqGqEF0gAgEPRp+CYFQAtUQEPoeGMFBZ1CDWBl8mLBYhEAHPEIIeLBATMmsQS4QCHngwwGPJDtiKPQRAgn+h457LvxY4MHDvCQAWtwEocKAzCMgWiCSQcGCjxzhjnCQAWGFj6NIWABdY1XHpSgVHuhooM4IgBSm3GARa+LsEwxWBylom2SEh3JYLiDqgwWDDh0asiKBoG+QDJRJIDTo8JMgFgalBqVQHCtFDxggHNQIUwPSDc4AQkhoKcHBUygUMg/ycCArgAcC7M5agAWuKQ8PsN2+QwRUiigITKw5hCgDHhAvQrSVKiHKgjUSCCiAJILBB3BFQBjglhAJKGYMfhC4hchFA7tDrteEbwTBeKNDCKgwZSMBCycgLJCZAxIZQQMzJ4SDwjKmcKOCA0DZ8NoRMARwkxEgBDBWg106yPDGESMwx2EXL3x4BAoZVNKgBhOaSAQDJcg2iAHpuZgEDhd0MF4XFoRAn41IwEDBABxABuSRSB4ZBAAh+QQJAwA/ACwAAAAAKAApAIUEAgSEgoREQkTEwsQkIiRkYmTk4uSkoqQUEhSUkpRUUlTU0tQ0MjR0cnT08vS0trQMCgyMioxMSkzMyswsKixsamzs6uysqqwcGhycmpxcWlzc2tw8Ojx8enz8+vy8vrwEBgSEhoRERkTExsQkJiRkZmTk5uSkpqQUFhSUlpRUVlTU1tQ0NjR0dnT09vS8urwMDgyMjoxMTkzMzswsLixsbmzs7uysrqwcHhycnpxcXlzc3tw8Pjx8fnz8/vwAAAAG/sDfD8A63AoQoXLJbDqboIjHR11QntjsEpCgen0GknbspE2/1EmSzIRhcOtlAP0NsIUQ2c3gcKwKIEsXdF42cVkgJSt0HiVLA4RUhmMcE5E+K3EjkR4KWlEuPg4HMgQkBRtUHhhKD5EFWgSbDiEITAQ2VGJCGYSTWAq5A1dOvatKBYQTAE8ANR4uAYFPyTtxFIQfTyBdNioAxE7JB0sgBnTLTTCDGwxCJBxPDT6eSyl0NtNKEDeYOEsSwi3ZA4MJD0I0tuTARIAJgAYomKCwEKEJhAV0Wizp4WFDQ3Uh9P1oYOMjkxp0ZijR4MHCLicCGiiBYSDEEwTn0Lhj4cCF/gQtMVgI6bGh4JMWdFJgSGVTC4obEAhYEJEFwQ40DiAtMKpFQ4cTHcYkYySADYAFD0Rue0HnBTMyNCiyYRHqi4mXWWAMUGGBq5YQdEYccgIghacFOthAgIQmxtsnOmD9SPHizhw0HipgoVHhbQUXrMZgsEDIRYHHSlCcVqLChwYylwl5aID6BwGRGnykGEPTy4AzX2IMPuljwJjWXjIUAO5bIJNBBmo3GeSFnAYHdBwEiHgrlAW1TBCY+OLoB4erdCwkYPGWgCUfJsAvYfGlrxIMjAmZGLGhrg+VWZTwhUzlhMDcJT4koEUHXtwg3Q8CYISgDx4klEUFVLzgVxsNK+QUiR1aUDBDChs6gYAGL2BXH21kPJgFCgIU0EMEOoR2x4045qjjjjwKEQQAIfkECQMAQAAsAAAAACgAKQCGBAIEhIKEREJExMLEJCIkpKKkZGJk5OLkFBIUlJKUVFJU1NLUNDI0tLK0dHJ09PL0DAoMjIqMTEpMzMrMLCosrKqsbGps7OrsHBocnJqcXFpc3NrcPDo8vLq8fHp8/Pr8BAYEhIaEREZExMbEJCYkpKakZGZk5ObkFBYUlJaUVFZU1NbUNDY0tLa0dHZ09Pb0DA4MjI6MTE5MzM7MLC4srK6sbG5s7O7sHB4cnJ6cXF5c3N7cPD48vL68fH58/P78AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB/6AQEAAMh0nFysVOiCCjY6PkJGNACE/lpc/MxSSnJ2CMh+YmDsEnqaOACOioj0Qp44QMIyPJKuiHzanADQhIwcnMzYAjhq2ohOeAAI9oaIejh7Glh8HPp04FS+WBxMLzQcIjZW2LxYwrpIAGic/JwE0EAAAGDmWm4IuxgaeEBk/Fw7QOQIx4QeLRsVWHZglicKMHw1wcIrxoZSgWqtKdBJw44UHhpEi7IDRCMKBVTE4KXhwQoapDike1RMVExIACy823OuE4ACHRwpWdRj2yMWHGRhOqWgBEggMdpgeCBRk4gVSXR1EREqwSoIjCTklRuIBkkUNorSaXarQiMGNC/47IeHwKghAibioCtySiGPDBxWeDKAQJCBAJxoPRCWAMeBHBLSSYPgAACIHyU4xRD0o8WPCZU8qJKgQYArFyVs/deVw8UqBWktsXwGR4OAVAK6jktpu9QrGAlEdpnpyYUI2i9c/ckDuRGGAbBu2AiyXBGIDiVMwTt+yMN3miOKmTFzyhumFsGQjcpgieMmBgdcfIgh/BOIEMk8c1GYAYAL5gOuSMNBOd46kgMkNLABgQGIHBhSJXhc09QgEG2i2CQcVirKBDboBAYIF2xAoCAXI/eASECg0QM4EJVSQ4Q89gLbKAx0qeIM0mLDWCYiiqPcIDgWUiMkBg+2omUWQsDdQAYOi3ECaJzyIkss1LgxwQCg3VIBXZL9ZkoCIj8CAAwVFviLCAw84AKZsnmAQDptwxiknEIEAACH5BAkDAD4ALAAAAAAoACkAhQQCBISChERCRMTCxCQiJOTi5KSipGRiZBQSFJSSlNTS1DQyNPTy9HRydFRSVLSytAwKDIyKjExKTMzKzCwqLOzq7GxqbBwaHJyanNza3Dw6PPz6/Hx6fLy6vKyqrFxaXAQGBISGhERGRMTGxCQmJOTm5GRmZBQWFJSWlNTW1DQ2NPT29HR2dLS2tAwODIyOjExOTMzOzCwuLOzu7GxubBweHJyenNze3Dw+PPz+/Hx+fLy+vKyurFxeXAAAAAAAAAb+QJ8QwZnMKqOICCJsOp/QaFOWyVmvuQLHJe16TzesOJfSeM/P0Hg8E6CbrgsICqquxSXCmcDqlGYZOlxNCBt3YzYAUjI2DGMGikIkh2MMCFAILys5FQY9GgQONxt6QhR3FQc1CAs0HhhMTjhhEx+xTQc5C001awwkZyABGwUfkU8qDCdNEJtiNmcXHSsYg1E4E8cAE2MWXiQpM8ZeJiFPL2M9XQsFGTJoKCpPEmMNUgIlMRdoIBhzThAKiDEQxcGMGMvQEDARJYKYDP+aCJiRYt8bGBafkHB2RV4TCiVKAHvjo1QUDGJQNLmQggEOkmhIzMCyYh+EHRu8wUQTQEz+gn45/O1EgyAFTYcxrHkBsYukCENYGJgh2ePYGQDosHjYSUEETAgjsJSosVOH1TMkKmAZcAuNAzcwOzyLeAYCBphPx3A428VGRi8AdlgReGWDCb5RGhx4IwCqCRtYDCN+4mArGh5XLPSDmmNDALpRYNyY3ISAIysvfIBwiKXFXygNZiTsQgPLDAkAXDTgmKPEB9BCQIxg8BqK3MgzjiQgfGUCDKvCOCnFVOIQhhoDxtxA0cBEgBhWYpxRQYmGah2877A4A+NQhYwyBnAek+GSF3p3IjwBIKLFaSzuoLHAHTfM9gQBBxgwggItNGBgFy4wdwUDcA2FxgHzVSCBhSQSAaDCCwMU8MBIHMIEAAiklRgEACH5BAkDAEAALAAAAAAoACkAhgQCBISChERCRMTCxCQiJKSipGRiZOTi5BQSFJSSlFRSVNTS1DQyNLSytHRydPTy9AwKDIyKjExKTMzKzCwqLKyqrGxqbOzq7BwaHJyanFxaXNza3Dw6PLy6vHx6fPz6/AQGBISGhERGRMTGxCQmJKSmpGRmZOTm5BQWFJSWlFRWVNTW1DQ2NLS2tHR2dPT29AwODIyOjExOTMzOzCwuLKyurGxubOzu7BweHJyenFxeXNze3Dw+PLy+vHx+fPz+/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAf+gECCACwxLR0lPiwggo2Oj5CRjRAJP5aXPwc+KJKdngAxmKI/JyYQnqiONKOsAyypQAAoBAiRAaysLx4AnTgOIxcvFxGMjiW4rB8hvI8EKQ8vPT4OOS8qjy3IJwejOo4QPjcXIQTMAAkpjzWsFQQQEBg6A5cPOIIMEy8JnI8sJY8pRk0o1giAhBmWfICw8GDGq0gIIjyyMMqApIUtNJT4EOFUJw2PGIzi4AnHhAsyUtVyBIIbJgGdGOzYQANWpEqYbEjicGKGPZuQWHzAVCESgxMT+AF9BGLepQseGxHYsUDp0kcKRKVshGDECQpXJUEYganFpAovYIaVJGHoJbD+AEL8MLG2E4AKmAoA0fEhA7O6kUjcuPRBw40FMAB7coHpwwseij2NxVQjMioaDy4dsGoZkglMDaKmAkBiaQRRCf6iAmDRJonMohwAtaHaUw5LIVYQBglLQc1UFGALmHrphQJYFHSmCmUJLI0dxU3UhgTjHyoEJy5JEERhA+EYoqlPSJX10gweBHDwxTTidyQUJwhKKjDqwQO3mB5E4CxIwQX5kIDgHTKsPJCBCDDwQsgBC6CCA2wE/gAhYQfM4FIMqJAQoSUPUGDAgKxc8FMnJOCHywfXAAGDBRuY+MMGJF2XHTIf0MYSDwHUgIgJK6VyDC4HKDAdYMSJckEA/FkUhkMGG1ywQgEKJNaZJACAAKBlgQAAIfkECQMAPQAsAAAAACgAKQCFBAIEhIKEREJExMLEJCIk5OLkZGJkpKKkFBIUVFJU1NLUNDI09PL0tLK0dHJ0lJaUDAoMTEpMzMrMLCos7OrsrKqsHBocXFpc3NrcPDo8/Pr8vLq8fHp8jI6MbG5snJ6cBAYEhIaEREZExMbEJCYk5ObkZGZkpKakFBYUVFZU1NbUNDY09Pb0tLa0dHZ0nJqcDA4MTE5MzM7MLC4s7O7srK6sHB4cXF5c3N7cPD48/P78vL68fH58AAAAAAAAAAAABv7AnrAHiQU6nBQBMGw6n9DnQqarWmUuS3TL7aFw1nCVwkN0z0OHeK1TCdAAFIr5BGzYa1YAtJ2EZCUUDVpOIGB4azVmThMnFAciNiQOJxBOMBRsFAMKGmIyhEQ8NAcETjamTZhrHhAAcRcNYRgkPVMYInRdICpiDbpDKwNWJSEMLzBoQ7JhHlEADixWHsBoamExXDFWAcpNE55WCVsIOzISOhjVZyBUVg5RNhI7CDAOzt5DLmEVUBYKA5Lle0KAATFLTVBIGIFiIBQAJ8LkaAJhBw4bDqNkCKfjwxAAL2gsyPishhUWizxouEByywJpVUL0iMDiwbqWQx5YYSCAgvuMRTihfNnJYGTQLRE4ejzK5YUVFUCZPoFBw8qLm1IFcNRgQGqdEToyVWGQ4QmJDidmtExQxUUHKwXUCoHgwqCOAXwcgkCnQwCIA1ZwTOhhoUWYAgKFAEjVZVsVLRAAVynh4JAVBcBAjDtjuEoqEB8Q6XBRCF6XglYeJEOQQOwaBYmFwHhxxoQYChhocBRT9ImNBmcqiGajYMUTD/24AFAwHM8AAyhAAJhQgEcXCJaHY5CwW4MCBSxYDOZiqLmGAwhAiKhg96QJNDuGF7gADEWCECcqhDB6xgAiDQ+EchQIHeymAwMfzIBVSwDk8MAAOxxggAULMvWKV0IEAQAh+QQJAwA/ACwAAAAAKAApAIUEAgSEgoTEwsREQkQkIiSkoqTk4uRkYmQUEhSUkpTU0tQ0MjS0srT08vR0cnRUUlQMCgyMiozMysxMSkwsKiysqqzs6uxsamwcGhycmpzc2tw8Ojy8urz8+vx8enxcXlwEBgSEhoTExsRERkQkJiSkpqTk5uRkZmQUFhSUlpTU1tQ0NjS0trT09vR0dnRUVlQMDgyMjozMzsxMTkwsLiysrqzs7uxsbmwcHhycnpzc3tw8Pjy8vrz8/vx8fnwAAAAG/sCfUAgiPR40yHDJbDqfFEGnR7VVZsqndvsj6KhgsOwB4pqXmbCayqKczbDvWm27AJ4AmqdQCgyeBHNzHSUITQs1Ej4bKDBlTjRzMhkVKmoqC0MQPiovWVwra3ZECxlTVA0TPyQCKYZvJGoCd0wUPGAtMRoPb0MoDWEOTyAeLWAfvUMgCmG8WjNgFivJQmlgM1oEEhXMPToE1A9hF08oChkgGBJUAp9nKBZgOU4oAhWPBBpUPtQFYDq0hiAQwADGkgUmelh49WZAmGlDABSQwXAIDQktcCQDIAJMgog+TLhxAgAFtR/iqhj88SHVSS4gblERtqMBv5dcNpwy4UWC/LstjpLl+NdiwxlEBkSMPIPDBpgMZgC4AEalQDIHYBg8GpZATYWNHamQwxPhFJgDyXBQ7dHij5MbZqkY+MkljQqqIpkAuBCXCrZeJIB5eGCshwoMmmLM+ZjM2ogfB04JMIhDwBytyVA47UHix95TLB4YmMOh4pkTYDp7hiuICoeVyTiAGfuDwjpBr09iiEelRYYUIgpfhi0EAMQtI1oLKkF3wQszHpTP0fFi6w8QHMBxSSE9moRTCm7QQLGAAYsz1qRzwAFgBI++BlRzQa3cxKjiCwJwkMHBh8kzGIw2hwUBIIaTEzsISEUHElxg2oFMIPCCDw48wB6EGB4YBAAh+QQJAwBAACwAAAAAKAApAIYEAgSEgoREQkTEwsQkIiSkoqRkYmTk4uQUEhSUkpRUUlTU0tQ0MjS0srR0cnT08vQMCgyMioxMSkzMyswsKiysqqxsamzs6uwcGhycmpxcWlzc2tw8Ojy8urx8enz8+vwEBgSEhoRERkTExsQkJiSkpqRkZmTk5uQUFhSUlpRUVlTU1tQ0NjS0trR0dnT09vQMDgyMjoxMTkzMzswsLiysrqxsbmzs7uwcHhycnpxcXlzc3tw8Pjy8vrx8fnz8/vwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAH/oBAgoNAACAghImKi4yJEAY9OwsNLhQAjZiZCC0/nZ4fLRyXmaSDADmeqZ0fKRilpSQPqrMHMqOLEDwyMJkms78fEYiJOC4JIhCkCbMLGRk9sp4DOIMwPgUMt5klqi6jAAg2F54nPEA8DQLapNyeHetACKidDwkFCK+EKakGjRrRL0TkI2QjlblGIjzd4DBQEItUDBkh6OFiQacTFBoiOOBJASMUPfphGNFpAr6BFTwFWDTRwiAMGzoVgIdJhace6yB0CKGNhqwPJkyJaLCjxMlEG1ehIAQiQ4lhhAx8+PGABhAMFaZ20sEogicbhFyM4KXoVKcVJGKm8rgIR7QF/qMUHCDRCIFFqqouHFW0rJMAICQucMVEYscvD5gwnOhUA8KAGlAbOZg1g6zkVS1uECBFYnGqBxExgRjgKQQpx7McvKLxotNKTAD2qWoROVMGehkZAfBAy9UrGBxLWiYEYLKqD3/z+fgxwPCPArUB+NCaSnU+BIsdsIjm4RYE2aoq0GxkoJM5Ca0/qBCEosOvAcnyASD5YzMQHa0XMljxa8bSgSS09kNuAOggyw3RqDKBbwNZ4IkPg0Bw2y8/dPBfQymtUoANGWxAnSoFxNcQCM5ReJwP45HilomzTGBfQ4IQkCCLE/D3QAwEXAIACgawUAoKN7BIlQ8QQOCAYS+MOFBBDwf0UwoAPZgICgOEwKBAAi10EAA1+Yjw4SodiJAijIIooNYHM4RAw5hkRkgDAyiw2eac+QQCACH5BAkDAEAALAAAAAAoACkAhgQCBISChERCRMTCxCQiJKSipGRiZOTi5BQSFJSSlFRSVNTS1DQyNLSytHRydPTy9AwKDIyKjExKTMzKzCwqLKyqrGxqbOzq7BwaHJyanFxaXNza3Dw6PLy6vHx6fPz6/AQGBISGhERGRMTGxCQmJKSmpGRmZOTm5BQWFJSWlFRWVNTW1DQ2NLS2tHR2dPT29AwODIyOjExOTMzOzCwuLKyurGxubOzu7BweHJyenFxeXNze3Dw+PLy+vHx+fPz+/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAf+gECCg4SFhoeIiYIIHAo8GCCKkpIwATc/mB8TFiiTnoQQDZijoxc+CJ+eLqSsmAscqYoQC62tLw6RhRAiAJ84H6wrBQUDD6Q1qIIAAibJngKsDr1AAAgmG6MbNEA4EQLTnxKkBeCDECHAPyceETCKADQmDIQipCKJPAeYF9uJFA3AbjijQAqDIgYvMB3oVwiAigukSAyCoQ9TJ0QEajDogGkFAUMGEo46AYFQjVEUEGFoIBHBDEwjnAFhIXJUhEIGRik4hKABi0EEKubINYvVAxyFcBj7EcMQjBYSCvFYakOQhVY5DrXomEtZAReHNAB7wABCxVEfGBJSMOrnIBf+FboSApDA1SpWFcqZW4EpxSAZCy7yxFbrxTxEV388cEfiQFRFKmr9SCCL7w8bEDpk0GsIRI9WK2QeUgBsg4cDghMFZfXhniQAJUY5mARjQKsQnA/hgPghpSIQsVkNKPkpAqYauZUFqFX1EwjCHzQkMtHqgwHinnT8qIHtxEeQ6UjNTgXipQIGlzpgp2ah5igPsYBIACZRgTHcgkDEqIU/VvCLOnwQHRAoiNJKCsklggBEP/gGgAsf3OACYawksN4nPKQTwCAAZCDZBy4kqIgDaJXgQQgzhEfKCxqIqMh+krVygWvxDZJDjKwp4KIkMOL4QQ4rfFABDxemEhmOKwg4AAQECrRwwg4FmMBBkYogQCErF7ggGgIUsEADJPGxcBYmO4RgUI2ToGBBDhkEIACVaMYp55yxBAIAIfkECQMAPwAsAAAAACgAKQCFBAIEhIKExMLEREJEJCIkpKKk5OLkZGJkFBIUlJKU1NLUVFJUNDI0tLK09PL0dHJ0DAoMjIqMzMrMTEpMLCosrKqs7OrsHBocnJqc3NrcXFpcPDo8vLq8/Pr8fHp8bGpsBAYEhIaExMbEREZEJCYkpKak5ObkFBYUlJaU1NbUVFZUNDY0tLa09Pb0dHZ0DA4MjI6MzM7MTE5MLC4srK6s7O7sHB4cnJ6c3N7cXF5cPD48vL68/P78fH58bG5sAAAABv7An3BILBqPyKRyyUQiPoUdLaG5AJrY4SrD63odpc01u7QZvOhuC3UhCyEaGIPYS9t5hgVZF+sqhgASaSI3FTEdXh0wEEwAPg5eNUMIJl8DYwA2D2ddHAg/CDZHAB5pBkMnkF0+RxABLX4yE0gyiGgCQxc1XR0nSRtceCStOHYJk5U8GYxJBMkpokUadzKAgjwKIEk2KCQlXSKfRBh2FsxCCV0450UkBZ8QHF0V5wB9acdEMrziRTYFvoScUNAlwJgTthLNKHLCQpcNRk7QiDaEgcMOen7USVNhDJEKXTwUgUBjwBEViGpQSJWmw0IjE7oI8AgAxoMkMPzkxIcEBP7BDhQ10NDmhOAdAwGPHOjSQwiJFASWjEiYKCOSFyl44AAAgsOHRvLSFPCIZBqPCR92sENygZMXHG2WdMVmYgUTki1nNZkBiUOjEHYKkMm5bEkOquDILhFQUIkOVS3tNtHRoYSJFhCPrHCIxoEqDFga8BgwoIWCflo4WyrFwwJqJCRadND2oDJRIRp2da4GAgOiOUtKWRACIECHCNpAeEDcwioIDRiSJhHUgRkIFy0C6GCcpoUGN0J0dblJ/EYHxDxq6AW/IVEBFyhSoMeTGfyPfXfuSKBof0B+OwbwZ58NkOWXwQIeCPDBWm4U8J8DMIgDwggPSGbfCdegYQEKFBu0Yt8QEPggQAYKVJBDXB82AsFtKbbo4otYBAEAIfkECQMAQAAsAAAAACgAKQCGBAIEhIKEREJExMLEJCIkpKKkZGJk5OLkFBIUlJKUVFJU1NLUNDI0tLK0dHJ09PL0DAoMjIqMTEpMzMrMLCosrKqsbGps7OrsHBocnJqcXFpc3NrcPDo8vLq8fHp8/Pr8BAYEhIaEREZExMbEJCYkpKakZGZk5ObkFBYUlJaUVFZU1NbUNDY0tLa0dHZ09Pb0DA4MjI6MTE5MzM7MLC4srK6sbG5s7O7sHB4cnJ6cXF5c3N7cPD48vL68fH58/P78AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB/6AQIKDhIWGh4iJiouKADAgiAgcMhIkMIyJACIdFyUAhRA+Fz+kPzs5PJCYgxAxH6UKhAApr6WlHx0Cn5ggJbY/LYQcL7+/HwU4jLPFF4QJtg8FISkDD6UnsYoSxT8fgwAzpRPJggAINielEaqHNdwngzA7pB8khzARtQ2Xhx3cPYMQpPvRYdchDhtIjUBxKAI3H4NAJPwRYhGGBQoZFnLAjQKhHqQcKIKgAkUGUj0QFIpRbIJBICFI2UgEw4ZKEBVIVWAHrpiFQiJIBYjkQaUgGP4o7sJRq9QBfoNQjCpwCIQHjYMudsvmopiLQzknsCvngsAhFtYu4ABx4NcOo/6FVPx4AVeQBR6JuhI0UcwAIhhtZRBSYWLkBFLEbPUYW8jDD6qCKGRgbIhH01IPGChCceAGQwgVyC3y9WvoIhs/htrwwGvArxEQGEGYcIPDirqJHNu64RETiw8PWDOSkLgUxFVAUvwQwIjFjWIjKCfq8WFG7EQk2hb7IGKVZRsHckgHwkB7qeIVVuWEweOBj5eCeAwstcBAqQtYE+F4sEOQigeFfWOBNbZsgAMGtgi2iAU/LDCIDAdkA0MGl/2wgVlAhENKDIy4c4FBtikgAka/TIAhEHyRMgN8hbBFioJAAGDACxUCUxcII5DyAAaK7KdjDA4ksEKNPyRwHSE4rEvQTW+IUFAcN5jpwKIgCFQwAm6FYEAglKQscCJyhzTA5Q8rKDAlmINQMN8t9R2JJnYVXPDADQvEkMqbyMGAAwpn4unnn4AGKmgigQAAIfkECQMAQAAsAAAAACgAKQCGBAIEhIKEREJExMLEJCIkpKKkZGJk5OLkFBIUlJKUVFJU1NLUNDI0tLK0dHJ09PL0DAoMjIqMTEpMzMrMLCosrKqsbGps7OrsHBocnJqcXFpc3NrcPDo8vLq8fHp8/Pr8BAYEhIaEREZExMbEJCYkpKakZGZk5ObkFBYUlJaUVFZU1NbUNDY0tLa0dHZ09Pb0DA4MjI6MTE5MzM7MLC4srK6sbG5s7O7sHB4cnJ6cXF5c3N7cPD48vL68fH58/P78AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB/6AQIKDhIWGh4cgAIiMjYw0BSsTLjCIIBgUGBCOlg4PP6A/A5uFAgMvPy87BQKknEAAIaGzJoUyn7OgKzYIrya5oR2EKAfAsxsai4+4wCuELrMtDhoOFRehFSiMKcagC4QdoB/JhAg21z87HIgaqMYNgzA7oCGICCkfPx8WyoUYxcB8DEJw7QGGRiIARgBxKIaxdYJgnPjRoh8iHBP0JWBYCFquAxyBQFjwI8ErCA1ALSyUAFiMQjV+BHglsgIoF/0A9Mj1gUYhGz9SMAJBgBCMcB8UDILADFQHi0AovBB2CIACV4IwrPhxwScQCcAEGAIx4EIlQyoOGmJw48eMSv4pZz099EuGIRkMGJnIl4FFvlAfWNg7UMMiCw2NAJQAtSHXS0YuHpAYhCEAVEM40B3TxgjBBqEiY3B2FICnBE63KADxIOIVCJuzYlw2pLgFhxw0LfwFNeAsJxwnVkzmJKDpD6U0gXjq5YgGwFk1kgOYUaFwIxKNgb3wyknCCwgxBCLCPuvDgFAmX9UQBiKHXUMcnuuzwCLUCd+MMDx4DCHDcEEAaNAWYDYAQMMspzmig0wDhUAKDPjM8gA5YIWSASeL4UYZDwCIQNIsF4gliA+zrBASIlvdMBoAPDTgTigrcAdES6E8gEMjEKCzgAkqBDDBbtgwN8iCs+TFCAwDdksDygXkFIJAU+8xspWSHxSg1iEegaKCIx5080EDguF4Hiit4ZgDkAekIGMjODT2wX+NUGCAAzpwgNUrGMTAT3J89unnn4AGKiifgQAAIfkECQMAQAAsAAAAACgAKQCGBAIEhIKEREJExMLEJCIkpKKkZGJk5OLkFBIUlJKUVFJU1NLUNDI0tLK0dHJ09PL0DAoMjIqMTEpMzMrMLCosrKqsbGps7OrsHBocnJqcXFpc3NrcPDo8vLq8fHp8/Pr8BAYEhIaEREZExMbEJCYkpKakZGZk5ObkFBYUlJaUVFZU1NbUNDY0tLa0dHZ09Pb0DA4MjI6MTE5MzM7MLC4srK6sbG5s7O7sHB4cnJ6cXF5c3N7cPD48vL68fH58/P78AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB/6AQIKDhIWGh4YgEACIjY6GACQONTsXIzYgjSAwEI+HMBkvP6OkPocMOSsHKy0eFIyeKBOktD8vGIUqD7W2LRywiDA9vLQGhCQ3xKMvBQSNCcqkIYTQPx85EhQ0CjEboxcGwIM0otE/EYMQKz8nDIYQGjvWJTCFDCsTAx/KLoMYohoaQYggaoazQyROEBMxCMeHCwge8TjAzt0hD7wedBKE4cYMcYgwDPhxgcchCbxqEAKxYoAnQTAq/LhhspAMXioKZVgAUmAJkhwK+ah1YOMgGTdwvQQCoQY7CoMADKPlwRCCEwGXAkEwckZEIAT2kTrwtVCCBj0dEVBYI1OEWv4WEJG4cFCrjH0ecOwiNcGooQwBChFQighADmsjST1g4YjEgnowazrCoLCWqUc+jAEBoCGTJ4y0OvgNVqOTCMaeQMgcW/eRBB0YLnuyQOsDw6UAAqRA8VLA3lEfaGgFokC2IwaVaaXQCqLHaITySF0YlXQpN08UKJJKYIJU1ZcdUDfikfxHBhAoR63wrPbG86g6fv8okEkAqQ/iG8VnXwhBCrHWBACLArQYh8hhrQ3CwwK03KABMG+R4tIjDAZGCAWh0LKBRYIwkAwpJ5R1CATTfeACCRRoUEM5pDTAmz/R3QeVJsl9ACApL9gAkgbECPCIU9E0kKAgKhAjwyMcyEIHXAcCpAUEDiySIoEnMkR3wQQ+MODkIAXwIpwnEDDAgIiP4DAdKTu8N5wjPHq35pse7LMAmW96AgAPDuBQ556HBAIAIfkECQMAPgAsAAAAACgAKQCFBAIEhIKExMLEREJEJCIkpKKk5OLkZGJkFBIUlJKU1NLUNDI0tLK09PL0VFJUdHJ0DAoMjIqMzMrMLCosrKqs7OrsbGpsHBocnJqc3NrcPDo8vLq8/Pr8XFpcTEpMfH58BAYEhIaExMbEJCYkpKak5ObkZGZkFBYUlJaU1NbUNDY0tLa09Pb0VFZUdHZ0DA4MjI6MzM7MLC4srK6s7O7sbG5sHB4cnJ6c3N7cPD48vL68/P78XF5cTE5MAAAAAAAABv5An3BILBqPRQAIgGw6j6DFZ6MQhC5O5fMIcQg4u3C4NDECPKRYRnTrnLa+nERMDwuYQ8AHXK+gCE0gHw11hWVDAyw7JQwFDAqKOzQBCGYwfIV0DkQFOyGVQgAXHRthKTlFD5iZYj1DIAoxIEcAGjo7HB+zPhOEuBICvoZDEBkRWTw0OxtvqgomL0IIpXUGuz4gKS5bBAI7KQQQI3hDMoUYRRvbWxAkOzgjRy+rOwNFIcdwIBjfNkYEdVJAKOKBAhwh+3YIiEbkQB0LRl5suMaOwQ4SFGfQMcCwCAx/B32cULAjAp4LkcKYQJKjRUghCxpwWOkjAp0YA6HUeCnEwv4OFjlsCONgr4kMnj4ArNiRYamYfEifTFBGR0LOqE8+0KlwKCRFJyAshuHAg+cLFXAABMA0g9zBFl+RHEi5owKogzZcbhlAlc66gyHcIplgQIyCEGFwdHyiAuITAikMX8ghpiycG2+cTIgcRoE/BGJ0CD5CgMJoIgMKdwbkA4KYBqydPPgQ6EFKZkMW0OmwRcfOIxOohSmw2KYYFE8glChgBEGEvhwCUOxV9ckJGixaMAEwIkCJjR7wjHDgvU6JxRErhDEQowS9Anc90KXTAGQTjawUoOLEClc8zarRkYEJVw3BDysc/OcEATBkUAEOBXgQlxAd9FffQQAgcBoRLyXgwEoGE2LVEysJiNgEBN70EZuJ/2QgIFosNnFCCALMcECBQwQBACH5BAkDAD0ALAAAAAAoACkAhQQCBISChMTCxERGRKSipOTi5CQiJGRmZBQSFJSSlNTS1FRWVLSytPTy9DQ2NHR2dAwKDIyKjMzKzExOTKyqrOzq7CwqLBwaHJyanNza3FxeXLy6vPz6/GxubDw+PHx+fAQGBISGhMTGxExKTKSmpOTm5CQmJGxqbBQWFJSWlNTW1FxaXLS2tPT29Dw6PHx6fAwODIyOjMzOzFRSVKyurOzu7CwuLBweHJyenNze3GRiZLy+vPz+/AAAAAAAAAAAAAb+wJ5wSCwaj8ikckkE3DQxQsgDUsIQVSYRNGBxeGAwJrudMHKlDGmF0HoE37BcZ3xxGplMA1yKGZIwES1yhDwMRSgVFCYgECgjBDU8NSFtRS4yhYUyAEQjOZZEFwGSKi5DIC97moQCnUMHKUk3FBwtJz0QFHItEjSZhThFJytLMyU8MSRhCicwQgAnhXREBxZMNzthGTOvQxCSYRwXRRPkTBAFPA1/RjAVcgzeQhZkSys4HCzPRQaEE1qUgMDAg4C9HjrkKDgYsAiEHRxCeAPAQE6xhkpuZOBwYMiNODwEQMC4xEGNBg6ExBB3iuQSaSoQGBgEJoZLJiAqktgFRkH9qJtJDCAL06Al0CUP5Dw4ipNAGAYMmRaxE2aEVCUzaIJhcRWJA3hgMvDg4AHjSCYmcoR5sWBrQxQ2mFxQsRaAPx4trmmZEVWUhDAROgGgGUELhA5LLNAV4w1shrNKRiyIPJRD4G9xOKRcgkGvO0FgGhyYt0LOiyUgctwwAmCEAjkJzglJK4erkgs1BjTpApIogw4aEoTb1lcIigYKPFzw8OF1mAoEWRGq8JO180IcMKBwIZ1QDdlIFmgF04LCZgTd5ZSofsQBAQUKaJxoN2RD+p7FGw7ozQpDVwDRSceBUVLBwEJ3LMxTYAL8gSECeF31YMILLOhRwQ46QEZEEAA7);" class="fortis-ajax-loader-img">' +
                    '<div style="position: absolute; top: 40%; width: 100%; text-align: center; margin-top: 40px;color: #fff;">Please wait ...</div>' +
                    '</div>' +
                    '</div>';

                if ( ! $('.fortis-ajax-loader', el).length) $('form', el).prepend(loader);
            },
            show : function() {
                this.init();
                $('.fortis-ajax-loader', el).fadeIn('fast');
            },
            hide : function() {
                $('.fortis-ajax-loader', el).fadeOut('fast');
            }
        };


        /**
         * Динамические опции
         * @param key
         * @param val
         * @returns {*}
         */

        function option (key, val) {
            if (val) {
                options[key] = val;
            } else {
                return options[key];
            }
        }

        /**
         * Поддержка событий хуками
         * @param hookName
         */

        function hook(hookName) {
            if (options[hookName] !== undefined) {
                options[hookName].call(el);
            }
        }

        /**
         * Инициализация
         */

        init();

        return {
            option: option,
            open: openSwitcher,
            close: closeSwitcher
        };
    }

    $.fn[pluginName] = function(options) {
        if (typeof arguments[0] === 'string') {
            var methodName = arguments[0];
            var args = Array.prototype.slice.call(arguments, 1);
            var returnVal;
            this.each(function() {
                if ($.data(this, 'plugin_' + pluginName) && typeof $.data(this, 'plugin_' + pluginName)[methodName] === 'function') {
                    returnVal = $.data(this, 'plugin_' + pluginName)[methodName].apply(this, args);
                } else {
                    throw new Error('Method ' +  methodName + ' does not exist on jQuery.' + pluginName);
                }
            });
            if (returnVal !== undefined){
                return returnVal;
            } else {
                return this;
            }
        } else if (typeof options === "object" || !options) {
            return this.each(function() {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
                }
            });
        }
    };

    $.fn[pluginName].defaults = {
        closeEsc : true,
        closeDoc : false,
        debug : false,
        onInit: function() {},
        onDestroy: function() {}
    };

    /**
     * =================================================================================================================
     */

})(jQuery);


var Base64 = {


    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

};