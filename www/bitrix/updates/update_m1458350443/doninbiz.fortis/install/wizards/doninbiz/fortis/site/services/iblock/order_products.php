<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if(!CModule::IncludeModule("iblock"))
    return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/order_products.xml";
$iblockCode = "order_products_".WIZARD_SITE_ID;
$iblockType = "fortis_orders";

$rsIBlock = CIBlock::GetList(array(), array("CODE" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
    $iblockID = $arIBlock["ID"];
    if (WIZARD_INSTALL_DEMO_DATA)
    {
        CIBlock::Delete($arIBlock["ID"]);
        $iblockID = false;
    }
}

if($iblockID == false)
{
    $permissions = Array(
        "1" => "X",
        "2" => "R"
    );
    $dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
    if($arGroup = $dbGroup -> Fetch())
    {
        $permissions[$arGroup["ID"]] = 'W';
    };
    $iblockID = WizardServices::ImportIBlockFromXML(
        $iblockXMLFile,
        "fortis_order_products",
        $iblockType,
        WIZARD_SITE_ID,
        $permissions
    );


    if ($iblockID < 1)
        return;


    // Свойства для формы
    $arProperty = Array();
    $dbProperty = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID" => $iblockID));
    while($arProp = $dbProperty->Fetch())
        $arProperty[$arProp["CODE"]] = $arProp["ID"];

    // языковый файл
    WizardServices::IncludeServiceLang("order_products.php");

    // Настройка формы
    WizardServices::SetIBlockFormSettings($iblockID, Array(
        'tabs' => "edit1--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_1')
            ."--,--ACTIVE--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_2')
            ."--,--ACTIVE_FROM--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_3')
            ."--,--NAME--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_4')
            ."--,--IBLOCK_ELEMENT_PROP_VALUE--#----".GetMessage('DONINBIZ_ORDER_PRODUCTS_5')
            ."--,--PROPERTY_".$arProperty['NAME']."--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_6')
            ."--,--PROPERTY_".$arProperty['MOBILE']."--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_7')
            ."--,--PROPERTY_".$arProperty['MOBILE_COUNTRY']."--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_8')
            ."--,--PROPERTY_".$arProperty['EMAIL']."--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_9')
            ."--,--PROPERTY_".$arProperty['PRODUCT']."--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_10')
            ."--,--PROPERTY_".$arProperty['COMMENT']."--#--".GetMessage('DONINBIZ_ORDER_PRODUCTS_11')
            ."--;--"
    ));

    //Поля инфоблока
    $iblock = new CIBlock;
    $arFields = Array(
        "ACTIVE" => "Y",
        "CODE" => $iblockCode,
        "XML_ID" => $iblockCode,
        "NAME" => "[".WIZARD_SITE_ID."] ".$iblock->GetArrayByID($iblockID, "NAME"),
        'FIELDS' => array (
            'ACTIVE' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'Y',
                ),
            'ACTIVE_FROM' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '=now',
                ),
        )
    );

    $iblock->Update($iblockID, $arFields);

}
else
{
    $arSites = array();
    $db_res = CIBlock::GetSite($iblockID);
    while ($res = $db_res->Fetch())
        $arSites[] = $res["LID"];
    if (!in_array(WIZARD_SITE_ID, $arSites))
    {
        $arSites[] = WIZARD_SITE_ID;
        $iblock = new CIBlock;
        $iblock->Update($iblockID, array("LID" => $arSites));
    }
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."order/product.php", array("ORDER_PRODUCTS_IBLOCK_ID" => $iblockID));

?>
