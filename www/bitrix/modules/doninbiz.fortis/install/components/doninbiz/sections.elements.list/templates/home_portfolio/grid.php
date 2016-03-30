
<?
    $sBColumn = "col-sm-{$iBootstrapColumn}";
    switch($iColumns) {
        case 4:
            $sBColumn = "col-lg-3 col-md-4 col-sm-6 col-xs-12";
            break;
        case 3:
            $sBColumn = "col-md-4 col-sm-6 col-xs-12";
            break;
        case 2:
            $sBColumn = "col-sm-6 col-xs-12";
            break;
        case 1:
            $sBColumn = "col-xs-12";
            break;
    }
?>

<div class="row">
    <ul class="grid items columns-<?=$iColumns?>">
        <?foreach($arResult['ALL_ITEMS'] as $aItem):?>
            <li class="<?=$sBColumn?> iso-item iso-section-<?=$aItem['SECTION_ID']?>">
                <?
                $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                $aPreviewImage = !empty($aItem['PREVIEW_PICTURE']) ? CFile::GetFileArray($aItem['PREVIEW_PICTURE']) : false;

                $aPreviewImageThumb = null;

                $min_size = 408;
                $sCustomBackgroundSize = null;
                if ($aPreviewImage) {
                    $aPreviewImageThumb = CFile::ResizeImageGet($aPreviewImage, array("width" => $min_size, "height" => $min_size), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);

                    if ($aPreviewImageThumb['width'] < 400 || $aPreviewImageThumb['height'] < 250)
                        $sCustomBackgroundSize = ' background-size: auto;';
                }

                if (empty($aPreviewImageThumb)) {
                    $aPreviewImageThumb['src'] = $this->GetFolder().'/images/no_photo.png';
                    $sCustomBackgroundSize = ' background-size: auto;';
                }
                ?>

                <div class="item-grid" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                    <div class="inner">

                        <div class="left-col">

                            <div class="aimg-hover magnific-gallery">
                                <div class="aimg-overlay"></div>
                                <div class="image-background hidden-xs" style="background-image: url(<?=$aPreviewImageThumb['src']?>);<?=$sCustomBackgroundSize?>">
                                    &nbsp;
                                </div>
                                <img src="<?=$aPreviewImageThumb['src']?>" class="img-responsive center-block visible-xs" alt="<?=$aItem['NAME']?>">
                                <div class="aimg-row">
                                    <?if($aPreviewImage):?>
                                        <a href="<?=$aPreviewImage["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$aItem['NAME']?>"><i class="fa fa-search-plus"></i></a>
                                    <?endif?>
                                    <a href="<?=$aItem["DETAIL_PAGE_URL"]?>" class="aimg-link"><i class="fa fa-link"></i></a>
                                </div>
                            </div>

                        </div>

                        <div class="right-col">

                            <div class="name">
                                <?if($aItem["DETAIL_PAGE_URL"]):?>
                                    <a href="<?=$aItem["DETAIL_PAGE_URL"]?>">
                                        <?=$aItem['NAME']?>
                                    </a>
                                <?else:?>
                                    <?=$aItem['NAME']?>
                                <?endif?>
                            </div>

                            <?if($aItem['PREVIEW_TEXT'] && $arParams['SHOW_DESC'] == 'Y'):?>
                                <div class="description">
                                    <?=$aItem['PREVIEW_TEXT']?>
                                </div>
                            <?endif?>

                            <?if($aItem["DETAIL_PAGE_URL"] && $arParams['SHOW_BUTTON'] == 'Y'):?>
                                <div class="buttons">
                                    <a href="<?=$aItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary"><?=GetMessage('FP_READ_MORE')?></a>
                                </div>
                            <?endif?>

                        </div>

                    </div>

                </div>
            </li>
        <?endforeach;?>
    </ul>
</div>