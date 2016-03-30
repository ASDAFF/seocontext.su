<br>
<h2 style="text-align: center;">Наши услуги №3</h2>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;" class="lead">
 <span style="font-size: 14pt;">Вывод определенных услуг с изображениями (компонент поддерживает от 1 до 4 колонок):</span>
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
	"services_photos",
	Array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCKS" => array("#SERVICES_IBLOCK_ID#"),
		"NEWS_COUNT" => "20",
		"FIELD_CODE" => array(0=>"ID",1=>"NAME",2=>"PREVIEW_PICTURE",3=>"PROPERTY_VIEW_HOME",4=>"PROPERTY_HOME_TEXT",5=>"",),
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"COLUMNS_COUNT" => "4"
	)
);?> <br>