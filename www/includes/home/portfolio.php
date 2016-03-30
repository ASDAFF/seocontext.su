<br>
<h2 style="text-align: center;">Наши работы</h2>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;" class="lead">
    <span style="font-size: 14pt;">Вывод определенных работ с изображениями по категориям (компонент поддерживает от 1 до 4 колонок):</span>
</p>
<p style="text-align: left;" class="lead">
</p>
<p style="text-align: center;" class="lead">
</p>
<p style="text-align: center;">
    <br>
</p>
<?$APPLICATION->IncludeComponent(
	"doninbiz:sections.elements.list", 
	"home_portfolio",
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "8",
		"FIELD_CODE" => array(
			0 => "ID",
			1 => "NAME",
			2 => "PREVIEW_TEXT",
			3 => "PREVIEW_PICTURE",
			4 => "PROPERTY_VIEW_HOME",
			5 => "",
		),
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"SECTION_SORT_BY1" => "ACTIVE_FROM",
		"SECTION_SORT_ORDER1" => "DESC",
		"SECTION_SORT_BY2" => "SORT",
		"SECTION_SORT_ORDER2" => "ASC",
		"ELEMENTS_SORT_BY1" => "ACTIVE_FROM",
		"ELEMENTS_SORT_ORDER1" => "DESC",
		"ELEMENTS_SORT_BY2" => "SORT",
		"ELEMENTS_SORT_ORDER2" => "ASC",
		"HOME_PORTFOLIO" => "Y",
		"COLUMNS_COUNT" => "3",
		"SHOW_DESC" => "N",
		"SHOW_BUTTON" => "N",
		"INCLUDE_SUBSECTIONS" => "Y"
	),
	false
);?>
<br>