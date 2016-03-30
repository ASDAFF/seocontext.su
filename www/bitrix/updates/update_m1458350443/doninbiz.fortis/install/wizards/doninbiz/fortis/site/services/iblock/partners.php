<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if(!CModule::IncludeModule("iblock"))
    return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/partners.xml";
$iblockCode = "partners_".WIZARD_SITE_ID;
$iblockType = "fortis_content";

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
        "fortis_partners",
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
    WizardServices::IncludeServiceLang("partners.php");

    // Настройка формы
    WizardServices::SetIBlockFormSettings($iblockID, Array(
        'tabs' => "edit1--#--".GetMessage('DONINBIZ_PARTNERS_1')
            ."--,--ACTIVE--#--".GetMessage('DONINBIZ_PARTNERS_2')
            ."--,--ACTIVE_FROM--#--".GetMessage('DONINBIZ_PARTNERS_3')
            ."--,--ACTIVE_TO--#--".GetMessage('DONINBIZ_PARTNERS_4')
            ."--,--NAME--#--".GetMessage('DONINBIZ_PARTNERS_5')
            ."--,--CODE--#--".GetMessage('DONINBIZ_PARTNERS_6')
            ."--,--SORT--#--".GetMessage('DONINBIZ_PARTNERS_7')
            ."--,--IBLOCK_ELEMENT_PROP_VALUE--#----".GetMessage('DONINBIZ_PARTNERS_8')
            ."--,--PROPERTY_".$arProperty['PHONE']."--#--".GetMessage('DONINBIZ_PARTNERS_9')
            ."--,--PROPERTY_".$arProperty['LINKS']."--#--".GetMessage('DONINBIZ_PARTNERS_10')
            ."--,--SECTIONS--#--".GetMessage('DONINBIZ_PARTNERS_11')
            ."--;--edit5--#--".GetMessage('DONINBIZ_PARTNERS_12')
            ."--,--PREVIEW_PICTURE--#--".GetMessage('DONINBIZ_PARTNERS_13')
            ."--,--PREVIEW_TEXT--#--".GetMessage('DONINBIZ_PARTNERS_14')
            ."--;--edit6--#--".GetMessage('DONINBIZ_PARTNERS_15')
            ."--,--DETAIL_PICTURE--#--".GetMessage('DONINBIZ_PARTNERS_16')
            ."--,--DETAIL_TEXT--#--".GetMessage('DONINBIZ_PARTNERS_17')
            ."--;--edit14--#--".GetMessage('DONINBIZ_PARTNERS_18')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--".GetMessage('DONINBIZ_PARTNERS_19')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--".GetMessage('DONINBIZ_PARTNERS_20')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--".GetMessage('DONINBIZ_PARTNERS_21')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--".GetMessage('DONINBIZ_PARTNERS_22')
            ."--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#----".GetMessage('DONINBIZ_PARTNERS_23')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--".GetMessage('DONINBIZ_PARTNERS_24')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--".GetMessage('DONINBIZ_PARTNERS_25')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--".GetMessage('DONINBIZ_PARTNERS_26')
            ."--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#----".GetMessage('DONINBIZ_PARTNERS_27')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--".GetMessage('DONINBIZ_PARTNERS_28')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--".GetMessage('DONINBIZ_PARTNERS_29')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--".GetMessage('DONINBIZ_PARTNERS_30')
            ."--,--SEO_ADDITIONAL--#----".GetMessage('DONINBIZ_PARTNERS_31')
            ."--,--TAGS--#--".GetMessage('DONINBIZ_PARTNERS_32')
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
            'IBLOCK_SECTION' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
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
            'ACTIVE_TO' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'SORT' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '0',
                ),
            'NAME' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '',
                ),
            'PREVIEW_PICTURE' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'FROM_DETAIL' => 'Y',
                            'SCALE' => 'Y',
                            'WIDTH' => 250,
                            'HEIGHT' => 200,
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'DELETE_WITH_DETAIL' => 'N',
                            'UPDATE_WITH_DETAIL' => 'N',
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => NULL,
                        ),
                ),
            'PREVIEW_TEXT_TYPE' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'text',
                ),
            'PREVIEW_TEXT' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'DETAIL_PICTURE' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'SCALE' => 'Y',
                            'WIDTH' => 1920,
                            'HEIGHT' => 1080,
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => NULL,
                        ),
                ),
            'DETAIL_TEXT_TYPE' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'text',
                ),
            'DETAIL_TEXT' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'XML_ID' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'CODE' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'UNIQUE' => 'N',
                            'TRANSLITERATION' => 'N',
                            'TRANS_LEN' => 100,
                            'TRANS_CASE' => 'L',
                            'TRANS_SPACE' => '-',
                            'TRANS_OTHER' => '-',
                            'TRANS_EAT' => 'Y',
                            'USE_GOOGLE' => 'N',
                        ),
                ),
            'TAGS' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'SECTION_NAME' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '',
                ),
            'SECTION_PICTURE' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'FROM_DETAIL' => 'N',
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'DELETE_WITH_DETAIL' => 'N',
                            'UPDATE_WITH_DETAIL' => 'N',
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => NULL,
                        ),
                ),
            'SECTION_DESCRIPTION_TYPE' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'text',
                ),
            'SECTION_DESCRIPTION' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'SECTION_DETAIL_PICTURE' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'SCALE' => 'N',
                            'WIDTH' => '',
                            'HEIGHT' => '',
                            'IGNORE_ERRORS' => 'N',
                            'METHOD' => 'resample',
                            'COMPRESSION' => 95,
                            'USE_WATERMARK_TEXT' => 'N',
                            'WATERMARK_TEXT' => '',
                            'WATERMARK_TEXT_FONT' => '',
                            'WATERMARK_TEXT_COLOR' => '',
                            'WATERMARK_TEXT_SIZE' => '',
                            'WATERMARK_TEXT_POSITION' => 'tl',
                            'USE_WATERMARK_FILE' => 'N',
                            'WATERMARK_FILE' => '',
                            'WATERMARK_FILE_ALPHA' => '',
                            'WATERMARK_FILE_POSITION' => 'tl',
                            'WATERMARK_FILE_ORDER' => NULL,
                        ),
                ),
            'SECTION_XML_ID' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' => '',
                ),
            'SECTION_CODE' =>
                array (
                    'IS_REQUIRED' => 'N',
                    'DEFAULT_VALUE' =>
                        array (
                            'UNIQUE' => 'N',
                            'TRANSLITERATION' => 'N',
                            'TRANS_LEN' => 100,
                            'TRANS_CASE' => 'L',
                            'TRANS_SPACE' => '-',
                            'TRANS_OTHER' => '-',
                            'TRANS_EAT' => 'Y',
                            'USE_GOOGLE' => 'N',
                        ),
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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."o-kompanii/partnery/index.php", array("PARTNERS_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."includes/home/partners.php", array("PARTNERS_IBLOCK_ID" => $iblockID));

?>
