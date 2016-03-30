<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if(!CModule::IncludeModule("iblock"))
    return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/home_slider.xml";
$iblockCode = "home_slider_".WIZARD_SITE_ID;
$iblockType = "fortis_other";

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
        "fortis_home_slider",
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
    WizardServices::IncludeServiceLang("home_slider.php");

    // Настройка формы
    WizardServices::SetIBlockFormSettings($iblockID, Array(
        'tabs' => "edit1--#--".GetMessage('DONINBIZ_HOME_SLIDER_1')
            ."--,--ACTIVE--#--".GetMessage('DONINBIZ_HOME_SLIDER_2')
            ."--,--ACTIVE_FROM--#--".GetMessage('DONINBIZ_HOME_SLIDER_3')
            ."--,--ACTIVE_TO--#--".GetMessage('DONINBIZ_HOME_SLIDER_4')
            ."--,--NAME--#--".GetMessage('DONINBIZ_HOME_SLIDER_5')
            ."--,--SORT--#--".GetMessage('DONINBIZ_HOME_SLIDER_6')
            ."--,--IBLOCK_ELEMENT_PROP_VALUE--#----".GetMessage('DONINBIZ_HOME_SLIDER_7')
            ."--,--PROPERTY_".$arProperty['NAME']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_8')
            ."--,--PROPERTY_".$arProperty['NAME_LINK']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_9')
            ."--,--DETAIL_PICTURE--#--".GetMessage('DONINBIZ_HOME_SLIDER_10')
            ."--,--PREVIEW_PICTURE--#--".GetMessage('DONINBIZ_HOME_SLIDER_11')
            ."--,--PROPERTY_".$arProperty['IMAGE_LINK']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_12')
            ."--,--PROPERTY_".$arProperty['IMAGE_ALIGN']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_13')
            ."--,--PREVIEW_TEXT--#--".GetMessage('DONINBIZ_HOME_SLIDER_14')
            ."--,--PROPERTY_".$arProperty['TEXT_COLOR']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_15')
            ."--,--edit1_csection1--#----".GetMessage('DONINBIZ_HOME_SLIDER_16')
            ."--,--PROPERTY_".$arProperty['ONE_BUTTON']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_17')
            ."--,--PROPERTY_".$arProperty['ONE_BUTTON_LINK']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_18')
            ."--,--PROPERTY_".$arProperty['ONE_BUTTON_CLASS']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_19')
            ."--,--edit1_csection2--#----".GetMessage('DONINBIZ_HOME_SLIDER_20')
            ."--,--PROPERTY_".$arProperty['TWO_BUTTON']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_21')
            ."--,--PROPERTY_".$arProperty['TWO_BUTTON_LINK']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_22')
            ."--,--PROPERTY_".$arProperty['TWO_BUTTON_CLASS']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_23')
            ."--,--edit1_csection3--#----".GetMessage('DONINBIZ_HOME_SLIDER_24')
            ."--,--PROPERTY_".$arProperty['THREE_BUTTON']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_25')
            ."--,--PROPERTY_".$arProperty['THREE_BUTTON_LINK']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_26')
            ."--,--PROPERTY_".$arProperty['THREE_BUTTON_CLASS']."--#--".GetMessage('DONINBIZ_HOME_SLIDER_27')
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
                            'FROM_DETAIL' => 'N',
                            'SCALE' => 'Y',
                            'WIDTH' => '',
                            'HEIGHT' => 400,
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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."includes/home_slider.php", array("HOME_SLIDER_IBLOCK_ID" => $iblockID));

?>
