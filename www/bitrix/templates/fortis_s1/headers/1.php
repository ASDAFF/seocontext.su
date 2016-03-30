<header class="header">

    <div class="container wrapper-container relative-pos">
        <div class="row">

            <div class="col-md-3 col-sm-4 col-xs-12">

                <a href="<?=SITE_DIR?>" class="logo">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "includes/company_logo.php",
                            "EDIT_TEMPLATE" => ""
                        ),
                        false
                    );?>
                </a>

            </div>

            <div class="col-md-4 hidden-sm col-xs-12">
                <div class="header-slogan">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "includes/company_logo_slogan.php",
                            "EDIT_TEMPLATE" => ""
                        ),
                        false
                    );?>
                </div>
            </div>

            <div class="col-md-5 col-sm-8 col-xs-12">
                <div class="header-right">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "includes/company_logo_right.php",
                            "EDIT_TEMPLATE" => ""
                        ),
                        false
                    );?>
                </div>
            </div>

        </div>
    </div>

    <div class="navigation-outer-color">
        <div class="container wrapper-container relative-pos">
            <div class="header-navigation-breakpoints">
                <div class="header-navigation load-nav">

                    <nav class="navbar" role="navigation">

                        <div class="navbar-header">
                            <div class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navigation">
                                <div class="inner">
                                    <i class="fa fa-arrow-circle-down"></i>
                                    <span class="text"><?=GetMessage('DONINBIZ_FORTIS_HEADER_4')?></span>
                                    <span class="sr-only"><?=GetMessage('DONINBIZ_FORTIS_HEADER_4')?></span>
                                    <i class="fa fa-arrow-circle-down"></i>
                                </div>
                            </div>
                        </div>

                        <div class="collapse navbar-collapse" id="header-navigation">

                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "general_horizontal_multilevel_1",
                                array(
                                    "ROOT_MENU_TYPE" => "top",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "MENU_CACHE_GET_VARS" => array(
                                    ),
                                    "MAX_LEVEL" => "4",
                                    "CHILD_MENU_TYPE" => 'left',
                                    "USE_EXT" => "Y",
                                    "DELAY" => "N",
                                    "ALLOW_MULTI_SELECT" => "N"
                                ),
                                false
                            );?>

                        </div>

                    </nav>

                </div>
            </div>
        </div>
    </div>

</header>