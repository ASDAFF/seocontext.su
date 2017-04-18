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

CJSCore::Init(array("ajax"));
?>
<script>
	BX.ready(function(){
		var input = BX("<?echo $arResult["ID"]?>");
		if (input)
			new JsSuggest(input, '<?echo $arResult["ADDITIONAL_VALUES"]?>');
	});
</script>
<IFRAME
	style="width:0px; height:0px; border: 0px;"
	src="javascript:''"
	name="<?echo $arResult["ID"]?>_div_frame"
	id="<?echo $arResult["ID"]?>_div_frame"
></IFRAME><input
	name="<?echo $arParams["NAME"]?>"
	id="<?echo $arResult["ID"]?>"
	value="<?echo $arParams["VALUE"]?>"
	class="search-suggest form-control"
	type="text"
	autocomplete="off"
	placeholder="<?=GetMessage("BSF_T_SEARCH_PLACEHOLDER");?>"
	autofocus
/>