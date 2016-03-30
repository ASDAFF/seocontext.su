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

<div class="company-history">

    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>

        <div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <i class="fa fa-circle circle"></i>
            <div class="row">
                <div class="col-sm-4">

                    <div class="date">
                        <span><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
                    </div>

                    <h4 class="name"><?=$arItem['NAME']?></h4>

                    <?if( ! empty($arItem['IMAGES'])):?>
                        <ul class="images-gallery magnific-images-gallery">
                            <?foreach($arItem['IMAGES'] as $arImage):?>
                                <li>
                                    <a href="<?=$arImage['ORIG']['SRC']?>" class="thumbnail">
                                        <img src="<?=$arImage['THUMB']['src']?>" title="<?=$arItem['NAME']?>" alt="<?=$arItem['NAME']?>">
                                    </a>
                                </li>
                            <?endforeach?>
                        </ul>
                    <?endif?>
                </div>
                <div class="col-sm-8">
                    <?=$arItem['PREVIEW_TEXT']?>
                </div>
            </div>
        </div>
    <?endforeach;?>

</div>