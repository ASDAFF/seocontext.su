<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;

if (!defined("WIZARD_SITE_DIR"))
	return;
 
if (WIZARD_INSTALL_DEMO_DATA)
{
	$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"); 
	
	$handle = @opendir($path);
	if ($handle)
	{
		while ($file = readdir($handle))
		{
			if (in_array($file, array(".", "..")))
				continue; 

			CopyDirFiles(
				$path.$file,
				WIZARD_SITE_PATH."/".$file,
				$rewrite = true, 
				$recursive = true,
				$delete_after_copy = false
			);
		}
		CModule::IncludeModule("search");
		CSearch::ReIndexAll(false, 0, Array(WIZARD_SITE_ID, WIZARD_SITE_DIR));
	}

	WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."ajax/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."informatsiya/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."katalog/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."kontakty/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."o-kompanii/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."order/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."poisk/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."portfolio/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."uslugi/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."includes/", Array("SITE_DIR" => WIZARD_SITE_DIR));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "404.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . ".top.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));

	$arUrlRewrite = array(); 
	if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
	{
		include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
	}

    $arNewUrlRewrite = array(
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."o-kompanii/nasha-komanda/#",
            "RULE" => "",
            "ID" => "doninbiz:sections.elements",
            "PATH" => WIZARD_SITE_DIR."o-kompanii/nasha-komanda/index.php",
        ),
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."informatsiya/novosti/#",
            "RULE" => "",
            "ID" => "bitrix:news",
            "PATH" => WIZARD_SITE_DIR."informatsiya/novosti/index.php",
        ),
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."informatsiya/aktsii/#",
            "RULE" => "",
            "ID" => "bitrix:news",
            "PATH" => WIZARD_SITE_DIR."informatsiya/aktsii/index.php",
        ),
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."informatsiya/stati/#",
            "RULE" => "",
            "ID" => "bitrix:news",
            "PATH" => WIZARD_SITE_DIR."informatsiya/stati/index.php",
        ),
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."portfolio/#",
            "RULE" => "",
            "ID" => "doninbiz:catalog.portfolio",
            "PATH" => WIZARD_SITE_DIR."portfolio/index.php",
        ),
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."katalog/#",
            "RULE" => "",
            "ID" => "doninbiz:catalog",
            "PATH" => WIZARD_SITE_DIR."katalog/index.php",
        ),
        array(
            "CONDITION" => "#^".WIZARD_SITE_DIR."uslugi/#",
            "RULE" => "",
            "ID" => "doninbiz:catalog.services",
            "PATH" => WIZARD_SITE_DIR."uslugi/index.php",
        ),
    );
	
	foreach ($arNewUrlRewrite as $arUrl)
	{
		if (!in_array($arUrl, $arUrlRewrite))
		{
			CUrlRewriter::Add($arUrl);
		}
	}
}

