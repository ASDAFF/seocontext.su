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

<?$frame = $this->createFrame()->begin()?>

<?
$aListStyles = array('grid', 'list');

if ($arParams['LIST_STYLE_DEFAULT'] == 'list') {
    $aListStyles = array_reverse($aListStyles);
}

$sListCurrentStyle = in_array($arParams['LIST_STYLE_DEFAULT'], $aListStyles) ? $arParams['LIST_STYLE_DEFAULT'] : 'grid';

if ( ! empty($_GET['list_style'])) {
    $sListCurrentStyle = in_array($_GET['list_style'], $aListStyles) ? $_GET['list_style'] : 'grid';
    $APPLICATION->set_cookie("PORTFOLIO_LIST_STYLE", $sListCurrentStyle, time() + 3600*24, "/");
} else {
    $sCookieListStyle = $APPLICATION->get_cookie("PORTFOLIO_LIST_STYLE");
    if ( ! empty($sCookieListStyle) && in_array($sCookieListStyle, $aListStyles)) {
        $sListCurrentStyle = $sCookieListStyle;
    }
}

$sListStyle = $arParams['LIST_STYLE'];

if (empty($sListStyle) || !in_array($sListStyle, $aListStyles)) {
    $sListStyle = 'all';
}

$iListStyleSelect = false;
if ($sListStyle == 'all') {
    $iListStyleSelect = true;
} else {
    $sListCurrentStyle = in_array($sListStyle, $aListStyles) ? $sListStyle : 'list';
}

?>

<?if($iListStyleSelect):?>
    <noindex>
        <ul class="list-style-catalog">
            <li class="text"><?=GetMessage('FP_SORTING_LIST_TYPE_NAME')?>:</li>
            <?foreach($aListStyles as $sLS):?>
                <li<?if($sListCurrentStyle == $sLS):?> class="active"<?endif?>>
                    <a href="<?=$APPLICATION->GetCurPageParam('list_style=' . $sLS, array('list_style'))?>" class="has-tooltip" title="<?=GetMessage('FP_SORTING_LIST_TYPE_' . mb_strtoupper($sLS))?>">
                        <i class="fa fa-<?if($sLS == 'grid'):?>th<?else:?>list<?endif?>"></i>
                    </a>
                </li>
            <?endforeach?>
        </ul>
    </noindex>
<?endif?>

<?$APPLICATION->IncludeComponent(
    "doninbiz:sections.elements.list",
    "portfolio",
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK" => $arParams["IBLOCK_ID"],
        "SECTION_SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
        "SECTION_SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
        "SECTION_SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
        "SECTION_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "ELEMENTS_SORT_BY1" => $arParams["ELEMENT_SORT_FIELD"],
        "ELEMENTS_SORT_ORDER1" => $arParams["ELEMENT_SORT_ORDER"],
        "ELEMENTS_SORT_BY2" => $arParams["ELEMENT_SORT_FIELD2"],
        "ELEMENTS_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
        "FIELD_CODE" => array(
            'PREVIEW_PICTURE', 'PREVIEW_TEXT'
        ),
        "INCLUDE_SUBSECTIONS" => $arParams['INCLUDE_SUBSECTIONS'],
        "LIST_CURRENT_STYLE" => $sListCurrentStyle
    ),
    $component)?>

<?$frame->end()?>