<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/install/wizard_sol/wizard.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/wizards/doninbiz/fortis/fortis.php");

/**
 * Class SelectSiteStep
 */
class SelectSiteStep extends CSelectSiteWizardStep
{

    function InitStep()
    {
        parent::InitStep();

        $wizard =& $this->GetWizard();
        $wizard->solutionName = "fortis";
        $this->SetNextStep("select_type_install");
    }

}

/**
 * Class SelectTypeInstallStep
 */
class SelectTypeInstallStep extends CWizardStep
{

    function InitStep()
    {
        $this->SetStepID("select_type_install");
        $this->SetTitle(GetMessage("DONINBIZ_UPDATE_TYPE"));
        $this->SetNextStep("select_template");
        $this->SetPrevStep("select_site");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $wizard =& $this->GetWizard();
    }

    function showStep() {
        $wizard =& $this->GetWizard();
        $siteID      = $wizard->GetVar("siteID");

        $this->content .= '<div id="solutions-container" class="inst-template-list-block">';

        $sFirstInstall = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));

        if ($sFirstInstall != 'Y') {

            $wizard->SetDefaultVar("typeInstall", 'newinstall');

            // New install
            $this->content .= '<div class="inst-template-description" style="height: auto;">';
            $this->content .= $this->ShowRadioField("typeInstall", 'newinstall', Array("id" => 'newinstall', "class" => "inst-template-list-inp"));
            $this->content .= '<label for="newinstall" class="inst-template-list-label" style="max-width: 600px;"><strong style="font-size: 22px;">'.GetMessage("DONINBIZ_NEW_INSTALL").'</strong></label>';
            $this->content .= '</div>';

        } else {

            $update = new FortisUpdate($siteID, WIZARD_SITE_DIR);
            $sUpdateStatusText = '';

            if ($update->check()) {
                $wizard->SetDefaultVar("typeInstall", 'update');

                // Update
                $this->content .= '<div class="inst-template-description" style="height: auto;">';
                $this->content .= $this->ShowRadioField("typeInstall", 'update', Array("id" => 'update', "class" => "inst-template-list-inp"));
                $this->content .= '<label for="update" class="inst-template-list-label" style="max-width: 600px;">
                <strong style="font-size: 22px;">'.GetMessage("DONINBIZ_UPDATE_LABEL").'</strong>
                    <p style="font-weight: normal; margin: 0;"><strong>'.GetMessage("DONINBIZ_UPDATE_WARNING_TEXT").'</strong> '.GetMessage("DONINBIZ_UPDATE_TEXT").'</p>
                </label>';
                $this->content .= '</div>';

                // ReInstall
                $this->content .= '<div class="inst-template-description" style="height: auto;">';
                $this->content .= $this->ShowRadioField("typeInstall", 'reinstall', Array("id" => 'reinstall', "class" => "inst-template-list-inp"));
                $this->content .= '<label for="reinstall" class="inst-template-list-label" style="max-width: 600px;"><strong style="font-size: 22px;">'.GetMessage("DONINBIZ_REINSTALL_LABEL").'</strong><p style="font-weight: normal; margin: 0;">'.GetMessage("DONINBIZ_REINSTALL_TEXT").'</p></label>';
                $this->content .= '</div>';

            } else {
                $wizard->SetDefaultVar("typeInstall", '');
                if ($update->getCurVersion() == $update->getLastVersion()) {
                    $this->content .= '<p><span style="color: green;">'.GetMessage("DONINBIZ_ACTUAL_V_TEXT").'</span><br /><br />'.GetMessage("DONINBIZ_CURRENT_VERSION").' <b>' . $update->getCurVersion() . '</b><br />'.GetMessage("DONINBIZ_LAST_VERSION").' <b>' . $update->getLastVersion() . '</b></p>';
                } else {
                    $this->content .= '<p>'.GetMessage("DONINBIZ_LAST_VERSION").' <b>' . $update->getLastVersion() . '</b></p>';
                    $this->content .= '<p>'.GetMessage("DONINBIZ_INCORRECT_WARNING_CONTINUE").'</p>';
                }
            }

        }

        $this->content .= '</div>';
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();

        if ($wizard->IsNextButtonClick()) {
            $typeInstall = $wizard->GetVar('typeInstall');
            $siteID      = $wizard->GetVar("siteID");

            if ($typeInstall == 'update') {

                $update = new FortisUpdate($siteID, WIZARD_SITE_DIR);

                if ($update->check()) {

                    if ($update->process()) {

                        $wizard->SetCurrentStep("finish");
                        $wizard->SetVar('update_msg', GetMessage("DONINBIZ_UPDATE_SUCCESS_TEXT", array('#LAST_VERSION#' => '<b>' . $update->getLastVersion() . '</b>')));

                    } else {
                        $this->SetError(GetMessage("DONINBIZ_UPDATE_ERROR"));
                    }

                } else {
                    $this->SetError(GetMessage('DONINBIZ_ALREADY_EXISTS') . ' '.GetMessage("DONINBIZ_CURRENT_VERSION").' ' . $update->getCurVersion() . '. '.GetMessage("DONINBIZ_LAST_VERSION").' ' . $update->getLastVersion());
                }
            }
        }

    }
}


