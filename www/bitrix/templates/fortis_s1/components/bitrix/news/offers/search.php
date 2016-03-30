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
$this->setFrameMode(false);
global $sFortisColSidebar, $sFortisColContent;

include('functions.php');
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

				<?if($arParams["USE_RSS"]=="Y"):?>
					<h4>
						<?=GetMessage("RSS_LABEL")?>
						<?
						$rss_url = CComponentEngine::makePathFromTemplate($arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss_section"], $arResult["VARIABLES"]);
						if(method_exists($APPLICATION, 'addheadstring'))
							$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$rss_url.'" href="'.$rss_url.'" />');
						?>
						<a href="<?=$rss_url?>" class="news-rss-link" title="rss" target="_self"><img alt="RSS" src="<?=$templateFolder?>/images/gif-light/feed-icon-16x16.gif" border="0" align="right" /></a>
					</h4>
				<?endif?>

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
				<br />

				<?if($arParams["USE_FILTER"]=="Y"):?>
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.filter",
						"",
						Array(
							"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"FILTER_NAME" => $arParams["FILTER_NAME"],
							"FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
							"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
							"CACHE_TYPE" => $arParams["CACHE_TYPE"],
							"CACHE_TIME" => $arParams["CACHE_TIME"],
							"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						),
						$component
					);
					?>
					<br />
				<?endif?>

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
				<?$APPLICATION->IncludeComponent(
					"bitrix:search.page",
					"def",
					Array(
						"CHECK_DATES" => $arParams["CHECK_DATES"]!=="N"? "Y": "N",
						"arrWHERE" => Array("iblock_".$arParams["IBLOCK_TYPE"]),
						"arrFILTER" => Array("iblock_".$arParams["IBLOCK_TYPE"]),
						"SHOW_WHERE" => "N",
						//"PAGE_RESULT_COUNT" => "",
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => Array($arParams["IBLOCK_ID"]),
						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"PAGER_SHOW_ALWAYS" => "N",
					),
					$component
				);?>
				<br/>
				<p><a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]?>"><?=GetMessage("T_NEWS_DETAIL_BACK")?></a></p>
			</div>

		</div>
	</div>
</div>