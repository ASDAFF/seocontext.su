<?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "a:4:{s:10:\"google_lat\";s:9:\"55.750504\";s:10:\"google_lon\";s:9:\"37.653182\";s:12:\"google_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:4:\"TEXT\";s:116:\"Москва, 109028 Серебряническая набережная, 29 Бизнес-центр «Silver City»\";s:3:\"LON\";s:9:\"37.653182\";s:3:\"LAT\";s:9:\"55.750504\";}}}",
		"MAP_WIDTH" => "100%",
		"MAP_HEIGHT" => "300",
		"CONTROLS" => array(
			0 => "SMALL_ZOOM_CONTROL",
			1 => "SCALELINE",
		),
		"OPTIONS" => array(
			0 => "ENABLE_DBLCLICK_ZOOM",
			1 => "ENABLE_DRAGGING",
			2 => "ENABLE_KEYBOARD",
		),
		"MAP_ID" => ""
	),
	false
);?>