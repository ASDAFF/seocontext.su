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

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<div class="customer-reviews">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

        $arImage      = $arItem['PREVIEW_PICTURE'];
        $arThumbImage = CFile::ResizeImageGet($arImage, array("width" => 100, "height" => 60));
        /*$sImageAlt   = $arImage['ALT'];
        $sImageTitle = $arImage['TITLE'];*/
        $sImageAlt   = $arItem["PROPERTY_NAME_VALUE"];
        $sImageTitle = $arItem["PROPERTY_NAME_VALUE"];
        ?>
        <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

            <blockquote id="review-<?=$arItem['ID']?>"<?if($arThumbImage['src']):?> class="not-quote-block"<?endif?>>
                <?if($arThumbImage['src']):?>
                    <img class="img-responsive image" src="<?=$arThumbImage['src']?>" alt="<?=$sImageAlt?>" title="<?=$sImageTitle?>">
                <?else:?>
                    <span class="quote-block"><i class="fa fa-quote-right"></i></span>
                <?endif?>
                <h3>
                    <?=$arItem["PROPERTY_NAME_VALUE"]?>
                    <?if($arItem["PROPERTY_STATUS_VALUE"]):?>
                        <small>(<?=$arItem["PROPERTY_STATUS_VALUE"]?>)</small>
                    <?endif?>
                    <?if($arItem["PROPERTY_SITE_VALUE"]):?>
                        <div class="link">
                            <i class="fa fa-external-link-square"></i>
                            <a href="<?=$arItem["PROPERTY_SITE_VALUE"]?>" target="_blank" rel="nofollow">
                                <span><?=$arItem["PROPERTY_SITE_VALUE"]?></span>
                            </a>
                        </div>
                    <?endif?>
                </h3>
                <?if($arParams['DISPLAY_DATE'] == 'Y'):?>
                    <div class="date">
                        <?=$arItem["DISPLAY_ACTIVE_FROM"]?>
                    </div>
                <?endif?>
                <div class="clearfix text">
                    <?=$arItem['PREVIEW_TEXT']?>
                </div>
            </blockquote>
        </div>
    <?endforeach;?>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?>
<?endif;?>