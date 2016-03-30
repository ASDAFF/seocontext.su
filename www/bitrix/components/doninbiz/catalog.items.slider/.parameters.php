<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arTypesEx = CIBlockParameters::GetIBlockTypes();

$arIBlocks = Array();
$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
{
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];
}

$arSorts = Array(
	"ASC" => GetMessage("HCIS_IBLOCK_DESC_ASC"),
	"DESC" => GetMessage("HCIS_IBLOCK_DESC_DESC"),
);

$arSortFields = Array(
		"ID"          => GetMessage("HCIS_IBLOCK_DESC_FID"),
		"NAME"        => GetMessage("HCIS_IBLOCK_DESC_FNAME"),
		"ACTIVE_FROM" => GetMessage("HCIS_IBLOCK_DESC_FACT"),
		"SORT"        => GetMessage("HCIS_IBLOCK_DESC_FSORT"),
		"TIMESTAMP_X" => GetMessage("HCIS_IBLOCK_DESC_FTSAMP")
);

// Стикеры
$oEnums = CIBlockProperty::GetPropertyEnum('OFFERS', Array('SORT' => 'ASC'), array("IBLOCK_ID" => $arCurrentValues['IBLOCK']));
$arEnums = array();
while ($arEnum = $oEnums->GetNext()) {
    $arEnums[$arEnum['XML_ID']] = $arEnum;
}

//var_dump($arEnums);

$arComponentParameters = array(
	"GROUPS" => array(
        'ELEMENTS' => array(
            'NAME' => GetMessage("HCIS_IBLOCK_ELEMENTS_TYPE")
        )
	),
	"PARAMETERS"  =>  array(
		"IBLOCK_TYPE"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HCIS_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "fortis_content",
			"REFRESH" => "Y",
		),
		"IBLOCK"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("HCIS_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"MULTIPLE" => "N",
            "REFRESH" => "Y",
		),
        "DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
            "DETAIL",
            "DETAIL_URL",
            GetMessage("HCIS_IBLOCK_DESC_DETAIL_PAGE_URL"),
            "",
            "URL_TEMPLATES"
        ),
		"FIELD_CODE" => CIBlockParameters::GetFieldCode(GetMessage("HCIS_BNL_FIELD_CODE"), "DATA_SOURCE"),
		"CACHE_TIME"  =>  Array("DEFAULT"=>300),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("HCIS_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);

$arComponentParameters['PARAMETERS']['PRICE_PREFIX'] = array(
    'PARENT' => 'ELEMENTS',
    'NAME' => GetMessage('HCIS_TPL_PRICE_PREFIX'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
);
$arComponentParameters['PARAMETERS']['PRICE_SUFFIX'] = array(
    'PARENT' => 'ELEMENTS',
    'NAME' => GetMessage('HCIS_TPL_PRICE_SUFFIX'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('HCIS_PRICE_SUFFIX_DEFAULT')
);
$arComponentParameters['PARAMETERS']['MESS_NOT_AVAILABLE'] = array(
    'PARENT' => 'ELEMENTS',
    'NAME' => GetMessage('HCIS_TPL_MESS_NOT_AVAILABLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('HCIS_TPL_MESS_NOT_AVAILABLE_DEFAULT')
);

foreach($arEnums as $arSticker) {
    // Группы
    $arComponentParameters['GROUPS'][$arSticker['XML_ID']] = array('NAME' => $arSticker['VALUE']);

    // Параметры
    $arComponentParameters['PARAMETERS']['USE_' . $arSticker['XML_ID']] = array(
        "PARENT" => $arSticker['XML_ID'],
        "NAME" => GetMessage("HCIS_BNL_USE_TAB"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
        "REFRESH" => "Y",
    );

    if ($arCurrentValues['USE_' . $arSticker['XML_ID']] != 'Y')
        continue;

    $arComponentParameters['PARAMETERS']['LIMIT_' . $arSticker['XML_ID']] = array(
        'PARENT' => $arSticker['XML_ID'],
        'NAME' => GetMessage('HCIS_LIMIT_ELEMENTS'),
        'TYPE' => 'STRING',
        'DEFAULT' => 20
    );

    $arComponentParameters['PARAMETERS']['ELEMENTS_SORT_BY1_' . $arSticker['XML_ID']] = array(
        "PARENT" => $arSticker['XML_ID'],
        "NAME" => GetMessage("HCIS_IBLOCK_DESC_IBORD1"),
        "TYPE" => "LIST",
        "DEFAULT" => "ACTIVE_FROM",
        "VALUES" => $arSortFields,
        "ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters['PARAMETERS']['ELEMENTS_SORT_ORDER1_' . $arSticker['XML_ID']] = array(
        "PARENT" => $arSticker['XML_ID'],
        "NAME" => GetMessage("HCIS_IBLOCK_DESC_IBBY1"),
        "TYPE" => "LIST",
        "DEFAULT" => "DESC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters['PARAMETERS']['ELEMENTS_SORT_BY2_' . $arSticker['XML_ID']] = array(
        "PARENT" => $arSticker['XML_ID'],
        "NAME" => GetMessage("HCIS_IBLOCK_DESC_IBORD2"),
        "TYPE" => "LIST",
        "DEFAULT" => "ACTIVE_FROM",
        "VALUES" => $arSortFields,
        "ADDITIONAL_VALUES" => "Y",
    );
    $arComponentParameters['PARAMETERS']['ELEMENTS_SORT_ORDER2_' . $arSticker['XML_ID']] = array(
        "PARENT" => $arSticker['XML_ID'],
        "NAME" => GetMessage("HCIS_IBLOCK_DESC_IBBY2"),
        "TYPE" => "LIST",
        "DEFAULT" => "DESC",
        "VALUES" => $arSorts,
        "ADDITIONAL_VALUES" => "Y",
    );
}

?>
