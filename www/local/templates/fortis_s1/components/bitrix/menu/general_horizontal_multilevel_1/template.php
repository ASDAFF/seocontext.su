<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?if (!empty($arResult)):?>
	<ul class="nav navbar-nav visible-items">

	<?
	$previousLevel = 0;
	foreach($arResult as $arItem):?>

		<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
			<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
		<?endif?>

		<?if ($arItem["IS_PARENT"]):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li class="dropdown<?if ($arItem["SELECTED"]):?> active<?endif?>"><a href="<?=$arItem["LINK"]?>" class="dropdown-toggle" data-toggle="dropdown"><?=$arItem["TEXT"]?></a>
					<ul class="dropdown-menu" role="menu">
			<?else:?>
				<li class="dropdown<?if ($arItem["SELECTED"]):?> active<?endif?>"><a href="<?=$arItem["LINK"]?>" class="dropdown-toggle" data-toggle="dropdown"><?=$arItem["TEXT"]?></a>
					<ul class="dropdown-menu" role="menu">
			<?endif?>

		<?else:?>

			<?if ($arItem["PERMISSION"] > "D"):?>

				<?if ($arItem["DEPTH_LEVEL"] == 1):?>
					<li class="<?if ($arItem["SELECTED"]):?>active<?endif?>"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
				<?else:?>
					<li class="<?if ($arItem["SELECTED"]):?>active<?endif?>"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
				<?endif?>

			<?else:?>

				<?if ($arItem["DEPTH_LEVEL"] == 1):?>
					<li class="<?if ($arItem["SELECTED"]):?>active<?endif?>"><a href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
				<?else:?>
					<li class="disabled"><a href="" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
				<?endif?>

			<?endif?>

		<?endif?>

		<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

	<?endforeach?>

	<?if ($previousLevel > 1)://close last item tags?>
		<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
	<?endif?>

	</ul>

	<ul class="nav navbar-nav hidden-items">
		<li class="dropdown">
			<a href="#" class="dropdown-toggle circles" data-toggle="dropdown">
				<i class="fa fa-circle"></i>
				<i class="fa fa-circle"></i>
				<i class="fa fa-circle"></i>
			</a>
			<ul class="dropdown-menu" role="menu"></ul>
		</li>
	</ul>

	<ul class="nav navbar-nav search-item">
		<li class="dropdown">
			<a href="#" class="dropdown-toggle search-link" data-toggle="dropdown">
				<i class="fa fa-search"></i>
		<span class="hidden-sm hidden-md hidden-lg">
			<?=GetMessage('SEARCH_TEXT')?>
		</span>
			</a>
			<ul class="dropdown-menu" role="menu">
				<li>
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						"",
						Array(
							"AREA_FILE_SHOW" => "file",
							"PATH" => SITE_DIR . "includes/top_menu_search.php",
							"EDIT_TEMPLATE" => ""
						),
						false
					);?>
				</li>
			</ul>
		</li>
	</ul>

<?endif?>