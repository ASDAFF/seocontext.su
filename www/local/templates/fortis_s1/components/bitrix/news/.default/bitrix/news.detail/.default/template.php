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

$iIsPicture = $arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"]);
if ($iIsPicture)
	$aPreviewPicture = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array("width" => 250, "height" => 250));
?>

<div class="news-detail">

	<?if($iIsPicture):?>
		<div class="image-outer">
            <div class="aimg-hover detail_picture">
                <div class="aimg-overlay"></div>
                <img
                    class="img-thumbnail"
                    src="<?=$aPreviewPicture['src']?>"
                    alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
                    title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
                    >
                <div class="aimg-row">
                    <a href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$arResult['NAME']?>"><i class="fa fa-search-plus"></i></a>
                </div>
            </div>
        </div>
	<?endif?>

	<div class="date-soc">
		<?
		if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
		{
			?>
			<?/*<div class="news-detail-share">
				<noindex>
					<?
					$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
						"HANDLERS" => $arParams["SHARE_HANDLERS"],
						"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
						"PAGE_TITLE" => $arResult["~NAME"],
						"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
						"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
						"HIDE" => $arParams["SHARE_HIDE"],
					),
						$component,
						array("HIDE_ICONS" => "Y")
					);
					?>
				</noindex>
			</div>*/?>
			<div class="news-detail-share">
                <noindex>
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
                </noindex>
			</div>
		<?
		}
		?>
		<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
			<span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
		<?endif;?>
	</div>

	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?>
	<?endif;?>

    <?
        $sNavString = $arResult["NAV_RESULT"] ? $arResult["NAV_STRING"] : null;
        if ($arResult["NAV_RESULT"])
            $sNavString = str_replace('ul class="pagination"', 'ul class="pagination pagination-sm"', $sNavString);
    ?>

	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$sNavString?><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><?=$sNavString?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>

	<?if(!empty($arResult["FIELDS"])):?>
        <br />
		<dl class="dl-horizontal">
			<?foreach($arResult["FIELDS"] as $code=>$value):
				if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code)
				{
					?><dt><?=GetMessage("IBLOCK_FIELD_".$code)?>:</dt><?
					if (!empty($value) && is_array($value))
					{
						$aValuePicture = CFile::ResizeImageGet($value, array("width" => 250, "height" => 250));
						?><dd><img border="0" src="<?=$aValuePicture['src']?>"></dd><?
					}
				}
				else
				{
					?><dt><?=GetMessage("IBLOCK_FIELD_".$code)?>:</dt><dd><?=$value;?></dd><?
				}
				?>
			<?endforeach;?>
		</dl>
	<?endif;?>

	<?if(!empty($arResult["DISPLAY_PROPERTIES"])):?>
        <br />
		<dl class="dl-horizontal">
			<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
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
	<?endif;?>

</div>