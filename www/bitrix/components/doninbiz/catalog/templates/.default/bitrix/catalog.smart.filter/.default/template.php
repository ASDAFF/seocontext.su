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
if ($arResult['HIDE_FILTER'])
	return;
?>

<h4><?echo GetMessage("CT_BCSF_FILTER_TITLE")?></h4>

<div class="bx_filter_vertical">
	<div class="bx_filter_section m4">
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<?endforeach;

            foreach($arResult["ITEMS"] as $key=>$arItem) {
                if( ! in_array($arItem["DISPLAY_TYPE"], array('A', 'B')))
                    continue;

                if(empty($arItem["VALUES"]["MAX"]["VALUE"]) || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"])
                    continue;
                ?>
                <div class="bx_filter_container<?=getControlChecked($arItem["VALUES"]) == true ? ' active' : ''?>">
                <span class="bx_filter_container_title2">
                    <span class="bx_filter_container_modef"></span>
                    <?//=$arItem["NAME"]?>
                    <?=GetMessage('CT_BCSF_FILTER_PRICE')?>
                </span>
                <?
                switch ($arItem["DISPLAY_TYPE"])
                {
                case "A"://NUMBERS_WITH_SLIDER
                    ?>
                    <div class="bx_filter_parameters_box_container_block_outer filter-price-range">
                        <div class="bx_filter_parameters_box_container_block">
                            <div class="bx_filter_input_container">
                                <input
                                    class="min-price form-control"
                                    type="text"
                                    name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                    id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                    value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                    size="5"
                                    onkeyup="smartFilter.keyup(this)"
                                    placeholder="<?=GetMessage('CT_BCSF_FILTER_PRICE_FROM')?>"
                                    />
                            </div>

                            <div class="bx_filter_input_container">
                                <input
                                    class="max-price form-control"
                                    type="text"
                                    name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                    id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                    value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                    size="5"
                                    onkeyup="smartFilter.keyup(this)"
                                    placeholder="<?=GetMessage('CT_BCSF_FILTER_PRICE_TO')?>"
                                    />
                            </div>
                        </div>
                    </div>

                    <div id="price-range-slider"
                         data-min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>"
                         data-max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>"
                         data-from="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                         data-to="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                        ></div>

                <?
                break;
                case "B"://NUMBERS
                ?>
                    <div class="bx_filter_parameters_box_container_block_outer">
                        <div class="bx_filter_parameters_box_container_block">
                            <div class="bx_filter_input_container">
                                <input
                                    class="min-price form-control"
                                    type="text"
                                    name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
                                    id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
                                    value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
                                    size="5"
                                    onkeyup="smartFilter.keyup(this)"
                                    placeholder="<?=GetMessage('CT_BCSF_FILTER_PRICE_FROM')?>"
                                    />
                            </div>

                            <div class="bx_filter_input_container">
                                <input
                                    class="max-price form-control"
                                    type="text"
                                    name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
                                    id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
                                    value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
                                    size="5"
                                    onkeyup="smartFilter.keyup(this)"
                                    placeholder="<?=GetMessage('CT_BCSF_FILTER_PRICE_TO')?>"
                                    />
                            </div>
                        </div>
                    </div>
                <?
                break;
                }
                echo '</div>';
            }

			foreach($arResult["ITEMS"] as $key=>$arItem):
				if($arItem["PROPERTY_TYPE"] == "N" ):
					if (!$arItem["VALUES"]["MIN"]["VALUE"] || !$arItem["VALUES"]["MAX"]["VALUE"] || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"])
						continue;
					?>
					<?/*
					$arJsParams = array(
						"leftSlider" => 'left_slider_'.$key,
						"rightSlider" => 'right_slider_'.$key,
						"tracker" => "drag_tracker_".$key,
						"trackerWrap" => "drag_track_".$key,
						"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
						"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
						"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
						"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
						"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
						"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
						"precision" => 0
					);
					*/?><!--
					<script type="text/javascript">
						BX.ready(function(){
							var trackBar<?/*=$key*/?> = new BX.Iblock.SmartFilter.Vertical(<?/*=CUtil::PhpToJSObject($arJsParams)*/?>);
						});
					</script>-->
				<?elseif(!empty($arItem["VALUES"]) && !isset($arItem["PRICE"])):?>
				<div class="bx_filter_container<?=getControlChecked($arItem["VALUES"]) == true ? ' active' : ''?>">
					<span class="bx_filter_container_title" onclick="smartFilter.hideFilterProps(this)">
						<span class="bx_filter_container_modef"></span>
						<?=$arItem["NAME"]?>
						<i class="fa"></i>
					</span>
					<div class="bx_filter_block clearfix">
						<?foreach($arItem["VALUES"] as $val => $ar):?>
						<div class="checkbox">
							<label for="<?echo $ar["CONTROL_ID"]?>"<?echo $ar["DISABLED"] ? ' class="disabled"': ''?>>
								<input
									type="checkbox"
									value="<?echo $ar["HTML_VALUE"]?>"
									name="<?echo $ar["CONTROL_NAME"]?>"
									id="<?echo $ar["CONTROL_ID"]?>"
									<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
									onclick="smartFilter.click(this)"
								/>
								<?echo $ar["VALUE"];?>
							</label>
						</div>
						<?endforeach;?>
					</div>
				</div>
				<?endif;
			endforeach;?>
			<div class="clearfix"></div>
			<div class="bx_filter_control_section">

				<div class="text-center">
					<input class="btn btn-primary" type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>" />
					<input class="btn btn-default" type="submit" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>" />
				</div>

				<div class="bx_filter_popup_result left" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
					<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
					<span class="arrow"></span>
					<a href="<?echo $arResult["FILTER_URL"]?>"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
				</div>

			</div>

		</form>
		<div class="clearfix"></div>
	</div>
</div>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
</script>

<?
function getControlChecked($arr = array()) {
	$checked = false;
	foreach ($arr as $ar) {
		if (!empty($ar['CHECKED']) && $ar['CHECKED'] == true)
			$checked = true;
	}
	return $checked;
}
?>