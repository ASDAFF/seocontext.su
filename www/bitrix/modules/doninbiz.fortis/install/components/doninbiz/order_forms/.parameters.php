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

$arEvent = Array();
$dbType = CEventMessage::GetList($by="ID", $order="DESC", $arFilter);
while($arType = $dbType->GetNext())
    $arEvent[$arType["ID"]] = "[".$arType["ID"]."] ".$arType["SUBJECT"];

$arComponentParameters = array(
	"GROUPS" => array(
        'TEXTS' => array(
            'NAME' => GetMessage('ORF_GR_TEXTS')
        ),
        'NOTICE' => array(
            'NAME' => GetMessage('ORF_GR_NOTICE')
        ),
        'CAPTCHA' => array(
            'NAME' => GetMessage('ORF_GR_CAPTCHA')
        ),
        'YA' => array(
            'NAME' => GetMessage('ORF_GR_YA')
        )
	),
	"PARAMETERS" => array(
        "IBLOCK_TYPE"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('ORF_PAR_IBLOCK_TYPE'),
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "fortis_orders",
            "REFRESH" => "Y",
        ),
        "IBLOCK"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('ORF_PAR_IBLOCK'),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => 'workers'
        ),
        "EVENT_MESSAGE_ID" => Array(
            "NAME" => GetMessage('ORF_PAR_EVENT_MESSAGE_ID'),
            "TYPE" => "LIST",
            "VALUES" => $arEvent,
            "DEFAULT" => "",
            "MULTIPLE" => "N",
            "COLS" => 25,
            "PARENT" => "NOTICE",
        ),
        'EMAILS' => array(
            'PARENT' => 'NOTICE',
            'NAME' => GetMessage('ORF_PAR_EMAILS'),
            'TYPE' => 'STRING',
        ),
        'SUCCESS_TEXT' => array(
            'PARENT' => 'NOTICE',
            'NAME' => GetMessage('ORF_PAR_SUCCESS_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('ORF_PAR_SUCCESS_TEXT_DEFAULT')
        ),
        'FORM_NAME' => array(
            'PARENT' => 'TEXTS',
            'NAME' => GetMessage('ORF_PAR_FORM_NAME'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('ORF_PAR_FORM_NAME_DEFAULT')
        ),
        'FORM_TEXT' => array(
            'PARENT' => 'TEXTS',
            'NAME' => GetMessage('ORF_PAR_FORM_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('ORF_PAR_FORM_TEXT_DEFAULT')
        ),
        'SUBMIT_TEXT' => array(
            'PARENT' => 'TEXTS',
            'NAME' => GetMessage('ORF_PAR_SUBMIT_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('ORF_PAR_SUBMIT_TEXT_DEFAULT')
        ),
        'YA_COUNTER' => array(
            'PARENT' => 'YA',
            'NAME' => GetMessage('ORF_PAR_YA_COUNTER'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ),
        'YA_GOAL' => array(
            'PARENT' => 'YA',
            'NAME' => GetMessage('ORF_PAR_YA_GOAL'),
            'TYPE' => 'STRING',
            'DEFAULT' => ''
        ),
        'USE_CAPTCHA' => array(
            'PARENT' => 'CAPTCHA',
            'NAME' => GetMessage('ORF_PAR_USE_CAPTCHA'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ),
	),
);
?>