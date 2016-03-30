<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

if (!defined("WIZARD_TEMPLATE_ID"))
    return;

$templateDir = BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_SITE_ID;

/*CopyDirFiles(
    WIZARD_THEME_ABSOLUTE_PATH,
    $_SERVER["DOCUMENT_ROOT"].$templateDir,
    $rewrite = true,
    $recursive = true,
    $delete_after_copy = false
);*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/wizards/doninbiz/fortis/fortis.php");

$aFortisConfig = array(
    'header_color' => 'light',
    'zebra' => '1',
    'sidebar_align' => 'left',
);

$wizard =& $this->GetWizard();

$templateColor  = $wizard->GetVar("templateColor");
$templateStyle  = base64_decode($wizard->GetVar("templateStyle"));
$templateLayout = $wizard->GetVar("templateLayout");

$aFortisConfig['layout'] = empty($templateLayout) ? 'wide' : $templateLayout;

if (empty($templateColor) || empty($templateStyle)) {
    $aFortisConfig['color'] = FortisWizard::instance()->getDefaultColor();
} else {
    $aFortisConfig['color'] = $templateColor;
    // сохранение сгенерированного common.css
    file_put_contents($_SERVER["DOCUMENT_ROOT"].$templateDir . "/assets/css/common.css", $templateStyle);
}

// сохраняем конфиг файл темы
$sSave = var_export($aFortisConfig, true);
file_put_contents($_SERVER["DOCUMENT_ROOT"].$templateDir . '/style-switcher-conf.php', "<?php\n\n\$aFortisConfig = {$sSave};\n\n?>");

?>