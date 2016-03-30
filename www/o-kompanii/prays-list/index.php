<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Прайс-лист");
$APPLICATION->SetTitle("Прайс-лист");?>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "top",
		"EDIT_TEMPLATE" => ""
	)
);?><?$APPLICATION->IncludeComponent(
	"doninbiz:sections.elements.list", 
	"price_list", 
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "9",
		"FIELD_CODE" => array(
			0 => "NAME",
			1 => "PROPERTY_AMOUNT",
			2 => "PROPERTY_PRICE",
			3 => "PROPERTY_UNIT",
			4 => "PROPERTY_ARTICLE",
			5 => "",
		),
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"SECTION_SORT_BY1" => "SORT",
		"SECTION_SORT_ORDER1" => "ASC",
		"SECTION_SORT_BY2" => "ID",
		"SECTION_SORT_ORDER2" => "ASC",
		"ELEMENTS_SORT_BY1" => "PROPERTY_PRICE",
		"ELEMENTS_SORT_ORDER1" => "ASC",
		"ELEMENTS_SORT_BY2" => "SORT",
		"ELEMENTS_SORT_ORDER2" => "ASC",
		"PRICE_PREFIX" => "",
		"PRICE_SUFFIX" => " <i class=\"fa fa-rub\"></i>"
	),
	false
);?>
 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "bottom",
		"EDIT_TEMPLATE" => ""
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>