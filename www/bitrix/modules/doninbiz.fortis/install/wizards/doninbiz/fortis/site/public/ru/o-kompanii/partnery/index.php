<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Наши партнеры");
$APPLICATION->SetTitle("Партнеры");?><?$APPLICATION->IncludeComponent(
	"doninbiz:sections.elements.list", 
	"partners", 
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "#PARTNERS_IBLOCK_ID#",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DETAIL_TEXT",
			4 => "DETAIL_PICTURE",
			5 => "PROPERTY_PHONE",
			6 => "PROPERTY_LINKS",
			7 => "",
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
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>