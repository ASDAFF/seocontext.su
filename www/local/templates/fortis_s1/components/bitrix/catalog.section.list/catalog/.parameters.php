<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arViewModeList = array(
	'LIST'   => GetMessage('CPT_BCSL_VIEW_MODE_LIST')
);

$arTemplateParameters = array(
	'VIEW_MODE' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CPT_BCSL_VIEW_MODE'),
		'TYPE' => 'LIST',
		'VALUES' => $arViewModeList,
		'MULTIPLE' => 'N',
		'DEFAULT' => 'LINE',
		'REFRESH' => 'Y'
	),
	'SHOW_DESCRIPTION' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('FHC_SHOW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y'
	),
);

?>