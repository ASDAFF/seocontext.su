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

$arResult['IBLOCK'] = $arParams['IBLOCK'];
$arResult['ERROR']   = array();
$arResult['SUCCESS'] = null;
$arResult['DATA'] = array(
    'NAME'            => (!empty($_POST['NAME'])             ? (trim($_POST['NAME'])) : ''),
    'STATUS'          => (!empty($_POST['STATUS'])           ? (trim($_POST['STATUS'])) : ''),
    'SITE'            => (!empty($_POST['SITE'])             ? (trim($_POST['SITE'])) : ''),
    'PREVIEW_TEXT'    => (!empty($_POST['PREVIEW_TEXT'])     ? (trim($_POST['PREVIEW_TEXT'])) : ''),
);

if (empty($arParams['SUCCESS_TEXT']))
    $arParams['SUCCESS_TEXT'] = GetMessage('REV_COM_SUCCESS_TEXT');

if (empty($arResult['IBLOCK']))
    $arResult['ERROR']['GLOBAL'] = GetMessage('REV_COM_NOT_IBLOCK');

if ($_REQUEST['add_new_order'] && check_bitrix_sessid()) {

    if (empty($arResult['DATA']['NAME'])) {
        $arResult['ERROR']['NAME'] = GetMessage('REV_COM_ERR_NOT_NAME');
    }

    if (empty($arResult['DATA']['PREVIEW_TEXT']) && empty($arResult['DATA']['PREVIEW_TEXT'])) {
        $arResult['ERROR']['PREVIEW_TEXT'] = GetMessage('REV_COM_ERR_NOT_PREVIEW_TEXT');
    }

    if ( ! empty($_FILES['PREVIEW_PICTURE'])) {
        if (CFile::CheckImageFile($_FILES['PREVIEW_PICTURE'])) {
            $arResult['ERROR']['PREVIEW_PICTURE'] = GetMessage('REV_COM_ERR_NOT_PREVIEW_PICTURE');
        }
    }

    if ($iUseCaptcha) {
        include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");
        $captcha_code = $_POST["captcha_sid"];
        $captcha_word = $_POST["captcha_word"];
        $cpt = new CCaptcha();
        $captchaPass = COption::GetOptionString("main", "captcha_password", "");
        if (strlen($captcha_word) > 0 && strlen($captcha_code) > 0) {
            if ( ! $cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
                $arResult['ERROR']['CAPTCHA'] = GetMessage('REV_COM_ERR_CAPTCHA_INCORRECT');
        }
        else
            $arResult['ERROR']['CAPTCHA'] = GetMessage('REV_COM_ERR_NOT_CAPTCHA');
    }

    if ( ! count($arResult['ERROR'])) {
        // добавление заявки
        $arFields = array(
            "ACTIVE"            => "N",
            "DATE_ACTIVE_FROM"  => ConvertTimeStamp(time(), "FULL"),
            "NAME"              => "{$arResult['DATA']['NAME']}" . ( ! empty($arResult['DATA']['STATUS']) ? ', ' . $arResult['DATA']['STATUS'] : '') . ( ! empty($arResult['DATA']['SITE']) ? ', ' . $arResult['DATA']['SITE'] : ''),
            "IBLOCK_ID"         => $arParams['IBLOCK'],
            "PREVIEW_PICTURE"   => $_FILES['PREVIEW_PICTURE'],
            "PREVIEW_TEXT"      => $arResult['DATA']['PREVIEW_TEXT'],
        );
        $oElement = new CIBlockElement();
        $idElement = $oElement->Add($arFields, false, false, true);

        CIBlockElement::SetPropertyValueCode($idElement, "NAME", $arResult['DATA']['NAME']);
        CIBlockElement::SetPropertyValueCode($idElement, "STATUS", $arResult['DATA']['STATUS']);
        CIBlockElement::SetPropertyValueCode($idElement, "SITE", $arResult['DATA']['SITE']);
        CIBlockElement::SetPropertyValueCode($idElement, "DATE", date("d.m.Y H:i:s"));

        if ( ! $idElement) {
            $arResult['ERROR']['GLOBAL'] = GetMessage('REV_COM_ERR_SAVE');
        }
        if ($oElement->LAST_ERROR) {
            $arResult['ERROR']['GLOBAL'] = GetMessage('REV_COM_ERR') . ' ' . $oElement->LAST_ERROR;
        }

        // отправка заявки на email
        $arParams['EMAILS'] = COption::GetOptionString("main", "email_from") . ( !empty($arParams['EMAILS']) ? ',' . $arParams['EMAILS'] : '' );
        if ($arParams['EMAILS'] && $idElement) {
            $arFields = array(
                "NAME"           => $arResult['DATA']['NAME'],
                "STATUS"         => $arResult['DATA']['STATUS'],
                "SITE"           => $arResult['DATA']['SITE'],
                "PREVIEW_TEXT"   => $arResult['DATA']['PREVIEW_TEXT'],
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