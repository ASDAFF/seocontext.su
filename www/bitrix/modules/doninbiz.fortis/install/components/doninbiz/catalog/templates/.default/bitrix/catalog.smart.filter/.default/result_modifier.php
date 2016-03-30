<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['HIDE_FILTER'] = true;

foreach($arResult['ITEMS'] as $arItem) {
    if ( ! empty($arItem['VALUES'])) {
        if ( ! empty($arItem["VALUES"]["MAX"]) && empty($arItem["VALUES"]["MAX"]["VALUE"])) {
            $arResult['HIDE_FILTER'] = true;
            continue;
        }
        $arResult['HIDE_FILTER'] = false;
        break;
    }
}