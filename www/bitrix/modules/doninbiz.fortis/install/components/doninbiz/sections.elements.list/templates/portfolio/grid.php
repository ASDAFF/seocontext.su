<div class="grid items">
    <div class="row">
        <?$i=0;foreach($aSection['ITEMS'] as $aItem):?>
            <div class="col-sm-6">
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
                                <?=$aItem['NAME']?>
                            </div>

                            <?if($aItem['PREVIEW_TEXT']):?>
                                <div class="description">
                                    <?=$aItem['PREVIEW_TEXT']?>
                                </div>
                            <?endif?>

                            <?if($aItem["DETAIL_PAGE_URL"]):?>
                                <div class="buttons">
                                    <a href="<?=$aItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary"><?=GetMessage('FP_READ_MORE')?></a>
                                </div>
                            <?endif?>

                        </div>

                    </div>

                </div>
            </div>
            <?=(++$i % 2 == 0 ? '</div><div class="row">' : '')?>
        <?endforeach;?>
    </div>
</div>