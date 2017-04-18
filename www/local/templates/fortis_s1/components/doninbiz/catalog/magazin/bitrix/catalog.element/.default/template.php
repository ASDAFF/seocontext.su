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
$this->setFrameMode(true);
?>

<?

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BASIS_PRICE' => $strMainID.'_basis_price',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'BASKET_ACTIONS' => $strMainID.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$strTitle = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);

$iNoPhoto = false;
if (is_array($arResult['MORE_PHOTO']) && strpos($arResult['MORE_PHOTO'][0]['SRC'], '/images/no_photo.png'))
	$iNoPhoto = true;

$arMediumImages = $arSmallImages = array();
if ( ! $iNoPhoto) {
	foreach($arResult['MORE_PHOTO'] as $iKeyPhoto => $arPhoto) {
		$arMediumImages[$iKeyPhoto] = array_merge(
			array('image_id' => $arPhoto['ID']),
			(array)CFile::ResizeImageGet($arPhoto['ID'], array('width' => 336, 'height' => 336))
		);
		$arSmallImages[$iKeyPhoto]  = array_merge(
			array('image_id' => $arPhoto['ID']),
			(array)CFile::ResizeImageGet($arPhoto['ID'], array('width' => 80, 'height' => 80))
		);
	}
}

$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
$useSocBar = ('Y' == $arParams['USE_SOCIAL_BAR']);

$arStickers = array();
if ( ! empty($arResult['PROPERTIES']['OFFERS']['VALUE_XML_ID'])) {
    $arStickers = array_combine($arResult['PROPERTIES']['OFFERS']['VALUE_XML_ID'], $arResult['PROPERTIES']['OFFERS']['VALUE_ENUM']);
}

?>

