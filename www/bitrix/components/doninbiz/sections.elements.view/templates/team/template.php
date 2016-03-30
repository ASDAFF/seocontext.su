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

$aPreviewPicture = $arResult["PREVIEW_PICTURE"];
$aDetailPicture = $arResult["DETAIL_PICTURE"];

$aDetailPictureThumb = $aPreviewPictureThumb = null;
if ( ! empty($aDetailPicture)) {
    $aDetailPictureThumb = CFile::ResizeImageGet($aDetailPicture, array("width" => 300, "height" => 300));
} elseif ( ! empty($aPreviewPicture)) {
    $aPreviewPictureThumb = CFile::ResizeImageGet($aPreviewPicture, array("width" => 300, "height" => 300));
}

/*if ( ! $aDetailPictureThumb && ! $aPreviewPictureThumb) {
    $aPreviewPictureThumb['src'] = $this->GetFolder().'/images/no_photo.png';
}*/

$aEmails = null;
if($arResult['PROPERTY_EMAILS_VALUE']) {
    $aEmails = trim($arResult['PROPERTY_EMAILS_VALUE'], ',');
    $aEmails = explode(',', $aEmails);
}

$aSocs = array();
if($arResult['PROPERTY_SOC_VK_VALUE']) {
    $aSocs['vkontakte'] = $arResult['PROPERTY_SOC_VK_VALUE'];
}
if($arResult['PROPERTY_SOC_FB_VALUE']) {
    $aSocs['facebook'] = $arResult['PROPERTY_SOC_FB_VALUE'];
}
if($arResult['PROPERTY_SOC_OK_VALUE']) {
    $aSocs['odnoklassniki'] = $arResult['PROPERTY_SOC_OK_VALUE'];
}
if($arResult['PROPERTY_SOC_TW_VALUE']) {
    $aSocs['twitter'] = $arResult['PROPERTY_SOC_TW_VALUE'];
}
if($arResult['PROPERTY_SOC_GP_VALUE']) {
    $aSocs['googleplus'] = $arResult['PROPERTY_SOC_GP_VALUE'];
}
if($arResult['PROPERTY_SOC_MR_VALUE']) {
    $aSocs['mail'] = $arResult['PROPERTY_SOC_MR_VALUE'];
}

?>

<div class="our-team-view">

    <?if ( ! empty($aDetailPictureThumb)):?>
        <div class="image">
            <div class="aimg-hover detail_picture">
                <div class="aimg-overlay"></div>
                <img
                    class="img-thumbnail"
                    border="0"
                    src="<?=$aDetailPictureThumb['src']?>"
                    alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
                    title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
                    />
                <div class="aimg-row">
                    <a href="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" target="_blank" class="aimg-fullscreen" title="<?=$arResult['NAME']?>"><i class="fa fa-search-plus"></i></a>
                </div>
            </div>
        </div>
    <?elseif ( ! empty($aPreviewPictureThumb)):?>
        <div class="image">
            <img class="img-thumbnail" src="<?=$aPreviewPictureThumb['src']?>" alt="<?=$arResult["NAME"]?>">
        </div>
    <?endif?>

    <h3>
        <?=$arResult["NAME"]?>
        <?if($arResult['PROPERTY_PROFESSION_VALUE']):?>
            <small>(<?=$arResult['PROPERTY_PROFESSION_VALUE']?>)</small>
        <?endif?>

        <?if( ! empty($aSocs)):?>
            <ul class="ssm">
                <?foreach($aSocs as $aSoc => $aSocLink):?>
                    <li class="<?=$aSoc?>">
                        <a href="<?=$aSocLink?>" rel="nofollow" target="_blank"></a>
                    </li>
                <?endforeach?>
            </ul>
        <?endif?>
    </h3>

    <?if($arResult['PROPERTY_PHONES_VALUE'] ||  ! empty($aEmails)):?>
        <div class="contacts-outer">
            <ul class="contacts">
                <?if($arResult['PROPERTY_PHONES_VALUE']):?>
                    <li>
                        <span class="icon">
                            <i class="fa fa-phone-square"></i>
                        </span>
                        <span class="cont-text">
                            <?=$arResult['PROPERTY_PHONES_VALUE']?>
                        </span>
                    </li>
                <?endif?>
                <?if($arResult['PROPERTY_SKYPE_VALUE']):?>
                    <li>
                        <span class="icon">
                            <i class="fa fa-skype"></i>
                        </span>
                        <span class="cont-text">
                            <?=$arResult['PROPERTY_SKYPE_VALUE']?>
                        </span>
                    </li>
                <?endif?>
                <?if( ! empty($aEmails)):?>
                    <li>
                        <span class="icon">
                            <i class="fa fa-envelope-square"></i>
                        </span>
                        <span class="cont-text">
                            <?$ie=0;foreach($aEmails as $sEmail):?>
                                <a href="mailto:<?=$sEmail?>">
                                    <?=$sEmail?>
                                </a>
                                <?=(++$ie != count($aEmails) ? ',' : '')?>
                            <?endforeach?>
                        </span>
                    </li>
                <?endif?>
            </ul>
        </div>
    <?endif?>

    <hr class="hr" />

    <div class="detail-text">
        <?echo $arResult["DETAIL_TEXT"];?>
    </div>

</div>