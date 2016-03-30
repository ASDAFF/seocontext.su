<br>
<h2 style="text-align: center;">Наши преимущества №2</h2>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;" class="lead">
 <span style="font-size: 14pt;">Если необходимы небольшие блоки для отображения Вашей информации, то Вы можете использовать более компактную конструкцию (также в настройках компонента можно указать количество колонок от 1 до 4):</span>
</p>
<p style="text-align: left;" class="lead">
</p>
<p style="text-align: center;" class="lead">
</p>
<p style="text-align: center;">
 <br>
</p>
 <?$APPLICATION->IncludeComponent(
	"bitrix:news.line",
	"features_v2",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "fortis_other",
		"IBLOCKS" => array("14"),
		"NEWS_COUNT" => "21",
		"FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"PREVIEW_PICTURE",3=>"PROPERTY_ICON",4=>"PROPERTY_LINK",5=>"PROPERTY_BUTTON_NAME",6=>"",),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"COLUMNS_COUNT" => "3"
	)
);?> <br>