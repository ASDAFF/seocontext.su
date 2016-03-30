<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$iShowHeaderFooter = preg_match("/" . addcslashes(SITE_DIR . 'order/call.php', '/.')."(.*)+/iu", $_SERVER['REQUEST_URI']) && ( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' );

if ($iShowHeaderFooter) {
    $iFortisSkipSidebar = $iFortisSkipHeading = true;
    $_GET['hideModal'] = true;
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetPageProperty("title", "Заказать звонок");
    $APPLICATION->SetTitle("Заказать звонок");
}

?>

<?if($iShowHeaderFooter):?>
    <div class="row">
    <div class="col-sm-10 col-sm-push-1 col-md-8 col-md-push-2">
<?endif?>

<?$APPLICATION->IncludeComponent(
	"doninbiz:order_forms", 
	"call", 
	array(
		"IBLOCK_TYPE" => "fortis_orders",
		"IBLOCK" => "19",
		"FORM_NAME" => "Заказать звонок",
		"FORM_TEXT" => "Пожалуйста, представьтесь и укажите номер Вашего телефона",
		"SUBMIT_TEXT" => "ЗАКАЗАТЬ",
		"EVENT_MESSAGE_ID" => "71",
		"EMAILS" => "",
		"SUCCESS_TEXT" => "Спасибо! Наш менеджер свяжется с Вами в ближайшее время.",
		"YA_COUNTER" => "",
		"YA_GOAL" => "",
		"USE_CAPTCHA" => "Y"
	),
	false
);?>

<?if($iShowHeaderFooter):?>
    </div>
    </div>
<?endif?>

<?
if ($iShowHeaderFooter) {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
}
?>