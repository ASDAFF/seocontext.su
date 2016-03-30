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

<div class="licenses">

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

                <hr />

                <div class="items">
                    <?foreach($aSection['ITEMS'] as $aItem):?>
                        <?
                            $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                            $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                            $aImage = !empty($aItem['PREVIEW_PICTURE']) ? $aItem['PREVIEW_PICTURE'] : $aItem['DETAIL_PICTURE'];

                            if (is_numeric($aImage))
                                $aImage = CFile::GetFileArray($aImage);

                            $sImageAlt = !empty($aImage["DESCRIPTION"]) ? $aImage["DESCRIPTION"] : $aItem['NAME'];
                            $sImageTitle = $sImageAlt;

                            $aImageThumb = null;
                            if ($aImage)
                                $aImageThumb = CFile::ResizeImageGet($aImage, array("width" => 300, "height" => 300));

                            $sDescription = !empty($aItem['PREVIEW_TEXT']) ? $aItem['PREVIEW_TEXT'] : $aItem['DETAIL_TEXT'];

                            /*if (empty($aImage))
                                $aImage['src'] = $this->GetFolder().'/images/no_photo.png';*/

                        ?>

                        <div class="item clearfix" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                            <?if( ! empty($aImage) && ! empty($aImageThumb)):?>
                                <div class="image">
                                    <div class="aimg-hover magnific-gallery">
                                        <div class="aimg-overlay"></div>
                                        <img
                                            class="img-thumbnail"
                                            border="0"
                                            src="<?=$aImageThumb['src']?>"
                                            alt="<?=$sImageAlt?>"
                                            title="<?=$sImageTitle?>"
                                            />
                                        <div class="aimg-row">
                                            <a href="<?=$aImage["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$sImageTitle?>"><i class="fa fa-search-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?endif?>

                            <div class="text">
                                <h3 class="name"><?=$aItem['NAME']?></h3>
                                <?=$sDescription?>
                            </div>

                        </div>
                    <?endforeach;?>
                </div>
            <?endif?>
            <?if( ++$is != count($arResult['SECTIONS'])):?>
                <br /><br /><br />
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

                $aImage = !empty($aItem['PREVIEW_PICTURE']) ? $aItem['PREVIEW_PICTURE'] : $aItem['DETAIL_PICTURE'];

                if (is_numeric($aImage))
                    $aImage = CFile::GetFileArray($aImage);

                $sImageAlt   = !empty($aImage["DESCRIPTION"]) ? $aImage["DESCRIPTION"] : $aItem['NAME'];
                $sImageTitle = $sImageAlt;

                $aImageThumb = null;
                if ($aImage)
                    $aImageThumb = CFile::ResizeImageGet($aImage, array("width" => 300, "height" => 300));

                $sDescription = !empty($aItem['PREVIEW_TEXT']) ? $aItem['PREVIEW_TEXT'] : $aItem['DETAIL_TEXT'];

                /*if (empty($aImage))
                    $aImage['src'] = $this->GetFolder().'/images/no_photo.png';*/

                ?>

                <div class="item clearfix" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                    <?if( ! empty($aImage) && ! empty($aImageThumb)):?>
                        <div class="image">
                            <div class="aimg-hover magnific-gallery">
                                <div class="aimg-overlay"></div>
                                <img
                                    class="img-thumbnail"
                                    border="0"
                                    src="<?=$aImageThumb['src']?>"
                                    alt="<?=$sImageAlt?>"
                                    title="<?=$sImageTitle?>"
                                    />
                                <div class="aimg-row">
                                    <a href="<?=$aImage["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$sImageTitle?>"><i class="fa fa-search-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    <?endif?>

                    <div class="text">
                        <h3 class="name"><?=$aItem['NAME']?></h3>
                        <?=$sDescription?>
                    </div>

                </div>
            <?endforeach;?>
        </div>
    <?endif?>

</div>