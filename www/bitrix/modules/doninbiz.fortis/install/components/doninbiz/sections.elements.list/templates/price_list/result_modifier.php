<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

foreach($arResult['SECTIONS'] as $k => $arSection) {
    $res = CIBlockSection::GetList(
        Array(),
        Array("IBLOCK_ID" => $arParams['IBLOCK'], "ID" => $arSection['ID']),
        false,
	    Array("UF_*")
    );
    if($ar_res = $res->GetNext()) {

        if (empty($ar_res['UF_PRICE_PROPERTIES']))
            continue;

        $arPriceProperties = array();
        $arPricePropertiesXmlIds = array();
        foreach($ar_res['UF_PRICE_PROPERTIES'] as $sUpp) {
            $res_fields_enum = CUserFieldEnum::GetList(array(), array(
                "ID" => intval($sUpp)
            ));
            $arPriceProperties[intval($sUpp)] = $res_fields_enum->GetNext();

            $arPricePropertiesXmlIds[] = $arPriceProperties[intval($sUpp)]['XML_ID'];
        }
        $arResult['SECTIONS'][$k]['PRICE_PROPERTIES'] = $arPriceProperties;
        $arResult['SECTIONS'][$k]['PRICE_PROPERTIES_XML_IDS'] = $arPricePropertiesXmlIds;
    }
}

?>