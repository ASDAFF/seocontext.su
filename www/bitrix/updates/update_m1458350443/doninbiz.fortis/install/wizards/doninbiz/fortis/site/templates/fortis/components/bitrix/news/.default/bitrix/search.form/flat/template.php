<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="news-search-form">
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<input class="form-control" type="text" name="q" value="<?=htmlspecialchars(@$_GET['q'], ENT_QUOTES)?>" size="15" maxlength="50" placeholder="<?=GetMessage("BSF_T_SEARCH_BUTTON");?>" />
		<button name="s" type="submit" class="submit">
			<i class="fa fa-search"></i>
		</button>
	</form>
</div>