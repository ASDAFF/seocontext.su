<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);

global $sFortisColSidebar, $sFortisColContent;

?>


<div class="content">
	<div class="container wrapper-container">
		<div class="row">

			<div class="<?=$sFortisColSidebar?>">

                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/sidebar_top.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>

				<?$APPLICATION->IncludeComponent("bitrix:menu", "left_multilevel", Array(
					"ROOT_MENU_TYPE" => "left",	// Тип меню для первого уровня
					"MENU_CACHE_TYPE" => "A",	// Тип кеширования
					"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
					"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
					"MENU_CACHE_GET_VARS" => array(	// Значимые переменные запроса
						0 => "",
					),
					"MAX_LEVEL" => "4",	// Уровень вложенности меню
					"CHILD_MENU_TYPE" => "leftchild",	// Тип меню для остальных уровней
					"USE_EXT" => "Y",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
					"DELAY" => "N",	// Откладывать выполнение шаблона меню
					"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
				),
					false
				);?>

                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/sidebar_bottom.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>

			</div>

			<div class="<?=$sFortisColContent?>">

				<?$ElementID = $APPLICATION->IncludeComponent(
					"bitrix:catalog.element",
					"",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
						"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
						"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
						"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
						"CHECK_SECTION_ID_VARIABLE" => (isset($arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"]) ? $arParams["DETAIL_CHECK_SECTION_ID_VARIABLE"] : ''),
						"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
						"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
						"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
						"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
						"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
						"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
						"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
						"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
						"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
						"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

						"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
						"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
						"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
						"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
						"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
						"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],

						"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
						"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
						"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
						'USE_ELEMENT_COUNTER' => $arParams['USE_ELEMENT_COUNTER'],

						'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
						'LABEL_PROP' => $arParams['LABEL_PROP'],
						'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
						'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
						'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
						'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
						'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
						'SHOW_MAX_QUANTITY' => $arParams['DETAIL_SHOW_MAX_QUANTITY'],
						'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
						'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
						'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
						'MESS_BTN_COMPARE' => $arParams['MESS_BTN_COMPARE'],
						'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
						'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
						'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),
						'USE_COMMENTS' => $arParams['DETAIL_USE_COMMENTS'],
						'BLOG_USE' => (isset($arParams['DETAIL_BLOG_USE']) ? $arParams['DETAIL_BLOG_USE'] : ''),
						'BLOG_URL' => (isset($arParams['DETAIL_BLOG_URL']) ? $arParams['DETAIL_BLOG_URL'] : ''),
						'BLOG_EMAIL_NOTIFY' => (isset($arParams['DETAIL_BLOG_EMAIL_NOTIFY']) ? $arParams['DETAIL_BLOG_EMAIL_NOTIFY'] : ''),
						'VK_USE' => (isset($arParams['DETAIL_VK_USE']) ? $arParams['DETAIL_VK_USE'] : ''),
						'VK_API_ID' => (isset($arParams['DETAIL_VK_API_ID']) ? $arParams['DETAIL_VK_API_ID'] : 'API_ID'),
						'FB_USE' => (isset($arParams['DETAIL_FB_USE']) ? $arParams['DETAIL_FB_USE'] : ''),
						'FB_APP_ID' => (isset($arParams['DETAIL_FB_APP_ID']) ? $arParams['DETAIL_FB_APP_ID'] : ''),
						'FB_THEME' => (isset($arParams['DETAIL_FB_THEME']) ? $arParams['DETAIL_FB_THEME'] : 'light'),
						'BRAND_USE' => (isset($arParams['DETAIL_BRAND_USE']) ? $arParams['DETAIL_BRAND_USE'] : 'N'),
						'BRAND_PROP_CODE' => (isset($arParams['DETAIL_BRAND_PROP_CODE']) ? $arParams['DETAIL_BRAND_PROP_CODE'] : ''),
						'DISPLAY_NAME' => (isset($arParams['DETAIL_DISPLAY_NAME']) ? $arParams['DETAIL_DISPLAY_NAME'] : ''),
						'ADD_DETAIL_TO_SLIDER' => (isset($arParams['DETAIL_ADD_DETAIL_TO_SLIDER']) ? $arParams['DETAIL_ADD_DETAIL_TO_SLIDER'] : ''),
						'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
						"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
						"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
						"DISPLAY_PREVIEW_TEXT_MODE" => (isset($arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE']) ? $arParams['DETAIL_DISPLAY_PREVIEW_TEXT_MODE'] : ''),
						"DETAIL_PICTURE_MODE" => (isset($arParams['DETAIL_DETAIL_PICTURE_MODE']) ? $arParams['DETAIL_DETAIL_PICTURE_MODE'] : ''),
						'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
						'DISPLAY_COMPARE' => (isset($arParams['USE_COMPARE']) ? $arParams['USE_COMPARE'] : ''),
						'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
						'SHOW_BASIS_PRICE' => (isset($arParams['DETAIL_SHOW_BASIS_PRICE']) ? $arParams['DETAIL_SHOW_BASIS_PRICE'] : 'Y'),
						'PRICE_PREFIX' => $arParams['PRICE_PREFIX'],
						'PRICE_SUFFIX' => $arParams['PRICE_SUFFIX'],
						'USE_SOCIAL_BAR' => $arParams['USE_SOCIAL_BAR'],
						'TEXT_ORDER' => $arParams['TEXT_ORDER'],
						'TEXT_QUESTION' => $arParams['TEXT_QUESTION'],
						'TEXT_PRICE_NOT_DISCOUNT' => $arParams['TEXT_PRICE_NOT_DISCOUNT'],
						'DISPLAY_CAT_NAME' => $arParams['DISPLAY_CAT_NAME'],
					),
					$component
				);?>

			</div>

		</div>
	</div>
</div>