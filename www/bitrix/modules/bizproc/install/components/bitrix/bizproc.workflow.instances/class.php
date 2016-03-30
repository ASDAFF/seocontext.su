<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Bizproc\WorkflowInstanceTable;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('bizproc'))
{
	return;
}

Loc::loadMessages(__FILE__);

class BizprocWorkflowInstances extends \CBitrixComponent
{
	const GRID_ID = 'bizproc_instances';
	const DEFAULT_PAGE_SIZE = 50;

	protected $isAdmin;
	protected $gridOptions;
	protected static $fields = array(
		'ID' => 'ID',
		'MODIFIED' => 'MODIFIED',
		'OWNER_ID' => 'OWNER_ID',
		'OWNED_UNTIL' => 'OWNED_UNTIL',
		'WS_MODULE_ID' => 'STATE.MODULE_ID',
		'WS_ENTITY' => 'STATE.ENTITY',
		'WS_DOCUMENT_ID' => 'STATE.DOCUMENT_ID',
		'WS_STARTED' => 'STATE.STARTED',
		'WS_STARTED_BY' => 'STATE.STARTED_BY',
		'WS_WORKFLOW_TEMPLATE_ID' => 'STATE.WORKFLOW_TEMPLATE_ID',
		'WS_STARTED_USER_NAME' => 'STATE.STARTED_USER.NAME',
		'WS_STARTED_USER_LAST_NAME' => 'STATE.STARTED_USER.LAST_NAME',
		'WS_STARTED_USER_LOGIN' => 'STATE.STARTED_USER.LOGIN',
	);
	
	protected function isAdmin()
	{
		global $USER;
		if ($this->isAdmin === null)
		{
			$this->isAdmin = $USER->IsAdmin() || (Loader::includeModule('bitrix24') && \CBitrix24::IsPortalAdmin($USER->GetID()));
		}
		return $this->isAdmin;
	}

	public function onPrepareComponentParams($params)
	{
		if (empty($params['NAME_TEMPLATE']))
			$params['NAME_TEMPLATE'] = COption::GetOptionString("bizproc", "name_template", CSite::GetNameFormat(false), SITE_ID);

		return $params;
	}

	protected function getFieldName($name)
	{
		return $name && isset(static::$fields[$name]) ? static::$fields[$name] : null;
	}
	
	protected function getGridHeaders()
	{
		return array(
			array('id' => 'ID', 'name' => 'ID', 'default' => true, 'sort' => 'ID'),
			array('id' => 'MODIFIED', 'name' => Loc::getMessage('BPWI_MODIFIED'), 'default' => true, 'sort' => 'MODIFIED'),
			array('id' => 'OWNER_ID', 'name' => Loc::getMessage('BPWI_IS_LOCKED'), 'default' => true, 'sort' => 'OWNER_ID'),
			array('id' => 'OWNED_UNTIL', 'name' => Loc::getMessage('BPWI_OWNED_UNTIL'), 'default' => true, 'sort' => 'OWNED_UNTIL'),
			array('id' => 'WS_STARTED', 'name' => Loc::getMessage('BPWI_WS_STARTED'), 'default' => false, 'sort' => 'WS_STARTED'),
			array('id' => 'WS_STARTED_BY', 'name' => Loc::getMessage('BPWI_WS_STARTED_BY'), 'default' => false, 'sort' => 'WS_STARTED_BY'),
			array('id' => 'WS_WORKFLOW_TEMPLATE_ID', 'name' => Loc::getMessage('BPWI_WS_WORKFLOW_TEMPLATE_ID'), 'default' => false, 'sort' => 'WS_WORKFLOW_TEMPLATE_ID', 'ormField' => 'STATE.WORKFLOW_TEMPLATE_ID'),
		);
	}
	
	protected function getFilter()
	{
		return array(
			array('id' => 'MODIFIED', 'name' => Loc::getMessage('BPWI_MODIFIED'), 'type' => 'date', 'default' => false),
			array('id' => 'OWNER_ID',  'name' => Loc::getMessage('BPWI_IS_LOCKED'), 'type' => 'list',
				'items' => array(
					0 => Loc::getMessage('BPWI_NO_MATTER'),
					1 => Loc::getMessage('BPWI_YES'),
					2 => Loc::getMessage('BPWI_NO'),
				), 'default' => true),
			array('id' => 'WS_STARTED', 'name' => Loc::getMessage('BPWI_WS_STARTED'), 'type' => 'date', 'default' => true),
			array('id' => 'WS_STARTED_BY', 'name' => Loc::getMessage('BPWI_WS_STARTED_BY'), 'type' => 'user', 'default' => true),
		);
	}
	
	protected function getFilterPresets()
	{
		return array(
			'filter_locked' => array('name' => Loc::getMessage('BPWI_FILTER_PRESET_LOCKED'), 'fields' => array('OWNER_ID' => 1)),
		);
	}
	
