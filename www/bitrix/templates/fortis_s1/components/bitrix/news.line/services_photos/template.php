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
    <div class="list-catalog home-services-photos columns-<?=$iColumns?>">

        <div class="row">
            <?$i= 0;$j=0;foreach($arResult["ITEMS"] as $arItem):?>

                <?
                    $j++;
                    if (array_key_exists('PROPERTY_VIEW_HOME_VALUE', $arItem) && $arItem['PROPERTY_VIEW_HOME_VALUE'] != 'Y')
                        continue;
                ?>

                <div class="col-sm-<?=$iBootstrapColumn?>">
                    <?
                    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                    ?>

                    <?
                        $sLink       = !empty($arItem["DETAIL_PAGE_URL"]) && $arItem["DETAIL_PAGE_URL"] != '/' ? $arItem["DETAIL_PAGE_URL"] : null;

                        $arImage      = $arItem['PREVIEW_PICTURE'];
                        $arThumbImage = CFile::ResizeImageGet($arImage, array("width" => 540, "height" => 300));
                        /*$sImageAlt   = $arImage['ALT'];
                        $sImageTitle = $arImage['TITLE'];*/
                        $sImageAlt   = $arItem["PROPERTY_NAME_VALUE"];
                        $sImageTitle = $arItem["PROPERTY_NAME_VALUE"];
                    ?>

                    <div class="item grid<?if($j == count($arResult["ITEMS"])):?> last-item<?endif?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                        <div class="inner">

                            <?if( ! empty($arThumbImage['src'])):?>
                                <div class="outer-image">
                                    <?if($sLink):?>
                                        <a class="image" href="<?=$sLink?>" title="<?=$sImageTitle?>">
                                            <img src="<?=$arThumbImage['src']?>" alt="<?=$sImageAlt?>">
                                        </a>
                                    <?else:?>
                                        <img src="<?=$arThumbImage['src']?>" alt="<?=$sImageAlt?>" title="<?=$sImageTitle?>">
                                    <?endif?>
                                </div>
                            <?else:?>
                                <div class="outer-image" style="background: url('<?=$this->GetFolder()?>/images/no_photo.png') center center no-repeat; min-height: 100px;"></div>
                            <?endif?>

                            <?if($sLink):?>
                                <a href="<?=$sLink?>" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=$arItem["NAME"]?></span>
                                        </h3>
                                    </div>
                                </a>
                            <?else:?>
                                <div class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=$arItem["NAME"]?></span>
                                        </h3>
                                    </div>
                                </div>
                            <?endif?>

                            <div class="text<?if( ! $arItem["PROPERTY_HOME_TEXT_VALUE"]["TEXT"]):?> no-text<?endif?>">
                                <div class="inner">
                                    <?=$arItem["PROPERTY_HOME_TEXT_VALUE"]["TEXT"]?>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <?++$i; if($i % $iColumns == 0 && $i != count($arResult["ITEMS"])) echo '</div><div class="row">';?>
            <?endforeach;?>
        </div>

    </div>
<?endif?>