/**
 * Class SelectTemplateStep
 */
class SelectTemplateStep extends CSelectTemplateWizardStep
{
    function InitStep() {
        parent::InitStep();
        $this->SetPrevStep("select_type_install");
    }

    function OnPostForm() {
        $wizard =& $this->GetWizard();

        if ($wizard->IsNextButtonClick()) {

            $templatesPath = WizardServices::GetTemplatesPath($wizard->GetPath()."/site");
            $arTemplates = WizardServices::GetTemplates($templatesPath);

            $templateID = $wizard->GetVar("templateID");
            $templateLayout = $wizard->GetVar("templateLayout");

            if (!array_key_exists($templateID, $arTemplates))
                $this->SetError(GetMessage("DONINBIZ_TEMPLATE_ERROR"));

            if (!array_key_exists($templateLayout, FortisWizard::instance()->getLayouts()))
                $this->SetError(GetMessage("DONINBIZ_LAYOUT_ERROR"));
        }
    }

    function showStep() {
        $wizard =& $this->GetWizard();
        $wizard->SetVar("templateID", 'fortis');

        $this->content .= '<div id="solutions-container" class="inst-template-list-block">';
        foreach(FortisWizard::instance()->getLayouts() as $sLayoutType => $aLayout) {
            if ($aLayout['default']) $wizard->SetDefaultVar("templateLayout", $sLayoutType);

            $this->content .= '<div class="inst-template-description">';

            $this->content .= $this->ShowRadioField("templateLayout", $sLayoutType, Array("id" => $sLayoutType, "class" => "inst-template-list-inp"));
            $this->content .= CFile::Show2Images($wizard->package->path.'/images/layouts/'.$aLayout['s_img'], $wizard->package->path.'/images/layouts/'.$aLayout['img'], 150, 150, ' class="inst-template-list-img"');
            $this->content .= '<label for="'.$sLayoutType.'" class="inst-template-list-label">'.GetMessage($aLayout['name']).'<p>'.GetMessage($aLayout['desc']).'</p></label>';

            $this->content .= '</div>';
        }
        $this->content .= '</div>';

        //$this->content .= '<pre>'.print_r($wizard, true).'</pre>';
    }
}


/**
 * Class SelectThemeStep
 */
class SelectThemeStep extends CSelectThemeWizardStep
{
    function OnPostForm() {
        $wizard =& $this->GetWizard();

        if ($wizard->IsNextButtonClick()) {
            $templateColor = $wizard->GetVar('templateColor');
            $templateStyle = $wizard->GetVar('templateStyle');

            if (empty($templateColor) || !preg_match('/#[a-f0-9]{6}/iu', $templateColor))
                $this->SetError(GetMessage("DONINBIZ_COLOR_ERROR"));

            if (empty($templateStyle))
                $this->SetError(GetMessage('DONINBIZ_COLOR_NO_GENERATED'));
        }
    }

