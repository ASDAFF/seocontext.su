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

$iColumns = !empty($arParams['COLUMNS_COUNT']) ? intval($arParams['COLUMNS_COUNT']) : 3;
$iBootstrapColumn = 12 / $iColumns;

?>

<ul class="nav nav-tabs light home-portfolio-categories">

    <li class="active">
        <a href="#" data-filter="*" class="all">
            <?=GetMessage('FHP_ALL_LINK')?>
        </a>
    </li>

    <?if( ! empty($arResult['SECTIONS'])):?>

        <?$is = 0; foreach($arResult['SECTIONS'] as $aSection):?>
            <?
                if (empty($aSection['ITEMS'])) continue;
            ?>

            <li>
                <a href="#" data-filter=".iso-section-<?=$aSection['ID']?>">
                    <?=$aSection['NAME']?>
                </a>
            </li>

        <?endforeach?>

    <?endif?>

</ul>

    <?if( ! empty($arResult['ALL_ITEMS'])):?>

        <div class="portfolio-list home-portfolio-list">

            <?require 'grid.php';?>

            <div class="clearfix"></div>

        </div>

    <?endif?>