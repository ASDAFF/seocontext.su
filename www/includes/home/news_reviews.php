<br>
<div class="row">
	<div class="col-sm-6">
		<h3>Наши новости</h3>
		 <?$APPLICATION->IncludeComponent(
	"bitrix:news.line", 
	"home_news", 
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCKS" => array("6"),
		"NEWS_COUNT" => "10",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_TEXT",
			3 => "DATE_ACTIVE_FROM",
			4 => "",
		),
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "j-M",
		"VIEW_ALL_ELEMENTS_BTN" => "Y"
	),
	false
);?>
	</div>
	<div class="col-sm-6">
		<h3>Отзывы клиентов</h3>
		 <?$APPLICATION->IncludeComponent(
	"bitrix:news.line", 
	"home_reviews", 
	array(
		"IBLOCK_TYPE" => "fortis_other",
		"IBLOCKS" => array("15"),
		"NEWS_COUNT" => "10",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_TEXT",
			3 => "PREVIEW_PICTURE",
			4 => "DATE_ACTIVE_FROM",
			5 => "PROPERTY_NAME",
			6 => "PROPERTY_STATUS",
			7 => "PROPERTY_SITE",
			8 => "PROPERTY_VIEW_HOME",
			9 => "",
		),
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"VIEW_ALL_ELEMENTS_BTN" => "Y",
		"VIEW_ADD_ELEMENT_BTN" => "Y"
	),
	false
);?>
	</div>
</div>
<br>