    function ShowStep() {
        $wizard =& $this->GetWizard();

        $this->content .= '
        <link href="'.$wizard->package->path.'/css/style.css" type="text/css" rel="stylesheet" />
        <link href="'.$wizard->package->path.'/site/templates/fortis/assets/css/vendor/bootstrap.min.css" type="text/css" rel="stylesheet" />
        <link href="'.$wizard->package->path.'/site/templates/fortis/assets/css/vendor/bootstrap-colorpicker.min.css" type="text/css" rel="stylesheet" />
        <script>var defaultPrimaryColor = "'.FortisWizard::instance()->getDefaultColor().'";</script>
        <script type="text/javascript" src="'.$wizard->package->path.'/site/templates/fortis/assets/js/vendor/jquery.min.js"></script>
        <script type="text/javascript" src="'.$wizard->package->path.'/site/templates/fortis/assets/js/vendor/bootstrap.min.js"></script>
        <script type="text/javascript" src="'.$wizard->package->path.'/site/templates/fortis/assets/js/vendor/bootstrap-colorpicker.min.js"></script>
        <script type="text/javascript" src="'.$wizard->package->path.'/js/common.js"></script>';

        $this->content .= '<h3 id="fortis-list-color-loading">'.GetMessage('DONINBIZ_COLOR_LOADING').'</h3>';

        $this->content .= '<div id="html_container"><div class="inst-template-color-block" id="solutions-container-color-block" style="display: none;">';
        $this->content .= '<h3>'.GetMessage("DONINBIZ_COLOR_SELECT_H3").'</h3>';

        $this->content .= '<div id="fortis-list-colors"><ul>';
        foreach(FortisWizard::instance()->getColors() as $iColor => $sColor) {
            if ($iColor == 0) $wizard->SetDefaultVar("templateColor", $sColor);

            $this->content .= '<li>
                <label style="background-color: '.$sColor.'"'.($iColor == 0 ? ' class="active"' : '').'>'.$this->ShowRadioField("templateColor", $sColor, Array("class" => "fortis-color")).'<span class="tick"></span></label>
            </li>';
        }
        $this->content .= '<li>
            <label class="color-picker-label">
                    '.$this->ShowRadioField("templateColor", null, Array("class" => "fortis-color")).'
                    <span class="init-color-picker"></span>
            </label>
        </li>';
        $this->content .= '</ul><div class="clearfix"></div></div>';

        $this->content .= '<h5>'.GetMessage('DONINBIZ_COLOR_PREVIEW').'</h5>';
        $this->content .= '<iframe id="preview_site" src="'.$wizard->package->path.'/preview.php?template_path='.$wizard->package->path.'/site/templates/fortis/&wizard_path='.$wizard->package->path.'/" frameBorder="0" width="800" height="500" style="border: 1px solid #999;"></iframe>';

        $this->content .= $this->ShowHiddenField('templateStyle', '', array('id' => 'fortis-template-style'));
        $this->content .= '</div></div>';
    }

}


/**
 * Class SiteSettingsStep
 */
class SiteSettingsStep extends CSiteSettingsWizardStep
{
    function InitStep() {
        parent::InitStep();
        $wizard =& $this->GetWizard();

        $siteLogo = $wizard->package->path . "/site/templates/fortis/assets/img/def-logo.png";

        $sSiteName = GetMessage('DONINBIZ_FIELD_SITENAME_DEF');
        $obSite = CSite::GetList($by = "def", $order = "desc", Array("LID" => $wizard->GetVar('siteID')));
        if ($arSite = $obSite->Fetch()) {
            $sSiteName = !empty($arSite['SITE_NAME']) ? $arSite['SITE_NAME'] : $sSiteName;
        }

        $wizard->SetDefaultVars(
            Array(
                "siteLogo"            => $siteLogo,
                "siteName"            => $sSiteName,
                "siteCompany"         => GetMessage('DONINBIZ_FIELD_SITECOMPANY'),
                "siteAddress"         => GetMessage('DONINBIZ_FIELD_ADDRESS_DEFAULT'),
                "sitePhone1"          => '8 (495) 0000-000',
                "sitePhone2"          => '8 (804) 0000-000',
                "siteEmail"           => 'info@company.ru',
                "siteCopyrights"      => $this->GetFileContent($_SERVER["DOCUMENT_ROOT"].$wizard->package->path."/site/public/".LANGUAGE_ID."/includes/footer_copyright.php", ""),
                "siteMetaKeywords"    => GetMessage('DONINBIZ_FIELD_META_KEYWORDS_DEFAULT'),
                "siteMetaDescription" => GetMessage('DONINBIZ_FIELD_META_DESCRIPTION_DEFAULT'),
            )
        );
    }

