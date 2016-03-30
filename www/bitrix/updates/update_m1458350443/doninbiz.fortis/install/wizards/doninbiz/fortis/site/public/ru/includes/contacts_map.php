<?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"INIT_MAP_TYPE" => "ROADMAP",
		"MAP_DATA" => "#SITE_MAP_DATA#",
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