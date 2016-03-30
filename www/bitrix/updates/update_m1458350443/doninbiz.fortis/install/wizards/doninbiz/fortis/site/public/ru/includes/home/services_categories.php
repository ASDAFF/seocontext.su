<br>
<h2 style="text-align: center;">Наши услуги №1</h2>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;" class="lead">
 <span style="font-size: 14pt;">Можно отобразить разделы услуг со списком услуг:</span>
</p>
<p style="text-align: left;" class="lead">
</p>
<p style="text-align: center;" class="lead">
</p>
<p style="text-align: center;">
 <br>
</p>
 <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"services",
	Array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK_ID" => "#SERVICES_IBLOCK_ID#",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"COUNT_ELEMENTS" => "N",
		"TOP_DEPTH" => "1",
		"SECTION_FIELDS" => array(0=>"",1=>"",),
		"SECTION_USER_FIELDS" => array(0=>"",1=>"",),
		"SECTION_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"VIEW_MODE" => "LIST",
		"SHOW_ELEMENTS" => "Y",
		"ELEMENTS_SORT_BY1" => "ACTIVE_FROM",
		"ELEMENTS_SORT_ORDER1" => "DESC"
	)
);?> <br>