    function ShowStep() {
        $wizard =& $this->GetWizard();

        $firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));

        $siteLogo = $wizard->GetVar("siteLogo", true);

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'.GetMessage("DONINBIZ_FIELD_LOGO").'</div>';
        $this->content .= CFile::ShowImage($siteLogo, 150, 50, "border=0 vspace=15");
        $this->content .= "<br />".$this->ShowFileField("siteLogo", Array("show_file_info" => "N", "id" => "site-logo"))."</div>";

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'.GetMessage("DONINBIZ_FIELDS").'</div>';

        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="siteCompany" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_SITENAME").':</label><br />
                '.$this->ShowInputField('text', 'siteName', array("id" => "siteName", "class" => "wizard-field")).'
            </div>';
        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteCopyrights" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_COPYRIGHTS").':</label><br />
			'.$this->ShowInputField('textarea', 'siteCopyrights', array("id" => "siteCopyrights", "class" => "wizard-field")).'
		</div>';

        $styleMeta = 'style="display:block"';
        if($firstStep == "Y") $styleMeta = 'style="display:none"';

        $this->content .= '<div id="bx_metadata" '.$styleMeta.'>';
        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="siteCompany" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_COMPANY").':</label><br />
                '.$this->ShowInputField('text', 'siteCompany', array("id" => "siteCompany", "class" => "wizard-field")).'
            </div>';
        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="siteAddress" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_ADDRESS").':</label><br />
                '.$this->ShowInputField('text', 'siteAddress', array("id" => "siteAddress", "class" => "wizard-field")).'
            </div>';
        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="sitePhone1" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_PHONES").':</label><br />
                '.$this->ShowInputField('text', 'sitePhone1', array("id" => "sitePhone1", "class" => "wizard-field")).'<br />
                '.$this->ShowInputField('text', 'sitePhone2', array("id" => "sitePhone2", "class" => "wizard-field")).'
            </div>';
        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="siteEmail" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_EMAIL").':</label><br />
                '.$this->ShowInputField('text', 'siteEmail', array("id" => "siteEmail", "class" => "wizard-field")).'
            </div>';

        $this->content .= '<div class="wizard-catalog-title">'.GetMessage("DONINBIZ_FIELD_META").'</div>';

        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="siteMetaKeywords" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_META_KEYWORDS").':</label><br />
                '.$this->ShowInputField('textarea', 'siteMetaKeywords', array("id" => "siteMetaKeywords", "class" => "wizard-field")).'
            </div>';

        $this->content .= '
            <div class="wizard-input-form-block">
                <label for="siteMetaDescription" class="wizard-input-title">'.GetMessage("DONINBIZ_FIELD_META_DESCRIPTION").':</label><br />
                '.$this->ShowInputField('textarea', 'siteMetaDescription', array("id" => "siteMetaDescription", "class" => "wizard-field")).'
            </div>';
        $this->content .= '</div>';

        if($firstStep == "Y") {
            $this->content .= $this->ShowCheckboxField("installDemoData", "Y",
                (array("id" => "install-demo-data", "onClick" => "if(this.checked == true){document.getElementById('bx_metadata').style.display='block';}else{document.getElementById('bx_metadata').style.display='none';}")));
            $this->content .= '<label for="install-demo-data"><h2 style="display: inline;">'.GetMessage("DONINBIZ_REINSTALL_DEMO_DATA").'</h2></label><br />';

        } else {
            $this->content .= $this->ShowHiddenField("installDemoData","Y");
        }

    }

    function OnPostForm() {
        $wizard =& $this->GetWizard();
        $res = $this->SaveFile("siteLogo", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 90, "max_width" => 200, "make_preview" => "Y"));
    }
}


/**
 * Class DataInstallStep
 */
class DataInstallStep extends CDataInstallWizardStep
{
    function CorrectServices(&$arServices) {
        $wizard =& $this->GetWizard();
        if($wizard->GetVar("installDemoData") != "Y") { }
    }
}


/**
 * Class FinishStep
 */
class FinishStep extends CFinishWizardStep
{
    function InitStep() {
        parent::InitStep();
        $wizard =& $this->GetWizard();
        $wizard->solutionName = "fortis";
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();

        $siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
        $rsSites = CSite::GetByID($siteID);
        $siteDir = "/";
        if ($arSite = $rsSites->Fetch())
            $siteDir = $arSite["DIR"];

        $wizard->SetFormActionScript(str_replace("//", "/", $siteDir."/?finish"));

        $this->CreateNewIndex();

        COption::SetOptionString("main", "wizard_solution", $wizard->solutionName, false, $siteID);

        if ($wizard->GetVar('update_msg')) {
            $this->content .= $wizard->GetVar('update_msg');
        } else {
            $this->content .= GetMessage("FINISH_STEP_CONTENT");
            if ($wizard->GetVar("installDemoData") == "Y")
                $this->content .= GetMessage("FINISH_STEP_REINDEX");

            $update = new FortisUpdate($siteID, WIZARD_SITE_DIR);
            $update->setLastVersion();
        }

    }
}

?>