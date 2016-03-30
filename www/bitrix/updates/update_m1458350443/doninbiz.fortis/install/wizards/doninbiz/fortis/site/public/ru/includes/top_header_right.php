<?$APPLICATION->IncludeComponent(
	"bitrix:news.line", 
	"social_networks", 
	array(
		"IBLOCK_TYPE" => "fortis_other",
		"IBLOCKS" => array("#SOCIAL_NETWORKS_IBLOCK_ID#"),
		"NEWS_COUNT" => "99",
		"FIELD_CODE" => array(
			0 => "",
			1 => "PROPERTY_TOOLTIP",
			2 => "PROPERTY_NETWORK",
			3 => "PROPERTY_LINK"
		),
		"SORT_BY1" => "ID",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y"
	),
	false
);?>