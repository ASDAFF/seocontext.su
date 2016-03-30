<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
    return;

$arTypesEx = CIBlockParameters::GetIBlockTypes(Array("all"=>" "));

$arIBlocks=Array();
$db_iblock = CIBlock::GetList(Array("SORT"=>"ASC"), Array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="all"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
    $arIBlocks[$arRes["ID"]] = $arRes["NAME"];

$arComponentParameters = array(
    "GROUPS" => array(
    ),
    "PARAMETERS" => array(
        "IBLOCK_TYPE" => Array(
            "PARENT" => "BASE",
            "NAME"=>GetMessage("CP_BMS_IBLOCK_TYPE"),
            "TYPE"=>"LIST",
            "VALUES"=>$arTypesEx,
            "DEFAULT"=>"catalog",
            "ADDITIONAL_VALUES"=>"N",
            "REFRESH" => "Y",
        ),
        "IBLOCK_ID" => Array(
            "PARENT" => "BASE",
            "NAME"=>GetMessage("CP_BMS_IBLOCK_ID"),
            "TYPE"=>"LIST",
            "VALUES"=>$arIBlocks,
            "DEFAULT"=>'1',
            "MULTIPLE"=>"N",
            "ADDITIONAL_VALUES"=>"N",
            "REFRESH" => "Y",
        ),
        "DEPTH_LEVEL" => Array(
            "PARENT" => "DATA_SOURCE",
            "NAME" => GetMessage("CP_BMS_DEPTH_LEVEL"),
            "TYPE" => "STRING",
            "DEFAULT" => "1",
        ),
        "CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
    ),
);