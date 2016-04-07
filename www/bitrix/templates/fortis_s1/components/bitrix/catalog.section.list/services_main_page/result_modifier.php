<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arResult['ELEMENTS'] = array();
if (0 < $arResult['SECTIONS_COUNT'] && $arParams['SHOW_ELEMENTS'] == 'Y') {

    $arParams["ELEMENTS_SORT_BY1"] = trim($arParams["ELEMENTS_SORT_BY1"]);
    if(strlen($arParams["ELEMENTS_SORT_BY1"]) <= 0)
        $arParams["ELEMENTS_SORT_BY1"] = "ACTIVE_FROM";

    if( ! preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENTS_SORT_ORDER1"]))
        $arParams["ELEMENTS_SORT_ORDER1"] = "DESC";

    $arOrderElements = array(
        $arParams["ELEMENTS_SORT_BY1"] => $arParams["ELEMENTS_SORT_ORDER1"],
        $arParams["ELEMENTS_SORT_BY2"] => $arParams["ELEMENTS_SORT_ORDER2"],
    );

    $arFilter = array(
        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y",
        "CHECK_PERMISSIONS" => "Y",
    );

    $arFilter['SECTION_ID'] = array();
    foreach($arResult['SECTIONS'] as $iS => $aSection) {
        /*if ($aSection['DEPTH_LEVEL'] > 1) {
            unset($arResult['SECTIONS'][$iS]);
        }*/

        $arFilter['SECTION_ID'][] = $aSection['ID'];
    }

    $oElements = CIBlockElement::GetList( $arOrderElements, $arFilter, false, false, array('ID', 'IBLOCK_SECTION_ID', 'NAME', 'DETAIL_PAGE_URL') );


    while($arElement = $oElements->GetNext()) {
        $arResult['ELEMENTS'][$arElement['IBLOCK_SECTION_ID']][] = $arElement;
    }

}
?>