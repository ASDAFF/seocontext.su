<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
?>

<?$APPLICATION->IncludeComponent(
    "doninbiz:sections.elements.view",
    "team",
    Array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams['IBLOCK'],
        "SECTION_URL"	=>	"",
        "CHECK_DATES" => "Y",
        "FIELD_CODE" => $arParams['FIELD_CODE'],
        "PROPERTY_CODE" => array("",""),
        "IBLOCK_URL" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_GROUPS" => "Y",
        "SET_TITLE" => "Y",
        "SET_BROWSER_TITLE" => "Y",
        "BROWSER_TITLE" => "-",
        "SET_META_KEYWORDS" => "Y",
        "META_KEYWORDS" => "-",
        "SET_META_DESCRIPTION" => "Y",
        "META_DESCRIPTION" => "-",
        "SET_STATUS_404" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "ADD_ELEMENT_CHAIN" => "N",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "USE_PERMISSIONS" => "N",
        "PAGER_TEMPLATE" => ".default",

        "DETAIL_URL"	=> $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
        "ELEMENT_ID"    => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE"  => $arResult["VARIABLES"]["ELEMENT_CODE"]
    )
);?>