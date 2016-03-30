<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class doninbiz_fortis extends CModule
{
	var $MODULE_ID = 'doninbiz.fortis'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = "Y";

	function __construct()
	{
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("doninbiz.fortis_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("doninbiz.fortis_MODULE_DESC");
		$this->PARTNER_NAME = GetMessage("doninbiz.fortis_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("doninbiz.fortis_PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{
        global $DB, $DBType, $APPLICATION;

        RegisterModule("doninbiz.fortis");
        RegisterModuleDependences("main", "OnBeforeProlog", "doninbiz.fortis", "CDoninbizFortis", "ShowPanel");

		return true;
	}

	function UnInstallDB($arParams = array())
	{
        global $DB, $DBType, $APPLICATION;

        UnRegisterModuleDependences("main", "OnBeforeProlog", "doninbiz.fortis", "CDoninbizFortis", "ShowPanel");
        UnRegisterModule("doninbiz.fortis");
        
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/doninbiz.fortis/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        
		return true;
	}

    function InstallPublic()
    {
    }

	function UnInstallFiles()
	{
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;
        
        $this->InstallFiles();
        $this->InstallDB(false);
        $this->InstallEvents();
        $this->InstallPublic();

        $APPLICATION->IncludeAdminFile(GetMessage("doninbiz.fortis_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/doninbiz.fortis/install/step.php");
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;
        
        $this->UnInstallDB();
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        $APPLICATION->IncludeAdminFile(GetMessage("doninbiz.fortis_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/doninbiz.fortis/install/unstep.php");
	}
}
?>