<div class="col-sm-3" id="<?=$strMainID?>">

    <div class="item grid stickers-outer">

        <div class="outer-image stickers-relative">
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

        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="name">
            <div class="inner">
                <h3>
                    <span>
                        <?=$productTitle?>
                    </span>
                    <?if($arResult['SECTION']['NAME']):?>
                        <small>
                            <?=$arResult['SECTION']['NAME']?>
                        </small>
                    <?endif?>
                </h3>
            </div>
        </a>

        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" class="price-status">
            <div class="inner">

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
                    <div>
                                                    <span class="label <?=$arItem['PROPERTIES']['STATUS']['VALUE_XML_ID']?>">
                                                        <?=$arItem['PROPERTIES']['STATUS']['VALUE']?>
                                                    </span>
                    </div>
                <?endif?>
            </div>
        </a>

        <?if($useVoteRating):?>
            <div class="more-rating clearfix">
                <div class="inner">
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

                    <a class="more" href="<? echo $arItem['DETAIL_PAGE_URL']; ?>">
                        <span><?=$arParams['MESS_BTN_DETAIL']?></span> <i class="fa fa-angle-double-right hidden-sm hidden-md"></i>
                    </a>
                </div>
            </div>
        <?endif?>

    </div>

</div>