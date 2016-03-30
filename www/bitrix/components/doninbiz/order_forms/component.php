<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");

/** @global CDatabase $DB */
global $DB;
/** @global CUser $USER */
global $USER;
/** @global CMain $APPLICATION */
global $APPLICATION;

if(!CModule::IncludeModule("iblock")) return;

$iUseCaptcha = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N") == 'Y' ? true : false;
//$iUseCaptcha = (($arParams["USE_CAPTCHA"] != "N") ? "Y" : "N") == 'Y' ? true : false;
$arResult['USE_CAPTCHA'] = $iUseCaptcha;

$arResult['IS_GOAL'] = (!empty($arParams['YA_COUNTER']) && !empty($arParams['YA_GOAL']));

$arResult['IBLOCK'] = $arParams['IBLOCK'];
$arResult['ERROR']   = array();
$arResult['SUCCESS'] = null;
$arResult['DATA'] = array(
    'NAME'           => (!empty($_POST['NAME'])           ? htmlspecialcharsbx($_POST['NAME']) : ''),
    'MOBILE'         => (!empty($_POST['MOBILE'])         ? htmlspecialcharsbx($_POST['MOBILE']) : ''),
    'MOBILE_COUNTRY' => (!empty($_POST['MOBILE_COUNTRY']) ? htmlspecialcharsbx($_POST['MOBILE_COUNTRY']) : ''),
    'EMAIL'          => (!empty($_POST['EMAIL'])          ? htmlspecialcharsbx($_POST['EMAIL']) : ''),
    'PRODUCT'        => (!empty($_POST['PRODUCT'])        ? htmlspecialcharsbx($_POST['PRODUCT']) : ''),
    'COMMENT'        => (!empty($_POST['COMMENT'])        ? htmlspecialcharsbx($_POST['COMMENT']) : ''),
);

if (SITE_CHARSET == 'windows-1251') {
    foreach($arResult['DATA'] as $k => $d) {
        $from = mb_detect_encoding($d);
        $arResult['DATA'][$k] = mb_convert_encoding($d, SITE_CHARSET, $from);
    }
}

if (empty($arParams['SUCCESS_TEXT']))
    $arParams['SUCCESS_TEXT'] = GetMessage('ORF_COM_SUCCESS_TEXT');

if (empty($arParams['SUBMIT_TEXT']))
    $arParams['SUBMIT_TEXT'] = GetMessage('ORF_COM_SUBMIT_TEXT');

if (empty($arResult['IBLOCK']))
    $arResult['ERROR']['GLOBAL'] = GetMessage('ORF_COM_NOT_IBLOCK');

//if (empty($arParams['EMAILS']))
//    $arResult['ERROR']['GLOBAL'] = 'Не указаны email адреса получателей';

if ($_REQUEST['add_new_order'] && check_bitrix_sessid()) {

    if (empty($arResult['DATA']['NAME'])) {
        $arResult['ERROR']['NAME'] = GetMessage('ORF_COM_ERR_NOT_NAME');
    }

    if (empty($arResult['DATA']['MOBILE']) && empty($arResult['DATA']['PHONE'])) {
        $arResult['ERROR']['MOBILE'] = GetMessage('ORF_COM_ERR_NOT_MOBILE');
    }

    if ($iUseCaptcha) {
        include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
        $captcha_code = $_POST["captcha_sid"];
        $captcha_word = $_POST["captcha_word"];
        $cpt = new CCaptcha();
        $captchaPass = COption::GetOptionString("main", "captcha_password", "");
        if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0) {
            if ( ! $cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
                $arResult['ERROR']['CAPTCHA'] = GetMessage('ORF_COM_ERR_CAPTCHA_INCORRECT');
        }
        else
            $arResult['ERROR']['CAPTCHA'] = GetMessage('ORF_COM_ERR_NOT_CAPTCHA');
    }

    if ( ! count($arResult['ERROR'])) {
        // добавление заявки
        $arFields = array(
            "ACTIVE"            => "N",
            "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "FULL"),
            "NAME"              => "{$arResult['DATA']['NAME']}, {$arResult['DATA']['MOBILE']} ({$arResult['DATA']['MOBILE_COUNTRY']})",
            "IBLOCK_ID"         => $arParams['IBLOCK'],
            //"IBLOCK_SECTION_ID" => 'fortis_orders'
        );
        $oElement = new CIBlockElement();
        $idElement = $oElement->Add($arFields, false, false, true);

        CIBlockElement::SetPropertyValueCode($idElement, "NAME", $arResult['DATA']['NAME']);
        CIBlockElement::SetPropertyValueCode($idElement, "MOBILE", $arResult['DATA']['MOBILE']);
        CIBlockElement::SetPropertyValueCode($idElement, "MOBILE_COUNTRY", $arResult['DATA']['MOBILE_COUNTRY']);
        CIBlockElement::SetPropertyValueCode($idElement, "EMAIL", $arResult['DATA']['EMAIL']);
        CIBlockElement::SetPropertyValueCode($idElement, "PRODUCT", $arResult['DATA']['PRODUCT']);
        CIBlockElement::SetPropertyValueCode($idElement, "COMMENT", array( array( "TEXT" => $arResult['DATA']['COMMENT'], "TYPE" => "TEXT" )) );
        CIBlockElement::SetPropertyValueCode($idElement, "DATE", date("d.m.Y H:i:s"));

        if ( ! $idElement) {
            $arResult['ERROR']['GLOBAL'] = GetMessage('ORF_COM_ERR_SAVE');
        }
        if ($oElement->LAST_ERROR) {
            $arResult['ERROR']['GLOBAL'] = GetMessage('ORF_COM_ERR') . ' ' . $oElement->LAST_ERROR;
        }

        // отправка заявки на email
        $arParams['EMAILS'] = COption::GetOptionString("main", "email_from") . ( !empty($arParams['EMAILS']) ? ',' . $arParams['EMAILS'] : '' );
        if ($arParams['EMAILS']) {
            $arFields = array(
                "NAME"           => $arResult['DATA']['NAME'],
                "MOBILE"         => $arResult['DATA']['MOBILE'],
                "MOBILE_COUNTRY" => $arResult['DATA']['MOBILE_COUNTRY'],
                "EMAIL"          => $arResult['DATA']['EMAIL'],
                "PRODUCT"        => $arResult['DATA']['PRODUCT'],
                "COMMENT"        => $arResult['DATA']['COMMENT'],
                "DATE"           => date('d.m.Y H:i'),
                "ID"             => $idElement,
                "EMAIL_TO"       => str_replace(" ", "", $arParams['EMAILS']),
            );
            if (is_numeric($arParams['EVENT_MESSAGE_ID']) && $arParams['EVENT_MESSAGE_ID'] > 0) {
                CEvent::Send('FORTIS_ORDERS', SITE_ID, $arFields, "N", $arParams['EVENT_MESSAGE_ID']);
            }
        }

        if (!count($arResult['ERROR']))
            $arResult['SUCCESS'] = 1;
    }

}

if($iUseCaptcha)
    $arResult["capCode"] =  htmlspecialcharsbx($APPLICATION->CaptchaGetCode());

$this->IncludeComponentTemplate();

?>