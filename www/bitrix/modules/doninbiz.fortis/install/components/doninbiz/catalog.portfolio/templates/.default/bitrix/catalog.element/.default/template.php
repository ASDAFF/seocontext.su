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

<?
$strMainID = $this->GetEditAreaId($arResult['ID']);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$strTitle = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    : $arResult['NAME']
);
$strAlt = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);

$iNoPhoto = false;
if (is_array($arResult['MORE_PHOTO']) && strpos($arResult['MORE_PHOTO'][0]['SRC'], '/images/no_photo.png'))
    $iNoPhoto = true;

$arMediumImages = $arSmallImages = array();
if ( ! $iNoPhoto) {
    foreach($arResult['MORE_PHOTO'] as $iKeyPhoto => $arPhoto) {
        $arMediumImages[$iKeyPhoto] = array_merge(
            array('image_id' => $arPhoto['ID']),
            (array)CFile::ResizeImageGet($arPhoto['ID'], array('width' => 438, 'height' => 438))
        );
        $arSmallImages[$iKeyPhoto]  = array_merge(
            array('image_id' => $arPhoto['ID']),
            (array)CFile::ResizeImageGet($arPhoto['ID'], array('width' => 100, 'height' => 100))
        );
    }
}

$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
$useSocBar = ('Y' == $arParams['USE_SOCIAL_BAR']);
?>

