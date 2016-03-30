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
    "doninbiz:sections.elements.list",
    "team",
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK" => $arParams['IBLOCK'],
        "FIELD_CODE" => $arParams['FIELD_CODE'],
        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
        "CACHE_TIME" => $arParams['CACHE_TIME'],
        "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
        "SECTION_SORT_BY1" => $arParams['SECTION_SORT_BY1'],
        "SECTION_SORT_ORDER1" => $arParams['SECTION_SORT_ORDER1'],
        "SECTION_SORT_BY2" => $arParams['SECTION_SORT_BY2'],
        "SECTION_SORT_ORDER2" => $arParams['SECTION_SORT_ORDER2'],
        "ELEMENTS_SORT_BY1" => $arParams['ELEMENTS_SORT_BY1'],
        "ELEMENTS_SORT_ORDER1" => $arParams['ELEMENTS_SORT_ORDER1'],
        "ELEMENTS_SORT_BY2" => $arParams['ELEMENTS_SORT_BY2'],
        "ELEMENTS_SORT_ORDER2" => $arParams['ELEMENTS_SORT_ORDER2'],
        "SEF_MODE" => $arParams['SEF_MODE'],
        "SEF_FOLDER" => $arParams['SEF_FOLDER'],
        "SEF_URL_TEMPLATES" => $arParams['SEF_URL_TEMPLATES'],

        "IBLOCK_URL"    => '',
        "DETAIL_URL"	=> $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
        "ELEMENT_ID"    => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE"  => $arResult["VARIABLES"]["ELEMENT_CODE"],
    ),
    false
);?>