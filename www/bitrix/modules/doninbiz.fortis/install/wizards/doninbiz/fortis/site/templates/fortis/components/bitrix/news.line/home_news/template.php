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
    <div class="home-news-slider-outer">
        <div class="row">
            <ul class="home-news-slider">
                <li>
                    <?$i=0;foreach($arResult["ITEMS"] as $arItem):?>
                        <?
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        $arDate = !empty($arItem['DISPLAY_ACTIVE_FROM']) ? explode('-', $arItem['DISPLAY_ACTIVE_FROM']) : false;
                        ?>

                        <div class="col-md-6">

                            <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                                <?if($arDate):?>
                                    <div class="date-block">
                                        <div class="day"><?=$arDate[0]?></div>
                                        <div class="month"><?=$arDate[1]?></div>
                                    </div>
                                <?endif?>

                                <h4><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h4>

                                <div class="text" data-show-text="" data-hide-text="" data-mode="chars" data-truncate="150">
                                    <?=$arItem['PREVIEW_TEXT']?>
                                </div>

                                <div class="view-more">
                                    <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
                                        <?=GetMessage('FHSN_READ_MORE')?> <i class="fa fa-angle-double-right"></i>
                                    </a>
                                </div>

                            </div>

                        </div>

                        <?++$i; if($i % 2 == 0 && $i != count($arResult["ITEMS"])) echo '</li><li>';?>
                    <?endforeach;?>
                </li>
            </ul>
        </div>

        <?if($arParams['VIEW_ALL_ELEMENTS_BTN'] == 'Y'):?>
            <a href="<?=$arResult['URL']?>" class="btn btn-bordered-default btn-all-items">
                <?=GetMessage('FHSN_ALL_ITEMS')?>
                <i class="fa fa-angle-double-right"></i>
            </a>
        <?endif?>

    </div>

<?endif?>