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

<div class="portfolio-list">

    <?if( ! empty($arResult['SECTIONS'])):?>

        <?$is = 0; foreach($arResult['SECTIONS'] as $aSection):?>
            <?
            $this->AddEditAction($aSection['ID'], $aSection['EDIT_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($aSection['ID'], $aSection['DELETE_LINK'], CIBlock::GetArrayByID($aSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
            ?>

            <?if (empty($aSection['ITEMS'])) continue?>

            <div class="section" id="<?=$this->GetEditAreaId($aSection['ID']);?>">
                <h2><?=$aSection['NAME']?></h2>

                <div class="description">
                    <?=$aSection['DESCRIPTION']?>
                </div>
            </div>

            <?if ( ! empty($aSection['ITEMS'])):?>

                <hr />

                <?
                    switch($arParams['LIST_CURRENT_STYLE']) {
                        case 'list':
                            require 'list.php';
                            break;
                        default:
                            require 'grid.php';
                            break;
                    }
                ?>

            <?endif?>
            <?if( ++$is != count($arResult['SECTIONS'])):?>
                <br />
            <?endif?>
        <?endforeach?>

    <?endif?>

</div>