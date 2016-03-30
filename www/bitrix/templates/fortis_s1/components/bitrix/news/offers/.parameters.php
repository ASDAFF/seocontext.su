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
$arTemplateParameters['TEXT_PRODUCTS'] = array(
    'NAME' => GetMessage('CP_BC_TPL2_TEXT_PRODUCTS'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL2_TEXT_PRODUCTS_DEFAULT')
);
$arTemplateParameters['TEXT_SERVICES'] = array(
    'NAME' => GetMessage('CP_BC_TPL2_TEXT_SERVICES'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL2_TEXT_SERVICES_DEFAULT')
);

$arTemplateParameters['PRICE_PREFIX'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_PRICE_PREFIX'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
);
$arTemplateParameters['PRICE_SUFFIX'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_PRICE_SUFFIX'),
    'TYPE' => 'STRING',
    'DEFAULT' => 'ð.'
);
$arTemplateParameters['MESS_NOT_AVAILABLE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_MESS_NOT_AVAILABLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_MESS_NOT_AVAILABLE_DEFAULT')
);

?>