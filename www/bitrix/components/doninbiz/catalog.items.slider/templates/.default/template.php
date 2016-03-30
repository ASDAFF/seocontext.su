<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
?>


<?if ( ! empty($arResult)):?>

    <div class="home-best-products-tabs">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs light" role="tablist">
            <?$i=0;foreach($arResult as $sType => $arItem):?>

                <?if($arParams['USE_' . $sType] == 'N') continue?>

                <li role="presentation"<?if(++$i == 1):?> class="active"<?endif?>>
                    <a href="#tab_<?=$sType?>" aria-controls="tab_<?=$sType?>" role="tab" data-toggle="tab" data-to="home-best-products-slider-<?=$sType?>">
                        <i class="flaticon-<?if ($sType == 'new'):?>new105<?elseif($sType == 'hit'):?>first31<?elseif($sType == 'discount'):?>discount5<?elseif($sType == 'best_price'):?>good<?endif?>"></i>
                        <?=$arItem['INFO']['VALUE']?>
                    </a>
                </li>
            <?endforeach?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content light">
            <?$i=0;foreach($arResult as $sType => $arItems):?>

                <?if($arParams['USE_' . $sType] == 'N') continue?>

                <div role="tabpanel" class="tab-pane<?if(++$i == 1):?> active<?endif?>" id="tab_<?=$sType?>">

                <div class="list-catalog">
                    <div class="home-best-products-slider" id="home-best-products-slider-<?=$sType?>">
                        <?foreach($arItems['ITEMS'] as $arProduct):?>
                            <?
                            $this->AddEditAction($arProduct['ID'], $arProduct['EDIT_LINK'], CIBlock::GetArrayByID($arProduct["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($arProduct['ID'], $arProduct['DELETE_LINK'], CIBlock::GetArrayByID($arProduct["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('FHPS_BNL_ELEMENT_DELETE_CONFIRM')));

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
                            <div class="item grid stickers-outer" id="<?=$this->GetEditAreaId($arProduct['ID']);?>">

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
                        <?endforeach?>
                    </div>
                </div>

                </div>
            <?endforeach?>
        </div>

    </div>

<?endif?>