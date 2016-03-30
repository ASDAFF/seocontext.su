<br>
<h2 style="text-align: center;">Каталог продукции</h2>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;" class="lead">
 <span style="font-size: 14pt;">Ассортиментный ряд компании насчитывает более четырех тысяч позиций. С учетом потребностей рынка компания ежегодно разрабатывает и выводит на рынок десятки новых изделий:</span>
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
	"catalog", 
	array(
		"COMPONENT_TEMPLATE" => "catalog",
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_CODE" => "",
		"COUNT_ELEMENTS" => "Y",
		"TOP_DEPTH" => "2",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"VIEW_MODE" => "LIST",
		"SHOW_PARENT_NAME" => "N",
		"SHOW_DESCRIPTION" => "Y"
	),
	false
);?> <br>