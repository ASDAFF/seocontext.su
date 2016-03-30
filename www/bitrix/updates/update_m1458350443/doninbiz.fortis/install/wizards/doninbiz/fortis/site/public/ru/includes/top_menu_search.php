<?$APPLICATION->IncludeComponent(
    "bitrix:search.title",
    "top_menu",
    Array(
        "NUM_CATEGORIES" => "1",
        "TOP_COUNT" => "5",
        "ORDER" => "date",
        "USE_LANGUAGE_GUESS" => "Y",
        "CHECK_DATES" => "N",
        "SHOW_OTHERS" => "N",
        "PAGE" => "#SITE_DIR#poisk/index.php",
        "SHOW_INPUT" => "Y",
        "INPUT_ID" => "title-search-input",
        "CONTAINER_ID" => "title-search",
        "CATEGORY_0_TITLE" => "",
        "CATEGORY_0" => array("iblock_fortis_content")
    )
);?>