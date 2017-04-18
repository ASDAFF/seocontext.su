<div class="item list stickers-outer" id="<?=$strMainID?>">
    <div class="outer">
        <div class="inner">
            <div class="left stickers-relative">
                <a class="image" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">

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

                    <img src="<?=$sPreviewImage['src']?>" alt="<? echo $imgTitle; ?>">
                </a>
            </div>
            <div class="right">

                <div class="price-name">
                    <div class="tr">
                        <div class="name-td">
                            <h3>
                                <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>">
                                    <span>
                                        <?=$productTitle?>
                                    </span>
                                    <?if($arResult['SECTION']['NAME']):?>
                                        <small>
                                            <?=$arResult['SECTION']['NAME']?>
                                        </small>
                                    <?endif?>
                                </a>
                            </h3>
                        </div>
                        <div class="price-td">
                            <?if(empty($arItem['PROPERTIES']['OLD_PRICE']['VALUE']) && empty($arItem['PROPERTIES']['NEW_PRICE']['VALUE'])):?>
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

                            <?if($arItem['PROPERTIES']['STATUS']['VALUE_XML_ID'] && $arItem['PROPERTIES']['STATUS']['VALUE']):?>
                                <div class="outer-label">
                                                                <span class="label <?=$arItem['PROPERTIES']['STATUS']['VALUE_XML_ID']?>">
                                                                    <?=$arItem['PROPERTIES']['STATUS']['VALUE']?>
                                                                </span>
                                </div>
                            <?endif?>
                        </div>
                    </div>
                </div>

                <div class="desc">
                    <?=$arItem['PREVIEW_TEXT']?>
                </div>

                <div class="rating-more">
                    <div class="tr">
                        <div class="rating hidden-xs">
                            <?if($useVoteRating):?>
                                <?$APPLICATION->IncludeComponent(
                                    "doninbiz:iblock.vote",
                                    "stars",
                                    array(
                                        "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                                        "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                        "ELEMENT_ID" => $arItem['ID'],
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
                            <?endif?>
                        </div>
                        <div class="more">
                            <a class="btn btn-primary get-buy-form" href="<?=SITE_DIR?>order/product.php" data-name="<?=$arItem["NAME"]?>">
                                <?=$arParams['TEXT_ORDER']?>
                            </a>
                            <a class="btn btn-default" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>">
                                <?=$arParams['MESS_BTN_DETAIL']?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>