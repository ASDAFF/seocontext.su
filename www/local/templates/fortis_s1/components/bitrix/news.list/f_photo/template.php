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

<?if($arResult["ITEMS"]):?>

    <?if($arResult['NAME']):?>
        <h4><?=$arResult['NAME']?></h4>
    <?endif;?>
    <ul class="news-related images">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            $aPreviewImage = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE'] : $arItem['DETAIL_PICTURE'];

            if ($aPreviewImage)
                $aPreviewImage = CFile::ResizeImageGet($aPreviewImage, array("width" => 190, "height" => 190), BX_RESIZE_IMAGE_EXACT);

            if (empty($aPreviewImage))
                $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

            ?>
            <li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="image-outer">
                    <div class="aimg-hover">
                        <div class="aimg-overlay"></div>

                        <img class="img-thumbnail" src="<?=$aPreviewImage['src']?>" alt="<?=$arItem['NAME']?>">

                        <div class="aimg-row">
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="aimg-link"><i class="fa fa-link"></i></a>
                        </div>

                        <div class="name hidden-xs">
                            <?echo $arItem["NAME"]?>
                        </div>
                    </div>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="visible-xs">
                        <?echo $arItem["NAME"]?>
                    </a>
                </div>
            </li>
        <?endforeach;?>
    </ul>
    <div class="clearfix"></div>
    <br/>

<?endif?>