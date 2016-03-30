<?
$iFortisSkipHeading          = true;
$iFortisSkipContentContainer = true;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("title", "Главная страница");
$APPLICATION->SetTitle("Главная");
?>
    <div class="home-slider">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR . "includes/home_slider.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
                "ACTIVE_COMPONENT" => "Y"
            )
        );?>
    </div>

    <div class="content no-margin-top no-margin-bottom">

        <? /* Текст с приветствием и кнопкой заказа */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_intro.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши преимущества №1 */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_features_v1.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши преимущества №2 */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_features_v2.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши услуги №1 */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_services_categories.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши услуги №2 */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_services_icons.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши услуги №3 */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_services_photos.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Блок заказа услуг и задать вопрос */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_order_service.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши работы */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_portfolio.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Заказ проекта */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_order_portfolio.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Наши акции */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_offers.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Каталог продукции */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_catalog_categories.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Каталог продукции */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_catalog_items.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Новости и отзывы клиентов */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_news_reviews.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Партнеры на главной */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/outer_partners.php",
                "EDIT_TEMPLATE" => ""
            ),
            false,
            array(
            "ACTIVE_COMPONENT" => "Y"
            )
        );?>

        <? /* Остались вопросы? */ ?>
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."includes/home/outer_order_bottom.php",
            "EDIT_TEMPLATE" => ""
        ),
            false,
            array(
                "ACTIVE_COMPONENT" => "Y"
            )
        );?>

    </div>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>