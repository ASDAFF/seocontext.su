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

<?if ( ! empty($arResult['ITEMS'])):?>
    <div class="services-section-list-items">

        <?if ($arParams["DISPLAY_TOP_PAGER"]):?>
            <?=$arResult["NAV_STRING"]?>
        <?endif?>

        <?
            $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
            $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
            $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
        ?>

        <?foreach ($arResult['ITEMS'] as $key => $arItem):?>
            <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
                $strMainID = $this->GetEditAreaId($arItem['ID']);

                $productTitle = (
                isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
                    ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                    : $arItem['NAME']
                );
                $imgTitle = (
                isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
                    ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                    : $arItem['NAME']
                );

                $sPreviewImage = !empty($arItem['PREVIEW_PICTURE']) ? $arItem['PREVIEW_PICTURE'] : $arItem['PREVIEW_PICTURE_SECOND'];

                $sPreviewImageThumb = null;
                if ($sPreviewImage)
                    $sPreviewImageThumb = CFile::ResizeImageGet($sPreviewImage['ID'], array("width" => 300, "height" => 300));

                /*if (empty($sPreviewImage))
                    $sPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';*/

                $iIsPicture = ! empty($sPreviewImage['ID']);
                $sRightCol  = $iIsPicture ? 'col-sm-8' : 'col-xs-12';
            ?>

            <div class="service-item row" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                <?if ($iIsPicture):?>
                    <div class="left-col col-sm-4">
                        <div class="aimg-hover">
                            <div class="aimg-overlay"></div>
                            <img class="img-thumbnail" src="<?=$sPreviewImageThumb['src']?>" alt="<? echo $imgTitle; ?>">
                            <div class="aimg-row">
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="aimg-link" title="<?=GetMessage('CT_BCS_CATALOG_BTN_READ')?>"><i class="fa fa-link"></i></a>
                                <a href="<?=$sPreviewImage['SRC']?>" target="_blank" class="aimg-fullscreen" title="<?=$productTitle?>"><i class="fa fa-search-plus"></i></a>
                            </div>
                        </div>
                    </div>
                <?endif?>

                <div class="right-col <?=$sRightCol?><?if($iIsPicture):?> has-picture<?endif?>">

                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="name"><?=$productTitle?></a>

                    <?if($arItem['PREVIEW_TEXT']):?>
                        <div class="description">
                            <?=$arItem['PREVIEW_TEXT']?>
                        </div>
                    <?endif?>

                    <div class="buttons">
                        <a href="<?=SITE_DIR?>order/service.php" class="btn btn-primary get-service-form" data-name="<?=$productTitle?>">
                            <?=GetMessage('CT_BCS_CATALOG_BTN_ORDER')?>
                        </a>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn btn-default">
                            <?=GetMessage('CT_BCS_CATALOG_BTN_READ')?>
                        </a>
                    </div>

                </div>

            </div>

            <hr class="visible-xs" />

        <?endforeach?>

        <?if ($arParams["DISPLAY_BOTTOM_PAGER"]):?>
            <?=$arResult["NAV_STRING"]?>
        <?endif?>

    </div>
<?endif?>