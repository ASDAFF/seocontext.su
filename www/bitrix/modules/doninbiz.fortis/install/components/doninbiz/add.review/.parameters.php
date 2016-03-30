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
            'NAME' => GetMessage('REV_GR_TEXTS')
        ),
        'NOTICE' => array(
            'NAME' => GetMessage('REV_GR_NOTICE')
        ),
        'FIELDS' => array(
            'NAME' => GetMessage('REV_GR_FIELDS')
        ),
        'CAPTCHA' => array(
            'NAME' => GetMessage('REV_GR_CAPTCHA')
        )
	),
	"PARAMETERS" => array(
        "IBLOCK_TYPE"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('REV_PAR_IBLOCK_TYPE'),
            "TYPE" => "LIST",
            "VALUES" => $arTypesEx,
            "DEFAULT" => "fortis_content",
            "REFRESH" => "Y",
        ),
        "IBLOCK"  =>  Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('REV_PAR_IBLOCK'),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => ''
        ),
        "EVENT_MESSAGE_ID" => Array(
            "NAME" => GetMessage('REV_PAR_EVENT_MESSAGE_ID'),
            "TYPE" => "LIST",
            "VALUES" => $arEvent,
            "DEFAULT" => "",
            "MULTIPLE" => "N",
            "COLS" => 25,
            "PARENT" => "NOTICE",
        ),
        'EMAILS' => array(
            'PARENT' => 'NOTICE',
            'NAME' => GetMessage('REV_PAR_EMAILS'),
            'TYPE' => 'STRING',
        ),
        'SUCCESS_TEXT' => array(
            'PARENT' => 'NOTICE',
            'NAME' => GetMessage('REV_PAR_SUCCESS_TEXT'),
            'TYPE' => 'STRING',
            'DEFAULT' => GetMessage('REV_PAR_SUCCESS_TEXT_DEFAULT')
        ),
        'USE_CAPTCHA' => array(
            'PARENT' => 'CAPTCHA',
            'NAME' => GetMessage('REV_PAR_USE_CAPTCHA'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ),
	),
);

$arComponentParameters['PARAMETERS']['VIEW_NAME_LABEL'] = array(
    'PARENT' => 'FIELDS',
    'NAME' => GetMessage('REV_PAR_VIEW_NAME_LABEL'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('REV_PAR_VIEW_NAME_DEFAULT')
);

$arComponentParameters['PARAMETERS']['VIEW_STATUS'] = array(
    'PARENT' => 'FIELDS',
    'NAME' => GetMessage('REV_PAR_VIEW_STATUS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);
if (isset($arCurrentValues['VIEW_STATUS']) && 'Y' == $arCurrentValues['VIEW_STATUS'])
{
    $arComponentParameters['PARAMETERS']['VIEW_STATUS_LABEL'] = array(
        'PARENT' => 'FIELDS',
        'NAME' => GetMessage('REV_PAR_VIEW_STATUS_LABEL'),
        'TYPE' => 'STRING',
        'DEFAULT' => GetMessage('REV_PAR_VIEW_STATUS_DEFAULT')
    );
}

$arComponentParameters['PARAMETERS']['VIEW_SITE'] = array(
    'PARENT' => 'FIELDS',
    'NAME' => GetMessage('REV_PAR_VIEW_SITE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);
if (isset($arCurrentValues['VIEW_SITE']) && 'Y' == $arCurrentValues['VIEW_SITE'])
{
    $arComponentParameters['PARAMETERS']['VIEW_SITE_LABEL'] = array(
        'PARENT' => 'FIELDS',
        'NAME' => GetMessage('REV_PAR_VIEW_SITE_LABEL'),
        'TYPE' => 'STRING',
        'DEFAULT' => GetMessage('REV_PAR_VIEW_SITE_DEFAULT')
    );
}

$arComponentParameters['PARAMETERS']['VIEW_PREVIEW_PICTURE'] = array(
    'PARENT' => 'FIELDS',
    'NAME' => GetMessage('REV_PAR_VIEW_PREVIEW_PICTURE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
    'REFRESH' => 'Y'
);
if (isset($arCurrentValues['VIEW_PREVIEW_PICTURE']) && 'Y' == $arCurrentValues['VIEW_PREVIEW_PICTURE'])
{
    $arComponentParameters['PARAMETERS']['VIEW_PREVIEW_PICTURE_LABEL'] = array(
        'PARENT' => 'FIELDS',
        'NAME' => GetMessage('REV_PAR_VIEW_PREVIEW_PICTURE_LABEL'),
        'TYPE' => 'STRING',
        'DEFAULT' => GetMessage('REV_PAR_VIEW_PREVIEW_PICTURE_DEFAULT')
    );
}

?>