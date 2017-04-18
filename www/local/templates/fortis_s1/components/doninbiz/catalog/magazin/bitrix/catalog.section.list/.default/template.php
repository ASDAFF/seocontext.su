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

$iViewDesc = !empty($arParams['SECTION_VIEW_DESC']) ? (in_array($arParams['SECTION_VIEW_DESC'], array('all', 'top')) ? true : false) : true;

if ($arParams['SECTION_VIEW_TOP_LINKS'] == 'N')
    return;

?>

<div class="fortis-catalog-sections">

	<?if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID']):?>
		<?
		$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
		?>

		<h3 class="parent-name" id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>">
			<?
			echo (
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
				? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
				: $arResult['SECTION']['NAME']
			);
			?>
		</h3>

	<?endif;?>


	<?if (0 < $arResult["SECTIONS_COUNT"]):?>

		<div class="sections-table">

			<?
			$arSections = get_tree_sections($arResult['SECTIONS']);
			$i = 0;
			?>

			<div class="section-tr">
				<?foreach ($arSections as $arSection):?>
					<?
					$i++;
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
					?>
					<div class="section-td<?=($i % 2 == 0 ? ' last-child' : '')?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">

						<table class="img-links">

                            <tr>
                                <?if(false != $arSection['PICTURE']):?>
                                    <td class="parent-img">
                                        <?
                                        $img_alt = (
                                        '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                                            ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                                            : $arSection["NAME"]
                                        );
                                        $img_title = (
                                        '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                                            ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                                            : $arSection["NAME"]
                                        );
                                        ?>
                                        <a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>">
                                            <img class="img-thumbnail no-border" src="<?=$arSection['PICTURE']['SRC']?>" alt="<?=$img_alt?>" title="<?=$img_title?>">
                                        </a>
                                    </td>
                                <?endif?>

                                <td class="right-links<?=(false != $arSection['PICTURE'] ? ' is-image' : '')?>">
                                    <a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>" class="parent-link">
                                        <? echo $arSection["NAME"];?><?
                                        if ($arParams["COUNT_ELEMENTS"]) {
                                            ?> <span class="count">(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
                                        }
                                        ?>
                                    </a>

                                    <?if(!empty($arSection['CHILDS'])):?>
                                        <ul class="sections-ul">
                                            <?foreach($arSection['CHILDS'] as $arChildSection):?>
                                                <li>
                                                    <a href="<? echo $arChildSection["SECTION_PAGE_URL"]; ?>" class="children-link">
                                                        <? echo $arChildSection["NAME"];?><?
                                                        if ($arParams["COUNT_ELEMENTS"]) {
                                                            ?> <span class="count">(<? echo $arChildSection["ELEMENT_CNT"]; ?>)</span><?
                                                        }
                                                        ?>
                                                    </a>
                                                </li>
                                            <?endforeach?>
                                        </ul>
                                    <?endif?>
                                </td>
                            </tr>

						</table>

						<?
						if ('' != $arSection['DESCRIPTION'] && $iViewDesc) {
							?><hr/><div class="catalog-description"><? echo $arSection['DESCRIPTION']; ?></div><?
						}
						?>

					</div>

					<?
						if($i % 2 == 0) {
							echo '</div><div class="section-tr">';
						}
					?>
				<?endforeach?>
			</div>

		</div>

	<?endif?>

</div>