	protected function getDocumentTypes()
	{
		return array(
			'*' => array('NAME' => Loc::getMessage('BPWI_FILTER_DOCTYPE_ALL')),
			'processes' => array('NAME' => Loc::getMessage('BPWI_FILTER_DOCTYPE_CLAIMS'), 'MODULE_ID' => 'lists'),
			'crm' => array('NAME' => Loc::getMessage('BPWI_FILTER_DOCTYPE_CRM'), 'MODULE_ID' => 'crm'),
			'disk' => array('NAME' => Loc::getMessage('BPWI_FILTER_DOCTYPE_DISK'), 'MODULE_ID' => 'disk'),
			'lists' => array('NAME' => Loc::getMessage('BPWI_FILTER_DOCTYPE_LISTS'), 'MODULE_ID' => 'iblock')
		);
	}

	protected function setPageTitle($title)
	{
		global $APPLICATION;
		$APPLICATION->SetTitle($title);
	}

	protected function getGridOptions()
	{
		if ($this->gridOptions === null)
		{
			$this->gridOptions = new CGridOptions(static::GRID_ID);
		}
		return $this->gridOptions;
	}

	protected function mergeFilters(array $filter, array $gridFilter)
	{
		foreach ($gridFilter as $key => $value)
		{
			if (substr($key, -5) == '_from')
			{
				$op = '>=';
				$newKey = substr($key, 0, -5);
			}
			elseif (substr($key, -3) == '_to')
			{
				$op = '<=';
				$newKey = substr($key, 0, -3);

				if (in_array($newKey, array('MODIFIED', 'WS_STARTED')))
				{
					if (!preg_match('/\\d\\d:\\d\\d:\\d\\d\$/', $value))
						$value .= ' 23:59:59';
				}
			}
			else
			{
				$op = '';
				$newKey = $key;
			}

			$fieldKey = $this->getFieldName($newKey);
			if (!$fieldKey)
				continue;

			if ($fieldKey == 'OWNER_ID')
			{
				if (!$value)
					continue;
				if ($value == 2)
					$value = null;
				else
				{
					$op = '!';
					$value = null;
				}
			}


			$filter[$op.$fieldKey] = $value;
		}

		return $filter;
	}

	protected function getSorting()
	{
		$gridSort = $this->getGridOptions()->getSorting(array('sort' => array('MODIFIED' => 'desc')));
		$orderRule = $gridSort['sort'];
		$orderKeys  = array_keys($orderRule);
		if ($this->getFieldName($orderKeys[0]) === null)
		{
			$orderRule = array('MODIFIED' => 'desc');
		}
		return $orderRule;
	}

	protected function getPaginationInfo()
	{
		$pageSize = $this->getGridOptions()->getNavParams(array('nPageSize' => static::DEFAULT_PAGE_SIZE));
		$pageSize = $pageSize['nPageSize'];
		$currentPage = isset($_REQUEST['pageNumber'])? max(1, (int)$_REQUEST['pageNumber']) : 1;
		$offset = ($currentPage - 1)*$pageSize;
		return array($currentPage, $pageSize, $offset);
	}

