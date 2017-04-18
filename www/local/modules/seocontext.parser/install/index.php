<?php

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Seocontext\Parser\SearchQueryTable;
use Seocontext\Parser\WebSiteTable;
use Seocontext\Parser\ParserTable;
use Seocontext\Parser\RowDataTable;


Loc::loadMessages(__FILE__);


/**
 * Created by PhpStorm.
 * User: shara
 * Date: 24.04.2016
 * Time: 13:51
 */
class seocontext_parser extends CModule
{
    const MODULE_ID = 'seocontext.parser';

    /** @var string */
    var $MODULE_ID = 'seocontext.parser';

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    /**
     * seocontext_parser constructor.
     */
    public function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('SEOCONTEXT_PARSER_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SEOCONTEXT_PARSER_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('SEOCONTEXT_PARSER_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('SEOCONTEXT_PARSER_PARTNER_URI');
    }

    /**
     * @param bool $notDocumentRoot
     * @return mixed|string
     */
    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    /**
     *
     */
    public function doInstall()
    {
        global $APPLICATION;
        if ($this->isVersionD7()) {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("SEOCONTEXT_INSTALL_ERROR_VERSION"));
        }


        $APPLICATION->IncludeAdminFile(Loc::getMessage("SEOCONTEXT_INSTALL_TITLE"), $this->GetPath() . "/install/step.php");
    }

    /**
     *
     */
    public function doUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if ($request["step"] < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("SEOCONTEXT_UNINSTALL_TITLE"), $this->GetPath() . "/install/unstep1.php");
        } elseif ($request["step"] == 2) {
            $this->UnInstallFiles();
            $this->UnInstallEvents();

            if ($request["savedata"] != "Y")
                $this->UnInstallDB();

            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(Loc::getMessage("SEOCONTEXT_UNINSTALL_TITLE"), $this->GetPath() . "/install/unstep2.php");
        }
    }

    // D7
    /**
     * @return bool
     */
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    /**
     * @throws \Bitrix\Main\LoaderException
     */
    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);
        if (!Application::getConnection(SearchQueryTable::getConnectionName())->isTableExists(
            Base::getInstance('Seocontext\Parser\SearchQueryTable')->getDBTableName()
        )
        ) {
            Base::getInstance('Seocontext\Parser\SearchQueryTable')->createDbTable();
        }
        if (!Application::getConnection(WebSiteTable::getConnectionName())->isTableExists(
            Base::getInstance('Seocontext\Parser\WebSiteTable')->getDBTableName()
        )
        ) {
            Base::getInstance('Seocontext\Parser\WebSiteTable')->createDbTable();
        }

        if (!Application::getConnection(RowDataTable::getConnectionName())->isTableExists(
            Base::getInstance('Seocontext\Parser\RowDataTable')->getDBTableName()
        )
        ) {
            Base::getInstance('Seocontext\Parser\RowDataTable')->createDbTable();
        }
        if (!Application::getConnection(ParserTable::getConnectionName())->isTableExists(
            Base::getInstance('Seocontext\Parser\ParserTable')->getDBTableName()
        )
        ) {
            Base::getInstance('Seocontext\Parser\ParserTable')->createDbTable();
        }

    }

    /**
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     */
    public function UnInstallEvents()
    {
        Loader::includeModule($this->MODULE_ID);
        Application::getConnection(SearchQueryTable::getConnectionName())->
        queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance('Seocontext\Parser\SearchQueryTable')->
            getDBTableName());
        Application::getConnection(WebSiteTable::getConnectionName())->
        queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance('Seocontext\Parser\WebSiteTable')->
            getDBTableName());
        Application::getConnection(RowDataTable::getConnectionName())->
        queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance('Seocontext\Parser\RowDataTable')->
            getDBTableName());
        Application::getConnection(ParserTable::getConnectionName())->
        queryExecute('DROP TABLE IF EXISTS ' . Base::getInstance('Seocontext\Parser\ParserTable')->
            getDBTableName());

        /** @var TYPE_NAME $this */
        \Bitrix\Main\Config\Option::delete($this->MODULE_ID);
    }

    /**
     *
     */
    public function InstallFiles()
    {
        parent::InstallFiles(); // TODO: Change the autogenerated stub
    }

}