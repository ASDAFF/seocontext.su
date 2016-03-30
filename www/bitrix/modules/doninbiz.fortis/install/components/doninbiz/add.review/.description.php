<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage('REV_DESC_NAME'),
	"DESCRIPTION" => GetMessage('REV_DESC_NAME'),
	"ICON" => "/images/icon.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "DoninBiz", // for example "my_project"
		"CHILD" => array(
			"ID" => "order", // for example "my_project:services"
			"NAME" => GetMessage('REV_DESC_NAME2'),  // for example "Services"
		),
	),
	"COMPLEX" => "N",
);

?>