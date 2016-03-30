<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arServices = Array(
    "main" => Array(
        "NAME" => GetMessage("DONINBIZ_SERVICE_MAIN_SETTINGS"),
        "STAGES" => Array(
            "files.php", // Copy bitrix files
            "template.php", // Install template
            "theme.php", // Install theme
            "group.php", // Install group
            "menu.php", // Install menu
            "settings.php"
        ),
    ),

    "iblock" => Array(
        "NAME" => GetMessage("DONINBIZ_SERVICE_IBLOCK"),
        "STAGES" => Array(
            "types.php", //IBlock types
            "actions.php",
            "jobs.php",
            "faq.php",
            "catalog.php",
            "licenses.php",
            "news.php",
            "partners.php",
            "portfolio.php",
            "price_list.php",
            "our_team.php",
            "articles.php",
            "services.php",

            "history_company.php",
            "features.php",
            "customer_reviews.php",
            "home_slider.php",
            "social_networks.php",

            "order_questions.php",
            "order_calls.php",
            "order_products.php",
            "order_services.php",

            "mess.php"
        ),
    )
);
?>