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

<div class="faq-list" role="tabpanel">

    <?if( ! empty($arResult['SECTIONS'])):?>

        <ul class="nav nav-tabs light" role="tablist">
            <?$is = 0;foreach($arResult['SECTIONS'] as $aSection):?>
                <?
                $this->AddEditAction($aSection['ID'], $aSection['EDIT_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_EDIT"));
                $this->AddDeleteAction($aSection['ID'], $aSection['DELETE_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
                ?>
                <li role="presentation" id="<?=$this->GetEditAreaId($aSection['ID']);?>"<?if(++$is == 1):?> class="active"<?endif?>>
                    <a href="#tab-<?=$aSection['ID']?>" role="tab" data-toggle="tab">
                        <?=$aSection['NAME']?>
                    </a>
                </li>
            <?endforeach;?>
        </ul>
    <?endif?>

    <?if( ! empty($arResult['SECTIONS'])):?>

        <div class="tab-content light">
            <?$is = 0;foreach($arResult['SECTIONS'] as $aSection):?>

                <div id="tab-<?=$aSection['ID']?>" class="tab-pane<?if(++$is == 1):?> active<?endif?>" role="tabpanel">

                    <?if( ! empty($aSection['DESCRIPTION'])):?>
                        <div class="section-text">
                            <?=$aSection['DESCRIPTION']?>
                        </div>
                    <?endif?>

                    <?if ( ! empty($aSection['ITEMS'])):?>

                        <div class="panel-group" role="tablist" aria-multiselectable="true" id="accordion-<?=$aSection['ID']?>">
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
                                    $aImageThumb = CFile::ResizeImageGet($aImage, array("width" => 100, "height" => 150));

                                $sDescription = !empty($aItem['PREVIEW_TEXT']) ? $aItem['PREVIEW_TEXT'] : $aItem['DETAIL_TEXT'];

                                /*if (empty($aImage))
                                    $aImage['src'] = $this->GetFolder().'/images/no_photo.png';*/

                                ?>

                                <div class="panel panel-default" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                                    <div class="panel-heading" role="tab" id="heading-<?=$aItem['ID']?>">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion-<?=$aSection['ID']?>" href="#collapse-<?=$aItem['ID']?>" aria-expanded="true" aria-controls="collapse-<?=$aItem['ID']?>" class="collapsed">
                                                <?=$aItem['NAME']?>
                                                <i class="fa"></i>
                                            </a>
                                        </h4>
                                    </div>

                                    <div id="collapse-<?=$aItem['ID']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?=$aItem['ID']?>">
                                        <div class="panel-body">
                                            <?if( ! empty($aImage) && ! empty($aImageThumb)):?>
                                                <div class="image">
                                                    <a href="<?=$aImage["SRC"]?>" class="thumbnail magnific-image" title="<?=$sImageTitle?>">
                                                        <img
                                                            border="0"
                                                            src="<?=$aImageThumb['src']?>"
                                                            alt="<?=$sImageAlt?>"
                                                            title="<?=$sImageTitle?>"
                                                            />
                                                    </a>
                                                </div>
                                            <?endif?>

                                            <div class="text">
                                                <?=$sDescription?>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            <?endforeach?>
                        </div>
                    <?endif?>

                </div>


            <?endforeach?>
        </div>

    <?endif?>

</div>