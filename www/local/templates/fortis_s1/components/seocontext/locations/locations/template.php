<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 */
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

<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

<div class="seocontext-detecting-location">
    <div>
        <span class="seocontext-detected-location"></span>
        <span class="seocontext-detected-location-question">
            <?=GetMessage('SEOCONTEXT_LOCATIONS_IS_YOUR_POSITION')?>
        </span>
    </div>

    <a href="#" class="seocontext-detected-location-yes"><?= GetMessage('SEOCONTEXT_LOCATIONS_YES') ?></a>
    <a href="#" class="seocontext-detected-location-no seocontext-locations-show" data-mfp-src="div.seocontext-locations">
        <?= GetMessage('SEOCONTEXT_LOCATIONS_NO') ?>
    </a>
</div>

<a href="#" class="seocontext-selected-location seocontext-locations-show" data-mfp-src="div.seocontext-locations">
    <?= GetMessage('SEOCONTEXT_LOCATIONS_CHOOSE_LOCATION') ?>
</a>


<div class="seocontext-locations mfp-hide">
    <input type="text" size="50" name="location">
    <? if ($arParams['RELOAD_PAGE'] == 'Y'): ?>
        <input type="hidden" id="seocontext_locations_reload" value="true">
    <? endif; ?>
    <ul class="selected-locations">
        <? foreach ($arResult['selected'] as $location): ?>
            <li data-code="<?= $location['CODE'] ?>">
                <?= $location['NAME'] ?>
            </li>
        <? endforeach; ?>
    </ul>
    <button><?= GetMessage('SEOCONTEXT_LOCATIONS_SAVE') ?></button>
</div>
<div id="script-container"></div>

