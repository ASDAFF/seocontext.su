<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Вопросы и ответы");
$APPLICATION->SetTitle("Вопросы и ответы");?><?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "top_text",
		"EDIT_TEMPLATE" => ""
	)
);?><br>
 <?$APPLICATION->IncludeComponent(
	"doninbiz:sections.elements.list",
	"faq",
	Array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "3",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"PREVIEW_PICTURE",3=>"DETAIL_TEXT",4=>"DETAIL_PICTURE",5=>"",),
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
	)
);?>
 <?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "page",
		"AREA_FILE_SUFFIX" => "bottom_text",
		"EDIT_TEMPLATE" => ""
	)
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>