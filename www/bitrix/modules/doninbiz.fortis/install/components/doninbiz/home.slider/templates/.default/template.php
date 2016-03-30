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
<div class="home-slider-init">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

        // Фоновое изображение
        $sBackgroundImage = !empty($arItem['DETAIL_PICTURE']) && !empty($arItem['DETAIL_PICTURE']['SRC'])
            ? ' style="background-image: url(' . $arItem['DETAIL_PICTURE']['SRC'] . ')!important;"'
            : ' style="background-color: #ccc!important;"';
        // Изображение
        $sPreviewPicture  = !empty($arItem['PREVIEW_PICTURE']) && !empty($arItem['PREVIEW_PICTURE']['ID'])
            ? '<img src="'.$arItem['PREVIEW_PICTURE']['SRC'].'" alt="'.$arItem['PROPERTY_NAME_VALUE'].'">'
            : '';
        // Изображение с ссылкой
        $sPreviewPicture  = !empty($sPreviewPicture) && !empty($arItem['PROPERTY_IMAGE_LINK_VALUE'])
            ? '<a href="'.$arItem['PROPERTY_IMAGE_LINK_VALUE'].'">'.$sPreviewPicture.'</a>'
            : $sPreviewPicture;

        // название
        $sSlideName = !empty($arItem['PROPERTY_NAME_VALUE']) ? $arItem['PROPERTY_NAME_VALUE'] : '';
        // название с ссылкой
        $sSlideName = !empty($sSlideName) && !empty($arItem['PROPERTY_NAME_LINK_VALUE'])
            ? '<a href="'.$arItem['PROPERTY_NAME_LINK_VALUE'].'" class="name">'.$sSlideName.'</a>'
            : '<span class="name">'.$sSlideName.'</span>';

        // Текст
        $sSlideTxt = !empty($arItem['PREVIEW_TEXT']) ? '<div class="txt">'.$arItem['PREVIEW_TEXT'].'</div>' : '';

        // Цвет текста
        $aSlideColor = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_TEXT_COLOR_ENUM_ID']);
        $sSlideColor = !empty($aSlideColor) && !empty($aSlideColor['XML_ID']) ? ' ' . ToLower($aSlideColor['XML_ID']) : '';

        // Кнопки

        // первая
        $PROPERTY_ONE_BUTTON_CLASS_ENUM_ID = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_ONE_BUTTON_CLASS_ENUM_ID']);
        $sOneButtonClass = !empty($arItem['PROPERTY_ONE_BUTTON_CLASS_ENUM_ID']) ? $PROPERTY_ONE_BUTTON_CLASS_ENUM_ID['XML_ID'] : 'default';
        $sOneButton = !empty($arItem['PROPERTY_ONE_BUTTON_VALUE']) && !empty($arItem['PROPERTY_ONE_BUTTON_LINK_VALUE'])
            ? '<a href="'.$arItem['PROPERTY_ONE_BUTTON_LINK_VALUE'].'" class="btn btn-'.$sOneButtonClass.'">'.$arItem['PROPERTY_ONE_BUTTON_VALUE'].'</a>'
            : '';
        // вторая
        $PROPERTY_TWO_BUTTON_CLASS_ENUM_ID = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_TWO_BUTTON_CLASS_ENUM_ID']);
        $sTwoButtonClass = !empty($arItem['PROPERTY_TWO_BUTTON_CLASS_ENUM_ID']) ? $PROPERTY_TWO_BUTTON_CLASS_ENUM_ID['XML_ID'] : 'default';
        $sTwoButton = !empty($arItem['PROPERTY_TWO_BUTTON_VALUE']) && !empty($arItem['PROPERTY_TWO_BUTTON_LINK_VALUE'])
            ? '<a href="'.$arItem['PROPERTY_TWO_BUTTON_LINK_VALUE'].'" class="btn btn-'.$sTwoButtonClass.'">'.$arItem['PROPERTY_TWO_BUTTON_VALUE'].'</a>'
            : '';
        // третья
        $PROPERTY_THREE_BUTTON_CLASS_ENUM_ID = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_THREE_BUTTON_CLASS_ENUM_ID']);
        $sThreeButtonClass = !empty($arItem['PROPERTY_THREE_BUTTON_CLASS_ENUM_ID']) ? $PROPERTY_THREE_BUTTON_CLASS_ENUM_ID['XML_ID'] : 'default';
        $sThreeButton = !empty($arItem['PROPERTY_THREE_BUTTON_VALUE']) && !empty($arItem['PROPERTY_THREE_BUTTON_LINK_VALUE'])
            ? '<a href="'.$arItem['PROPERTY_THREE_BUTTON_LINK_VALUE'].'" class="btn btn-'.$sThreeButtonClass.'">'.$arItem['PROPERTY_THREE_BUTTON_VALUE'].'</a>'
            : '';

        // Положение изображения
        $aImageAlign = CIBlockPropertyEnum::GetByID($arItem['PROPERTY_IMAGE_ALIGN_ENUM_ID']);
        if (!empty($aImageAlign) && !empty($aImageAlign['XML_ID']) && $aImageAlign['XML_ID'] == 'RIGHT') {
            $sColImage = ' col-md-6 col-md-push-6';
            $sColText = ' col-md-6 col-md-pull-6';
        } else {
            $sColImage = ' col-md-6';
            $sColText = ' col-md-6';
        }

		?>
        <div class="slide<?=$sSlideColor?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>"<?=$sBackgroundImage?>>

            <div class="wrapper-container container">
                <div class="row">

                    <?if($sPreviewPicture):?>
                        <div class="hidden-xs hidden-sm<?=$sColImage?>">
                            <div class="image-outer">
                                <div class="image">
                                    <?=$sPreviewPicture?>
                                </div>
                            </div>
                        </div>
                    <?endif?>

                    <?if($sSlideName || $sSlideTxt):?>
                        <div class="col-xs-12<?if($sPreviewPicture):?><?=$sColText?><?else:?> no-image<?endif?>">
                            <div class="text">

                                <?=$sSlideName?>

                                <?=$sSlideTxt?>

                                <?if($sOneButton):?>
                                    <div class="buttons">
                                        <?=$sOneButton?>
                                        <?=$sTwoButton?>
                                        <?=$sThreeButton?>
                                    </div>
                                <?endif?>

                            </div>
                        </div>
                    <?endif?>

                </div>
            </div>

        </div>
	<?endforeach;?>
</div>
