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

$arDetailPicture = $arResult['DETAIL_PICTURE'];
$arDetailPictureThumb = false;
$iIsDetailPicture = ! empty($arDetailPicture);

if ($iIsDetailPicture)
    $arDetailPictureThumb = CFile::ResizeImageGet($arDetailPicture, array("width" => 250, "height" => 300));

?>

<div class="services-view">

    <?if ($iIsDetailPicture):?>
        <div class="image">
            <div class="aimg-hover magnific-gallery">
                <div class="aimg-overlay"></div>
                <img class="img-thumbnail" src="<?=$arDetailPictureThumb['src']?>" alt="<?=$strAlt?>">
                <div class="aimg-row">
                    <a href="<?=$arDetailPicture['SRC']?>" target="_blank" class="aimg-fullscreen" title="<?=$strTitle?>">
                        <i class="fa fa-search-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    <?endif?>

    <?
    if ('' != $arResult['PREVIEW_TEXT']) {
        if (
            'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
            || ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
        )
        {
            ?>
            <div class="description">
                <?
                echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
                ?>
            </div>
        <?
        }
    }
    ?>

    <? if ('' != $arResult['DETAIL_TEXT']) { ?>
        <div class="description">
            <?
            if ('html' == $arResult['DETAIL_TEXT_TYPE'])
            {
                echo $arResult['DETAIL_TEXT'];
            }
            else
            {
                ?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
            }
            ?>
        </div>
    <? } ?>

    <?if($arResult['PROPERTIES']['DISPLAY_ORDER_BLOCK']['VALUE'] == 'Y'):?>
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
                        <a href="<?=SITE_DIR?>order/service.php" class="btn btn-primary get-service-form" data-name="<?=$arResult['NAME']?>">
                            <?=GetMessage('CT_BCE_CATALOG_BTN_MESSAGE_ORDER_SERVICE')?>
                        </a>
                        <a href="<?=SITE_DIR?>order/question.php" class="btn btn-default get-question-form" data-name="<?=$arResult['NAME']?>">
                            <?=GetMessage('CT_BCE_CATALOG_BTN_MESSAGE_ORDER_QUESTION')?>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    <?endif?>

</div>



<?if (!empty($arResult['PROPERTIES']['GALLERY']['VALUE'])):?>
    <br /><br />
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
    <br />
<?endif?>



