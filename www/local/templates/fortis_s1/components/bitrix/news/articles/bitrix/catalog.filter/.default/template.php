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
<form class="sidebar-filter" name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" role="form">
	<?foreach($arResult["ITEMS"] as $arItem):
		if(array_key_exists("HIDDEN", $arItem)):
			echo $arItem["INPUT"];
		endif;
	endforeach;?>

	<h4><?=GetMessage("IBLOCK_FILTER_TITLE")?>:</h4>

	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?if(!array_key_exists("HIDDEN", $arItem)):?>
			<div class="form-group">
				<label><?=$arItem["NAME"]?>:</label>
				<?=str_replace(array('type="text"'), array('type="text" class="form-control"'), $arItem["INPUT"]);?>
			</div>
		<?endif?>
	<?endforeach;?>
	<button type="submit" class="btn btn-primary" name="set_filter" value="1"><?=GetMessage("IBLOCK_SET_FILTER")?></button>
	<input type="hidden" name="set_filter" value="Y" />
	<button type="submit" class="btn btn-default" name="del_filter" value="1"><?=GetMessage("IBLOCK_DEL_FILTER")?></button>
</form>
