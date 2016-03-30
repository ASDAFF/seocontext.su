<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

$arResult['ALL_ITEMS'] = array();

foreach($arResult['SECTIONS'] as $iS => $arSec) {
    foreach($arSec['ITEMS'] as $iI => $arItem) {

        if ($arItem['PROPERTY_VIEW_HOME_VALUE'] != 'Y') {
            unset($arResult['SECTIONS'][$iS]['ITEMS'][$iI]);
            continue;
        }

        $arResult['ALL_ITEMS'][] = array_merge(array('SECTION_ID' => $arSec['ID']), $arItem);
    }
}

?>