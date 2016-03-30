<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?if (!empty($arResult)):?>

<ul class="sidebar-navigation">

<?
$previousLevel = 0;
foreach($arResult as $arItem):?>

	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>">
				<a href="<?=$arItem["LINK"]?>"><i class="fa"></i><?=$arItem["TEXT"]?></a>
					<ul class="item-submenu">
		<?else:?>
			<li class="parent<?if ($arItem["SELECTED"]):?> item-selected<?endif?>"><a href="<?=$arItem["LINK"]?>"><i class="fa"></i><?=$arItem["TEXT"]?></a>
				<ul class="item-submenu">
		<?endif?>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><a href="<?=$arItem["LINK"]?>"><i class="fa"></i><?=$arItem["TEXT"]?></a></li>
			<?else:?>
				<li <?if ($arItem["SELECTED"]):?> class="parent item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>"><i class="fa"></i><?=$arItem["TEXT"]?></a></li>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?> disabled"><a href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><i class="fa"></i><?=$arItem["TEXT"]?></a></li>
			<?else:?>
				<li class="denied disabled"><a href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
<?endif?>