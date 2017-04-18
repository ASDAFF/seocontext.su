<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

$db_props = CIBlockElement::GetProperty(
    $arResult['IBLOCK_ID'],
    $arResult['ID'],
    array(),
    array()
);

while($ar_props = $db_props->Fetch()) {
    if (empty($arResult['PROPERTIES'][$ar_props['CODE']]))
        $arResult['PROPERTIES'][$ar_props['CODE']] = $ar_props;

    if ( ! empty($ar_props['VALUE'])) {

        if ( ! is_array($arResult['PROPERTIES'][$ar_props['CODE']]['VALUE']))
            $arResult['PROPERTIES'][$ar_props['CODE']]['VALUE'] = array();

        $arResult['PROPERTIES'][$ar_props['CODE']]['VALUE'][] = $ar_props['VALUE'];
    }
}

$arResult['DOCS'] = array();
if ( ! empty($arResult['PROPERTIES']['DOCS']['VALUE'])) {
    foreach($arResult['PROPERTIES']['DOCS']['VALUE'] as $iKeyDoc => $arDoc) {
        $arDocFile = CFile::GetFileArray($arDoc);
        $arResult['DOCS'][$iKeyDoc] = $arDocFile;
        $arResult['DOCS'][$iKeyDoc]['EXT'] = substr(strrchr($arDocFile['FILE_NAME'], '.'), 1);
        $arResult['DOCS'][$iKeyDoc]['VIEW_SIZE'] = CFile::FormatSize($arDocFile['FILE_SIZE']);
    }
}

?>