	public function executeComponent()
	{
		if ($this->arParams['SET_TITLE'])
		{
			$this->setPageTitle(Loc::getMessage('BPWI_PAGE_TITLE'));
		}

		if (!$this->isAdmin())
		{
			$this->arResult['FatalErrorMessage'] = Loc::getMessage('BPWI_NO_ACCESS');
			$this->includeComponentTemplate();
			return;
		}

		if (!empty($_POST['ID']) && check_bitrix_sessid())
		{
			foreach((array)$_POST['ID'] as $id)
			{
				CBPDocument::killWorkflow($id);
			}
		}

		$selectFields = array('ID', 'MODIFIED', 'OWNER_ID', 'OWNED_UNTIL',
			'WS_MODULE_ID' => $this->getFieldName('WS_MODULE_ID'),
			'WS_ENTITY' => $this->getFieldName('WS_ENTITY'),
			'WS_DOCUMENT_ID' => $this->getFieldName('WS_DOCUMENT_ID')
		);
		$gridColumns = $this->getGridOptions()->getVisibleColumns();

		$this->arResult['HEADERS'] = $this->getGridHeaders();

		foreach ($this->arResult['HEADERS'] as $h)
		{
			if ((count($gridColumns) <= 0 || in_array($h['id'], $gridColumns)) && !in_array($h['id'], $selectFields))
				$selectFields[$h['id']] = $this->getFieldName($h['id']);
		}

		if (isset($selectFields['WS_STARTED_BY']))
		{
			$selectFields['WS_STARTED_USER_NAME'] =  $this->getFieldName('WS_STARTED_USER_NAME');
			$selectFields['WS_STARTED_USER_LAST_NAME'] =  $this->getFieldName('WS_STARTED_USER_LAST_NAME');
			$selectFields['WS_STARTED_USER_LOGIN'] =  $this->getFieldName('WS_STARTED_USER_LOGIN');
		}

		$filter = array();
		$templatesFilter = array();

		$this->arResult['DOCUMENT_TYPES'] = $this->getDocumentTypes();
		if (!empty($_REQUEST['type']) && isset($this->arResult['DOCUMENT_TYPES'][$_REQUEST['type']]))
		{
			$this->arResult['DOCUMENT_TYPES'][$_REQUEST['type']]['ACTIVE'] = true;
			if (!empty($this->arResult['DOCUMENT_TYPES'][$_REQUEST['type']]['MODULE_ID']))
			{
				$filter = array('STATE.MODULE_ID' => $this->arResult['DOCUMENT_TYPES'][$_REQUEST['type']]['MODULE_ID']);
				$templatesFilter = array('MODULE_ID' => $filter['STATE.MODULE_ID']);
			}
		}
		else
			$this->arResult['DOCUMENT_TYPES']['*']['ACTIVE'] = true;

		$templatesList = array('' => Loc::getMessage('BPWI_WORKFLOW_ID_ANY'));
		$dbResTmp = \CBPWorkflowTemplateLoader::GetList(
			array('NAME' => 'ASC'),
			$templatesFilter,
			false,
			false,
			array('ID', 'NAME')
		);
		while ($arResTmp = $dbResTmp->GetNext())
			$templatesList[$arResTmp['ID']] = $arResTmp['NAME'];

		$this->arResult['FILTER'] = $this->getFilter();
		$this->arResult['FILTER'][] = array('id' => 'WS_WORKFLOW_TEMPLATE_ID', 'name' => Loc::getMessage('BPWI_WS_WORKFLOW_TEMPLATE_ID'), 'type' => 'list', 'items' => $templatesList);

		$gridFilter = $this->getGridOptions()->getFilter($this->arResult['FILTER']);
		$filter = $this->mergeFilters($filter, $gridFilter);

		list ($currentPage, $pageSize, $offset) = $this->getPaginationInfo();

		$this->arResult['SORT'] = $this->getSorting();
		$this->arResult['CURRENT_PAGE'] = $currentPage;
		$this->arResult['SHOW_NEXT_PAGE'] = false;
		$this->arResult['RECORDS'] = array();

		$iterator = WorkflowInstanceTable::getList(array(
			'order' => $this->arResult['SORT'],
			'select' => $selectFields,
			'filter' => $filter,
			'limit' => $pageSize + 1,
			'offset' => $offset,
		));

		$rowsCount = 0;
		while ($row = $iterator->fetch())
		{
			$rowsCount++;
			if($rowsCount > $pageSize)
			{
				$this->arResult['SHOW_NEXT_PAGE'] = true;
				break;
			}

			$row['OWNER_ID'] = $row['OWNER_ID']? Loc::getMessage('BPWI_YES') : Loc::getMessage('BPWI_NO');
			$row['WS_WORKFLOW_TEMPLATE_ID'] = $templatesList[$row['WS_WORKFLOW_TEMPLATE_ID']];

			if (!empty($row['WS_STARTED_BY']))
			{
				$row['WS_STARTED_BY'] = CUser::FormatName(
						$this->arParams["NAME_TEMPLATE"],
						array(
							'LOGIN' => $row['WS_STARTED_USER_LOGIN'],
							'NAME' => $row['WS_STARTED_USER_NAME'],
							'LAST_NAME' => $row['WS_STARTED_USER_LAST_NAME'],
						),
						true)." [".$row['WS_STARTED_BY']."]";
			}
			$row['DOCUMENT_URL'] = '';
			if (!empty($row['WS_MODULE_ID']))
				$row['DOCUMENT_URL'] = CBPDocument::GetDocumentAdminPage(array(
					$row['WS_MODULE_ID'],
					$row['WS_ENTITY'],
					$row['WS_DOCUMENT_ID']
				));

			$rowActions = array(
				array(
					"ICONCLASS"=>"edit",
					"DEFAULT" => true,
					"TEXT"=>Loc::getMessage("BPWI_OPEN_DOCUMENT"),
					"ONCLICK"=> $row['DOCUMENT_URL']? "window.open('".$row["DOCUMENT_URL"]."');" : 'alert(\''.Loc::getMessage('BPWI_NO_DOCUMENT').'\')'
				),
				array(
					"ICONCLASS"=>"delete",
					"TEXT"=>Loc::getMessage("BPWI_DELETE_LABEL"),
					"ONCLICK" => "bxGrid_".static::GRID_ID.".DeleteItem('".$row['ID']."', '".Loc::getMessage("BPWI_DELETE_CONFIRM")."')"
				),
			);

			$this->arResult['RECORDS'][] = array('data' => $row, 'editable' => true, 'actions' => $rowActions);
		}

		$this->arResult['ROWS_COUNT'] = sizeof($this->arResult['RECORDS']);
		$this->arResult['GRID_ID'] = static::GRID_ID;
		$this->arResult['FILTER_PRESETS'] = $this->getFilterPresets();
		$this->includeComponentTemplate();

	}
}