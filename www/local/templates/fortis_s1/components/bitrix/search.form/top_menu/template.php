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
$this->setFrameMode(true);?>
<form action="<?=$arResult["FORM_ACTION"]?>" class="navbar-form search-form" role="search">

	<?if($arParams["USE_SUGGEST"] === "Y"):?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:search.suggest.input",
			"top_menu",
			array(
				"NAME" => "q",
				"VALUE" => "",
				"INPUT_SIZE" => 15,
				"DROPDOWN_SIZE" => 10,
			),
			$component, array("HIDE_ICONS" => "Y")
		);?>
	<?else:?>
		<input type="text" name="q" value="" maxlength="50" class="field" placeholder="<?=GetMessage("BSF_T_SEARCH_PLACEHOLDER");?>" autofocus />
	<?endif;?>

	<button type="submit" class="button" title="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>">
		<i class="fa fa-search"></i>
	</button>
</form>