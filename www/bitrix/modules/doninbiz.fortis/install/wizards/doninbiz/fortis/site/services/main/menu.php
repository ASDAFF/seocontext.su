<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

	CModule::IncludeModule('fileman');
	$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);

	if($arMenuTypes['left'] && $arMenuTypes['left'] == GetMessage("WIZ_MENU_LEFT_DEFAULT"))
		$arMenuTypes['left'] =  GetMessage("WIZ_MENU_LEFT");

	if( ! $arMenuTypes['leftchild'])
		$arMenuTypes['leftchild'] = GetMessage("WIZ_MENU_LEFT_CHILD");

	if( ! $arMenuTypes['footer'])
		$arMenuTypes['footer'] = GetMessage("WIZ_MENU_FOOTER");

	if( ! $arMenuTypes['footer_block'])
		$arMenuTypes['footer_block'] = GetMessage("WIZ_MENU_FOOTER_BLOCK");
		
	SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
	COption::SetOptionInt("fileman", "num_menu_param", 2, false ,WIZARD_SITE_ID);

?>