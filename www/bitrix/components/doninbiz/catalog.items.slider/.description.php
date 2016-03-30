<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage('HCIS_COMP_NAME'),
    "DESCRIPTION" => GetMessage('HCIS_COMP_DESC'),
    "ICON" => "/images/icon.gif",
    "SORT" => 10,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "DoninBiz", // for example "my_project"
        "CHILD" => array(
            "ID" => "catalog", // for example "my_project:services"
            "NAME" => GetMessage('HCIS_COMP_NAME'),  // for example "Services"
        ),
    ),
    "COMPLEX" => "N",
);

?>