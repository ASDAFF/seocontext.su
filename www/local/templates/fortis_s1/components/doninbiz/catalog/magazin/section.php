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

$ar_result = CIBlockSection::GetList(
    array("SORT" => "ASC"),
    array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ID" => $arResult["VARIABLES"]["SECTION_ID"]),
    false,
    Array("UF_*")
);
if($res = $ar_result->GetNext()){
    $arResult['SECTION'] = $res;
}

$arResult['SECTION']['LIST_STYLE'] = 'all';
if ( ! empty($arResult['SECTION']['UF_C_LIST_STYLE'])) {
    $oListStyle = CUserFieldEnum::GetList(array(), array(
        "ID" => $arResult['SECTION']['UF_C_LIST_STYLE']
    ));
    $arListStyle = $oListStyle->GetNext();
    if ($arListStyle['XML_ID'])
        $arResult['SECTION']['LIST_STYLE'] = $arListStyle['XML_ID'];
}

?>


<?
$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');
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

				<?
					if ($arParams['USE_FILTER'] == 'Y') {
						$arFilter = array(
							"IBLOCK_ID" => $arParams["IBLOCK_ID"],
							"ACTIVE" => "Y",
							"GLOBAL_ACTIVE" => "Y",
						);
						if (0 < intval($arResult["VARIABLES"]["SECTION_ID"])) {
							$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
						} elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"]) {
							$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
						}

						$obCache = new CPHPCache();
						if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
							$arCurSection = $obCache->GetVars();
						} elseif ($obCache->StartDataCache()) {
							$arCurSection = array();
							if (Loader::includeModule("iblock")) {
								$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

								if (defined("BX_COMP_MANAGED_CACHE")) {
									global $CACHE_MANAGER;
									$CACHE_MANAGER->StartTagCache("/iblock/catalog");

									if ($arCurSection = $dbRes->Fetch()) {
										$CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);
									}
									$CACHE_MANAGER->EndTagCache();
								} else {
									if (!$arCurSection = $dbRes->Fetch())
										$arCurSection = array();
								}
							}
							$obCache->EndDataCache($arCurSection);
						}
						if (!isset($arCurSection)) {
							$arCurSection = array();
						}
						?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:catalog.smart.filter",
							".default",
							array(
								"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
								"IBLOCK_ID" => $arParams["IBLOCK_ID"],
								"SECTION_ID" => $arCurSection['ID'],
								"FILTER_NAME" => $arParams["FILTER_NAME"],
								"PRICE_CODE" => $arParams["PRICE_CODE"],
								"CACHE_TYPE" => $arParams["CACHE_TYPE"],
								"CACHE_TIME" => $arParams["CACHE_TIME"],
								"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
								"SAVE_IN_SESSION" => "N",
								"XML_EXPORT" => "Y",
								"SECTION_TITLE" => "NAME",
								"SECTION_DESCRIPTION" => "DESCRIPTION",
								'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
								"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"]
							),
							$component,
							array('HIDE_ICONS' => 'Y')
						);
					}?>

				<h4><?=GetMessage('CP_BC_TPL_CATALOG_NAME')?></h4>
				<?$APPLICATION->IncludeComponent("bitrix:menu", "left_multilevel", Array(
					"ROOT_MENU_TYPE" => "left",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(
						0 => "",
					),
					"MAX_LEVEL" => "4",
					"CHILD_MENU_TYPE" => "leftchild",
					"USE_EXT" => "Y",
					"DELAY" => "N",
					"ALLOW_MULTI_SELECT" => "N",
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

				<?$APPLICATION->IncludeComponent(
					"bitrix:catalog.section.list",
					"",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
						"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
						"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
						"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
						"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),

                        'SECTION_VIEW_DESC' => $arParams['SECTION_VIEW_DESC'],

                        'SECTION_VIEW_TOP_LINKS' => $arParams['SECTION_VIEW_TOP_LINKS'],
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);?>



                <?
                $frame = $this->createFrame()->begin();

                    $aListStyles = array('grid', 'list', 'table');
                    $sListCurrentStyle = 'grid';

                    if ( ! empty($_GET['list_style'])) {
                        $sListCurrentStyle = in_array($_GET['list_style'], $aListStyles) ? $_GET['list_style'] : 'grid';
                        $APPLICATION->set_cookie("CATALOG_LIST_STYLE", $sListCurrentStyle, time() + 3600*24, "/");
                    } else {
                        $sCookieListStyle = $APPLICATION->get_cookie("CATALOG_LIST_STYLE");
                        if ( ! empty($sCookieListStyle) && in_array($sCookieListStyle, $aListStyles)) {
                            $sListCurrentStyle = $sCookieListStyle;
                        }
                    }

                    $sListStyle = $arResult['SECTION']['LIST_STYLE'];

                    if (empty($sListStyle) || !in_array($sListStyle, $aListStyles)) {
                        $sListStyle = 'all';
                    }

                    $iListStyleSelect = false;
                    if ($sListStyle == 'all') {
                        $iListStyleSelect = true;
                    } else {
                        $sListCurrentStyle = in_array($sListStyle, $aListStyles) ? $sListStyle : 'grid';
                    }

                    $sSort  = !empty($_GET['sort'])  ? $_GET['sort']  : '';
                    $sOrder = !empty($_GET['order']) ? $_GET['order'] : 'asc';

                    $useVoteRating = ('Y' == $arParams['DETAIL_USE_VOTE_RATING']);

                    $sElementSortField  = $arParams["ELEMENT_SORT_FIELD"];
                    $sElementSortOrder  = $arParams["ELEMENT_SORT_ORDER"];
                    $sElementSortField2 = $arParams["ELEMENT_SORT_FIELD2"];
                    $sElementSortOrder2 = $arParams["ELEMENT_SORT_ORDER2"];

                    if ( ! empty($sSort) && ! empty($sOrder)) {
                        switch($sSort) {
                            case 'NAME':
                                $sElementSortField = 'name';
                                $sElementSortOrder = $sOrder;
                                break;
                            case 'RATING':
                                $sElementSortField = 'PROPERTY_rating';
                                $sElementSortOrder = $sOrder;
                                break;
                            case 'PRICE':
                                $sElementSortField  = 'PROPERTY_NEW_PRICE';
                                $sElementSortOrder  = $sOrder . ',nulls';
                                break;
                        }
                    }

                    $iSECTION_VIEW_SORTING_BLOCK = $arParams['SECTION_VIEW_SORTING_BLOCK'] == 'N' ? false : true;
                ?>

                <?if($iSECTION_VIEW_SORTING_BLOCK):?>
                <hr />

                <noindex>
                    <?if($iListStyleSelect):?>
                        <ul class="list-style-catalog">
                            <li class="text"><?=GetMessage('F_SORTING_LIST_TYPE_NAME')?>:</li>
                            <li<?if($sListCurrentStyle == 'grid'):?> class="active"<?endif?>>
                                <a href="<?=$APPLICATION->GetCurPageParam('list_style=grid', array('list_style'))?>" class="has-tooltip" title="<?=GetMessage('F_SORTING_LIST_TYPE_GRID')?>">
                                    <i class="fa fa-th"></i>
                                </a>
                            </li>
                            <li<?if($sListCurrentStyle == 'list'):?> class="active"<?endif?>>
                                <a href="<?=$APPLICATION->GetCurPageParam('list_style=list', array('list_style'))?>" class="has-tooltip" title="<?=GetMessage('F_SORTING_LIST_TYPE_LIST')?>">
                                    <i class="fa fa-list"></i>
                                </a>
                            </li>
                            <li<?if($sListCurrentStyle == 'table'):?> class="active"<?endif?>>
                                <a href="<?=$APPLICATION->GetCurPageParam('list_style=table', array('list_style'))?>" class="has-tooltip" title="<?=GetMessage('F_SORTING_LIST_TYPE_TABLE')?>">
                                    <i class="fa fa-align-justify"></i>
                                </a>
                            </li>
                        </ul>
                    <?endif?>

                    <ul class="sorting-list-catalog">
                        <li class="text visible-xs-inline-block"><?=GetMessage('F_SORTING_LABEL')?>:</li>
                        <li<?=($sSort == 'PRICE' ? ' class="active"' : '')?>>
                            <a href="<?=$APPLICATION->GetCurPageParam('sort=PRICE&order=' . ($sOrder == 'asc' && $sSort == 'PRICE' ? 'desc' : 'asc'), array('sort', 'order'))?>" title="<?=GetMessage('F_SORTING_BY')?> <?=($sOrder == 'asc' && $sSort == 'PRICE' ? GetMessage('F_SORTING_DESC') : GetMessage('F_SORTING_ASC'))?>" class="has-tooltip">
                                <?if($sSort == 'PRICE'):?>
                                    <i class="fa fa-sort-amount-<?=($sOrder == 'asc' ? 'asc' : 'desc')?>"></i>
                                <?else:?>
                                    <i class="fa fa-unsorted"></i>
                                <?endif?>
                                <span><?=GetMessage('F_SORTING_BY_PRICE')?></span>
                            </a>
                        </li>
                        <?if($useVoteRating):?>
                            <li<?=($sSort == 'RATING' ? ' class="active"' : '')?>>
                                <a href="<?=$APPLICATION->GetCurPageParam('sort=RATING&order=' . ($sOrder == 'asc' && $sSort == 'RATING' ? 'desc' : 'asc'), array('sort', 'order'))?>" title="<?=GetMessage('F_SORTING_BY')?> <?=($sOrder == 'asc' && $sSort == 'RATING' ? GetMessage('F_SORTING_DESC') : GetMessage('F_SORTING_ASC'))?>" class="has-tooltip">
                                    <?if($sSort == 'RATING'):?>
                                        <i class="fa fa-sort-numeric-<?=($sOrder == 'asc' ? 'asc' : 'desc')?>"></i>
                                    <?else:?>
                                        <i class="fa fa-unsorted"></i>
                                    <?endif?>
                                    <span><?=GetMessage('F_SORTING_BY_RATING')?></span>
                                </a>
                            </li>
                        <?endif?>
                        <li<?=($sSort == 'NAME' ? ' class="active"' : '')?>>
                            <a href="<?=$APPLICATION->GetCurPageParam('sort=NAME&order=' . ($sOrder == 'asc' && $sSort == 'NAME' ? 'desc' : 'asc'), array('sort', 'order'))?>" title="<?=GetMessage('F_SORTING_BY')?> <?=($sOrder == 'asc' && $sSort == 'NAME' ? GetMessage('F_SORTING_DESC') : GetMessage('F_SORTING_ASC'))?>" class="has-tooltip">
                                <?if($sSort == 'NAME'):?>
                                    <i class="fa fa-sort-alpha-<?=($sOrder == 'asc' ? 'asc' : 'desc')?>"></i>
                                <?else:?>
                                    <i class="fa fa-unsorted"></i>
                                <?endif?>
                                <span><?=GetMessage('F_SORTING_BY_NAME')?></span>
                            </a>
                        </li>
                    </ul>
                </noindex>

                <div class="clearfix"></div>

                <hr />
                <?endif?>


				<?
				$intSectionID = 0;
				?><?$intSectionID = $APPLICATION->IncludeComponent(
					"bitrix:catalog.section",
					"",
					array(
						"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
						"IBLOCK_ID" => $arParams["IBLOCK_ID"],
						"ELEMENT_SORT_FIELD" => $sElementSortField,
						"ELEMENT_SORT_ORDER" => $sElementSortOrder,
						"ELEMENT_SORT_FIELD2" => $sElementSortField2,
						"ELEMENT_SORT_ORDER2" => $sElementSortOrder2,
						"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
						"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
						"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
						"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
						"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
						"BASKET_URL" => $arParams["BASKET_URL"],
						"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
						"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
						"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
						"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
						"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
						"FILTER_NAME" => $arParams["FILTER_NAME"],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"CACHE_FILTER" => $arParams["CACHE_FILTER"],
						"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
						"SET_TITLE" => $arParams["SET_TITLE"],
						"SET_STATUS_404" => $arParams["SET_STATUS_404"],
						"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
						"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
						"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
						"PRICE_CODE" => $arParams["PRICE_CODE"],
						"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
						"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

						"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
						"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
						"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
						"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
						"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

						"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
						"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
						"PAGER_TITLE" => $arParams["PAGER_TITLE"],
						"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
						"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
						"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
						"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
						"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

						"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
						"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
						"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
						"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
						"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
						"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
						"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
						"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

						"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
						"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
						"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
						"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
						'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
						'CURRENCY_ID' => $arParams['CURRENCY_ID'],
						'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],

						'LABEL_PROP' => $arParams['LABEL_PROP'],
						'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
						'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

						'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
						'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
						'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
						'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
						'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
						'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
						'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
						'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
						'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
						'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],

						'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
						"ADD_SECTIONS_CHAIN" => "N",
						//'ADD_TO_BASKET_ACTION' => $basketAction,
						'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
						'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],

                        "SHOW_ALL_WO_SECTION" => "Y",

                        'PRICE_PREFIX' => $arParams['PRICE_PREFIX'],
                        'PRICE_SUFFIX' => $arParams['PRICE_SUFFIX'],
                        'TEXT_PRICE_NOT_DISCOUNT' => $arParams['TEXT_PRICE_NOT_DISCOUNT'],

                        'USE_VOTE_RATING' => $arParams['DETAIL_USE_VOTE_RATING'],
                        'VOTE_DISPLAY_AS_RATING' => (isset($arParams['DETAIL_VOTE_DISPLAY_AS_RATING']) ? $arParams['DETAIL_VOTE_DISPLAY_AS_RATING'] : ''),

                        'LIST_CURRENT_STYLE' => $sListCurrentStyle,

                        'TEXT_ORDER' => $arParams['TEXT_ORDER'],
                        'TEXT_QUESTION' => $arParams['TEXT_QUESTION'],

                        'SECTION_VIEW_DESC' => $arParams['SECTION_VIEW_DESC'],
					),
					$component
				);?>

                <?$frame->end();?>

			</div>

		</div>
	</div>
</div>