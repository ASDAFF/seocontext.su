<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if(!CModule::IncludeModule("iblock"))
    return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog.xml";
$iblockCode = "catalog_".WIZARD_SITE_ID;
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
        "fortis_catalog",
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
    WizardServices::IncludeServiceLang("catalog.php");

    // Настройка формы
    WizardServices::SetIBlockFormSettings($iblockID, Array(
        'tabs' => "edit1--#--".GetMessage('DONINBIZ_CATALOG_1')
        ."--,--ACTIVE--#--".GetMessage('DONINBIZ_CATALOG_2')
        ."--,--ACTIVE_FROM--#--".GetMessage('DONINBIZ_CATALOG_3')
        ."--,--ACTIVE_TO--#--".GetMessage('DONINBIZ_CATALOG_4')
        ."--,--NAME--#--".GetMessage('DONINBIZ_CATALOG_5')
        ."--,--CODE--#--".GetMessage('DONINBIZ_CATALOG_6')
        ."--,--SORT--#--".GetMessage('DONINBIZ_CATALOG_7')
        ."--,--edit1_csection1--#----".GetMessage('DONINBIZ_CATALOG_8')
        ."--,--PROPERTY_".$arProperty['OFFERS']."--#--".GetMessage('DONINBIZ_CATALOG_9')
        ."--,--PROPERTY_".$arProperty['STATUS']."--#--".GetMessage('DONINBIZ_CATALOG_10')
        ."--,--IBLOCK_ELEMENT_PROP_VALUE--#----".GetMessage('DONINBIZ_CATALOG_11')
        ."--,--PROPERTY_".$arProperty['NEW_PRICE']."--#--".GetMessage('DONINBIZ_CATALOG_12')
        ."--,--PROPERTY_".$arProperty['OLD_PRICE']."--#--".GetMessage('DONINBIZ_CATALOG_13')
        ."--,--edit1_csection2--#----".GetMessage('DONINBIZ_CATALOG_14')
        ."--,--PROPERTY_".$arProperty['ARTICLE']."--#--".GetMessage('DONINBIZ_CATALOG_15')
        ."--,--PROPERTY_".$arProperty['BRAND']."--#--".GetMessage('DONINBIZ_CATALOG_16')
        ."--,--PROPERTY_".$arProperty['TYPE']."--#--".GetMessage('DONINBIZ_CATALOG_17')
        ."--,--PROPERTY_".$arProperty['SIZE']."--#--".GetMessage('DONINBIZ_CATALOG_18')
        ."--,--PROPERTY_".$arProperty['COLOR']."--#--".GetMessage('DONINBIZ_CATALOG_19')
        ."--,--PROPERTY_".$arProperty['WEIGHT']."--#--".GetMessage('DONINBIZ_CATALOG_20')
        ."--,--PROPERTY_".$arProperty['COUNTRY']."--#--".GetMessage('DONINBIZ_CATALOG_21')
        ."--,--edit1_csection3--#----".GetMessage('DONINBIZ_CATALOG_22')
        ."--,--PROPERTY_".$arProperty['DOCS']."--#--".GetMessage('DONINBIZ_CATALOG_23')
        ."--,--edit1_csection4--#----".GetMessage('DONINBIZ_CATALOG_24')
        ."--,--PROPERTY_".$arProperty['vote_count']."--#--".GetMessage('DONINBIZ_CATALOG_25')
        ."--,--PROPERTY_".$arProperty['rating']."--#--".GetMessage('DONINBIZ_CATALOG_26')
        ."--,--PROPERTY_".$arProperty['vote_sum']."--#--".GetMessage('DONINBIZ_CATALOG_48')."--;--edit2--#--".GetMessage('DONINBIZ_CATALOG_27')
        ."--,--SECTIONS--#--".GetMessage('DONINBIZ_CATALOG_49')."--;--edit5--#--".GetMessage('DONINBIZ_CATALOG_28')
        ."--,--PREVIEW_PICTURE--#--".GetMessage('DONINBIZ_CATALOG_29')
        ."--,--PREVIEW_TEXT--#--".GetMessage('DONINBIZ_CATALOG_50')."--;--edit6--#--".GetMessage('DONINBIZ_CATALOG_30')
        ."--,--DETAIL_PICTURE--#--".GetMessage('DONINBIZ_CATALOG_31')
        ."--,--PROPERTY_".$arProperty['MORE_PHOTO']."--#--".GetMessage('DONINBIZ_CATALOG_32')
        ."--,--DETAIL_TEXT--#--".GetMessage('DONINBIZ_CATALOG_51')."--;--edit14--#--".GetMessage('DONINBIZ_CATALOG_33')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--".GetMessage('DONINBIZ_CATALOG_34')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--".GetMessage('DONINBIZ_CATALOG_35')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--".GetMessage('DONINBIZ_CATALOG_36')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--".GetMessage('DONINBIZ_CATALOG_37')
        ."--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#----".GetMessage('DONINBIZ_CATALOG_38')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--".GetMessage('DONINBIZ_CATALOG_39')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--".GetMessage('DONINBIZ_CATALOG_40')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--".GetMessage('DONINBIZ_CATALOG_41')
        ."--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#----".GetMessage('DONINBIZ_CATALOG_42')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--".GetMessage('DONINBIZ_CATALOG_43')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--".GetMessage('DONINBIZ_CATALOG_44')
        ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--".GetMessage('DONINBIZ_CATALOG_45')
        ."--,--SEO_ADDITIONAL--#----".GetMessage('DONINBIZ_CATALOG_46')
        ."--,--TAGS--#--".GetMessage('DONINBIZ_CATALOG_47')
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
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => '',
                ),
            'ACTIVE' =>
                array (
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' => 'Y',
                ),
            'ACTIVE_FROM' =>
                array (
                    'IS_REQUIRED' => 'Y',
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
                            'WIDTH' => 1920,
                            'HEIGHT' => 1080,
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
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' =>
                        array (
                            'UNIQUE' => 'Y',
                            'TRANSLITERATION' => 'Y',
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
                            'FROM_DETAIL' => 'Y',
                            'SCALE' => 'Y',
                            'WIDTH' => 125,
                            'HEIGHT' => 125,
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
                            'SCALE' => 'Y',
                            'WIDTH' => 125,
                            'HEIGHT' => 125,
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
                    'IS_REQUIRED' => 'Y',
                    'DEFAULT_VALUE' =>
                        array (
                            'UNIQUE' => 'Y',
                            'TRANSLITERATION' => 'Y',
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



    $iUserTypeEntityID = false;
    // Получаем ID пользовательского свойства
    $obUserField = new CUserTypeEntity;
    $rsData = CUserTypeEntity::GetList(array(), array( "FIELD_NAME" => "UF_C_LIST_STYLE", "ENTITY_ID" => 'IBLOCK_'.$iblockID.'_SECTION' ));
    $iUserTypeEntityID = false;
    while($arRes = $rsData->Fetch()) {
        $iUserTypeEntityID = $arRes['ID'];
    }

    // Добавление пользовательского свойства
    if ($iUserTypeEntityID) {
        $arFields = array (
            'ENTITY_ID' => 'IBLOCK_'.$iblockID.'_SECTION',
            'FIELD_NAME' => 'UF_C_LIST_STYLE',
            'USER_TYPE_ID' => 'enumeration',
            'XML_ID' => 'C_LIST_STYLE',
            'SORT' => '100',
        );

        // Получаем установленные языки
        $arLanguages = Array();
        $rsLanguage = CLanguage::GetList($by, $order, array());
        while($arLanguage = $rsLanguage->Fetch())
            $arLanguages[] = $arLanguage["LID"];

        foreach($arLanguages as $languageID) {
            WizardServices::IncludeServiceLang("catalog.php", $languageID);
            $arFields['EDIT_FORM_LABEL'][$languageID] = GetMessage('DONINBIZ_CATALOG_PROP_1');
            $arFields['LIST_COLUMN_LABEL'][$languageID] = GetMessage('DONINBIZ_CATALOG_PROP_1');
        }

        $obUserField  = new CUserTypeEntity;
        $obUserField->Update($iUserTypeEntityID, $arFields);

        WizardServices::IncludeServiceLang("catalog.php");
        // добавляем поля в список
        $obEnum = new CUserFieldEnum;
        $obEnum->SetEnumValues(
            $iUserTypeEntityID,
            array(
                'n0' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_CATALOG_PROP_2'),
                    'DEF' => 'Y',
                    'SORT' => '90',
                    'XML_ID' => 'all'
                ),
                'n1' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_CATALOG_PROP_3'),
                    'DEF' => 'N',
                    'SORT' => '100',
                    'XML_ID' => 'grid',
                ),
                'n2' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_CATALOG_PROP_4'),
                    'DEF' => 'N',
                    'SORT' => '110',
                    'XML_ID' => 'list',
                ),
                'n3' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_CATALOG_PROP_5'),
                    'DEF' => 'N',
                    'SORT' => '120',
                    'XML_ID' => 'table',
                )
            )
        );
    }

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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."katalog/index.php", array("CATALOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."katalog/.left.menu_ext.php", array("CATALOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."includes/home/catalog_categories.php", array("CATALOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."includes/home/catalog_items.php", array("CATALOG_IBLOCK_ID" => $iblockID));

?>
