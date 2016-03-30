<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if( ! CModule::IncludeModule("iblock")) return;

if ( ! function_exists('formatMoney')) {
    function formatMoney($amount = null) {
        if ( ! $amount)
            return;

        $amount = number_format($amount, 2, '.', ' ');

        $amount_arr = explode('.', $amount);
        if (!intval($amount_arr[1]))
            $amount = str_replace('.' . $amount_arr[1], '', $amount);

        return $amount;
    }
}


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 300;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
if(strlen($arParams["IBLOCK_TYPE"])<=0)
	$arParams["IBLOCK_TYPE"] = "fortis_content";
if($arParams["IBLOCK_TYPE"]=="-")
	$arParams["IBLOCK_TYPE"] = "";

if (empty($arParams["IBLOCK"])) {
    $this->AbortResultCache();
    ShowError(GetMessage('IBLOCK_EMPTY'));
    return;
}

if(!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
foreach($arParams["FIELD_CODE"] as $key => $val)
	if(!$val)
		unset($arParams["FIELD_CODE"][$key]);

// Стикеры
$oEnums = CIBlockProperty::GetPropertyEnum('OFFERS', Array('SORT' => 'ASC'), array("IBLOCK_ID" => $arParams["IBLOCK"]));
$arEnums = array();
while ($arEnum = $oEnums->GetNext()) {
    $arEnums[$arEnum['XML_ID']] = $arEnum;
}

if (empty($arEnums))
    return;

foreach($arEnums as $sTypeSticker => $arSticker) {

    // Сортировка элементов
    // Первая
    $arParams["ELEMENTS_SORT_BY1_" . $sTypeSticker] = trim($arParams["ELEMENTS_SORT_BY1_" . $sTypeSticker]);
    if(strlen($arParams["ELEMENTS_SORT_BY1_" . $sTypeSticker])<=0)
        $arParams["ELEMENTS_SORT_BY1_" . $sTypeSticker] = "ACTIVE_FROM";
    if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENTS_SORT_ORDER1_" . $sTypeSticker]))
        $arParams["ELEMENTS_SORT_ORDER1_" . $sTypeSticker]="DESC";
    // Вторая
    if(strlen($arParams["ELEMENTS_SORT_BY2_" . $sTypeSticker])<=0)
        $arParams["ELEMENTS_SORT_BY2_" . $sTypeSticker] = "SORT";
    if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENTS_SORT_ORDER2_" . $sTypeSticker]))
        $arParams["ELEMENTS_SORT_ORDER2_" . $sTypeSticker]="ASC";


}

if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))) {
    if (!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }
    // выбираем
    $arSelect = array_merge($arParams["FIELD_CODE"], array(
        "ID",
        "IBLOCK_ID",
        "ACTIVE_FROM",
        "DETAIL_PAGE_URL",
        "PREVIEW_PICTURE",
        "NAME",
    ));
    // фильтруем
    $arFilter = array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK"],
        "ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y",
        "CHECK_PERMISSIONS" => "Y",
    );

    foreach($arEnums as $sTypeSticker => $arSticker) {

        $arResult[$sTypeSticker]['INFO'] = $arSticker;

        // сортируем
        $arOrderElements = array(
            $arParams["ELEMENTS_SORT_BY1_" . $sTypeSticker] => $arParams["ELEMENTS_SORT_ORDER1_" . $sTypeSticker],
            $arParams["ELEMENTS_SORT_BY2_" . $sTypeSticker] => $arParams["ELEMENTS_SORT_ORDER2_" . $sTypeSticker],
        );

        $arFilter['PROPERTY_OFFERS_VALUE'] = $arSticker['VALUE'];

        $arLimit = array('nTopCount' => ( is_numeric($arParams["LIMIT_" . $sTypeSticker]) ? $arParams["LIMIT_" . $sTypeSticker] : 20 ) );

        $oItems = CIBlockElement::GetList($arOrderElements, $arFilter, false, $arLimit, $arSelect);
        $oItems->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);

        while($oItem = $oItems->GetNextElement()) {

            $aItem = $oItem->GetFields();
            $aItem['PROPERTIES'] = $oItem->GetProperties();

            $arButtonsElement = CIBlock::GetPanelButtons(
                $arParams["IBLOCK"],
                $aItem['ID'],
                0,
                array("SESSID" => false)
            );
            $aItem["EDIT_LINK"]   = $arButtonsElement["edit"]["edit_element"]["ACTION_URL"];
            $aItem["DELETE_LINK"] = $arButtonsElement["edit"]["delete_element"]["ACTION_URL"];

            $arResult[$sTypeSticker]['ITEMS'][] = $aItem;
        }
    }

    $this->SetResultCacheKeys(array(
        array_keys($arEnums)
    ));

    $this->IncludeComponentTemplate();
}

if(
    $arParams["IBLOCK"] > 0
    && $USER->IsAuthorized()
    && $APPLICATION->GetShowIncludeAreas()
    && CModule::IncludeModule("iblock")
) {
    $arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK"], 0, 0, array("SECTION_BUTTONS" => false));
    $this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
}


?>
