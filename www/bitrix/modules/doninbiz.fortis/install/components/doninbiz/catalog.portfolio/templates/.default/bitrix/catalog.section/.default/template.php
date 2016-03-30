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

    <?if( ! empty($arResult['NAME'])):?>
        <div class="section">
            <h2><?=$arResult['NAME']?></h2>

            <div class="description">
                <?=$arResult['DESCRIPTION']?>
            </div>
        </div>
        <hr />
    <?else:?>
        <div class="clearfix"></div>
        <br />
    <?endif?>

    <?if ( ! empty($arResult['ITEMS'])):?>

        <?
            switch($arParams['LIST_CURRENT_STYLE']) {
                case 'grid':
                    require 'grid.php';
                    break;
                default:
                    require 'list.php';
                    break;
            }
        ?>

    <?endif?>

</div>