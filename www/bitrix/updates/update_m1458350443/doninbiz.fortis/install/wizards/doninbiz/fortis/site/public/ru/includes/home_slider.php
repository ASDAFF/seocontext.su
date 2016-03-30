<?$APPLICATION->IncludeComponent(
	"doninbiz:home.slider", 
	".default", 
	array(
		"IBLOCK_TYPE" => "fortis_other",
		"IBLOCKS" => array("#HOME_SLIDER_IBLOCK_ID#"),
		"FIELD_CODE" => array(
			0 => "PREVIEW_TEXT",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "PROPERTY_NAME",
			4 => "PROPERTY_IMAGE_ALIGN",
			5 => "PROPERTY_TEXT_COLOR",
			6 => "PROPERTY_NAME_LINK",
			7 => "PROPERTY_IMAGE_LINK",
			8 => "PROPERTY_ONE_BUTTON",
			9 => "PROPERTY_ONE_BUTTON_LINK",
			10 => "PROPERTY_TWO_BUTTON",
			11 => "PROPERTY_TWO_BUTTON_LINK",
			12 => "PROPERTY_THREE_BUTTON",
			13 => "PROPERTY_THREE_BUTTON_LINK",
			14 => "PROPERTY_ONE_BUTTON_CLASS",
			15 => "PROPERTY_TWO_BUTTON_CLASS",
			16 => "PROPERTY_THREE_BUTTON_CLASS",
			17 => "",
		),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "ASC",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?>