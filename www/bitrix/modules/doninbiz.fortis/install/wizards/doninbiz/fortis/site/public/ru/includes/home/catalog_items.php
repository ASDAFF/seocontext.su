<br>
<h2 style="text-align: center;">Лучшие предложения</h2>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;">
</p>
<p style="text-align: center;" class="lead">
 <span style="font-size: 14pt;">Так можно отобразить Ваши лучшие предложения из каталога продукции. Товары отображаются во вкладке на основе стикера. Любую вкладку можно отключить и настроить параметры сортировки товаров.</span>
</p>
<p style="text-align: left;" class="lead">
</p>
<p style="text-align: center;" class="lead">
</p>
<p style="text-align: center;">
 <br>
</p>
<?$APPLICATION->IncludeComponent(
	"doninbiz:catalog.items.slider", 
	".default", 
	array(
		"IBLOCK_TYPE" => "fortis_content",
		"IBLOCK" => "#CATALOG_IBLOCK_ID#",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_URL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"CACHE_GROUPS" => "Y",
		"PRICE_PREFIX" => "",
		"PRICE_SUFFIX" => "р.",
		"MESS_NOT_AVAILABLE" => "По запросу",
		"USE_new" => "Y",
		"ELEMENTS_SORT_BY1_new" => "ACTIVE_FROM",
		"ELEMENTS_SORT_ORDER1_new" => "DESC",
		"ELEMENTS_SORT_BY2_new" => "SORT",
		"ELEMENTS_SORT_ORDER2_new" => "ASC",
		"USE_hit" => "Y",
		"ELEMENTS_SORT_BY1_hit" => "PROPERTY_NEW_PRICE",
		"ELEMENTS_SORT_ORDER1_hit" => "ASC",
		"ELEMENTS_SORT_BY2_hit" => "SORT",
		"ELEMENTS_SORT_ORDER2_hit" => "ASC",
		"USE_discount" => "Y",
		"ELEMENTS_SORT_BY1_discount" => "PROPERTY_NEW_PRICE",
		"ELEMENTS_SORT_ORDER1_discount" => "ASC",
		"ELEMENTS_SORT_BY2_discount" => "SORT",
		"ELEMENTS_SORT_ORDER2_discount" => "ASC",
		"USE_best_price" => "Y",
		"ELEMENTS_SORT_BY1_best_price" => "PROPERTY_NEW_PRICE",
		"ELEMENTS_SORT_ORDER1_best_price" => "ASC",
		"ELEMENTS_SORT_BY2_best_price" => "SORT",
		"ELEMENTS_SORT_ORDER2_best_price" => "ASC",
		"LIMIT_new" => "20",
		"LIMIT_hit" => "20",
		"LIMIT_discount" => "20",
		"LIMIT_best_price" => "20"
	),
	false
);?>