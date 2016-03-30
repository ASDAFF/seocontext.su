<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if(!CModule::IncludeModule("iblock"))
    return;

$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch())
    $lid = $arSite["LANGUAGE_ID"];
if(strlen($lid) <= 0)
    $lid = "ru";

// языковый файл
WizardServices::IncludeServiceLang("mess.php");

// Почтовые события
$sMessageEventType = 'FORTIS_ORDERS';

if (WIZARD_INSTALL_DEMO_DATA) {
    // удаляем шаблоны
    $dbEventMess = CEventMessage::GetList($b="ID", $order="ASC", Array("EVENT_NAME" => $sMessageEventType, "SITE_ID" => WIZARD_SITE_ID));
    while($arEventMess = $dbEventMess->Fetch()) {
        $emessage = new CEventMessage;
        $emessage->Delete($arEventMess['ID']);
    }
    // удаление типа
    $et = new CEventType;
    $et->Delete($sMessageEventType);
}


$dbEvent = CEventMessage::GetList($b="ID", $order="ASC", Array("EVENT_NAME" => $sMessageEventType, "SITE_ID" => WIZARD_SITE_ID));
if( ! ($dbEvent->Fetch())) {

    // Добавление типа почтового события
    $dbEvent = CEventType::GetList(Array("TYPE_ID" => $sMessageEventType));
    if( ! ($dbEvent->Fetch())) {
        $et = new CEventType;
        $et->Add(array(
            "LID" => $lid,
            "EVENT_NAME" => $sMessageEventType,
            "NAME" => GetMessage("DONINBIZ_MES_EV_TYPE_NAME")
        ));
    }

    // Отзывы клиентов
    $emess = new CEventMessage;
    $iMECustomerReviewsID = $emess->Add(array(
        'EVENT_NAME' => $sMessageEventType,
        'ACTIVE' => 'Y',
        'LID' => WIZARD_SITE_ID,
        'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
        'EMAIL_TO' => '#EMAIL_TO#',
        'SUBJECT' => GetMessage('DONINBIZ_CUSTOMER_REVIEWS_SUBJECT'),
        'MESSAGE' => GetMessage('DONINBIZ_CUSTOMER_REVIEWS_MESSAGE'),
        'BODY_TYPE' => 'text'
    ));

    // Заказ услуги
    $emess = new CEventMessage;
    $iMEOrderServiceID = $emess->Add(array(
        'EVENT_NAME' => $sMessageEventType,
        'ACTIVE' => 'Y',
        'LID' => WIZARD_SITE_ID,
        'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
        'EMAIL_TO' => '#EMAIL_TO#',
        'SUBJECT' => GetMessage('DONINBIZ_ORDER_SERVICE_SUBJECT'),
        'MESSAGE' => GetMessage('DONINBIZ_ORDER_SERVICE_MESSAGE'),
        'BODY_TYPE' => 'text'
    ));

    // Заказ звонка
    $emess = new CEventMessage;
    $iMEOrderCallID = $emess->Add(array(
        'EVENT_NAME' => $sMessageEventType,
        'ACTIVE' => 'Y',
        'LID' => WIZARD_SITE_ID,
        'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
        'EMAIL_TO' => '#EMAIL_TO#',
        'SUBJECT' => GetMessage('DONINBIZ_ORDER_CALL_SUBJECT'),
        'MESSAGE' => GetMessage('DONINBIZ_ORDER_CALL_MESSAGE'),
        'BODY_TYPE' => 'text'
    ));

    // Задать вопрос
    $emess = new CEventMessage;
    $iMEOrderQuestionID = $emess->Add(array(
        'EVENT_NAME' => $sMessageEventType,
        'ACTIVE' => 'Y',
        'LID' => WIZARD_SITE_ID,
        'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
        'EMAIL_TO' => '#EMAIL_TO#',
        'SUBJECT' => GetMessage('DONINBIZ_ORDER_QUESTION_SUBJECT'),
        'MESSAGE' => GetMessage('DONINBIZ_ORDER_QUESTION_MESSAGE'),
        'BODY_TYPE' => 'text'
    ));

    // Заказ товара
    $emess = new CEventMessage;
    $iMEOrderProductID = $emess->Add(array(
        'EVENT_NAME' => $sMessageEventType,
        'ACTIVE' => 'Y',
        'LID' => WIZARD_SITE_ID,
        'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
        'EMAIL_TO' => '#EMAIL_TO#',
        'SUBJECT' => GetMessage('DONINBIZ_ORDER_PRODUCT_SUBJECT'),
        'MESSAGE' => GetMessage('DONINBIZ_ORDER_PRODUCT_MESSAGE'),
        'BODY_TYPE' => 'text'
    ));


    // замена айдишников
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."o-kompanii/otzyvy-klientov/index.php", array("CUSTOMER_REVIEWS_EVENT_ID" => $iMECustomerReviewsID));

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."order/service.php", array("ORDER_SERVICE_EVENT_ID" => $iMEOrderServiceID));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."order/portfolio.php", array("ORDER_SERVICE_EVENT_ID" => $iMEOrderServiceID));

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."order/call.php", array("ORDER_CALL_EVENT_ID" => $iMEOrderCallID));

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."order/question.php", array("ORDER_QUESTION_EVENT_ID" => $iMEOrderQuestionID));

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."order/product.php", array("ORDER_PRODUCT_EVENT_ID" => $iMEOrderProductID));

}

?>