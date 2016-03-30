<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!is_array($arResult["arMap"]) || count($arResult["arMap"]) < 1)
    return;

$arRootNode = Array();
foreach($arResult["arMap"] as $index => $arItem)
{
    if ($arItem["LEVEL"] == 0)
        $arRootNode[] = $index;
}

$allNum = count($arRootNode);
$colNum = ceil($allNum / $arParams["COL_NUM"]);
$colNum2 = !is_numeric($arParams["COL_NUM"]) ? 2 : $arParams["COL_NUM"];

$colLg  = floor(12 / $colNum2);
$colMd  = floor(12 / $colNum2);
$colSm  = floor(12 / $colNum2);

?>
<div class="row map-columns">
    <div class="col-lg-<?=$colLg?> col-md-<?=$colMd?> col-sm-<?=$colSm?> col-xs-6">
        <ul class="map-level map-level-0">

            <?
            $previousLevel = -1;
            $counter = 0;
            $column = 1;
            foreach($arResult["arMap"] as $index => $arItem):?>

            <?if ($arItem["LEVEL"] < $previousLevel):?>
                <?=str_repeat("</ul></li>", ($previousLevel - $arItem["LEVEL"]));?>
            <?endif?>


            <?if ($counter >= $colNum && $arItem["LEVEL"] == 0):
            $allNum = $allNum-$counter;
            $colNum = ceil(($allNum) / ($arParams["COL_NUM"] > 1 ? ($arParams["COL_NUM"]-$column) : 1));
            $counter = 0;
            $column++;
            ?>
        </ul></div><div class="col-lg-<?=$colLg?> col-md-<?=$colMd?> col-sm-<?=$colSm?> col-xs-6"><ul class="map-level map-level-0">
            <?endif?>

            <?if (array_key_exists($index+1, $arResult["arMap"]) && $arItem["LEVEL"] < $arResult["arMap"][$index+1]["LEVEL"]):?>

            <li><a href="<?=$arItem["FULL_PATH"]?>"><i class="fa fa-caret-right"></i> <?=$arItem["NAME"]?></a>
                <ul class="map-level map-level-<?=$arItem["LEVEL"]+1?>">

                    <?else:?>

                        <li><a href="<?=$arItem["FULL_PATH"]?>"><i class="fa fa-caret-right"></i> <?=$arItem["NAME"]?></a></li>

                    <?endif?>


                    <?
                    $previousLevel = $arItem["LEVEL"];
                    if($arItem["LEVEL"] == 0)
                        $counter++;
                    ?>

                    <?endforeach?>

                    <?if ($previousLevel > 1)://close last item tags?>
                        <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
                    <?endif?>

                </ul>
    </div>
</div>