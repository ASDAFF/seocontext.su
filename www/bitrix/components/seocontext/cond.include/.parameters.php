<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use Seocontext\Locations\Manager;
use Bitrix\Main\Config\Option;

if (!Loader::includeModule('seocontext.locations')) {
	//ShowError(Loc::getMessage('ACADEMY_D7_MODULE_NOT_INSTALLED'));
	throw new Main\LoaderException(Loc::getMessage('SEOCONTEXT_MODULE_NOT_INSTALLED'));
}

// Loading selected locations
$locations = Manager::getSelectedLocations();
$selectedLocations = array();
foreach($locations as $loc)
{
	$selectedLocations[$loc['CODE']]=$loc['NAME'];
}

// Get content for chosen location
//AddMessage2Log($arCurrentValues);
//AddMessage2Log($_REQUEST);

$siteId = $_REQUEST['src_site'];
$basePath = Option::get('seocontext.locations','base_path');
if (substr(trim($basePath),-1)!='/')
	$basePath .= '/';

$contentDir = $siteId;
if ($arCurrentValues != null && strlen($arCurrentValues['CONTENT_DIR'])>0)
{
	$contentDir = $arCurrentValues['CONTENT_DIR'];
}


$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"CACHE_TIME" => array('DEFAULT'=>36000),

		"CONTENT_DIR" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SEOCONTEXT_CONTENT_DIR").' '.$basePath,
			"TYPE" => "STRING",
			"DEFAULT" => $contentDir
		),

		"ALL_ON_PAGE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SEOCONTEXT_ALL_ON_PAGE"),
			"TYPE" => "CHECKBOX",
		),

		"SITE_ID" => array(
			"HIDDEN" => "Y",
			"NAME" => "SITE_ID",
			"TYPE" => "STRING",
			"DEFAULT" => "s1"
		),

		"CONTENT" => array(
			'PARENT' => 'BASE',
			"NAME" => GetMessage("SEOCONTEXT_CONTENT"),
			"TYPE" => "CUSTOM",
			// todo: autodetect settings.js path
			'JS_FILE' => '/bitrix/components/seocontext/cond.include/settings/settings.js',
			'JS_EVENT' => 'onCondIncludeSettingsCreate',
			// todo: escape $content - replace || with \|\| and \ with \\
			'JS_DATA' => $basePath
		)

	),
);
?>