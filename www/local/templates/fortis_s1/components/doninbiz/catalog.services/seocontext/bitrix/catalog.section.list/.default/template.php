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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>

<?if (0 < $arResult["SECTIONS_COUNT"]):?>
    <div class="services-section-list">
        <div class="row">
            <?$i = 0;foreach ($arResult['SECTIONS'] as $arSection):?>

                <div class="col-sm-6">
                    <?
                        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                        $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

                        if (false === $arSection['PICTURE']) {
                            $arSection['PICTURE'] = array(
                                'SRC' => $templateFolder . '/images/no_photo.png',
                                'ALT' => (
                                '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                                    ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                                    : $arSection["NAME"]
                                ),
                                'TITLE' => (
                                '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                                    ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                                    : $arSection["NAME"]
                                )
                            );
                        } else {

                        }
                    ?>

                    <div class="section clearfix" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
                        <a href="<?=$arSection['SECTION_PAGE_URL']?>" class="thumbnail">
                            <img src="<?=$arSection['PICTURE']['SRC']?>" alt="<?=$arSection['PICTURE']['TITLE']?>">
                        </a>
                        <div class="right">
                            <h2 class="title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><?=$arSection['NAME']?></a>
                                <? if ($arParams["COUNT_ELEMENTS"]):?><span>(<?=$arSection['ELEMENT_CNT']?>)</span><?endif?></h2><?
                            if ('' != $arSection['DESCRIPTION']) {
                                ?><div class="description"><?=$arSection['DESCRIPTION']?></div><?
                            }
                            ?>
                        </div>
                    </div>
                    <hr class="visible-xs" />
                </div>

                <?++$i;if($i % 2 == 0) { echo '</div>'.($i != count($arResult['SECTIONS']) ? '<hr class="hidden-xs" />' : '').'<div class="row">'; }?>
            <?endforeach?>
        </div>
        <hr class="hidden-xs" />
    </div>
<?endif?>