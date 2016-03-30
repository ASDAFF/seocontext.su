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

<div class="partners-list">

    <?if( ! empty($arResult['SECTIONS'])):?>

        <?$is = 0; foreach($arResult['SECTIONS'] as $aSection):?>
            <?
            $this->AddEditAction($aSection['ID'], $aSection['EDIT_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($aSection['ID'], $aSection['DELETE_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
            ?>

            <div class="section" id="<?=$this->GetEditAreaId($aSection['ID']);?>">
                <h2>
                    <?=$aSection['NAME']?>
                </h2>

                <div class="description">
                    <?=$aSection['DESCRIPTION']?>
                </div>
            </div>

            <?if ( ! empty($aSection['ITEMS'])):?>

                <div class="items">
                    <?foreach($aSection['ITEMS'] as $aItem):?>
                        <?
                            $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                            // лого партнера
                            $aPreviewImage = !empty($aItem['PREVIEW_PICTURE']) ? $aItem['PREVIEW_PICTURE'] : false;
                            if (is_numeric($aPreviewImage))
                                $aPreviewImage = CFile::GetFileArray($aPreviewImage);

                            $sPreviewImageAlt = !empty($aPreviewImage["DESCRIPTION"]) ? $aPreviewImage["DESCRIPTION"] : $aItem['NAME'];
                            $sPreviewImageTitle = $sPreviewImageAlt;

                            $aPreviewImageThumb = null;
                            if ($aPreviewImage)
                                $aPreviewImageThumb = CFile::ResizeImageGet($aPreviewImage, array("width" => 250, "height" => 200));

                            // изображение в детальном тексте
                            $aDetailImage = !empty($aItem['DETAIL_PICTURE']) ? $aItem['DETAIL_PICTURE'] : false;
                            if (is_numeric($aDetailImage))
                                $aDetailImage = CFile::GetFileArray($aDetailImage);

                            $sDetailImageAlt = !empty($aDetailImage["DESCRIPTION"]) ? $aDetailImage["DESCRIPTION"] : $aItem['NAME'];
                            $sDetailImageTitle = $sDetailImageAlt;

                            $aDetailImageThumb = null;
                            if ($aDetailImage)
                                $aDetailImageThumb = CFile::ResizeImageGet($aDetailImage, array("width" => 150, "height" => 150));

                            $sDescription = !empty($aItem['PREVIEW_TEXT']) ? $aItem['PREVIEW_TEXT'] : null;
                            $sText = !empty($aItem['DETAIL_TEXT']) ? $aItem['DETAIL_TEXT'] : null;

                            $aSites = null;
                            if($aItem['PROPERTY_LINKS_VALUE'] && $aItem['PROPERTY_LINKS_VALUE'] != 'http://') {
                                $aSites = trim($aItem['PROPERTY_LINKS_VALUE'], ',');
                                $aSites = explode(',', $aSites);
                            }

                            /*if (empty($aPreviewImage))
                                $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';*/

                        ?>

                        <div class="row item" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                            <?if( ! empty($aPreviewImage) && ! empty($aPreviewImageThumb)):?>
                                <div class="col-sm-4" id="partner-<?=$aItem['ID']?>">

                                    <div class="image">
                                        <img
                                            class="img-responsive"
                                            border="0"
                                            src="<?=$aPreviewImageThumb['src']?>"
                                            alt="<?=$sPreviewImageAlt?>"
                                            title="<?=$sPreviewImageTitle?>">
                                    </div>

                                    <?if($aItem['PROPERTY_PHONE_VALUE'] ||  ! empty($aSites)):?>
                                        <div class="contacts-outer">
                                            <ul class="contacts">
                                                <?if($aItem['PROPERTY_PHONE_VALUE']):?>
                                                    <li>
                                                        <span class="icon">
                                                            <i class="fa fa-phone-square"></i>
                                                        </span>
                                                        <span class="cont-text">
                                                            <?=$aItem['PROPERTY_PHONE_VALUE']?>
                                                        </span>
                                                    </li>
                                                <?endif?>
                                                <?if( ! empty($aSites)):?>
                                                    <li>
                                                        <span class="icon">
                                                            <i class="fa fa-external-link-square"></i>
                                                        </span>
                                                        <span class="cont-text">
                                                            <?$ie=0;foreach($aSites as $sSite):?>
                                                                <a href="<?=$sSite?>" target="_blank" rel="nofollow">
                                                                    <?=$sSite?>
                                                                </a>
                                                                <?=(++$ie != count($aSites) ? ',' : '')?>
                                                            <?endforeach?>
                                                        </span>
                                                    </li>
                                                <?endif?>
                                            </ul>
                                        </div>
                                    <?endif?>

                                </div>
                            <?endif?>

                            <div class="<?if( ! empty($aPreviewImage) && ! empty($aPreviewImageThumb)):?>col-sm-8<?else:?>col-xs-12<?endif?>">
                                <div class="description">
                                    <h3 class="name"><?=$aItem['NAME']?></h3>
                                    <?=$sDescription?>
                                </div>
                                <?if ( ! empty($sText)):?>
                                    <div class="full-text">
                                        <div class="text">
                                            <?if( ! empty($aDetailImage) && ! empty($aDetailImageThumb)):?>
                                                <div class="text-image">
                                                    <div class="aimg-hover">
                                                        <div class="aimg-overlay"></div>
                                                        <img
                                                            class="img-thumbnail"
                                                            border="0"
                                                            src="<?=$aDetailImageThumb['src']?>"
                                                            alt="<?=$sDetailImageAlt?>"
                                                            title="<?=$sDetailImageTitle?>">
                                                        <div class="aimg-row">
                                                            <a href="<?=$aDetailImage["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$sDetailImageTitle?>"><i class="fa fa-search-plus"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?endif?>
                                            <?=$sText?>
                                        </div>
                                        <a href="#" class="partner-detail-toggle">
                                            <span><?=GetMessage('CT_BNL_MORE_INFO')?></span>
                                            <i class="down fa fa-angle-double-down"></i>
                                            <i class="up fa fa-angle-double-up"></i>
                                        </a>
                                    </div>
                                <?endif?>
                            </div>

                        </div>
                        <hr />
                    <?endforeach;?>
                </div>
            <?endif?>
            <?if( ++$is != count($arResult['SECTIONS'])):?>
                <br /><br />
            <?endif?>
        <?endforeach?>

        <?if( ! empty($arResult['ITEMS'])):?>
            <br /><br />
            <hr />
        <?endif?>

    <?endif?>

    <?if( ! empty($arResult['ITEMS'])):?>

        <div class="items without-section">
            <?foreach($arResult['ITEMS'] as $aItem):?>
                <?
                $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                // лого партнера
                $aPreviewImage = !empty($aItem['PREVIEW_PICTURE']) ? $aItem['PREVIEW_PICTURE'] : false;
                if (is_numeric($aPreviewImage))
                    $aPreviewImage = CFile::GetFileArray($aPreviewImage);

                $sPreviewImageAlt = !empty($aPreviewImage["DESCRIPTION"]) ? $aPreviewImage["DESCRIPTION"] : $aItem['NAME'];
                $sPreviewImageTitle = $sPreviewImageAlt;

                $aPreviewImageThumb = null;
                if ($aPreviewImage)
                    $aPreviewImageThumb = CFile::ResizeImageGet($aPreviewImage, array("width" => 250, "height" => 200));

                // изображение в детальном тексте
                $aDetailImage = !empty($aItem['DETAIL_PICTURE']) ? $aItem['DETAIL_PICTURE'] : false;
                if (is_numeric($aDetailImage))
                    $aDetailImage = CFile::GetFileArray($aDetailImage);

                $sDetailImageAlt = !empty($aDetailImage["DESCRIPTION"]) ? $aDetailImage["DESCRIPTION"] : $aItem['NAME'];
                $sDetailImageTitle = $sDetailImageAlt;

                $aDetailImageThumb = null;
                if ($aDetailImage)
                    $aDetailImageThumb = CFile::ResizeImageGet($aDetailImage, array("width" => 150, "height" => 150));

                $sDescription = !empty($aItem['PREVIEW_TEXT']) ? $aItem['PREVIEW_TEXT'] : null;
                $sText = !empty($aItem['DETAIL_TEXT']) ? $aItem['DETAIL_TEXT'] : null;

                $aSites = null;
                if($aItem['PROPERTY_LINKS_VALUE']) {
                    $aSites = trim($aItem['PROPERTY_LINKS_VALUE'], ',');
                    $aSites = explode(',', $aSites);
                }

                /*if (empty($aPreviewImage))
                    $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';*/

                ?>

                <div class="row item" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                    <?if( ! empty($aPreviewImage) && ! empty($aPreviewImageThumb)):?>
                        <div class="col-sm-4">

                            <div class="image">
                                <img
                                    class="img-responsive"
                                    border="0"
                                    src="<?=$aPreviewImageThumb['src']?>"
                                    alt="<?=$sPreviewImageAlt?>"
                                    title="<?=$sPreviewImageTitle?>">
                            </div>

                            <?if($aItem['PROPERTY_PHONE_VALUE'] ||  ! empty($aSites)):?>
                                <div class="contacts-outer">
                                    <ul class="contacts">
                                        <?if($aItem['PROPERTY_PHONE_VALUE']):?>
                                            <li>
                                                        <span class="icon">
                                                            <i class="fa fa-phone-square"></i>
                                                        </span>
                                                        <span class="cont-text">
                                                            <?=$aItem['PROPERTY_PHONE_VALUE']?>
                                                        </span>
                                            </li>
                                        <?endif?>
                                        <?if( ! empty($aSites)):?>
                                            <li>
                                                        <span class="icon">
                                                            <i class="fa fa-external-link-square"></i>
                                                        </span>
                                                        <span class="cont-text">
                                                            <?$ie=0;foreach($aSites as $sSite):?>
                                                                <?if($sSite == 'http://') { $sSite = 'http://' . $_SERVER['SERVER_NAME']; }?>
                                                                <a href="<?=$sSite?>" target="_blank" rel="nofollow">
                                                                    <?=$sSite?>
                                                                </a>
                                                                <?=(++$ie != count($aSites) ? ',' : '')?>
                                                            <?endforeach?>
                                                        </span>
                                            </li>
                                        <?endif?>
                                    </ul>
                                </div>
                            <?endif?>

                        </div>
                    <?endif?>

                    <div class="<?if( ! empty($aPreviewImage) && ! empty($aPreviewImageThumb)):?>col-sm-8<?else:?>col-xs-12<?endif?>">
                        <div class="description">
                            <h3 class="name"><?=$aItem['NAME']?></h3>
                            <?=$sDescription?>
                        </div>
                        <?if ( ! empty($sText)):?>
                            <div class="full-text">
                                <div class="text">
                                    <?if( ! empty($aDetailImage) && ! empty($aDetailImageThumb)):?>
                                        <div class="text-image">
                                            <div class="aimg-hover">
                                                <div class="aimg-overlay"></div>
                                                <img
                                                    class="img-thumbnail"
                                                    border="0"
                                                    src="<?=$aDetailImageThumb['src']?>"
                                                    alt="<?=$sDetailImageAlt?>"
                                                    title="<?=$sDetailImageTitle?>">
                                                <div class="aimg-row">
                                                    <a href="<?=$aDetailImage["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$sDetailImageTitle?>"><i class="fa fa-search-plus"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?endif?>
                                    <?=$sText?>
                                </div>
                                <a href="#" class="partner-detail-toggle">
                                    <span><?=GetMessage('CT_BNL_MORE_INFO')?></span>
                                    <i class="down fa fa-angle-double-down"></i>
                                    <i class="up fa fa-angle-double-up"></i>
                                </a>
                            </div>
                        <?endif?>
                    </div>

                </div>
                <hr />
            <?endforeach;?>
        </div>
    <?endif?>

</div>