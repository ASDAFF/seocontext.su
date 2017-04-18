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

    $useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);

?>

<?if ( ! empty($arResult['ITEMS'])):?>
	<div class="list-catalog">

        <?if ($arParams["DISPLAY_TOP_PAGER"]):?>
            <div class="text-center">
                <?=$arResult["NAV_STRING"]; ?>
            </div>
        <?endif?>

		<?if($arParams['LIST_CURRENT_STYLE'] == 'grid') { echo '<div class="row">'; }?>
		<?if($arParams['LIST_CURRENT_STYLE'] == 'table') { echo '<table class="item table table-hover table-condensed"><tbody>'; }?>
            <?
                $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
                $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
                $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
                $i = 0;
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

                    if ($sPreviewImage)
                        $sPreviewImage = CFile::ResizeImageGet($sPreviewImage['ID'], array("width" => 300, "height" => 300));

                    if (empty($sPreviewImage))
                        $sPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                    $sOldPrice = $arItem['PROPERTIES']['OLD_PRICE']['VALUE'];
                    $sNewPrice = $arItem['PROPERTIES']['NEW_PRICE']['VALUE'];

                    $sPrice = !empty($sNewPrice) ? $sNewPrice : $sOldPrice;

                    $iIsPrice    = !empty($sNewPrice) || !empty($sOldPrice);
                    $iIsOnePrice = empty($sOldPrice) || empty($sNewPrice);
                    $iIsStatus   = !empty($arItem['PROPERTIES']['STATUS']['VALUE_XML_ID']) && !empty($arItem['PROPERTIES']['STATUS']['VALUE']);

                    $arStickers = array();
                    if ( ! empty($arItem['PROPERTIES']['OFFERS']['VALUE_XML_ID'])) {
                        $arStickers = array_combine($arItem['PROPERTIES']['OFFERS']['VALUE_XML_ID'], $arItem['PROPERTIES']['OFFERS']['VALUE_ENUM']);
                    }

				?>

                    <?switch($arParams['LIST_CURRENT_STYLE']):
                        case 'grid':?>

                            <?include 'grid.php';?>

                        <?break;?>

                        <?case 'list':?>

                            <?include 'list.php';?>

                        <?break;?>

                        <?case 'table':?>

                            <?include 'table.php';?>

                        <?break?>
                    <?endswitch?>

				<?if(++$i % 4 == 0 && $arParams['LIST_CURRENT_STYLE'] == 'grid') { echo '</div><div class="row">'; }?>
			<?endforeach?>
        <?if($arParams['LIST_CURRENT_STYLE'] == 'table') { echo '</tbody></table>'; }?>
        <?if($arParams['LIST_CURRENT_STYLE'] == 'grid') { echo '</div>'; }?>

        <?if ($arParams["DISPLAY_BOTTOM_PAGER"]):?>
            <div class="text-center">
                <?=$arResult["NAV_STRING"]; ?>
            </div>
        <?endif?>

	</div>
<?endif?>

<?$iViewDesc = !empty($arParams['SECTION_VIEW_DESC']) ? (in_array($arParams['SECTION_VIEW_DESC'], array('all', 'bottom')) ? true : false) : true;?>

<?if($arResult['SECTION']['DESCRIPTION'] && $iViewDesc):?>
    <hr />
    <div class="seo-text">
        <?=$arResult['SECTION']['DESCRIPTION']?>
    </div>
<?endif?>