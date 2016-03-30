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

$iIsPicture = $arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"]);
if ($iIsPicture)
	$aPreviewPicture = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array("width" => 250, "height" => 250));
?>

<div class="news-detail offer-detail">

	<?if($iIsPicture):?>
		<div class="image-outer">
            <div class="aimg-hover detail_picture">
                <div class="aimg-overlay"></div>
                <img
                    class="img-thumbnail"
                    src="<?=$aPreviewPicture['src']?>"
                    alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
                    title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>">
                <div class="aimg-row">
                    <a href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$arResult['NAME']?>"><i class="fa fa-search-plus"></i></a>
                </div>
            </div>
        </div>
	<?endif?>

    <?
    $start_date = MakeTimeStamp($arResult['ACTIVE_FROM']);
    $end_date   = MakeTimeStamp($arResult['PROPERTIES']['DATE_END']['VALUE'][0]);
    ?>

    <div class="dates">
                <span class="label label-primary">
                    <?=ConvertTimeStamp($start_date, "SHORT", "ru")?>
                    <?if($end_date):?>
                        &mdash; <?=ConvertTimeStamp($end_date, "SHORT", "ru")?>
                    <?endif?>
                </span>
        <?if($end_date):?>
            <small>(<?=leftAgoOffer($end_date)?>)</small>
        <?endif?>
    </div>

	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?>
	<?endif;?>

    <?
        $sNavString = $arResult["NAV_RESULT"] ? $arResult["NAV_STRING"] : null;
        if ($arResult["NAV_RESULT"])
            $sNavString = str_replace('ul class="pagination"', 'ul class="pagination pagination-sm"', $sNavString);
    ?>

	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$sNavString?><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><?=$sNavString?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>

    <?
    if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
    {
        ?>
        <?/*<div class="news-detail-share">
				<noindex>
					<?
					$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
						"HANDLERS" => $arParams["SHARE_HANDLERS"],
						"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
						"PAGE_TITLE" => $arResult["~NAME"],
						"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
						"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
						"HIDE" => $arParams["SHARE_HIDE"],
					),
						$component,
						array("HIDE_ICONS" => "Y")
					);
					?>
				</noindex>
			</div>*/?>
        <div class="news-detail-share">
            <noindex>
                <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
            </noindex>
        </div>
    <?
    }
    ?>

    <?if($arResult['PROPERTIES']['DISPLAY_ORDER_BLOCK']['VALUE_XML_ID'] == 'Y'):?>
        <table class="horizontal-order-buttons">
            <tr>
                <td class="center-col">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "horizontal_order_block",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?>
                </td>
                <td class="right-col">
                    <div class="btn-group-vertical">
                        <a href="<?=SITE_DIR?>order/service.php" class="btn btn-primary get-service-form" data-name="<?=htmlspecialchars($arResult['NAME'])?>">
                            <?=GetMessage('STATI_BTN_ORDER_SERVICE')?>
                        </a>
                        <a href="<?=SITE_DIR?>order/question.php" class="btn btn-default get-question-form" data-name="<?=htmlspecialchars($arResult['NAME'])?>">
                            <?=GetMessage('STATI_BTN_ORDER_QUESTION')?>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    <?else:?>
        <br /><br />
    <?endif?>



    <?if(is_array($arResult['PROPERTIES']['RELATED_SERVICES']['VALUE']) && count($arResult['PROPERTIES']['RELATED_SERVICES']['VALUE'])):?>
        <br />
        <?if ($arParams['TEXT_SERVICES']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_SERVICES']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

        <div class="list-catalog">
            <div class="row">
                <?
                $i = 0;
                $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_SERVICES']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
                    'ID', 'NAME', 'DETAIL_PAGE_URL', 'EDIT_LINK', 'DELETE_LINK', 'PREVIEW_PICTURE',
                    'PROPERTY_*'
                ));
                ?>
                <?while($ob = $res->GetNextElement()):?>
                    <?$arService = $ob->GetFields()?>
                    <?if ($arService):?>
                        <?
                        $arService['PROPERTIES'] = $ob->GetProperties();
                        $productTitle = $arService['NAME'];
                        $imgTitle = $arService['NAME'];

                        $sPreviewImage = !empty($arService['PREVIEW_PICTURE']) ? $arService['PREVIEW_PICTURE'] : $arService['DETAIL_PICTURE'];

                        if ($sPreviewImage)
                            $sPreviewImage = CFile::ResizeImageGet($sPreviewImage, array("width" => 300, "height" => 300));

                        if (empty($sPreviewImage))
                            $sPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                        ?>
                        <div class="col-sm-3">

                            <div class="item grid">

                                <div class="outer-image">
                                    <a class="image" href="<? echo $arService['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">
                                        <img src="<?=$sPreviewImage['src']?>" alt="<? echo $imgTitle; ?>">
                                    </a>
                                </div>

                                <a href="<? echo $arService['DETAIL_PAGE_URL']; ?>" class="name">
                                    <div class="inner">
                                        <h3>
                                        <span>
                                            <?=$productTitle?>
                                        </span>
                                        </h3>
                                    </div>
                                </a>

                            </div>

                        </div>

                        <?if(++$i % 4 == 0) { echo '</div><div class="row">'; }?>
                    <?endif?>
                <?endwhile?>
            </div>
        </div>
    <?endif?>





    <?if(is_array($arResult['PROPERTIES']['RELATED_PRODUCTS']['VALUE']) && count($arResult['PROPERTIES']['RELATED_PRODUCTS']['VALUE'])):?>
        <br />
        <?if ($arParams['TEXT_PRODUCTS']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_PRODUCTS']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

        <div class="list-catalog">
            <div class="row">
                <?
                $i = 0;
                $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_PRODUCTS']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
                    'ID', 'NAME', 'DETAIL_PAGE_URL', 'EDIT_LINK', 'DELETE_LINK', 'PREVIEW_PICTURE',
                    'PROPERTY_*'
                ));
                ?>
                <?while($ob = $res->GetNextElement()):?>
                    <?$arProduct = $ob->GetFields()?>
                    <?if ($arProduct):?>
                        <?
                        $arProduct['PROPERTIES'] = $ob->GetProperties();
                        $productTitle = $arProduct['NAME'];
                        $imgTitle = $arProduct['NAME'];

                        $sFirstPhotoId = current($arProduct['PROPERTIES']['MORE_PHOTO']['VALUE']);
                        $aFirstPhoto   = CFile::GetFileArray($sFirstPhotoId);

                        $sPreviewImage = !empty($arProduct['PREVIEW_PICTURE']) ? $arProduct['PREVIEW_PICTURE'] : $aFirstPhoto;

                        if ($sPreviewImage)
                            $sPreviewImage = CFile::ResizeImageGet($sPreviewImage, array("width" => 300, "height" => 300));

                        if (empty($sPreviewImage))
                            $sPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                        $sOldPrice = $arProduct['PROPERTIES']['OLD_PRICE']['VALUE'];
                        $sNewPrice = $arProduct['PROPERTIES']['NEW_PRICE']['VALUE'];

                        $sPrice = !empty($sNewPrice) ? $sNewPrice : $sOldPrice;

                        $iIsPrice    = !empty($sNewPrice) || !empty($sOldPrice);
                        $iIsOnePrice = empty($sOldPrice) || empty($sNewPrice);
                        $iIsStatus   = !empty($arProduct['PROPERTIES']['STATUS']['VALUE_XML_ID']) && !empty($arProduct['PROPERTIES']['STATUS']['VALUE']);

                        $arStickers = array();
                        if ( ! empty($arProduct['PROPERTIES']['OFFERS']['VALUE_XML_ID'])) {
                            $arStickers = array_combine($arProduct['PROPERTIES']['OFFERS']['VALUE_XML_ID'], $arProduct['PROPERTIES']['OFFERS']['VALUE_ENUM']);
                        }
                        ?>
                        <div class="col-sm-3">

                            <div class="item grid stickers-outer">

                                <div class="outer-image stickers-relative">
                                    <a class="image" href="<? echo $arProduct['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">
                                        <img src="<?=$sPreviewImage['src']?>" alt="<? echo $imgTitle; ?>">

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
                                    </a>
                                </div>

                                <a href="<? echo $arProduct['DETAIL_PAGE_URL']; ?>" class="name">
                                    <div class="inner">
                                        <h3>
                                        <span>
                                            <?=$productTitle?>
                                        </span>
                                        </h3>
                                    </div>
                                </a>

                                <a href="<? echo $arProduct['DETAIL_PAGE_URL']; ?>" class="price-status">
                                    <div class="inner">

                                        <?if(empty($arProduct['PROPERTIES']['OLD_PRICE']['VALUE']) && empty($arProduct['PROPERTIES']['NEW_PRICE']['VALUE'])):?>
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
                                                    <?=formatMoney($sOldPrice).$arParams['PRICE_SUFFIX']?>
                                                </div>
                                            <?endif;?>

                                        <?endif?>

                                        <?if($arProduct['PROPERTIES']['STATUS']['VALUE_XML_ID'] && $arProduct['PROPERTIES']['STATUS']['VALUE']):?>
                                            <div>
                                            <span class="label <?=$arProduct['PROPERTIES']['STATUS']['VALUE_XML_ID']?>">
                                                <?=$arProduct['PROPERTIES']['STATUS']['VALUE']?>
                                            </span>
                                            </div>
                                        <?endif?>
                                    </div>
                                </a>

                            </div>

                        </div>

                        <?if(++$i % 4 == 0) { echo '</div><div class="row">'; }?>
                    <?endif?>
                <?endwhile?>
            </div>
        </div>
    <?endif?>





    <?if (!empty($arResult['PROPERTIES']['GALLERY']['VALUE'])):?>
        <?if ($arParams['TEXT_GALLERY']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_GALLERY']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

        <div class="catalog-element-gallery<?=(count($arResult['PROPERTIES']['GALLERY']['VALUE']) == 4 ? ' one-row' : '')?>">
            <div class="row">
                <?$i = 0;foreach($arResult['PROPERTIES']['GALLERY']['VALUE'] as $sImageId):?>
                    <?
                    $aOriginalImage = CFile::GetFileArray($sImageId);
                    if (empty($aOriginalImage))
                        continue;

                    $sImageText = $aOriginalImage['DESCRIPTION'];

                    $aThumb = CFile::ResizeImageGet($aOriginalImage, array("width" => 300, "height" => 300));
                    ?>
                    <div class="col-sm-3">
                        <div class="image">
                            <div class="aimg-hover magnific-gallery">
                                <div class="aimg-overlay"></div>
                                <img class="img-thumbnail" src="<?=$aThumb['src']?>" alt="<?=$sImageText?>">
                                <div class="aimg-row">
                                    <a href="<?=$aOriginalImage['SRC']?>" class="aimg-fullscreen" title="<?=$sImageText?>">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?=(++$i % 4 == 0 ? '</div><div class="row">' : '')?>
                <?endforeach?>
            </div>
        </div>
    <?endif?>

    <?if (!empty($arResult['DOCS'])):?>
        <br /><br />
        <?if ($arParams['TEXT_DOCS']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_DOCS']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

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

	<?if(!empty($arResult["FIELDS"])):?>
        <br />
		<dl class="dl-horizontal">
			<?foreach($arResult["FIELDS"] as $code=>$value):
				if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
				{
					?><dt><?=GetMessage("IBLOCK_FIELD_".$code)?>:</dt><?
					if (!empty($value) && is_array($value))
					{
						$aValuePicture = CFile::ResizeImageGet($value, array("width" => 250, "height" => 250));
						?><dd><img border="0" src="<?=$aValuePicture['src']?>"></dd><?
					}
				}
				else
				{
					?><dt><?=GetMessage("IBLOCK_FIELD_".$code)?>:</dt><dd><?=$value;?></dd><?
				}
				?>
			<?endforeach;?>
		</dl>
	<?endif;?>

	<?if(!empty($arResult["DISPLAY_PROPERTIES"])):?>
        <br />
		<dl class="dl-horizontal">
			<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
				<dt><?=$arProperty["NAME"]?>:</dt>
				<dd>
					<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
						<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
					<?else:?>
						<?=$arProperty["DISPLAY_VALUE"];?>
					<?endif?>
				</dd>
			<?endforeach;?>
		</dl>
	<?endif;?>

</div>