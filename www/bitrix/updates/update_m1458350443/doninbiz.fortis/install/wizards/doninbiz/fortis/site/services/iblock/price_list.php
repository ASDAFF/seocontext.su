<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if(!CModule::IncludeModule("iblock"))
    return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/price_list.xml";
$iblockCode = "price_list_".WIZARD_SITE_ID;
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
        "fortis_price_list",
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
    WizardServices::IncludeServiceLang("price_list.php");

    // Настройка формы
    WizardServices::SetIBlockFormSettings($iblockID, Array(
        'tabs' => "edit1--#--".GetMessage('DONINBIZ_PRICE_LIST_1')
            ."--,--ACTIVE--#--".GetMessage('DONINBIZ_PRICE_LIST_2')
            ."--,--ACTIVE_FROM--#--".GetMessage('DONINBIZ_PRICE_LIST_3')
            ."--,--ACTIVE_TO--#--".GetMessage('DONINBIZ_PRICE_LIST_4')
            ."--,--NAME--#--".GetMessage('DONINBIZ_PRICE_LIST_5')
            ."--,--SORT--#--".GetMessage('DONINBIZ_PRICE_LIST_6')
            ."--,--SECTIONS--#--".GetMessage('DONINBIZ_PRICE_LIST_7')
            ."--,--IBLOCK_ELEMENT_PROP_VALUE--#----".GetMessage('DONINBIZ_PRICE_LIST_8')
            ."--,--PROPERTY_".$arProperty['ARTICLE']."--#--".GetMessage('DONINBIZ_PRICE_LIST_9')
            ."--,--PROPERTY_".$arProperty['UNIT']."--#--".GetMessage('DONINBIZ_PRICE_LIST_10')
            ."--,--PROPERTY_".$arProperty['AMOUNT']."--#--".GetMessage('DONINBIZ_PRICE_LIST_11')
            ."--,--PROPERTY_".$arProperty['PRICE']."--#--".GetMessage('DONINBIZ_PRICE_LIST_12')
            ."--;--edit14--#--".GetMessage('DONINBIZ_PRICE_LIST_13')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--".GetMessage('DONINBIZ_PRICE_LIST_14')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--".GetMessage('DONINBIZ_PRICE_LIST_15')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--".GetMessage('DONINBIZ_PRICE_LIST_16')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--".GetMessage('DONINBIZ_PRICE_LIST_17')
            ."--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#----".GetMessage('DONINBIZ_PRICE_LIST_18')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--".GetMessage('DONINBIZ_PRICE_LIST_19')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--".GetMessage('DONINBIZ_PRICE_LIST_20')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--".GetMessage('DONINBIZ_PRICE_LIST_21')
            ."--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#----".GetMessage('DONINBIZ_PRICE_LIST_22')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--".GetMessage('DONINBIZ_PRICE_LIST_23')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--".GetMessage('DONINBIZ_PRICE_LIST_24')
            ."--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--".GetMessage('DONINBIZ_PRICE_LIST_25')
            ."--,--SEO_ADDITIONAL--#----".GetMessage('DONINBIZ_PRICE_LIST_26')
            ."--,--TAGS--#--".GetMessage('DONINBIZ_PRICE_LIST_27')
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



    $iUserTypeEntityID = false;
    // Получаем ID пользовательского свойства
    $obUserField = new CUserTypeEntity;
    $rsData = CUserTypeEntity::GetList(array(), array( "FIELD_NAME" => "UF_PRICE_PROPERTIES", "ENTITY_ID" => 'IBLOCK_'.$iblockID.'_SECTION' ));
    $iUserTypeEntityID = false;
    while($arRes = $rsData->Fetch()) {
        $iUserTypeEntityID = $arRes['ID'];
    }

    // Добавление пользовательского свойства
    if ($iUserTypeEntityID) {
        $arFields = array (
            'ENTITY_ID' => 'IBLOCK_'.$iblockID.'_SECTION',
            'FIELD_NAME' => 'UF_PRICE_PROPERTIES',
            'USER_TYPE_ID' => 'enumeration',
            'XML_ID' => 'C_PRICE_PROPERTIES',
            'SORT' => '100',
        );

        // Получаем установленные языки
        $arLanguages = Array();
        $rsLanguage = CLanguage::GetList($by, $order, array());
        while($arLanguage = $rsLanguage->Fetch())
            $arLanguages[] = $arLanguage["LID"];

        foreach($arLanguages as $languageID) {
            WizardServices::IncludeServiceLang("price_list.php", $languageID);
            $arFields['EDIT_FORM_LABEL'][$languageID] = GetMessage('DONINBIZ_PRICE_LIST_PROP_1');
            $arFields['LIST_COLUMN_LABEL'][$languageID] = GetMessage('DONINBIZ_PRICE_LIST_PROP_1');
        }

        $obUserField  = new CUserTypeEntity;
        $obUserField->Update($iUserTypeEntityID, $arFields);

        WizardServices::IncludeServiceLang("price_list.php");
        // добавляем поля в список
        $obEnum = new CUserFieldEnum;
        $obEnum->SetEnumValues(
            $iUserTypeEntityID,
            array(
                'n0' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_PRICE_LIST_PROP_2'),
                    'DEF' => 'N',
                    'SORT' => '90',
                    'XML_ID' => 'ARTICLE'
                ),
                'n1' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_PRICE_LIST_PROP_3'),
                    'DEF' => 'N',
                    'SORT' => '100',
                    'XML_ID' => 'UNIT',
                ),
                'n2' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_PRICE_LIST_PROP_4'),
                    'DEF' => 'N',
                    'SORT' => '110',
                    'XML_ID' => 'AMOUNT',
                ),
                'n3' => array (
                    'USER_FIELD_ID' => $iUserTypeEntityID,
                    'VALUE' => GetMessage('DONINBIZ_PRICE_LIST_PROP_5'),
                    'DEF' => 'Y',
                    'SORT' => '120',
                    'XML_ID' => 'PRICE',
                )
            )
        );

        // Необходимо назначить поля разделам
        // Получаем все ID полей и разбиваем по 2 массивам
        $rsEnum = CUserFieldEnum::GetList(array(), array( 'USER_FIELD_ID' => $iUserTypeEntityID ));
        $arProductsEnum = array(); $arTariffEnum = array();
        while ($arEnum = $rsEnum->GetNext()) {
            if (in_array($arEnum['XML_ID'], array('ARTICLE', 'UNIT', 'AMOUNT'))) {
                $arProductsEnum[] = $arEnum['ID'];
            } else {
                $arProductsEnum[] = $arEnum['ID'];
                $arTariffEnum[] = $arEnum['ID'];
            }
        }

        // Получаем разделы и привязываем поля
        if (count($arTariffEnum) && count($arProductsEnum)) {
            $rsSect = CIBlockSection::GetList(Array("SORT" => "ASC"), Array('IBLOCK_ID' => $iblockID), false, Array(), false);
            $arFields = array();
            while ($arSect = $rsSect->GetNext()) {
                $bs = new CIBlockSection;

                if ($arSect['NAME'] == GetMessage('DONINBIZ_PRICE_LIST_PROP_6')) {
                    $arFields = Array(
                        "UF_PRICE_PROPERTIES" => $arProductsEnum
                    );
                }
                if ($arSect['NAME'] == GetMessage('DONINBIZ_PRICE_LIST_PROP_7')) {
                    $arFields = Array(
                        "UF_PRICE_PROPERTIES" => $arTariffEnum
                    );
                }

                $bs->Update($arSect['ID'], $arFields);
            }
        }

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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."o-kompanii/prays-list/index.php", array("PRICE_LIST_IBLOCK_ID" => $iblockID));

?>
