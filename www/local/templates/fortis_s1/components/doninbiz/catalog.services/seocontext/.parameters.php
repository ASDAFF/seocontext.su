<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if (!Loader::includeModule('iblock'))
	return;
$boolCatalog = Loader::includeModule('catalog');

$arSKU = false;
$boolSKU = false;
if ($boolCatalog && (isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID']) > 0)
{
	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arCurrentValues['IBLOCK_ID']);
	$boolSKU = !empty($arSKU) && is_array($arSKU);
}

if (isset($arCurrentValues['SECTIONS_VIEW_MODE']) && 'TILE' == $arCurrentValues['SECTIONS_VIEW_MODE'])
{
	$arTemplateParameters['SECTIONS_HIDE_SECTION_NAME'] = array(
		'PARENT' => 'SECTIONS_SETTINGS',
		'NAME' => GetMessage('CPT_BC_SECTIONS_HIDE_SECTION_NAME'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N'
	);
}

$displayPreviewTextMode = array(
	'H' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_HIDE'),
	'E' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_EMPTY_DETAIL'),
	'S' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE_SHOW')
);

$arTemplateParameters['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_PREVIEW_TEXT_MODE'),
	'TYPE' => 'LIST',
	'VALUES' => $displayPreviewTextMode,
	'DEFAULT' => 'E'
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

$arTemplateParameters['TEXT_GALLERY'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_TEXT_GALLERY'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_TEXT_GALLERY_DEFAULT')
);
$arTemplateParameters['TEXT_DOCS'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_TEXT_DOCS'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_TEXT_DOCS_DEFAULT')
);
$arTemplateParameters['TEXT_PRODUCTS'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_TEXT_PRODUCTS'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_TEXT_PRODUCTS_DEFAULT')
);
$arTemplateParameters['TEXT_TEAM'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_TEXT_TEAM'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_TEXT_TEAM_DEFAULT')
);
$arTemplateParameters['TEXT_PORTFOLIO'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_TEXT_PORTFOLIO'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_TEXT_PORTFOLIO_DEFAULT')
);
$arTemplateParameters['TEXT_SERVICES'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => GetMessage('CP_BC_TPL_TEXT_SERVICES'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('CP_BC_TPL_TEXT_SERVICES_DEFAULT')
);


?>