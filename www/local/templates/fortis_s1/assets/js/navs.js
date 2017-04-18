$(function() {

    // Smart меню
    fortis_smart_navs();
    fortis_more_navs();

    $(window).resize(function() {
        fortis_more_navs();
    });

});

/**
 * Smart меню
 */
function fortis_smart_navs() {

    $('.header-navigation ul.navbar-nav.visible-items, .header-navigation ul.navbar-nav.hidden-items, .header-navigation ul.navbar-nav.search-item').each(function() {
        var $this = $(this);

        var options = {
            subMenusSubOffsetX: 5,
            subMenusSubOffsetY: -1,
            subIndicatorsPos: 'append',
            subIndicatorsText: '',
            collapsibleShowFunction: null,
            collapsibleHideFunction: null,
            rightToLeftSubMenus: $this.hasClass('visible-items') ? false : true,
            bottomToTopSubMenus: $this.closest('.navbar').hasClass('navbar-fixed-bottom')
        };

        if ($this.hasClass('search-item')) {
            options.subIndicators    = false;
            options.showOnClick      = true;
            options.hideOnClick      = true;
            options.subMenusMinWidth = 'auto';
            options.subMenusMaxWidth = 'auto';
        }

        $this.addClass('sm').smartmenus(options).find('a.current').parent().addClass('active');

    }).bind({
        'show.smapi': function(e, menu) {
            var $menu = $(menu),
                $scrollArrows = $menu.dataSM('scroll-arrows'),
                obj = $(this).data('smartmenus');
            if ($scrollArrows) {
                $scrollArrows.css('background-color', $(document.body).css('background-color'));
            }
            $menu.parent().addClass('open' + (obj.isCollapsible() ? ' collapsible' : ''));
        },
        'hide.smapi': function(e, menu) {
            $(menu).parent().removeClass('open collapsible');
        },
        'click.smapi': function(e, item) {
            var obj = $(this).data('smartmenus');

            if (obj.isCollapsible()) {
                var $item = $(item),
                    $sub = $item.parent().dataSM('sub');
                if ($sub && $sub.dataSM('shown-before') && $sub.is(':visible')) {
                    obj.itemActivate($item);
                    obj.menuHide($sub);
                    return false;
                }
            } else {
                if ($(item).hasClass('dropdown-toggle') && $(item).attr('href') != '#' && $(item).attr('href') != null ) {
                    location.href = $(item).attr('href');
                    return false;
                }
            }
        }
    });

    // fix collapsible menu detection for Bootstrap 3
    $.SmartMenus.prototype.isCollapsible = function() {
        return this.$firstLink.parent().css('float') != 'left';
    };

}

/**
 * Адаптирующееся меню
 */
function fortis_more_navs() {
    var $this           = this,
        nav_outer       = $('.header-navigation'),
        visible_items   = $('.visible-items', nav_outer),
        hidden_items    = $('.hidden-items', nav_outer),
        hidden_items_ul = $('.dropdown-menu', hidden_items),
        search_item     = $('.search-item', nav_outer),
        nav_max_width   = 0,
        items_width     = 0,
        visible_hidden  = false,
        container_width = intval(nav_outer.closest('.header-navigation-breakpoints').width());

    //nav_max_width = container_width - intval(hidden_items.width()) - intval(search_item.width());
    nav_max_width = container_width - intval(hidden_items.width()) - intval(search_item.width());

    hidden_items_ul.empty();

    //if ( (window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth) <= 767) {
    if ( Modernizr.mq('only screen and (max-width: 767px)') ) {
        visible_items.find(' > li').show();
        hidden_items.addClass('hidden');
        nav_outer.show();
        nav_outer.removeClass('load-nav');
        return;
    }

    visible_items.find(' > li').each(function () {
        var self = $(this),
            w    = self.width();
        items_width += w;

        self.show();
        if (items_width > nav_max_width) {
            hidden_items_ul.append(self.get(0).outerHTML);
            self.hide();
            visible_hidden = true;
        }
    });

    if (visible_hidden) {
        hidden_items.removeClass('hidden');
        hidden_items.smartmenus('refresh');
    } else {
        hidden_items.addClass('hidden');
    }

    nav_outer.show();
    setTimeout(function() {
        nav_outer.removeClass('load-nav');
    }, 100);
}