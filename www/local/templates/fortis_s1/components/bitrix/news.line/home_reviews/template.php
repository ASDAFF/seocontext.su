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

<?if ( ! empty($arResult["ITEMS"])):?>
    <div class="home-reviews-slider-outer">
        <ul class="home-reviews-slider">
            <?$i=0;foreach($arResult["ITEMS"] as $arItem):?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                if ($arItem['PROPERTY_VIEW_HOME_VALUE'] != 'Y')
                    continue;

                $arImage = !empty($arItem['PREVIEW_PICTURE']) ? CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array("height" => 40, "width" => 100), BX_RESIZE_IMAGE_PROPORTIONAL) : false;
                ?>

                <li>
                    <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                        <div class="review">

                            <span class="left-quote"><i class="fa fa-quote-left"></i></span>
                            <span class="right-quote"><i class="fa fa-quote-right"></i></span>

                            <div class="text" data-show-text="<?=GetMessage('FHSN_SHOW_REVIEW')?>" data-hide-text="<?=GetMessage('FHSN_HIDE_REVIEW')?>" data-mode="lines" data-truncate="3">
                                <?=$arItem['PREVIEW_TEXT']?>
                            </div>

                        </div>

                        <div class="autor">
                            <?if($arImage):?>
                                <div class="photo">
                                    <img src="<?=$arImage['src']?>" alt="<?=$arItem['PROPERTY_NAME_VALUE']?>" class="img-thumbnail">
                                </div>
                            <?endif?>
                            <div class="person">
                                    <span>
                                        <?if ($arItem['PROPERTY_NAME_VALUE'] && $arItem['PROPERTY_SITE_VALUE']):?>
                                            <a href="<?=$arItem['PROPERTY_SITE_VALUE']?>"><?=$arItem['PROPERTY_NAME_VALUE']?></a>
                                        <?elseif($arItem['PROPERTY_NAME_VALUE']):?>
                                            <?=$arItem['PROPERTY_NAME_VALUE']?>
                                        <?endif?>
                                    </span>
                                <?if ($arItem['PROPERTY_STATUS_VALUE']):?>
                                    <small><?=$arItem['PROPERTY_STATUS_VALUE']?></small>
                                <?endif?>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </li>
            <?endforeach;?>
        </ul>

        <div class="btn-group">
            <?if($arParams['VIEW_ALL_ELEMENTS_BTN'] == 'Y'):?>
                <a href="<?=$arResult['URL']?>" class="btn btn-bordered-default btn-all-items">
                    <i class="fa fa-comments"></i>
                    <?=GetMessage('FHSN_ALL_REVIEWS')?>
                </a>
            <?endif?>
            <?if($arParams['VIEW_ADD_ELEMENT_BTN'] == 'Y'):?>
                <a href="<?=$arResult['URL']?>#addReview" class="btn btn-bordered-primary btn-all-items">
                    <i class="fa fa-plus-circle"></i>
                    <?=GetMessage('FHSN_ADD_REVIEW')?>
                </a>
            <?endif?>
        </div>

    </div>
<?endif?>