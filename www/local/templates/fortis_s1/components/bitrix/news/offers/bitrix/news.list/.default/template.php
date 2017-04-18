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
<div class="offers-list news-list">

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>

<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

		$iIsPicture = $arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"]);

		if ($iIsPicture)
			$aPreviewPicture = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 300, "height" => 300));

	$sRightCol = $iIsPicture ? 'col-sm-8' : 'col-xs-12';

	?>
	<div class="news-item row" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

		<?if($iIsPicture):?>
			<div class="left-col col-sm-4">
					<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
						<div class="aimg-hover">
							<div class="aimg-overlay"></div>
							<img
								class="preview_picture img-thumbnail"
								src="<?=$aPreviewPicture['src']?>"
								alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
								title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
								>
							<div class="aimg-row">
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="aimg-link"><i class="fa fa-link"></i></a>
								<a href="<?=$arItem["PREVIEW_PICTURE"]['SRC']?>" target="_blank" class="aimg-fullscreen" title="<?=$arItem["NAME"]?>"><i class="fa fa-search-plus"></i></a>
							</div>
						</div>
					<?else:?>
						<img
							class="preview_picture img-thumbnail"
							src="<?=$aPreviewPicture['src']?>"
							alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
							title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
							style="float:left"
							>
					<?endif;?>
			</div>
		<?endif?>

		<div class="right-col <?=$sRightCol?><?if($iIsPicture):?> has-picture<?endif?>">

			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" class="news-name">
						<?echo $arItem["NAME"]?>
					</a>
				<?else:?>
					<span class="news-name"><?echo $arItem["NAME"]?></span>
				<?endif;?>
			<?endif;?>

			<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
				<span class="news-date-time">
					<i class="fa fa-clock-o"></i>
					<?echo $arItem["DISPLAY_ACTIVE_FROM"]?>
				</span>
			<?endif?>

            <?
                $start_date = MakeTimeStamp($arItem['ACTIVE_FROM']);
                $end_date   = MakeTimeStamp($arItem['PROPERTIES']['DATE_END']['VALUE'][0]);
            ?>

            <div class="dates">
                <span class="label label-primary">
                    <?=ConvertTimeStamp($start_date, "SHORT", "ru")?>
                    <?if($end_date):?>
                        &mdash; <?=ConvertTimeStamp($end_date, "SHORT", "ru")?>
                    <?endif?>
                </span>
                <?if($end_date):?>
                    <small>(<?=leftAgoOffer($end_date)?>)</small>
                <?endif?>
            </div>

			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
				<?echo $arItem["PREVIEW_TEXT"];?>
			<?endif;?>

            <?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
                <?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
                    <div class="view-more">
                        <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
                            <?=GetMessage('VIEW_MORE')?>
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </div>
                <?endif;?>
            <?endif;?>

			<?if(!empty($arItem["FIELDS"])):?>
				<dl class="dl-horizontal">
					<?foreach($arItem["FIELDS"] as $code=>$value):?>
						<dt><?=GetMessage("IBLOCK_FIELD_".$code)?>:</dt>
						<dd><?=$value;?></dd>
					<?endforeach;?>
					<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
						<dt><?=$arProperty["NAME"]?>:</dt>
						<dd>
							<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
								<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
							<?else:?>
								<?=$arProperty["DISPLAY_VALUE"];?>
							<?endif?>
						</dd>
					<?endforeach;?>
				</dl>
			<?endif?>

		</div>

	</div>
<?endforeach;?>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>

</div>
