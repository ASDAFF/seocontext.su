<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult['URL'] = '/informatsiya/novosti';
if ( ! empty($arParams['IBLOCKS'][0])) {
    $arIblock = GetIBlock($arParams['IBLOCKS'][0]);
    if ( ! empty($arIblock['LIST_PAGE_URL']))
        $arResult['URL'] = $arIblock['LIST_PAGE_URL'];
}

?>