<div class="detail-catalog" id="<? echo $strMainID; ?>">

	<div class="row">

		<div class="col-md-5">
			<?if($iNoPhoto):?>

                <div class="stickers-outer stickers-relative">
                    <div class="thumbnail no-photo">
                        <img src="<?=$arResult['MORE_PHOTO'][0]['SRC']?>" alt="">
                    </div>

                    <?if( ! empty($arStickers)):?>
                        <ul class="stickers">
                            <?foreach($arStickers as $sSticker => $sStickerName):?>
                                <li class="<?=$sSticker?>">
                                    <div class="sticker-outer">
                                        <i class="icon <?=$sSticker?>"></i>
                                        <span><?=$sStickerName?></span>
                                    </div>
                                </li>
                            <?endforeach?>
                        </ul>
                    <?endif?>
                </div>

			<?elseif(!empty($arResult['MORE_PHOTO'])):?>

				<?if($arResult['MORE_PHOTO_COUNT'] > 1):?>

                    <div class="stickers-outer stickers-relative">
                        <div class="slider-medium-images">
                            <?foreach($arMediumImages as $iKeyMI => $arMediumImage):?>
                                <div>
                                    <div class="aimg-hover thumbnail magnific-gallery detail_picture">
                                        <div class="outer">
                                            <div class="inner">
                                                <div class="aimg-overlay"></div>
                                                <img
                                                    border="0"
                                                    src="<?=$arMediumImage['src']?>"
                                                    alt="<?=$strAlt;?>"
                                                    title="<?=$strTitle;?>"
                                                    >
                                                <div class="aimg-row">
                                                    <a href="<?=$arResult['MORE_PHOTO'][$iKeyMI]['SRC']?>" target="_blank" class="aimg-fullscreen"
                                                       title="<?=(!empty($arResult['MORE_PHOTO'][$iKeyMI]['DESCRIPTION']) ? $arResult['MORE_PHOTO'][$iKeyMI]['DESCRIPTION'] : $strTitle)?>">
                                                        <i class="fa fa-search-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?endforeach?>
                        </div>

                        <?if( ! empty($arStickers)):?>
                            <ul class="stickers">
                                <?foreach($arStickers as $sSticker => $sStickerName):?>
                                    <li class="<?=$sSticker?>">
                                        <div class="sticker-outer">
                                            <i class="icon <?=$sSticker?>"></i>
                                            <span><?=$sStickerName?></span>
                                        </div>
                                    </li>
                                <?endforeach?>
                            </ul>
                        <?endif?>
                    </div>

					<div class="outer-slider-small-images">
						<div class="slider-small-images">
							<?foreach($arSmallImages as $iKeySI => $arSmallImage):?>
								<div class="thumbnail">
									<div class="outer">
                                        <div class="inner">
                                            <img border="0" src="<?=$arSmallImage['src']?>" alt="<?=$strAlt;?>">
                                        </div>
                                    </div>
								</div>
							<?endforeach?>
						</div>
					</div>

				<?else:?>
                    <div class="stickers-outer stickers-relative">
                        <div class="aimg-hover thumbnail detail_picture">
                            <div class="aimg-overlay"></div>
                            <img
                                border="0"
                                src="<?=$arResult['MORE_PHOTO'][0]['SRC']?>"
                                alt="<?=$strAlt?>"
                                title="<?=$strTitle?>"
                                >
                            <div class="aimg-row">
                                <a href="<?=$arResult['MORE_PHOTO'][0]['SRC']?>" target="_blank" class="aimg-fullscreen" title="<?=$strTitle;?>"><i class="fa fa-search-plus"></i></a>
                            </div>
                        </div>

                        <?if( ! empty($arStickers)):?>
                            <ul class="stickers">
                                <?foreach($arStickers as $sSticker => $sStickerName):?>
                                    <li class="<?=$sSticker?>">
                                        <div class="sticker-outer">
                                            <i class="icon <?=$sSticker?>"></i>
                                            <span><?=$sStickerName?></span>
                                        </div>
                                    </li>
                                <?endforeach?>
                            </ul>
                        <?endif?>
                    </div>
				<?endif?>

			<?endif?>

			<div class="hidden-xs hidden-sm">
				<?
				if ('Y' == $arParams['USE_COMMENTS'])
				{
					?>
					<?$APPLICATION->IncludeComponent(
					"doninbiz:catalog.comments",
					"",
					array(
						"ELEMENT_ID" => $arResult['ID'],
						"ELEMENT_CODE" => "",
						"IBLOCK_ID" => $arParams['IBLOCK_ID'],
						"URL_TO_COMMENT" => "",
						"WIDTH" => "",
						"COMMENTS_COUNT" => "5",
						"BLOG_USE" => 'N',
						"FB_USE" => $arParams['FB_USE'],
						"FB_APP_ID" => $arParams['FB_APP_ID'],
						"VK_USE" => $arParams['VK_USE'],
						"VK_API_ID" => $arParams['VK_API_ID'],
						"CACHE_TYPE" => $arParams['CACHE_TYPE'],
						"CACHE_TIME" => $arParams['CACHE_TIME'],
						"BLOG_TITLE" => "",
						"BLOG_URL" => $arParams['BLOG_URL'],
						"PATH_TO_SMILE" => "",
						"EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
						"AJAX_POST" => "Y",
						"SHOW_SPAM" => "Y",
						"SHOW_RATING" => "N",
						"FB_TITLE" => "",
						"FB_USER_ADMIN_ID" => "",
						"FB_COLORSCHEME" => $arParams['FB_THEME'],
						"FB_ORDER_BY" => "reverse_time",
						"VK_TITLE" => "",
						"TYPE" => "md-lg"
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);?>
				<?
				}
				?>
			</div>

		</div>

		<div class="col-md-7 general-info">
			<?if($useSocBar || $useVoteRating):?>
				<div class="row soc-rating">
					<?if($useSocBar):?>
						<div class="soc<?=($useSocBar ? ' col-xs-7' : ' col-xs-12')?>">
							<noindex>
								<script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
							</noindex>
						</div>
					<?endif?>
					<?if($useVoteRating):?>
					<div class="rating<?=($useVoteRating ? ' col-xs-5' : ' col-xs-12')?>">
						<?$APPLICATION->IncludeComponent(
								"doninbiz:iblock.vote",
								"stars",
								array(
									"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
									"IBLOCK_ID" => $arParams['IBLOCK_ID'],
									"ELEMENT_ID" => $arResult['ID'],
									"ELEMENT_CODE" => "",
									"MAX_VOTE" => "5",
									"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
									"SET_STATUS_404" => "N",
									"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
									"CACHE_TYPE" => $arParams['CACHE_TYPE'],
									"CACHE_TIME" => $arParams['CACHE_TIME']
								),
								$component,
								array("HIDE_ICONS" => "Y")
							)?>
					</div>
					<?endif?>
				</div>
			<?endif?>

			<?if ('Y' == $arParams['DISPLAY_NAME']):?>
				<h2 class="title">
					<?
					echo (
					isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
						? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
						: $arResult["NAME"]
					); ?>
				</h2>
				<?if ('Y' == $arParams['DISPLAY_CAT_NAME']):?>
					<small class="cat">
						<?=$arResult['SECTION']['NAME']?>
					</small>
				<?endif?>
			<?else:?>
				<?if ('Y' == $arParams['DISPLAY_CAT_NAME']):?>
					<div class="cat">
						<?=$arResult['SECTION']['NAME']?>
					</div>
				<?endif?>
			<?endif?>

			<ul class="price-order-block">
				<li>
					<div class="cell price-status">
						<?
						$sOldPrice = $arResult['PROPERTIES']['OLD_PRICE']['VALUE'];
						$sNewPrice = $arResult['PROPERTIES']['NEW_PRICE']['VALUE'];

						$sPrice = !empty($sNewPrice) ? $sNewPrice : $sOldPrice;

						$iIsPrice    = !empty($sNewPrice) || !empty($sOldPrice);
						$iIsOnePrice = empty($sOldPrice) || empty($sNewPrice);
						$iIsStatus   = !empty($arResult['PROPERTIES']['STATUS']['VALUE_XML_ID']) && !empty($arResult['PROPERTIES']['STATUS']['VALUE']);
						?>

						<?if(empty($arResult['PROPERTIES']['OLD_PRICE']['VALUE']) && empty($arResult['PROPERTIES']['NEW_PRICE']['VALUE'])):?>
							<div class="request">
								<?=$arParams['MESS_NOT_AVAILABLE']?>
							</div>
						<?else:?>

							<div class="new-price">
								<?=($arParams['PRICE_PREFIX'] ? '<small>'.$arParams['PRICE_PREFIX'].'</small>' : '').
								formatMoney($sPrice).
								($arParams['PRICE_SUFFIX'] ? '<small>'.$arParams['PRICE_SUFFIX'].'</small>' : '')?>
							</div>
							<?if($sOldPrice && $sNewPrice):?>
								<div class="old-price">
									<?=$arParams['TEXT_PRICE_NOT_DISCOUNT']?>:
									<span class="price">
										<?=formatMoney($sOldPrice).$arParams['PRICE_SUFFIX']?>
									</span>
								</div>
							<?endif;?>

						<?endif?>

						<?if($arResult['PROPERTIES']['STATUS']['VALUE_XML_ID'] && $arResult['PROPERTIES']['STATUS']['VALUE']):?>
							<span class="label <?=$arResult['PROPERTIES']['STATUS']['VALUE_XML_ID']?>">
								<?=$arResult['PROPERTIES']['STATUS']['VALUE']?>
							</span>
						<?endif?>
					</div>

					<div class="cell order-buttons">
						<div class="btn-group-vertical">
							<a href="<?=SITE_DIR?>order/product.php" class="btn btn-primary btn-block get-buy-form" data-name="<?=$arResult["NAME"], ENT_QUOTES?>">
								<?=$arParams['TEXT_ORDER']?>
							</a>
							<a href="<?=SITE_DIR?>order/question.php" class="btn btn-default btn-block get-question-form" data-name="<?=$arResult["NAME"], ENT_QUOTES?>">
								<?=$arParams['TEXT_QUESTION']?>
							</a>
						</div>
					</div>
				</li>
			</ul>


			<?
			if ('' != $arResult['PREVIEW_TEXT'])
			{
				if (
					'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
					|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
				)
				{
					?>
					<div class="preview-text">
						<?
						echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
						?>
					</div>
				<?
				}
			}
			?>


			<?if (!empty($arResult['DISPLAY_PROPERTIES'])):?>
				<h5 class="info"><?=GetMessage('CT_BCE_CATALOG_PROPERTIES')?></h5>
				<dl class="properties">
					<?
					foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
					{
						?>
						<div>
							<dt>
								<span><? echo $arOneProp['NAME']; ?>:</span>
							</dt>
							<dd>
								<span>
									<?
									echo (
									is_array($arOneProp['DISPLAY_VALUE'])
										? implode(' / ', $arOneProp['DISPLAY_VALUE'])
										: $arOneProp['DISPLAY_VALUE']
									);
									?>
								</span>
							</dd>
						</div>
					<?
					}
					unset($arOneProp);
					?>
				</dl>
			<?endif?>

			<?if ('' != $arResult['DETAIL_TEXT']):?>
				<div class="bx_item_description">
					<h5 class="info"><? echo GetMessage('FULL_DESCRIPTION'); ?></h5>
					<?if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
						echo $arResult['DETAIL_TEXT'];
					} else {
						?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
					}
					?>
				</div>
			<?endif?>

			<?if (!empty($arResult['DOCS'])):?>
				<h5 class="info"><?=GetMessage('CT_BCE_CATALOG_DOCS')?></h5>
				<ul class="docs<?=(count($arResult['DOCS']) == 1 ? ' one-file' : '')?>">
					<?foreach($arResult['DOCS'] as $arDoc):?>
						<?
						$sIconDir = SITE_TEMPLATE_PATH . '/assets/img/extensions/';
						$sName = !empty($arDoc['DESCRIPTION']) ? $arDoc['DESCRIPTION'] : str_replace('.' . $arDoc['EXT'], '', $arDoc['ORIGINAL_NAME']);

						$sIconPath = $sIconDir . 'blank.png';
						if ( ! empty($arDoc['EXT']) && file_exists($_SERVER["DOCUMENT_ROOT"] . $sIconDir . $arDoc['EXT'] . '.png'))
							$sIconPath = $sIconDir . $arDoc['EXT'] . '.png';
						?>
						<li>
							<img src="<?=$sIconPath?>" alt="<?=$sName?>" class="icon">
							<div class="link">
								<a href="/download.php?file=<?=$arDoc['ID']?>">
									<?=$sName?>
								</a>
								<?=$arDoc['VIEW_SIZE']?>
							</div>
						</li>
					<?endforeach?>
				</ul>
			<?endif?>

			<div class="shown-xs shown-sm hidden-md hidden-lg">
				<?
				if ('Y' == $arParams['USE_COMMENTS'])
				{
					?>
					<?$APPLICATION->IncludeComponent(
					"doninbiz:catalog.comments",
					"",
					array(
						"ELEMENT_ID" => $arResult['ID'],
						"ELEMENT_CODE" => "",
						"IBLOCK_ID" => $arParams['IBLOCK_ID'],
						"URL_TO_COMMENT" => "",
						"WIDTH" => "",
						"COMMENTS_COUNT" => "5",
						"BLOG_USE" => 'N',
						"FB_USE" => $arParams['FB_USE'],
						"FB_APP_ID" => $arParams['FB_APP_ID'],
						"VK_USE" => $arParams['VK_USE'],
						"VK_API_ID" => $arParams['VK_API_ID'],
						"CACHE_TYPE" => $arParams['CACHE_TYPE'],
						"CACHE_TIME" => $arParams['CACHE_TIME'],
						"BLOG_TITLE" => "",
						"BLOG_URL" => $arParams['BLOG_URL'],
						"PATH_TO_SMILE" => "",
						"EMAIL_NOTIFY" => $arParams['BLOG_EMAIL_NOTIFY'],
						"AJAX_POST" => "Y",
						"SHOW_SPAM" => "Y",
						"SHOW_RATING" => "N",
						"FB_TITLE" => "",
						"FB_USER_ADMIN_ID" => "",
						"FB_COLORSCHEME" => $arParams['FB_THEME'],
						"FB_ORDER_BY" => "reverse_time",
						"VK_TITLE" => "",
						"TYPE" => "xs-sm"
					),
					$component,
					array("HIDE_ICONS" => "Y")
				);?>
				<?
				}
				?>
			</div>

		</div>

	</div>

</div>