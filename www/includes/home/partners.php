<?$APPLICATION->IncludeComponent(
	"bitrix:news.line", 
	"home_partners", 
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCKS" => array(
			0 => "7",
		),
		"NEWS_COUNT" => "20",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_PICTURE",
			3 => "",
		),
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => ""
	),
	false
);?>