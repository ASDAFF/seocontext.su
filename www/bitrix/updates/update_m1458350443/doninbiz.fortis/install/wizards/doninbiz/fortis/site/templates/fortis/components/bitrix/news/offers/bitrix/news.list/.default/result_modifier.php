<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */


foreach($arResult['ITEMS'] as $iKey => $arItem) {
    $db_props = CIBlockElement::GetProperty(
        $arItem['IBLOCK_ID'],
        $arItem['ID'],
        array(),
        array()
    );

    while($ar_props = $db_props->Fetch()) {
        if (empty($arItem['PROPERTIES'][$ar_props['CODE']]))
            $arItem['PROPERTIES'][$ar_props['CODE']] = $ar_props;

        if ( ! empty($ar_props['VALUE'])) {

            if ( ! is_array($arItem['PROPERTIES'][$ar_props['CODE']]['VALUE']))
                $arItem['PROPERTIES'][$ar_props['CODE']]['VALUE'] = array();

            $arItem['PROPERTIES'][$ar_props['CODE']]['VALUE'][] = $ar_props['VALUE'];
        }
    }

    $arResult['ITEMS'][$iKey] = $arItem;
}

?>