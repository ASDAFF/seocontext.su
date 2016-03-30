<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Лицензии");
$APPLICATION->SetTitle("Лицензии");?><?$APPLICATION->IncludeComponent(
	"doninbiz:sections.elements.list", 
	"licenses",
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "5",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DETAIL_TEXT",
			4 => "DETAIL_PICTURE",
			5 => "",
		),
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"SECTION_SORT_BY1" => "ID",
		"SECTION_SORT_ORDER1" => "ASC",
		"SECTION_SORT_BY2" => "SORT",
		"SECTION_SORT_ORDER2" => "ASC",
		"ELEMENTS_SORT_BY1" => "ID",
		"ELEMENTS_SORT_ORDER1" => "ASC",
		"ELEMENTS_SORT_BY2" => "SORT",
		"ELEMENTS_SORT_ORDER2" => "ASC"
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>