<?if($arResult['PROPERTIES']['RELATED_TEAM']['VALUE']):?>
    <br /><br />
    <?if ($arParams['TEXT_TEAM']):?>
        <div class="page-header">
            <h2 class="lead big"><?=$arParams['TEXT_TEAM']?></h2>
        </div>
    <?else:?>
        <div class="clearfix"></div>
        <br /><br />
    <?endif?>

    <div class="our-team-list">

        <div class="items without-section">
            <div class="row">

                <?$i = 0;?>
                <?
                $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_TEAM']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
                    'ID', 'NAME', 'DETAIL_PAGE_URL', 'EDIT_LINK', 'DELETE_LINK', 'PREVIEW_PICTURE', 'PREVIEW_TEXT',
                    'PROPERTY_*'
                ));
                ?>
                <?while($ob = $res->GetNextElement()):?>
                    <?$arTeam = $ob->GetFields();?>
                    <?if($arTeam):?>

                        <div class="col-sm-4">
                            <?
                                $arTeam['PROPERTIES'] = $ob->GetProperties();
                                $aPreviewImage = !empty($arTeam['PREVIEW_PICTURE']) ? $arTeam['PREVIEW_PICTURE'] : false;

                                if ($aPreviewImage)
                                    $aPreviewImage = CFile::ResizeImageGet($aPreviewImage, array("width" => 300, "height" => 300));

                                if (empty($aPreviewImage))
                                    $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                                $aEmails = null;
                                if($arTeam['PROPERTIES']['EMAILS']['VALUE']) {
                                    $aEmails = trim($arTeam['PROPERTIES']['EMAILS']['VALUE'], ',');
                                    $aEmails = explode(',', $aEmails);
                                }

                                $aSocs = array();
                                if($arTeam['PROPERTIES']['SOC_VK']['VALUE']) {
                                    $aSocs['vkontakte'] = $arTeam['PROPERTIES']['SOC_VK']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_FB']['VALUE']) {
                                    $aSocs['facebook'] = $arTeam['PROPERTIES']['SOC_FB']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_OK']['VALUE']) {
                                    $aSocs['odnoklassniki'] = $arTeam['PROPERTIES']['SOC_OK']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_TW']['VALUE']) {
                                    $aSocs['twitter'] = $arTeam['PROPERTIES']['SOC_TW']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_GP']['VALUE']) {
                                    $aSocs['googleplus'] = $arTeam['PROPERTIES']['SOC_GP']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_MR']['VALUE']) {
                                    $aSocs['mail'] = $arTeam['PROPERTIES']['SOC_MR']['VALUE'];
                                }
                            ?>

                            <div class="item">

                                <div class="outer-image">
                                    <a href="<?=$arTeam['DETAIL_PAGE_URL']?>" class="image">
                                        <img class="img-responsive" src="<?=$aPreviewImage['src']?>" alt="<?=$arTeam['NAME']?>">
                                    </a>
                                </div>

                                <div class="text">

                                    <div class="fio clearfix">

                                        <?if( ! empty($aSocs)):?>
                                            <ul class="ssm">
                                                <?foreach($aSocs as $aSoc => $aSocLink):?>
                                                    <li class="<?=$aSoc?>">
                                                        <a href="<?=$aSocLink?>" rel="nofollow" target="_blank"></a>
                                                    </li>
                                                <?endforeach?>
                                            </ul>
                                        <?endif?>

                                        <a class="name" href="<?=$arTeam['DETAIL_PAGE_URL']?>"><?=$arTeam['NAME']?></a>
                                        <?if($arTeam['PROPERTIES']['PROFESSION']['VALUE']):?>
                                            <div class="prof"><?=$arTeam['PROPERTIES']['PROFESSION']['VALUE']?></div>
                                        <?endif?>
                                    </div>

                                    <?if($arTeam['PROPERTIES']['PHONES']['VALUE'] ||  ! empty($aEmails)):?>
                                        <div class="contacts-outer">
                                            <ul class="contacts">
                                                <?if($arTeam['PROPERTIES']['PHONES']['VALUE']):?>
                                                    <li>
                                                            <span class="icon">
                                                                <i class="fa fa-phone-square"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$arTeam['PROPERTIES']['PHONES']['VALUE']?>
                                                            </span>
                                                    </li>
                                                <?endif?>
                                                <?if($arTeam['PROPERTIES']['SKYPE']['VALUE']):?>
                                                    <li>
                                                            <span class="icon">
                                                                <i class="fa fa-skype"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$arTeam['PROPERTIES']['SKYPE']['VALUE']?>
                                                            </span>
                                                    </li>
                                                <?endif?>
                                                <?if( ! empty($aEmails)):?>
                                                    <li>
                                                            <span class="icon">
                                                                <i class="fa fa-envelope-square"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?$ie=0;foreach($aEmails as $sEmail):?>
                                                                    <a href="mailto:<?=$sEmail?>">
                                                                        <?=$sEmail?>
                                                                    </a>
                                                                    <?=(++$ie != count($aEmails) ? ',' : '')?>
                                                                <?endforeach?>
                                                            </span>
                                                    </li>
                                                <?endif?>
                                            </ul>
                                        </div>
                                    <?endif?>

                                    <div class="description text-center">
                                        <a href="<?=SITE_DIR?>order/question.php" class="btn btn-primary get-question-form" data-name="<?=$arResult['NAME']?>">
                                            <?=GetMessage('CT_BCE_CATALOG_BTN_MESSAGE_ORDER_QUESTION')?>
                                        </a>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <?if(++$i % 4 == 0) { echo '</div><div class="row">'; }?>
                    <?endif?>
                <?endwhile?>
            </div>
        </div>

    </div>

<?endif?>



<?if(is_array($arResult['PROPERTIES']['RELATED_SERVICES']['VALUE']) && count($arResult['PROPERTIES']['RELATED_SERVICES']['VALUE'])):?>
    <br /> <br />
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



<?if(is_array($arResult['PROPERTIES']['RELATED_PORTFOLIO']['VALUE']) && count($arResult['PROPERTIES']['RELATED_PORTFOLIO']['VALUE'])):?>
    <br /><br />
    <?if ($arParams['TEXT_PORTFOLIO']):?>
        <div class="page-header">
            <h2 class="lead big"><?=$arParams['TEXT_PORTFOLIO']?></h2>
        </div>
    <?else:?>
        <div class="clearfix"></div>
        <br /><br />
    <?endif?>

    <div class="list-catalog">
        <div class="row">
            <?
            $i = 0;
            $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_PORTFOLIO']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
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
    <br /><br />
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