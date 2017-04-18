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

include('functions.php');
?>

<?
    if(empty($arResult['ITEMS']))
        return;
?>

<div class="home-offers-slider">
    <?foreach($arResult['ITEMS'] as $arItem):?>
        <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            $arImage      = $arItem['PREVIEW_PICTURE'];
            $arThumbImage = CFile::ResizeImageGet($arImage, array("width" => 300, "height" => 300));
        ?>

        <div class="slide">
            <div class="outer" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="inner">
                    <?if( ! empty($arThumbImage)):?>
                        <div class="left">
                            <div class="aimg-hover">
                                <div class="aimg-overlay"></div>
                                <img
                                    class="img-thumbnail"
                                    src="<?=$arThumbImage['src']?>"
                                    alt="<?=$arImage["ALT"]?>"
                                    title="<?=$arImage["TITLE"]?>">
                                <div class="aimg-row">
                                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="aimg-link"><i class="fa fa-link"></i></a>
                                    <a href="<?=$arImage['SRC']?>" target="_blank" class="aimg-fullscreen" title="<?=$arItem["NAME"]?>"><i class="fa fa-search-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    <?endif?>

                    <div class="right<?if(empty($arThumbImage)):?> no-image<?endif?>">
                        <h4 class="name">
                            <?if( ! empty($arItem["DETAIL_PAGE_URL"])):?>
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                                    <?=$arItem['NAME']?>
                                </a>
                            <?else:?>
                                <?=$arItem['NAME']?>
                            <?endif?>
                        </h4>

                        <?
                        $start_date = MakeTimeStamp($arItem['ACTIVE_FROM']);
                        $end_date   = MakeTimeStamp($arItem['PROPERTY_DATE_END_VALUE']);
                        ?>

                        <div class="dates">
                            <span class="label label-primary">
                                <?=ConvertTimeStamp($start_date, "SHORT", "ru")?>
                                <?if($end_date):?>
                                    &mdash; <?=ConvertTimeStamp($end_date, "SHORT", "ru")?>
                                <?endif?>
                            </span>
                            <?if($end_date):?>
                                <small>(<?=leftAgoOffer($end_date)?>)</small>
                            <?endif?>
                        </div>

                        <?=$arItem['PREVIEW_TEXT']?>

                        <?if( ! empty($arItem["DETAIL_PAGE_URL"])):?>
                            <div class="read-more">
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-primary">
                                    <?=GetMessage('FHO_READ_MORE')?> <i class="fa fa-angle-double-right"></i>
                                </a>
                            </div>
                        <?endif?>

                    </div>
                </div>
            </div>
        </div>

    <?endforeach?>
</div>