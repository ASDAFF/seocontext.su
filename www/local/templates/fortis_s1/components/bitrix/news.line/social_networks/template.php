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
<ul class="ssm top-social-icons">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

        $sLiClass = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_NETWORK_ENUM_ID']);
        $sTooltip = $arItem['PROPERTY_TOOLTIP_VALUE'];
        $sTooltip = !empty($sTooltip) ? ' title="'.$sTooltip.'"' : '';
		?>
        <li id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="<?=$sLiClass['XML_ID']?>">
            <a href="<?=$arItem['PROPERTY_LINK_VALUE']?>" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"<?=$sTooltip?>></a>
        </li>
	<?endforeach;?>
</ul>