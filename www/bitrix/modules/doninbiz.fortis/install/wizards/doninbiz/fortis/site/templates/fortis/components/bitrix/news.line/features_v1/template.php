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

$iColumns = !empty($arParams['COLUMNS_COUNT']) ? intval($arParams['COLUMNS_COUNT']) : 3;
$iBootstrapColumn = 12 / $iColumns;

?>

<?if( ! empty($arResult["ITEMS"])):?>
    <div class="home-features-v1">

        <div class="row">
            <?$i= 0;foreach($arResult["ITEMS"] as $arItem):?>

                <div class="col-sm-<?=$iBootstrapColumn?>">
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>

                    <?
                        $sLink       = !empty($arItem['PROPERTY_LINK_VALUE']) ? $arItem['PROPERTY_LINK_VALUE'] : (!empty($arItem["DETAIL_PAGE_URL"]) && $arItem["DETAIL_PAGE_URL"] != SITE_DIR ? $arItem["DETAIL_PAGE_URL"] : null);
                        $sButtonName = !empty($arItem['PROPERTY_BUTTON_NAME']) ? $arItem['PROPERTY_BUTTON_NAME'] : GetMessage('FHF1_BUTTON_NAME');

                        $arImage      = $arItem['PREVIEW_PICTURE'];
                        $arThumbImage = CFile::ResizeImageGet($arImage, array("width" => 70, "height" => 35));
                        /*$sImageAlt   = $arImage['ALT'];
                        $sImageTitle = $arImage['TITLE'];*/
                        $sImageAlt   = $arItem["PROPERTY_NAME_VALUE"];
                        $sImageTitle = $arItem["PROPERTY_NAME_VALUE"];

                        $sIcon = !empty($arItem['PROPERTY_ICON_VALUE']) ? $arItem['PROPERTY_ICON_VALUE'] : null;
                    ?>

                    <div class="item<?if($i == count($arResult["ITEMS"])-1):?> last-item<?endif?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <div class="icon-block">
                            <div class="outer">
                                <div class="inner">
                                    <?if( ! empty($arThumbImage['src'])):?>
                                        <img src="<?=$arThumbImage['src']?>" alt="<?=$sImageAlt?>" title="<?=$sImageTitle?>">
                                    <?elseif($sIcon):?>
                                        <i class="fa fa-<?=$sIcon?>"></i>
                                    <?endif?>
                                </div>
                            </div>
                        </div>

                        <div class="texts<?if( ! $sLink):?> no-button<?endif?>">

                            <div class="name">
                                <?if($sLink):?>
                                    <a href="<?=$sLink?>"><?=$arItem["NAME"]?></a>
                                <?else:?>
                                    <?=$arItem["NAME"]?>
                                <?endif?>
                            </div>

                            <div class="text">
                                <?=$arItem["PREVIEW_TEXT"]?>
                            </div>

                        </div>

                        <?if($sLink):?>
                            <div class="button">
                                <a href="<?=$sLink?>" class="btn btn-primary btn-sm"><?=$sButtonName?></a>
                            </div>
                        <?endif?>
                    </div>

                </div>

                <?++$i; if($i % $iColumns == 0 && $i != count($arResult["ITEMS"])) echo '</div><div class="row">';?>
            <?endforeach;?>
        </div>

    </div>
<?endif?>