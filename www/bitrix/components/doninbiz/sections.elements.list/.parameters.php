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
	"ASC" => GetMessage("T_IBLOCK_DESC_ASC"),
	"DESC" => GetMessage("T_IBLOCK_DESC_DESC"),
);

$arSortFields = Array(
		"ID"          => GetMessage("T_IBLOCK_DESC_FID"),
		"NAME"        => GetMessage("T_IBLOCK_DESC_FNAME"),
		"ACTIVE_FROM" => GetMessage("T_IBLOCK_DESC_FACT"),
		"SORT"        => GetMessage("T_IBLOCK_DESC_FSORT"),
		"TIMESTAMP_X" => GetMessage("T_IBLOCK_DESC_FTSAMP")
);

$arComponentParameters = array(
	"GROUPS" => array(
        'SECTION' => array(
            'NAME' => GetMessage("T_IBLOCK_SECTION_TYPE")
        ),
        'ELEMENTS' => array(
            'NAME' => GetMessage("T_IBLOCK_ELEMENTS_TYPE")
        )
	),
	"PARAMETERS"  =>  array(
		"IBLOCK_TYPE"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $arTypesEx,
			"DEFAULT" => "news",
			"REFRESH" => "Y",
		),
		"IBLOCK"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arIBlocks,
			"DEFAULT" => '',
			"MULTIPLE" => "N",
		),
        "DETAIL_URL" => CIBlockParameters::GetPathTemplateParam(
            "DETAIL",
            "DETAIL_URL",
            GetMessage("T_IBLOCK_DESC_DETAIL_PAGE_URL"),
            "",
            "URL_TEMPLATES"
        ),
		"FIELD_CODE" => CIBlockParameters::GetFieldCode(GetMessage("CP_BNL_FIELD_CODE"), "DATA_SOURCE"),
		"SECTION_SORT_BY1"  =>  Array(
			"PARENT" => "SECTION",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ACTIVE_FROM",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SECTION_SORT_ORDER1"  =>  Array(
			"PARENT" => "SECTION",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SECTION_SORT_BY2"  =>  Array(
			"PARENT" => "SECTION",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD2"),
			"TYPE" => "LIST",
			"DEFAULT" => "SORT",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SECTION_SORT_ORDER2"  =>  Array(
			"PARENT" => "SECTION",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"ELEMENTS_SORT_BY1"  =>  Array(
			"PARENT" => "ELEMENTS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ACTIVE_FROM",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"ELEMENTS_SORT_ORDER1"  =>  Array(
			"PARENT" => "ELEMENTS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"ELEMENTS_SORT_BY2"  =>  Array(
			"PARENT" => "ELEMENTS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD2"),
			"TYPE" => "LIST",
			"DEFAULT" => "SORT",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"ELEMENTS_SORT_ORDER2"  =>  Array(
			"PARENT" => "ELEMENTS",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>300),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);

if ($arCurrentValues['HOME_PORTFOLIO'] == 'Y') {

    $arComponentParameters["PARAMETERS"]["COLUMNS_COUNT"] = Array(
        "NAME" => GetMessage("FFHP_COLUMNS_COUNT"),
        "TYPE" => "LIST",
        "VALUES" => array(1 => 1, 2 => 2, 3 => 3, 4 => 4),
        "DEFAULT" => "3",
    );

    $arComponentParameters["PARAMETERS"]["SHOW_DESC"] = Array(
        "NAME" => GetMessage("FFHP_SHOW_DESC"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    );

    $arComponentParameters["PARAMETERS"]["SHOW_BUTTON"] = Array(
        "NAME" => GetMessage("FFHP_SHOW_BUTTON"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    );

}

?>
