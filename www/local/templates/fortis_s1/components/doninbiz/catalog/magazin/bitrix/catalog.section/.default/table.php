<tr id="<?=$strMainID?>">
    <td class="image">
        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">
            <img class="img-responsive" src="<?=$sPreviewImage['src']?>" alt="<? echo $imgTitle; ?>">
        </a>
    </td>
    <td class="name">
        <a href="<? echo $arItem['DETAIL_PAGE_URL']; ?>">
            <?=$productTitle?>
        </a>
    </td>
    <td class="price">
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
            <span class="visible-xs label <?=$arItem['PROPERTIES']['STATUS']['VALUE_XML_ID']?>">
                                            <?=$arItem['PROPERTIES']['STATUS']['VALUE']?>
                                        </span>
        <?endif?>
    </td>
    <td class="status hidden-xs">
        <?if($arItem['PROPERTIES']['STATUS']['VALUE_XML_ID'] && $arItem['PROPERTIES']['STATUS']['VALUE']):?>
            <span class="label <?=$arItem['PROPERTIES']['STATUS']['VALUE_XML_ID']?>">
                                            <?=$arItem['PROPERTIES']['STATUS']['VALUE']?>
                                        </span>
        <?endif?>
    </td>
</tr>