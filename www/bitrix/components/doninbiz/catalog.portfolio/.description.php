<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("FP_IBLOCK_CATALOG_NAME"),
	"DESCRIPTION" => GetMessage("FP_IBLOCK_CATALOG_DESCRIPTION"),
	"ICON" => "/images/catalog.gif",
	"COMPLEX" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "DoninBiz",
		"CHILD" => array(
			"ID" => "catalog",
			"NAME" => GetMessage("FP_T_IBLOCK_DESC_CATALOG"),
			"SORT" => 30,
		)
	)
);
?>