<div class="detail-portfolio" id="<? echo $strMainID; ?>">

    <div class="row">

        <div class="col-md-6">
            <?if($iNoPhoto):?>

                <div class="thumbnail no-photo">
                    <img src="<?=$arResult['MORE_PHOTO'][0]['SRC']?>" alt="">
                </div>

            <?elseif(!empty($arResult['MORE_PHOTO'])):?>

                <?if($arResult['MORE_PHOTO_COUNT'] > 1):?>

                    <div class="slider-medium-images">
                        <?foreach($arMediumImages as $iKeyMI => $arMediumImage):?>
                            <div>
                                <div class="aimg-hover thumbnail magnific-gallery detail_picture">
                                    <div class="outer">
                                        <div class="inner">
                                            <div class="aimg-overlay"></div>
                                            <img
                                                border="0"
                                                src="<?=$arMediumImage['src']?>"
                                                alt="<?=$strAlt;?>"
                                                title="<?=$strTitle;?>"
                                                >
                                            <div class="aimg-row">
                                                <a href="<?=$arResult['MORE_PHOTO'][$iKeyMI]['SRC']?>" target="_blank" class="aimg-fullscreen"
                                                   title="<?=(!empty($arResult['MORE_PHOTO'][$iKeyMI]['DESCRIPTION']) ? $arResult['MORE_PHOTO'][$iKeyMI]['DESCRIPTION'] : $strTitle)?>">
                                                    <i class="fa fa-search-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endforeach?>
                    </div>

                    <div class="outer-slider-small-images">
                        <div class="slider-small-images">
                            <?foreach($arSmallImages as $iKeySI => $arSmallImage):?>
                                <div class="thumbnail">
                                    <div class="outer">
                                        <div class="inner">
                                            <img border="0" src="<?=$arSmallImage['src']?>" alt="<?=$strAlt;?>">
                                        </div>
                                    </div>
                                </div>
                            <?endforeach?>
                        </div>
                    </div>

                <?else:?>
                    <div class="aimg-hover thumbnail detail_picture">
                        <div class="aimg-overlay"></div>
                        <img
                            border="0"
                            src="<?=$arResult['MORE_PHOTO'][0]['SRC']?>"
                            alt="<?=$strAlt?>"
                            title="<?=$strTitle?>"
                            >
                        <div class="aimg-row">
                            <a href="<?=$arResult['MORE_PHOTO'][0]['SRC']?>" target="_blank" class="aimg-fullscreen" title="<?=$strTitle;?>"><i class="fa fa-search-plus"></i></a>
                        </div>
                    </div>
                <?endif?>

            <?endif?>

        </div>

        <div class="col-md-6 general-info">
            <?if($useSocBar):?>
                <div class="soc">
                    <noindex>
                        <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="icon" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,gplus"></div>
                    </noindex>
                </div>
            <?endif?>

            <?if (!empty($arResult['DISPLAY_PROPERTIES'])):?>
                <?if($arParams['TEXT_PROPERTIES']):?>
                    <h5 class="info"><?=$arParams['TEXT_PROPERTIES']?></h5>
                <?endif?>
                <dl class="properties">
                    <?
                    foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
                    {
                        ?>
                        <div>
                            <dt>
                                <span><? echo $arOneProp['NAME']; ?>:</span>
                            </dt>
                            <dd>
								<span>
									<?
                                    echo (
                                    is_array($arOneProp['DISPLAY_VALUE'])
                                        ? implode(' / ', $arOneProp['DISPLAY_VALUE'])
                                        : $arOneProp['DISPLAY_VALUE']
                                    );
                                    ?>
								</span>
                            </dd>
                        </div>
                    <?
                    }
                    unset($arOneProp);
                    ?>
                </dl>
            <?endif?>

            <?if ('' != $arResult['DETAIL_TEXT']):?>
                <div class="bx_item_description">
                    <?if($arParams['TEXT_DESCRIPTION']):?>
                        <h5 class="info"><?=$arParams['TEXT_DESCRIPTION']?></h5>
                    <?endif?>
                    <?if ('html' == $arResult['DETAIL_TEXT_TYPE']) {
                        echo $arResult['DETAIL_TEXT'];
                    } else {
                        ?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
                    }
                    ?>
                </div>
            <?endif?>

        </div>

    </div>



    <?if($arResult['PROPERTIES']['DISPLAY_ORDER_BLOCK']['VALUE'] == 'Y'):?>
        <table class="horizontal-order-buttons">
            <tr>
                <td class="center-col">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "page",
                            "AREA_FILE_SUFFIX" => "horizontal_order_block",
                            "EDIT_TEMPLATE" => ""
                        )
                    );?>
                </td>
                <td class="right-col">
                    <div class="btn-group-vertical">
                        <?if ($arParams['TEXT_ORDER_SERVICE']):?>
                            <a href="<?=SITE_DIR?>order/portfolio.php" class="btn btn-primary get-portfolio-form" data-name="<?=$arResult['NAME']?>">
                                <?=$arParams['TEXT_ORDER_SERVICE']?>
                            </a>
                        <?endif?>
                        <?if ($arParams['TEXT_ORDER_QUESTION']):?>
                            <a href="<?=SITE_DIR?>order/question.php" class="btn btn-default get-question-form" data-name="<?=$arResult['NAME']?>">
                                <?=$arParams['TEXT_ORDER_QUESTION']?>
                            </a>
                        <?endif?>
                    </div>
                </td>
            </tr>
        </table>
    <?endif?>



    <?if(is_array($arResult['PROPERTIES']['RELATED_PORTFOLIO']['VALUE']) && count($arResult['PROPERTIES']['RELATED_PORTFOLIO']['VALUE'])):?>
        <br /><br />
        <?if ($arParams['TEXT_PORTFOLIO']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_PORTFOLIO']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

        <div class="list-catalog">
            <div class="row">
                <?
                $i = 0;
                $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_PORTFOLIO']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
                    'ID', 'NAME', 'DETAIL_PAGE_URL', 'EDIT_LINK', 'DELETE_LINK', 'PREVIEW_PICTURE',
                    'PROPERTY_*'
                ));
                ?>
                <?while($ob = $res->GetNextElement()):?>
                    <?$arService = $ob->GetFields()?>
                    <?if ($arService):?>
                        <?
                        $arService['PROPERTIES'] = $ob->GetProperties();
                        $productTitle = $arService['NAME'];
                        $imgTitle = $arService['NAME'];

                        $sPreviewImage = !empty($arService['PREVIEW_PICTURE']) ? $arService['PREVIEW_PICTURE'] : $arService['DETAIL_PICTURE'];

                        if ($sPreviewImage)
                            $sPreviewImage = CFile::ResizeImageGet($sPreviewImage, array("width" => 300, "height" => 300));

                        if (empty($sPreviewImage))
                            $sPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                        ?>
                        <div class="col-sm-3">

                            <div class="item grid">

                                <div class="outer-image">
                                    <a class="image" href="<? echo $arService['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">
                                        <img src="<?=$sPreviewImage['src']?>" alt="<? echo $imgTitle; ?>">
                                    </a>
                                </div>

                                <a href="<? echo $arService['DETAIL_PAGE_URL']; ?>" class="name">
                                    <div class="inner">
                                        <h3>
                                        <span>
                                            <?=$productTitle?>
                                        </span>
                                        </h3>
                                    </div>
                                </a>

                            </div>

                        </div>

                        <?if(++$i % 4 == 0) { echo '</div><div class="row">'; }?>
                    <?endif?>
                <?endwhile?>
            </div>
        </div>
    <?endif?>




    <?if($arResult['PROPERTIES']['RELATED_TEAM']['VALUE']):?>
        <br /><br />
        <?if ($arParams['TEXT_TEAM']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_TEAM']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

        <div class="our-team-list">

            <div class="items without-section">
                <div class="row">

                    <?$i = 0;?>
                    <?
                    $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_TEAM']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
                        'ID', 'NAME', 'DETAIL_PAGE_URL', 'EDIT_LINK', 'DELETE_LINK', 'PREVIEW_PICTURE', 'PREVIEW_TEXT',
                        'PROPERTY_*'
                    ));
                    ?>
                    <?while($ob = $res->GetNextElement()):?>
                        <?$arTeam = $ob->GetFields();?>
                        <?if($arTeam):?>

                            <div class="col-sm-4">
                                <?
                                $arTeam['PROPERTIES'] = $ob->GetProperties();
                                $aPreviewImage = !empty($arTeam['PREVIEW_PICTURE']) ? $arTeam['PREVIEW_PICTURE'] : false;

                                if ($aPreviewImage)
                                    $aPreviewImage = CFile::ResizeImageGet($aPreviewImage, array("width" => 300, "height" => 300));

                                if (empty($aPreviewImage))
                                    $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                                $aEmails = null;
                                if($arTeam['PROPERTIES']['EMAILS']['VALUE']) {
                                    $aEmails = trim($arTeam['PROPERTIES']['EMAILS']['VALUE'], ',');
                                    $aEmails = explode(',', $aEmails);
                                }

                                $aSocs = array();
                                if($arTeam['PROPERTIES']['SOC_VK']['VALUE']) {
                                    $aSocs['vkontakte'] = $arTeam['PROPERTIES']['SOC_VK']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_FB']['VALUE']) {
                                    $aSocs['facebook'] = $arTeam['PROPERTIES']['SOC_FB']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_OK']['VALUE']) {
                                    $aSocs['odnoklassniki'] = $arTeam['PROPERTIES']['SOC_OK']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_TW']['VALUE']) {
                                    $aSocs['twitter'] = $arTeam['PROPERTIES']['SOC_TW']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_GP']['VALUE']) {
                                    $aSocs['googleplus'] = $arTeam['PROPERTIES']['SOC_GP']['VALUE'];
                                }
                                if($arTeam['PROPERTIES']['SOC_MR']['VALUE']) {
                                    $aSocs['mail'] = $arTeam['PROPERTIES']['SOC_MR']['VALUE'];
                                }
                                ?>

                                <div class="item">

                                    <div class="outer-image">
                                        <a href="<?=$arTeam['DETAIL_PAGE_URL']?>" class="image">
                                            <img class="img-responsive" src="<?=$aPreviewImage['src']?>" alt="<?=$arTeam['NAME']?>">
                                        </a>
                                    </div>

                                    <div class="text">

                                        <div class="fio clearfix">

                                            <?if( ! empty($aSocs)):?>
                                                <ul class="ssm">
                                                    <?foreach($aSocs as $aSoc => $aSocLink):?>
                                                        <li class="<?=$aSoc?>">
                                                            <a href="<?=$aSocLink?>" rel="nofollow" target="_blank"></a>
                                                        </li>
                                                    <?endforeach?>
                                                </ul>
                                            <?endif?>

                                            <a class="name" href="<?=$arTeam['DETAIL_PAGE_URL']?>"><?=$arTeam['NAME']?></a>
                                            <?if($arTeam['PROPERTIES']['PROFESSION']['VALUE']):?>
                                                <div class="prof"><?=$arTeam['PROPERTIES']['PROFESSION']['VALUE']?></div>
                                            <?endif?>
                                        </div>

                                        <?if($arTeam['PROPERTIES']['PHONES']['VALUE'] ||  ! empty($aEmails)):?>
                                            <div class="contacts-outer">
                                                <ul class="contacts">
                                                    <?if($arTeam['PROPERTIES']['PHONES']['VALUE']):?>
                                                        <li>
                                                            <span class="icon">
                                                                <i class="fa fa-phone-square"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$arTeam['PROPERTIES']['PHONES']['VALUE']?>
                                                            </span>
                                                        </li>
                                                    <?endif?>
                                                    <?if($arTeam['PROPERTIES']['SKYPE']['VALUE']):?>
                                                        <li>
                                                            <span class="icon">
                                                                <i class="fa fa-skype"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$arTeam['PROPERTIES']['SKYPE']['VALUE']?>
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

                                        <?if($arParams['TEXT_ORDER_QUESTION']):?>
                                            <div class="description text-center">
                                                <a href="<?=SITE_DIR?>order/question.php" class="btn btn-primary get-question-form" data-name="<?=$arResult['NAME']?>">
                                                    <?=$arParams['TEXT_ORDER_QUESTION']?>
                                                </a>
                                            </div>
                                        <?endif?>

                                    </div>

                                </div>

                            </div>

                            <?if(++$i % 4 == 0) { echo '</div><div class="row">'; }?>
                        <?endif?>
                    <?endwhile?>
                </div>
            </div>

        </div>

    <?endif?>




    <?if(is_array($arResult['PROPERTIES']['RELATED_SERVICES']['VALUE']) && count($arResult['PROPERTIES']['RELATED_SERVICES']['VALUE'])):?>
        <br /><br />
        <?if ($arParams['TEXT_SERVICES']):?>
            <div class="page-header">
                <h2 class="lead big"><?=$arParams['TEXT_SERVICES']?></h2>
            </div>
        <?else:?>
            <div class="clearfix"></div>
            <br /><br />
        <?endif?>

        <div class="list-catalog">
            <div class="row">
                <?
                $i = 0;
                $res = CIBlockElement::GetList(array(), $arFilter = array("ID" => $arResult['PROPERTIES']['RELATED_SERVICES']['VALUE'], "SHOW_HISTORY" => "Y", "SITE_ID" => SITE_ID), false, false, array(
                    'ID', 'NAME', 'DETAIL_PAGE_URL', 'EDIT_LINK', 'DELETE_LINK', 'PREVIEW_PICTURE',
                    'PROPERTY_*'
                ));
                ?>
                <?while($ob = $res->GetNextElement()):?>
                    <?$arService = $ob->GetFields()?>
                    <?if ($arService):?>
                        <?
                        $arService['PROPERTIES'] = $ob->GetProperties();
                        $productTitle = $arService['NAME'];
                        $imgTitle = $arService['NAME'];

                        $sPreviewImage = !empty($arService['PREVIEW_PICTURE']) ? $arService['PREVIEW_PICTURE'] : $arService['DETAIL_PICTURE'];

                        if ($sPreviewImage)
                            $sPreviewImage = CFile::ResizeImageGet($sPreviewImage, array("width" => 300, "height" => 300));

                        if (empty($sPreviewImage))
                            $sPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                        ?>
                        <div class="col-sm-3">

                            <div class="item grid">

                                <div class="outer-image">
                                    <a class="image" href="<? echo $arService['DETAIL_PAGE_URL']; ?>" title="<? echo $imgTitle; ?>">
                                        <img src="<?=$sPreviewImage['src']?>" alt="<? echo $imgTitle; ?>">
                                    </a>
                                </div>

                                <a href="<? echo $arService['DETAIL_PAGE_URL']; ?>" class="name">
                                    <div class="inner">
                                        <h3>
                                        <span>
                                            <?=$productTitle?>
                                        </span>
                                        </h3>
                                    </div>
                                </a>

                            </div>

                        </div>

                        <?if(++$i % 4 == 0) { echo '</div><div class="row">'; }?>
                    <?endif?>
                <?endwhile?>
            </div>
        </div>
    <?endif?>

</div>