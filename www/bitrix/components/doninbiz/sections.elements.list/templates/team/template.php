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

<div class="our-team-list">

    <?if( ! empty($arResult['SECTIONS'])):?>

        <?$is = 0; foreach($arResult['SECTIONS'] as $aSection):?>
            <?
            $this->AddEditAction($aSection['ID'], $aSection['EDIT_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($aSection['ID'], $aSection['DELETE_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
            ?>

            <div class="section" id="<?=$this->GetEditAreaId($aSection['ID']);?>">
                <h2>
                    <?=$aSection['NAME']?>
                </h2>

                <div class="description">
                    <?=$aSection['DESCRIPTION']?>
                </div>
            </div>

            <?if ( ! empty($aSection['ITEMS'])):?>

                <hr />

                <div class="items">
                    <div class="row">
                        <?$i=0;foreach($aSection['ITEMS'] as $aItem):?>
                            <div class="col-sm-4">
                                <?
                                    $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                    $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                                    $aPreviewImage = !empty($aItem['PREVIEW_PICTURE']) ? $aItem['PREVIEW_PICTURE'] : false;

                                    if ($aPreviewImage)
                                        $aPreviewImage = CFile::ResizeImageGet($aPreviewImage, array("width" => 300, "height" => 300));

                                    if (empty($aPreviewImage))
                                        $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                                    $aEmails = null;
                                    if($aItem['PROPERTY_EMAILS_VALUE']) {
                                        $aEmails = trim($aItem['PROPERTY_EMAILS_VALUE'], ',');
                                        $aEmails = explode(',', $aEmails);
                                    }

                                    $aSocs = array();
                                    if($aItem['PROPERTY_SOC_VK_VALUE']) {
                                        $aSocs['vkontakte'] = $aItem['PROPERTY_SOC_VK_VALUE'];
                                    }
                                    if($aItem['PROPERTY_SOC_FB_VALUE']) {
                                        $aSocs['facebook'] = $aItem['PROPERTY_SOC_FB_VALUE'];
                                    }
                                    if($aItem['PROPERTY_SOC_OK_VALUE']) {
                                        $aSocs['odnoklassniki'] = $aItem['PROPERTY_SOC_OK_VALUE'];
                                    }
                                    if($aItem['PROPERTY_SOC_TW_VALUE']) {
                                        $aSocs['twitter'] = $aItem['PROPERTY_SOC_TW_VALUE'];
                                    }
                                    if($aItem['PROPERTY_SOC_GP_VALUE']) {
                                        $aSocs['googleplus'] = $aItem['PROPERTY_SOC_GP_VALUE'];
                                    }
                                    if($aItem['PROPERTY_SOC_MR_VALUE']) {
                                        $aSocs['mail'] = $aItem['PROPERTY_SOC_MR_VALUE'];
                                    }
                                ?>

                                <div class="item" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                                    <div class="outer-image">
                                        <a href="<?=$aItem['DETAIL_PAGE_URL']?>" class="image">
                                            <img class="img-responsive" src="<?=$aPreviewImage['src']?>" alt="<?=$aItem['NAME']?>">
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

                                            <a class="name" href="<?=$aItem['DETAIL_PAGE_URL']?>"><?=$aItem['NAME']?></a>
                                            <?if($aItem['PROPERTY_PROFESSION_VALUE']):?>
                                                <div class="prof"><?=$aItem['PROPERTY_PROFESSION_VALUE']?></div>
                                            <?endif?>
                                        </div>

                                        <?if($aItem['PROPERTY_PHONES_VALUE'] ||  ! empty($aEmails)):?>
                                            <div class="contacts-outer">
                                                <ul class="contacts">
                                                    <?if($aItem['PROPERTY_PHONES_VALUE']):?>
                                                        <li>
                                                            <span class="icon">
                                                                <i class="fa fa-phone-square"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$aItem['PROPERTY_PHONES_VALUE']?>
                                                            </span>
                                                        </li>
                                                    <?endif?>
                                                    <?if($aItem['PROPERTY_SKYPE_VALUE']):?>
                                                        <li>
                                                            <span class="icon">
                                                                <i class="fa fa-skype"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$aItem['PROPERTY_SKYPE_VALUE']?>
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

                                        <?if ($aItem['PREVIEW_TEXT']):?>
                                            <div class="description">
                                                <?=$aItem['PREVIEW_TEXT']?>
                                            </div>
                                        <?endif?>

                                        <div class="text-left">
                                            <a class="read-more" href="<?= $aItem['DETAIL_PAGE_URL'] ?>">
                                                <span><?=GetMessage('CT_BNL_MORE_INFO')?></span> <i class="fa fa-angle-double-right"></i>
                                            </a>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <?=(++$i % 3 == 0 ? '</div><div class="row">' : '')?>
                        <?endforeach;?>
                    </div>
                </div>
            <?endif?>
            <?if( ++$is != count($arResult['SECTIONS'])):?>
                <br />
            <?endif?>
        <?endforeach?>

        <?if( ! empty($arResult['ITEMS'])):?>
            <br /><br />
            <hr />
        <?endif?>

    <?endif?>

    <?if( ! empty($arResult['ITEMS'])):?>

        <div class="items without-section">
            <div class="row">
                <?$i=0;foreach($arResult['ITEMS'] as $aItem):?>
                    <div class="col-sm-4">
                        <?
                        $this->AddEditAction($aItem['ID'], $aItem['EDIT_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                        $this->AddDeleteAction($aItem['ID'], $aItem['DELETE_LINK'], CIBlock::GetArrayByID($aItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                        $aPreviewImage = !empty($aItem['PREVIEW_PICTURE']) ? $aItem['PREVIEW_PICTURE'] : false;

                        if ($aPreviewImage)
                            $aPreviewImage = CFile::ResizeImageGet($aPreviewImage, array("width" => 300, "height" => 300));

                        if (empty($aPreviewImage))
                            $aPreviewImage['src'] = $this->GetFolder().'/images/no_photo.png';

                        $aEmails = null;
                        if($aItem['PROPERTY_EMAILS_VALUE']) {
                            $aEmails = trim($aItem['PROPERTY_EMAILS_VALUE'], ',');
                            $aEmails = explode(',', $aEmails);
                        }

                        $aSocs = array();
                        if($aItem['PROPERTY_SOC_VK_VALUE']) {
                            $aSocs['vkontakte'] = $aItem['PROPERTY_SOC_VK_VALUE'];
                        }
                        if($aItem['PROPERTY_SOC_FB_VALUE']) {
                            $aSocs['facebook'] = $aItem['PROPERTY_SOC_FB_VALUE'];
                        }
                        if($aItem['PROPERTY_SOC_OK_VALUE']) {
                            $aSocs['odnoklassniki'] = $aItem['PROPERTY_SOC_OK_VALUE'];
                        }
                        if($aItem['PROPERTY_SOC_TW_VALUE']) {
                            $aSocs['twitter'] = $aItem['PROPERTY_SOC_TW_VALUE'];
                        }
                        if($aItem['PROPERTY_SOC_GP_VALUE']) {
                            $aSocs['googleplus'] = $aItem['PROPERTY_SOC_GP_VALUE'];
                        }
                        if($aItem['PROPERTY_SOC_MR_VALUE']) {
                            $aSocs['mail'] = $aItem['PROPERTY_SOC_MR_VALUE'];
                        }
                        ?>

                        <div class="item" id="<?=$this->GetEditAreaId($aItem['ID']);?>">

                            <div class="outer-image">
                                <a href="<?=$aItem['DETAIL_PAGE_URL']?>" class="image">
                                    <img class="img-responsive" src="<?=$aPreviewImage['src']?>" alt="<?=$aItem['NAME']?>">
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

                                    <a class="name" href="<?=$aItem['DETAIL_PAGE_URL']?>"><?=$aItem['NAME']?></a>
                                    <?if($aItem['PROPERTY_PROFESSION_VALUE']):?>
                                        <div class="prof"><?=$aItem['PROPERTY_PROFESSION_VALUE']?></div>
                                    <?endif?>
                                </div>

                                <?if($aItem['PROPERTY_PHONES_VALUE'] ||  ! empty($aEmails)):?>
                                    <div class="contacts-outer">
                                        <ul class="contacts">
                                            <?if($aItem['PROPERTY_PHONES_VALUE']):?>
                                                <li>
                                                            <span class="icon">
                                                                <i class="fa fa-phone-square"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$aItem['PROPERTY_PHONES_VALUE']?>
                                                            </span>
                                                </li>
                                            <?endif?>
                                            <?if($aItem['PROPERTY_SKYPE_VALUE']):?>
                                                <li>
                                                            <span class="icon">
                                                                <i class="fa fa-skype"></i>
                                                            </span>
                                                            <span class="cont-text">
                                                                <?=$aItem['PROPERTY_SKYPE_VALUE']?>
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

                                <?if ($aItem['PREVIEW_TEXT']):?>
                                    <div class="description">
                                        <?=$aItem['PREVIEW_TEXT']?>
                                    </div>
                                <?endif?>

                                <div class="text-left">
                                    <a class="read-more" href="<?= $aItem['DETAIL_PAGE_URL'] ?>">
                                        <span><?=GetMessage('CT_BNL_MORE_INFO')?></span> <i class="fa fa-angle-double-right"></i>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                    <?=(++$i % 3 == 0 ? '</div><div class="row">' : '')?>
                <?endforeach;?>
            </div>
        </div>
    <?endif?>

</div>