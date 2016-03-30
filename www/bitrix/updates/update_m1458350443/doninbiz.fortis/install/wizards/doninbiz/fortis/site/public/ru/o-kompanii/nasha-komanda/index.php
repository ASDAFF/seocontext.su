<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Наша команда");
$APPLICATION->SetTitle("Наша команда");?><?$APPLICATION->IncludeComponent(
	"doninbiz:sections.elements", 
	"team",
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "#OUR_TEAM_IBLOCK_ID#",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "CODE",
			2 => "NAME",
			3 => "PREVIEW_TEXT",
			4 => "PREVIEW_PICTURE",
			5 => "PROPERTY_PROFESSION",
			6 => "PROPERTY_PHONES",
			7 => "PROPERTY_EMAILS",
			8 => "PROPERTY_SOC_VK",
			9 => "PROPERTY_SOC_FB",
			10 => "PROPERTY_SOC_OK",
			11 => "PROPERTY_SOC_MR",
			12 => "PROPERTY_SOC_GP",
			13 => "PROPERTY_SOC_TW",
			14 => "PROPERTY_SKYPE",
			15 => "",
		),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"SECTION_SORT_BY1" => "SORT",
		"SECTION_SORT_ORDER1" => "ASC",
		"SECTION_SORT_BY2" => "ID",
		"SECTION_SORT_ORDER2" => "ASC",
		"ELEMENTS_SORT_BY1" => "SORT",
		"ELEMENTS_SORT_ORDER1" => "ASC",
		"ELEMENTS_SORT_BY2" => "ID",
		"ELEMENTS_SORT_ORDER2" => "ASC",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "#SITE_DIR#o-kompanii/nasha-komanda/",
		"DETAIL_URL" => "",
		"SEF_URL_TEMPLATES" => array(
			"element" => "#ELEMENT_CODE#/",
		)
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>