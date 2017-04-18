<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arItems = array();
foreach($arResult["ITEMS"] as $arItem) {

    if (empty($arItems[$arItem['ID']]))
        $arItems[$arItem['ID']] = $arItem;

    if ($arItem['PROPERTY_IMAGES_VALUE']) {
        $arItems[$arItem['ID']]['IMAGES'][$arItem['PROPERTY_IMAGES_VALUE']]['ORIG']  = CFile::GetFileArray($arItem['PROPERTY_IMAGES_VALUE']);
        $arItems[$arItem['ID']]['IMAGES'][$arItem['PROPERTY_IMAGES_VALUE']]['THUMB'] = CFile::ResizeImageGet($arItem['PROPERTY_IMAGES_VALUE'], array("width" => 115, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
    }
}
$arResult["ITEMS"] = $arItems;

?>