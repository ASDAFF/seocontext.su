<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$iShowHeaderFooter = preg_match("/" . addcslashes(SITE_DIR . 'order/question.php', '/.')."(.*)+/iu", $_SERVER['REQUEST_URI']) && ( strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' );

if ($iShowHeaderFooter) {
    $iFortisSkipSidebar = $iFortisSkipHeading = true;
    $_GET['hideModal'] = true;
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
    $APPLICATION->SetPageProperty("title", "Задать вопрос");
    $APPLICATION->SetTitle("Задать вопрос");
}

?>

<?if($iShowHeaderFooter):?>
    <div class="row">
        <div class="col-sm-10 col-sm-push-1 col-md-8 col-md-push-2">
<?endif?>

<?$APPLICATION->IncludeComponent(
	"doninbiz:order_forms", 
	"question", 
	array(
		"IBLOCK_TYPE" => "fortis_orders",
		"IBLOCK" => "18",
		"FORM_NAME" => "Задать вопрос",
		"FORM_TEXT" => "Менеджеры свяжутся с Вами после отправки заявки",
		"SUBMIT_TEXT" => "ЗАКАЗАТЬ",
		"EVENT_MESSAGE_ID" => "72",
		"EMAILS" => "",
		"SUCCESS_TEXT" => "Спасибо за обращение! Наш менеджер свяжется с Вами в течение часа.",
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