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

// Сортировка разделов
// Первая
$arParams["SECTION_SORT_BY1"] = trim($arParams["SECTION_SORT_BY1"]);
if(strlen($arParams["SECTION_SORT_BY1"])<=0)
	$arParams["SECTION_SORT_BY1"] = "ACTIVE_FROM";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SECTION_SORT_ORDER1"]))
	$arParams["SECTION_SORT_ORDER1"]="DESC";
// Вторая
if(strlen($arParams["SECTION_SORT_BY2"])<=0)
	$arParams["SECTION_SORT_BY2"] = "SORT";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SECTION_SORT_ORDER2"]))
	$arParams["SECTION_SORT_ORDER2"]="ASC";

// Сортировка элементов
// Первая
$arParams["ELEMENTS_SORT_BY1"] = trim($arParams["ELEMENTS_SORT_BY1"]);
if(strlen($arParams["ELEMENTS_SORT_BY1"])<=0)
	$arParams["ELEMENTS_SORT_BY1"] = "ACTIVE_FROM";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENTS_SORT_ORDER1"]))
	$arParams["ELEMENTS_SORT_ORDER1"]="DESC";
// Вторая
if(strlen($arParams["ELEMENTS_SORT_BY2"])<=0)
	$arParams["ELEMENTS_SORT_BY2"] = "SORT";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["ELEMENTS_SORT_ORDER2"]))
	$arParams["ELEMENTS_SORT_ORDER2"]="ASC";

if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))) {
    if (!CModule::IncludeModule("iblock")) {
        $this->AbortResultCache();
        ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
        return;
    }
    $arSelect = array_merge($arParams["FIELD_CODE"], array(
        "ID",
        "IBLOCK_ID",
        "ACTIVE_FROM",
        "DETAIL_PAGE_URL",
        "NAME",
    ));
    $arFilter = array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK"],
        "ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y",
        "CHECK_PERMISSIONS" => "Y",
    );
    // Сортировка разделов
    $arOrderSection = array(
        $arParams["SECTION_SORT_BY1"] => $arParams["SECTION_SORT_ORDER1"],
        $arParams["SECTION_SORT_BY2"] => $arParams["SECTION_SORT_ORDER2"],
    );
    /*if (!array_key_exists("ID", $arOrderSection))
        $arOrderSection["ID"] = "DESC";*/
    // Сортировка элементов
    $arOrderElements = array(
        $arParams["ELEMENTS_SORT_BY1"] => $arParams["ELEMENTS_SORT_ORDER1"],
        $arParams["ELEMENTS_SORT_BY2"] => $arParams["ELEMENTS_SORT_ORDER2"],
    );
    /*if (!array_key_exists("ID", $arOrderElements))
        $arOrderElements["ID"] = "DESC";*/

    // Получаем разделы
    $oSections = CIBlockSection::GetList(
        $arOrderSection,
        array('IBLOCK_ID' => $arParams["IBLOCK"], 'GLOBAL_ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1),
        false,
        array('ID', 'NAME', 'DESCRIPTION', 'DESCRIPTION_TYPE', 'PICTURE', 'DETAIL_PICTURE')
    );

    $arResult = array(
        "SECTIONS" => array(),
    );
    // Записи с разделами
    while($aSection = $oSections->GetNext()) {

        $arButtonsSection = CIBlock::GetPanelButtons(
            $arParams["IBLOCK"],
            0,
            $aSection['ID'],
            array("SESSID" => false)
        );
        $aSection["EDIT_LINK"]   = $arButtonsSection["edit"]["edit_section"]["ACTION_URL"];
        $aSection["DELETE_LINK"] = $arButtonsSection["edit"]["delete_section"]["ACTION_URL"];

        $arResult['SECTIONS'][$aSection['ID']] = $aSection;

        // Есть раздел, получаем его элементы
        if ( ! empty($aSection) && ! empty($aSection['ID'])) {
            $arFilter['SECTION_ID'] = $aSection['ID'];
            // Отобразить все записи подраздела
            if ($arParams['INCLUDE_SUBSECTIONS'] == 'Y') {
                $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
            }

            $oItems = CIBlockElement::GetList($arOrderElements, $arFilter, false, false, $arSelect);
            $oItems->SetUrlTemplates($arParams["DETAIL_URL"], "", $arParams["IBLOCK_URL"]);

            while($aItem = $oItems->GetNext()) {

                $arButtonsElement = CIBlock::GetPanelButtons(
                    $arParams["IBLOCK"],
                    $aItem['ID'],
                    0,
                    array("SESSID" => false)
                );
                $aItem["EDIT_LINK"]   = $arButtonsElement["edit"]["edit_element"]["ACTION_URL"];
                $aItem["DELETE_LINK"] = $arButtonsElement["edit"]["delete_element"]["ACTION_URL"];

                $arResult['SECTIONS'][$aSection['ID']]['ITEMS'][] = $aItem;
            }
        }
    }
    // Записи без разделов
    $arFilter['SECTION_ID'] = false;
    $oItems = CIBlockElement::GetList($arOrderElements, $arFilter, false, false, $arSelect);
    while($aItem = $oItems->GetNext()) {

        $arButtonsElement = CIBlock::GetPanelButtons(
            $arParams["IBLOCK"],
            $aItem['ID'],
            0,
            array("SESSID" => false)
        );
        $aItem["EDIT_LINK"]   = $arButtonsElement["edit"]["edit_element"]["ACTION_URL"];
        $aItem["DELETE_LINK"] = $arButtonsElement["edit"]["delete_element"]["ACTION_URL"];

        $arResult['ITEMS'][] = $aItem;
    }

    $this->SetResultCacheKeys(array(
        'SECTIONS',
        'ITEMS'
    ));

    $this->IncludeComponentTemplate();
}

if(
    $arParams["IBLOCK"] > 0
    && $USER->IsAuthorized()
    && $APPLICATION->GetShowIncludeAreas()
    && CModule::IncludeModule("iblock")
)
{
    $arButtons = CIBlock::GetPanelButtons($arParams["IBLOCK"], 0, 0, array("SECTION_BUTTONS" => true));
    $this->AddIncludeAreaIcons(CIBlock::GetComponentMenu($APPLICATION->GetPublicShowMode(), $arButtons));
}

?>
