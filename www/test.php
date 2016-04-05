<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>Text here....<?$APPLICATION->IncludeComponent(
	"seocontext:locations",
	"locations",
	Array(
		"CACHE_TIME" => "36000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"RELOAD_PAGE" => "N"
	)
);?><br>
 <br>
 Text here...<br>
 <br>
 Text here...<br>
 Text here...<br>
 <?$APPLICATION->IncludeComponent(
	"seocontext:cond.include", 
	".default", 
	array(
		"ALL_ON_PAGE" => "Y",
		"CACHE_TIME" => "36000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"CONTENT" => "",
		"CONTENT_DIR" => ""
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