function ___writeToAreasFile($fn, $text)
{
	if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
		@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($fn, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($fn, BX_FILE_PERMISSIONS);
}

CheckDirPath(WIZARD_SITE_PATH."includes/");

$wizard =& $this->GetWizard();

$siteLogo = $wizard->GetVar("siteLogo");
if($siteLogo > 0)
{
	$ff = CFile::GetByID($siteLogo);
	if($zr = $ff->Fetch())
	{
		$strOldFile = str_replace("//", "/", WIZARD_SITE_ROOT_PATH."/".(COption::GetOptionString("main", "upload_dir", "upload"))."/".$zr["SUBDIR"]."/".$zr["FILE_NAME"]);
		@copy($strOldFile, WIZARD_SITE_PATH."includes/logo.gif");
		___writeToAreasFile(WIZARD_SITE_PATH."includes/company_logo.php", '<img src="'.WIZARD_SITE_DIR.'includes/logo.gif" alt="logo">');
		CFile::Delete($siteLogo);
	}
}
elseif( ! file_exists(WIZARD_SITE_PATH."includes/def-logo.png"))
{
	copy(WIZARD_TEMPLATE_ABSOLUTE_PATH."/assets/img/def-logo.png", WIZARD_SITE_PATH."includes/def-logo.png");
	___writeToAreasFile(WIZARD_SITE_PATH."includes/company_logo.php", '<img src="'.WIZARD_SITE_DIR.'includes/def-logo.png" alt="logo">');
}

// Копирайт
___writeToAreasFile(WIZARD_SITE_PATH."includes/footer_copyright.php", $wizard->GetVar('siteCopyrights'));

if (WIZARD_INSTALL_DEMO_DATA) {

    // название компании
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "includes/footer_block_2.php", array("SITE_COMPANY" => htmlspecialcharsbx($wizard->GetVar("siteCompany"))));

    // Гугл карта
    if($wizard->GetVar('siteAddress')) {
        $params = array(
            'geocode' => htmlspecialcharsbx($wizard->GetVar('siteAddress')),
            'format'  => 'json',
            'results' => 1,
            'kind'    => 'house'
        );
        $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
        if (is_object($response) && !empty($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found)) {
            // координаты
            $coord = explode(' ', $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
            $coordinaty = array(
                'lon' => trim($coord[0]),
                'lat' => trim($coord[1])
            );

            $serializeAddress = serialize(array (
                'google_lat' => $coordinaty['lat'],
                'google_lon' => $coordinaty['lon'],
                'google_scale' => 17,
                'PLACEMARKS' =>
                    array (
                        0 =>
                            array (
                                'TEXT' => htmlspecialcharsbx($wizard->GetVar('siteAddress')),
                                'LON' => $coordinaty['lon'],
                                'LAT' => $coordinaty['lat']
                            ),
                    ),
            ));
        } else {
            $serializeAddress = serialize(array (
                'google_lat' => 55.756394821424,
                'google_lon' => 37.619938828564,
                'google_scale' => 17,
                'PLACEMARKS' =>
                    array (
                        0 =>
                            array (
                                'TEXT' => htmlspecialcharsbx($wizard->GetVar('siteAddress')),
                                'LON' => 37.619907259941,
                                'LAT' => 55.756389670056,
                            ),
                    ),
            ));
        }

        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/contacts_map.php", array("SITE_MAP_DATA" => addslashes($serializeAddress)));
    }


    // Адреса
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/kontakty/sect_contacts.php", array("SITE_ADDRESS" => htmlspecialcharsbx($wizard->GetVar('siteAddress'))));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/footer_block_2.php", array("SITE_ADDRESS" => htmlspecialcharsbx($wizard->GetVar('siteAddress'))));


    // Номера телефонов
    $sitePhone1 = ($wizard->GetVar('sitePhone1') ? '<span class="tel-mobile">'.htmlspecialcharsbx($wizard->GetVar('sitePhone1')).'</span>' : '');
    $sitePhone2 = ($wizard->GetVar('sitePhone2') ? '<span class="tel-mobile">'.htmlspecialcharsbx($wizard->GetVar('sitePhone2')).'</span>' : '');

    // Контакты
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/kontakty/sect_contacts.php", array("SITE_PHONES" =>
        $sitePhone1 . ($sitePhone2 ? '<br />'.$sitePhone2 : '')
    ));
    // Подвал
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/footer_block_2.php", array("SITE_PHONE1" => $sitePhone1 ));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/footer_block_2.php", array("SITE_PHONE2" =>
        ($sitePhone2 ? '<tr><td></td><td class="right">' . $sitePhone2 . '</td></tr>' : '')
    ));
    // Шапка
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/top_header_left.php", array("SITE_PHONES" =>
        ($sitePhone1 ? '<li><i class="fa fa-phone"></i>' . $sitePhone1 . '</li>' : '') .
        ($sitePhone2 ? '<li><i class="fa fa-phone"></i>' . $sitePhone2 . '</li>' : '')
    ));


    // Email адрес
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/kontakty/sect_contacts.php", array("SITE_EMAIL" => htmlspecialcharsbx($wizard->GetVar('siteEmail'))));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/footer_block_2.php", array("SITE_EMAIL" => htmlspecialcharsbx($wizard->GetVar('siteEmail'))));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/includes/top_header_left.php", array("SITE_EMAIL" => htmlspecialcharsbx($wizard->GetVar('siteEmail'))));


    // Meta
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_DESCRIPTION" => htmlspecialcharsbx($wizard->GetVar("siteMetaDescription"))));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_KEYWORDS" => htmlspecialcharsbx($wizard->GetVar("siteMetaKeywords"))));
}
?>