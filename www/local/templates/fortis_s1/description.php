<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arTemplate = Array(
	'NAME' => GetMessage('T_FORTIS_NAME'),
	'DESCRIPTION' => GetMessage('T_FORTIS_DESC')
);
?>