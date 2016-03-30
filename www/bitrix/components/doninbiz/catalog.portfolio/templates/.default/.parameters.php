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

if (isset($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0)
{
	$arAllPropList = array();
	$arFilePropList = array(
		'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
	);
	$arListPropList = array(
		'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
	);
	$arHighloadPropList = array(
		'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
	);
	$rsProps = CIBlockProperty::GetList(
		array('SORT' => 'ASC', 'ID' => 'ASC'),
		array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
	);
	while ($arProp = $rsProps->Fetch())
	{
		$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
		if ('' == $arProp['CODE'])
			$arProp['CODE'] = $arProp['ID'];
		$arAllPropList[$arProp['CODE']] = $strPropName;
		if ('F' == $arProp['PROPERTY_TYPE'])
			$arFilePropList[$arProp['CODE']] = $strPropName;
		if ('L' == $arProp['PROPERTY_TYPE'])
			$arListPropList[$arProp['CODE']] = $strPropName;
		if ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			$arHighloadPropList[$arProp['CODE']] = $strPropName;
	}

	$arTemplateParameters['ADD_PICT_PROP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_ADD_PICT_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '-',
		'VALUES' => $arFilePropList
	);
	$arTemplateParameters['LABEL_PROP'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BC_TPL_LABEL_PROP'),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'N',
		'ADDITIONAL_VALUES' => 'N',
		'REFRESH' => 'N',
		'DEFAULT' => '-',
		'VALUES' => $arListPropList
	);

	if ($boolSKU)
	{
		$arDisplayModeList = array(
			'N' => GetMessage('CP_BC_TPL_DML_SIMPLE'),
			'Y' => GetMessage('CP_BC_TPL_DML_EXT')
		);
		$arTemplateParameters['PRODUCT_DISPLAY_MODE'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_PRODUCT_DISPLAY_MODE'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'Y',
			'DEFAULT' => 'N',
			'VALUES' => $arDisplayModeList
		);
		$arAllOfferPropList = array();
		$arFileOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$arTreeOfferPropList = array(
			'-' => GetMessage('CP_BC_TPL_PROP_EMPTY')
		);
		$rsProps = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array('IBLOCK_ID' => $arSKU['IBLOCK_ID'], 'ACTIVE' => 'Y')
		);
		while ($arProp = $rsProps->Fetch())
		{
			if ($arProp['ID'] == $arSKU['SKU_PROPERTY_ID'])
				continue;
			$arProp['USER_TYPE'] = (string)$arProp['USER_TYPE'];
			$strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];
			if ('' == $arProp['CODE'])
				$arProp['CODE'] = $arProp['ID'];
			$arAllOfferPropList[$arProp['CODE']] = $strPropName;
			if ('F' == $arProp['PROPERTY_TYPE'])
				$arFileOfferPropList[$arProp['CODE']] = $strPropName;
			if ('N' != $arProp['MULTIPLE'])
				continue;
			if (
				'L' == $arProp['PROPERTY_TYPE']
				|| 'E' == $arProp['PROPERTY_TYPE']
				|| ('S' == $arProp['PROPERTY_TYPE'] && 'directory' == $arProp['USER_TYPE'] && CIBlockPriceTools::checkPropDirectory($arProp))
			)
				$arTreeOfferPropList[$arProp['CODE']] = $strPropName;
		}
		$arTemplateParameters['OFFER_ADD_PICT_PROP'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_OFFER_ADD_PICT_PROP'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'N',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arFileOfferPropList
		);
		$arTemplateParameters['OFFER_TREE_PROPS'] = array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('CP_BC_TPL_OFFER_TREE_PROPS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'ADDITIONAL_VALUES' => 'N',
			'REFRESH' => 'N',
			'DEFAULT' => '-',
			'VALUES' => $arTreeOfferPropList
		);
	}
}

$arTemplateParameters['DETAIL_DISPLAY_NAME'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_DISPLAY_NAME'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);

$arTemplateParameters['DETAIL_ADD_DETAIL_TO_SLIDER'] = array(
	'PARENT' => 'DETAIL_SETTINGS',
	'NAME' => GetMessage('CP_BC_TPL_DETAIL_ADD_DETAIL_TO_SLIDER'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N'
);

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

$arTemplateParameters['LIST_STYLE'] = array(
    'PARENT' => 'LIST_SETTINGS',
    'NAME' => GetMessage('FP_LIST_STYLE_NAME'),
    'TYPE' => 'LIST',
    'VALUES' => array(
        'all'  => GetMessage('FP_LIST_STYLE_ALL'),
        'grid' => GetMessage('FP_LIST_STYLE_GRID'),
        'list' => GetMessage('FP_LIST_STYLE_LIST')
    ),
    'DEFAULT' => 'all',
    'REFRESH' => 'Y',
);
if ($arCurrentValues['LIST_STYLE'] == 'all') {
    $arTemplateParameters['LIST_STYLE_DEFAULT'] = array(
        'PARENT' => 'LIST_SETTINGS',
        'NAME' => GetMessage('FP_LIST_STYLE_DEFAULT_NAME'),
        'TYPE' => 'LIST',
        'VALUES' => array(
            'grid' => GetMessage('FP_LIST_STYLE_GRID'),
            'list' => GetMessage('FP_LIST_STYLE_LIST')
        ),
        'DEFAULT' => 'grid'
    );
}

$arTemplateParameters['USE_SOCIAL_BAR'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_TPL_USE_SOCIAL_BAR'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
);

$arTemplateParameters['TEXT_ORDER_SERVICE'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_ORDER_SERVICE'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_ORDER_SERVICE_DEFAULT')
);
$arTemplateParameters['TEXT_ORDER_QUESTION'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_ORDER_QUESTION'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_ORDER_QUESTION_DEFAULT')
);

$arTemplateParameters['TEXT_TEAM'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_TEAM'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_TEAM_DEFAULT')
);
$arTemplateParameters['TEXT_SERVICES'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_SERVICES'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_SERVICES_DEFAULT')
);
$arTemplateParameters['TEXT_PORTFOLIO'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_PORTFOLIO'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_PORTFOLIO_DEFAULT')
);

$arTemplateParameters['TEXT_PROPERTIES'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_PROPERTIES'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_PROPERTIES_DEFAULT')
);
$arTemplateParameters['TEXT_DESCRIPTION'] = array(
    'PARENT' => 'DETAIL_SETTINGS',
    'NAME' => GetMessage('FP_BC_TPL_TEXT_DESCRIPTION'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('FP_BC_TPL_TEXT_DESCRIPTION_DEFAULT')
);

?>