<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
    "bitrix:menu.sections",
    "",
    array(
        "IS_SEF" => "Y",
        "ID" => $_REQUEST["ID"],
        "IBLOCK_TYPE" => "fortis_content",
        "IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
        "SECTION_URL" => "",
        "DEPTH_LEVEL" => "9",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "SEF_BASE_URL" => "#SITE_DIR#katalog/",
        "SECTION_PAGE_URL" => "#SECTION_CODE_PATH#/",
        "DETAIL_PAGE_URL" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/"
    ),
    false
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>