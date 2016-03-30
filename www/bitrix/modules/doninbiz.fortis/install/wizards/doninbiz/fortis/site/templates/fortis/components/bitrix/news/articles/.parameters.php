<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"DISPLAY_DATE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PICTURE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"DISPLAY_PREVIEW_TEXT" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"USE_SHARE" => Array(
		"NAME" => GetMessage("T_IBLOCK_DESC_NEWS_USE_SHARE"),
		"TYPE" => "CHECKBOX",
		"MULTIPLE" => "N",
		"VALUE" => "Y",
		"DEFAULT" =>"N",
		"REFRESH"=> "Y",
	),
);

$arTemplateParameters['TEXT_GALLERY'] = array(
    'NAME' => GetMessage('CP_BC_TPL2_TEXT_GALLERY'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL2_TEXT_GALLERY_DEFAULT')
);
$arTemplateParameters['TEXT_DOCS'] = array(
    'NAME' => GetMessage('CP_BC_TPL2_TEXT_DOCS'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL2_TEXT_DOCS_DEFAULT')
);

?>