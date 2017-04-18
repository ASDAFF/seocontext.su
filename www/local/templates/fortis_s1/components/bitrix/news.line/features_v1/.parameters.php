<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"COLUMNS_COUNT" => Array(
		"NAME" => GetMessage("FFP_COLUMNS_COUNT"),
        "GROUP" => "BASE",
		"TYPE" => "LIST",
        "VALUES" => array(1 => 1, 2 => 2, 3 => 3, 4 => 4),
		"DEFAULT" => "3",
	)
);

?>