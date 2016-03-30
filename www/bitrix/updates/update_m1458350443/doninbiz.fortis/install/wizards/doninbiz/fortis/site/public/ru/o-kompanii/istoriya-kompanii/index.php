<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "История нашей компании");
$APPLICATION->SetTitle("История компании");?><?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"company_history",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "fortis_other",
		"IBLOCKS" => array("#HISTORY_COMPANY_IBLOCK_ID#"),
		"NEWS_COUNT" => "99",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"DATE_ACTIVE_FROM",3=>"PROPERTY_IMAGES",),
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "f Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>