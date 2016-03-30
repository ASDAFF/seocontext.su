<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arSortFields = Array(
    "ID"          => GetMessage("FHSS_IBLOCK_DESC_FID"),
    "NAME"        => GetMessage("FHSS_IBLOCK_DESC_FNAME"),
    "ACTIVE_FROM" => GetMessage("FHSS_IBLOCK_DESC_FACT"),
    "SORT"        => GetMessage("FHSS_IBLOCK_DESC_FSORT"),
    "TIMESTAMP_X" => GetMessage("FHSS_IBLOCK_DESC_FTSAMP")
);

$arSorts = Array(
    "ASC" => GetMessage("FHSS_IBLOCK_DESC_ASC"),
    "DESC" => GetMessage("FHSS_IBLOCK_DESC_DESC"),
);

$arTemplateParameters['SHOW_ELEMENTS'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('FHSS_SHOW_ELEMENTS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y'
);

if ($arCurrentValues['SHOW_ELEMENTS'] == 'Y') {

    $arTemplateParameters['ELEMENTS_SORT_BY1'] = Array(
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("FHSS_IBLOCK_DESC_IBORD1"),
        "TYPE" => "LIST",
        "DEFAULT" => "ACTIVE_FROM",
        "VALUES" => $arSortFields,
        "ADDITIONAL_VALUES" => "Y",
    );

    $arTemplateParameters['ELEMENTS_SORT_ORDER1'] = Array(
        "PARENT" => "VISUAL",
        "NAME" => GetMessage("FHSS_IBLOCK_DESC_IBBY1"),
        "TYPE" => "LIST",
        "DEFAULT" => "DESC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );

}



?>