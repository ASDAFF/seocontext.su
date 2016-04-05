<?
use Bitrix\Main\localization\Loc,
	Bitrix\Main,
	Bitrix\Iblock,
	Bitrix\Catalog;

Loc::loadMessages(__FILE__);

class CCatalogStepOperations
{
	const DEFAULT_SESSION_PREFIX = 'CC';
	protected $sessID = '';
	protected $errorCounter = 0;
	protected $errors = array();
	protected $stepErrors = array();
	protected $maxExecutionTime = 0;
	protected $maxOperationCounter = 0;
	protected $startOperationTime = 0;
	protected $lastID = 0;
	protected $allCounter = 0;
	protected $allOperationCounter = 0;
	protected $finishOperation = false;
	protected $defaultProgressTemplate = '#PROGRESS_BAR#';
	protected $progressTemplate = '#PROGRESS_BAR#';
	protected $errorTemplate = '';
	protected $params = null;

	public function __construct($sessID, $maxExecutionTime, $maxOperationCounter)
	{
		$sessID = (string)$sessID;
		if ($sessID == '')
			$sessID = self::DEFAULT_SESSION_PREFIX.time();
		$this->sessID = $sessID;
		$this->errorCounter = 0;
		$this->errors = array();
		$this->stepErrors = array();
		$maxExecutionTime = (int)$maxExecutionTime;
		if ($maxExecutionTime < 0)
			$maxExecutionTime = $this->getDefaultExecutionTime();
		$this->maxExecutionTime = $maxExecutionTime;
		$maxOperationCounter = (int)$maxOperationCounter;
		if ($maxOperationCounter < 0)
			$maxOperationCounter = 10;
		$this->maxOperationCounter = $maxOperationCounter;
		$this->startOperationTime = time();
		$this->finishOperation = false;
		$this->progressTemplate = Loc::getMessage('BX_STEP_OPERATION_PROGRESS_TEMPLATE').$this->defaultProgressTemplate;
	}

	public function __destruct()
	{
		if ($this->sessID != '' && isset($_SESSION[$this->sessID]))
			unset($_SESSION[$this->sessID]);
	}

	public function setParams($params)
	{
		if (!empty($params) && is_array($params))
			$this->params = $params;
	}

	public function initStep($allCount, $allOperationCount, $lastID)
	{
		if (isset($_SESSION[$this->sessID]) && is_array($_SESSION[$this->sessID]))
		{
			if (isset($_SESSION[$this->sessID]['ERRORS_COUNTER']) && (int)$_SESSION[$this->sessID]['ERRORS_COUNTER'] > 0)
				$this->errorCounter = (int)$_SESSION[$this->sessID]['ERRORS_COUNT'];
		}
		$this->stepErrors = array();
		$lastID = (int)$lastID;
		if ($lastID < 0)
			$lastID = 0;
		$this->lastID = $lastID;
		$allCount = (int)$allCount;
		if ($allCount < 0)
			$allCount = 0;
		$this->allCounter = $allCount;
		$allOperationCount = (int)$allOperationCount;
		if ($allOperationCount < 0)
			$allOperationCount = 0;
		$this->allOperationCounter = $allOperationCount;
	}

	public function saveStep()
	{
		if (!isset($_SESSION[$this->sessID]) || !is_array($_SESSION[$this->sessID]))
			$_SESSION[$this->sessID] = array();
		if ($this->errorCounter > 0)
		{
			if (!empty($this->stepErrors))
				$this->errors = $this->stepErrors;
			$_SESSION[$this->sessID]['ERRORS_COUNTER'] = $this->errorCounter;
		}

		if (!$this->finishOperation)
		{
			$period = time() - $this->startOperationTime;
			if ($this->maxExecutionTime > 2*$period)
				$this->maxOperationCounter = $this->maxOperationCounter*2;
			elseif ($period >= $this->maxExecutionTime)
				$this->maxOperationCounter = (int)(($this->maxOperationCounter*2)/3);
			unset($period);
			if ($this->maxOperationCounter < 10)
				$this->maxOperationCounter = 10;
		}

		return array(
			'sessID' => $this->sessID,
			'maxExecutionTime' => $this->maxExecutionTime,
			'maxOperationCounter' => $this->maxOperationCounter,
			'lastID' => $this->lastID,
			'allCounter' => $this->allCounter,
			'counter' => $this->allCounter,
			'allOperationCounter' => $this->allOperationCounter,
			'operationCounter' => $this->allOperationCounter,
			'errorCounter' => $this->errorCounter,
			'errors' => (!empty($this->stepErrors) ? '<p>'.implode('</p><p>', $this->stepErrors).'</p>' : ''),
			'finishOperation' => $this->finishOperation,
			'message' => $this->getMessage()
		);
	}

	public function startOperation()
	{

	}

	public function finalOperation()
	{

	}

	public function runOperation()
	{

	}

	public function run()
	{
		$this->startOperation();
		$this->runOperation();
		$this->finalOperation();
	}

	public function setProgressTemplates($template)
	{
		$template = (string)$template;
		if ($template !== '')
			$this->progressTemplate = $template.$this->defaultProgressTemplate;
	}

	public function getMessage()
	{
		$messageParams = array(
			'MESSAGE' => '',
			'PROGRESS_TOTAL' => $this->allCounter,
			'PROGRESS_VALUE' => $this->allOperationCounter,
			'TYPE' => 'PROGRESS',
			'DETAILS' => str_replace(array('#ALL#', '#COUNT#'), array($this->allCounter, $this->allOperationCounter), $this->progressTemplate),
			'HTML' => true
		);
		$message = new CAdminMessage($messageParams);
		return $message->Show();
	}

	public static function getAllCounter()
	{
		return 0;
	}

	public static function getDefaultExecutionTime()
	{
		$executionTime = (int)ini_get('max_execution_time');
		if ($executionTime <= 0)
			$executionTime = 60;
		return (int)(2*$executionTime/3);
	}
}

class CCatalogProductSetAvailable extends CCatalogStepOperations
{
	const SESSION_PREFIX = 'PSA';

	public function __construct($sessID, $maxExecutionTime, $maxOperationCounter)
	{
		$sessID = (string)$sessID;
		if ($sessID == '')
			$sessID = self::SESSION_PREFIX.time();
		parent::__construct($sessID, $maxExecutionTime, $maxOperationCounter);
	}

	public function runOperation()
	{
		global $DB;

		$tableName = '';
		switch (ToUpper($DB->type))
		{
			case 'MYSQL':
				$tableName = 'b_catalog_discount';
				break;
			case 'MSSQL':
				$tableName = 'B_CATALOG_DISCOUNT';
				break;
			case 'ORACLE':
				$tableName = 'B_CATALOG_DISCOUNT';
				break;
		}
		if ($tableName == '')
			return;

		$emptyList = true;
		CTimeZone::Disable();
		$filter = array('TYPE' => CCatalogProductSet::TYPE_SET, 'SET_ID' => 0);
		if ($this->lastID > 0)
			$filter['>ID'] = $this->lastID;
		$topCount = ($this->maxOperationCounter > 0 ? array('nTopCount' => $this->maxOperationCounter) : false);
		$productSetsIterator = CCatalogProductSet::getList(
			array('ID' => 'ASC'),
			$filter,
			false,
			$topCount,
			array('ID', 'OWNER_ID', 'ITEM_ID', 'MODIFIED_BY', 'TIMESTAMP_X')
		);
		while ($productSet = $productSetsIterator->Fetch())
		{
			$emptyList = false;
			$productSet['MODIFIED_BY'] = (int)$productSet['MODIFIED_BY'];
			if ($productSet['MODIFIED_BY'] == 0)
				$productSet['MODIFIED_BY'] = false;
			CCatalogProductSet::recalculateSet($productSet['ID'], $productSet['ITEM_ID']);
			$arTimeFields = array(
				'~TIMESTAMP_X' => $DB->CharToDateFunction($productSet['TIMESTAMP_X'], "FULL"),
				'~MODIFIED_BY' => $productSet['MODIFIED_BY']
			);
			$strUpdate = $DB->PrepareUpdate($tableName, $arTimeFields);
			if (!empty($strUpdate))
			{
				$strQuery = "update ".$tableName." set ".$strUpdate." where ID = ".$productSet['ID'];
				$DB->Query($strQuery, false, "File: ".__FILE__."<br>Line: ".__LINE__);
			}
			$this->allOperationCounter++;
			$this->lastID = $productSet['ID'];
			if ($this->maxExecutionTime > 0 && (time() - $this->startOperationTime > $this->maxExecutionTime))
				break;
		}
		CTimeZone::Enable();
		if ($emptyList)
			$this->finishOperation = true;
	}

	public static function getAllCounter()
	{
		return (int)CCatalogProductSet::getList(
			array(),
			array('TYPE' => CCatalogProductSet::TYPE_SET, 'SET_ID' => 0),
			array()
		);
	}
}

class CCatalogProductAvailable extends CCatalogStepOperations
{
	const SESSION_PREFIX = 'PA';

	protected $iblockData = null;
	protected $productList = array();

	public function __construct($sessID, $maxExecutionTime, $maxOperationCounter)
	{
		$sessID = (string)$sessID;
		if ($sessID == '')
			$sessID = self::SESSION_PREFIX.time();
		parent::__construct($sessID, $maxExecutionTime, $maxOperationCounter);
	}

	public function runOperation()
	{
		if (!isset($this->params['IBLOCK_ID']))
			return;
		$this->params['IBLOCK_ID'] = (int)$this->params['IBLOCK_ID'];
		if ($this->params['IBLOCK_ID'] <= 0)
			return;
		$this->iblockData = CCatalogSKU::GetInfoByIBlock($this->params['IBLOCK_ID']);
		if (empty($this->iblockData))
			return;

		switch ($this->iblockData['CATALOG_TYPE'])
		{
			case CCatalogSKU::TYPE_CATALOG:
				$this->runOperationCatalog();
				break;
			case CCatalogSKU::TYPE_OFFERS:
				$this->runOperationOfferIblock();
				break;
			case CCatalogSKU::TYPE_FULL:
				$this->runOperationFullCatalog();
				break;
			case CCatalogSKU::TYPE_PRODUCT:
				$this->runOperationProductIblock();
				break;
		}
	}

	public function getMessage()
	{
		if (empty($this->iblockData))
			return parent::getMessage();

		$title = '';

		switch ($this->iblockData['CATALOG_TYPE'])
		{
			case CCatalogSKU::TYPE_CATALOG:
				$title = Loc::getMessage(
					'BX_STEP_OPERATION_CATALOG_TITLE',
					array(
						'#ID#' => $this->iblockData['IBLOCK_ID'],
						'#NAME#' => CIBlock::GetArrayByID($this->iblockData['IBLOCK_ID'], 'NAME')
					)
				);
				break;
			case CCatalogSKU::TYPE_OFFERS:
				$title = Loc::getMessage(
					'BX_STEP_OPERATION_OFFERS_TITLE',
					array(
						'#ID#' => $this->iblockData['PRODUCT_IBLOCK_ID'],
						'#NAME#' => CIBlock::GetArrayByID($this->iblockData['PRODUCT_IBLOCK_ID'], 'NAME')
					)
				);
				break;
			case CCatalogSKU::TYPE_PRODUCT:
			case CCatalogSKU::TYPE_FULL:
				$title = Loc::getMessage(
					'BX_STEP_OPERATION_CATALOG_TITLE',
					array(
						'#ID#' => $this->iblockData['PRODUCT_IBLOCK_ID'],
						'#NAME#' => CIBlock::GetArrayByID($this->iblockData['PRODUCT_IBLOCK_ID'], 'NAME')
					)
				);
				break;
		}

		$messageParams = array(
				'MESSAGE' => $title,
				'PROGRESS_TOTAL' => $this->allCounter,
				'PROGRESS_VALUE' => $this->allOperationCounter,
				'TYPE' => 'PROGRESS',
				'DETAILS' => str_replace(array('#ALL#', '#COUNT#'), array($this->allCounter, $this->allOperationCounter), $this->progressTemplate),
				'HTML' => true
		);
		$message = new CAdminMessage($messageParams);
		return $message->Show();
	}

	public static function getAllCounter($iblockId)
	{
		$iblockId = (int)$iblockId;
		if ($iblockId <= 0)
			return 0;
		$iblockData = CCatalogSKU::GetInfoByIBlock($iblockId);
		if (empty($iblockData))
			return 0;

		return Iblock\ElementTable::getCount(array(
			'=IBLOCK_ID' => $iblockId,
			'=WF_PARENT_ELEMENT_ID' => null
		));
	}

	public static function getIblockList($iblockId)
	{
		$result = array();
		$iblockId = (int)$iblockId;
		if ($iblockId <= 0)
			return $result;

		$iblockData = CCatalogSKU::GetInfoByIBlock($iblockId);
		if (empty($iblockData))
			return $result;

		switch ($iblockData['CATALOG_TYPE'])
		{
			case CCatalogSKU::TYPE_CATALOG:
				$iblockName = CIBlock::GetArrayByID($iblockData['IBLOCK_ID'], 'NAME');
				$result[] = array(
					'ID' => $iblockData['IBLOCK_ID'],
					'COUNT' => static::getAllCounter($iblockData['IBLOCK_ID']),
					'NAME' => $iblockName,
					'TITLE' => Loc::getMessage(
						'BX_STEP_OPERATION_CATALOG_TITLE',
						array(
							'#ID#' => $iblockData['IBLOCK_ID'],
							'#NAME#' => $iblockName
						)
					)
				);
				unset($iblockName);
				break;
			case CCatalogSKU::TYPE_OFFERS:
				$result[] = array(
					'ID' => $iblockData['IBLOCK_ID'],
					'COUNT' => static::getAllCounter($iblockData['IBLOCK_ID']),
					'NAME' => CIBlock::GetArrayByID($iblockData['IBLOCK_ID'], 'NAME'),
					'TITLE' => Loc::getMessage(
						'BX_STEP_OPERATION_OFFERS_TITLE',
						array(
							'#ID#' => $iblockData['PRODUCT_IBLOCK_ID'],
							'#NAME#' => CIBlock::GetArrayByID($iblockData['PRODUCT_IBLOCK_ID'], 'NAME')
						)
					)
				);
				break;
			case CCatalogSKU::TYPE_PRODUCT:
			case CCatalogSKU::TYPE_FULL:
				$iblockName = CIBlock::GetArrayByID($iblockData['PRODUCT_IBLOCK_ID'], 'NAME');
				$result[] = array(
					'ID' => $iblockData['IBLOCK_ID'],
					'COUNT' => static::getAllCounter($iblockData['IBLOCK_ID']),
					'NAME' => CIBlock::GetArrayByID($iblockData['IBLOCK_ID'], 'NAME'),
					'TITLE' => Loc::getMessage(
						'BX_STEP_OPERATION_OFFERS_TITLE',
						array(
							'#ID#' => $iblockData['PRODUCT_IBLOCK_ID'],
							'#NAME#' => $iblockName
						)
					)
				);
				$result[] = array(
					'ID' => $iblockData['PRODUCT_IBLOCK_ID'],
					'COUNT' => static::getAllCounter($iblockData['PRODUCT_IBLOCK_ID']),
					'NAME' => $iblockName,
					'TITLE' => Loc::getMessage(
						'BX_STEP_OPERATION_CATALOG_TITLE',
						array(
							'#ID#' => $iblockData['PRODUCT_IBLOCK_ID'],
							'#NAME#' => $iblockName
						)
					)
				);
				unset($iblockName);
				break;
		}
		unset($iblockData);

		return $result;
	}

	protected function runOperationFullCatalog()
	{
		$checkSets = CBXFeatures::IsFeatureEnabled('CatCompleteSet');
		$emptyList = true;
		$filter = ($this->lastID > 0 ? array('>ID' => $this->lastID) : array());
		$select = array();
		Catalog\Product\Sku::disableUpdateAvailable();
		$productIterator = $this->getProductIterator($filter, $select);
		while ($product = $productIterator->fetch())
		{
			$emptyList = false;
			$product['PRODUCT_ID'] = (int)$product['PRODUCT_ID'];

			$offerState = Catalog\Product\Sku::getOfferState($product['ID'], $this->iblockData['PRODUCT_IBLOCK_ID']);
			switch ($offerState)
			{
				case Catalog\Product\Sku::OFFERS_AVAILABLE:
				case Catalog\Product\Sku::OFFERS_NOT_AVAILABLE:
					$fields = Catalog\Product\Sku::getDefaultParentSettings($offerState);
					break;
				case Catalog\Product\Sku::OFFERS_NOT_EXIST:
					switch ($product['TYPE'])
					{
						case Catalog\ProductTable::TYPE_SKU:
							$fields = Catalog\Product\Sku::getDefaultParentSettings($offerState);
							break;
						case Catalog\ProductTable::TYPE_PRODUCT:
							$fields['AVAILABLE'] = Catalog\ProductTable::calculateAvailable($product);
							break;
						case Catalog\ProductTable::TYPE_SET:
							$fields['AVAILABLE'] = Catalog\ProductTable::calculateAvailable($product);
							break;
						default:
							$fields = Catalog\ProductTable::getDefaultAvailableSettings();
							$fields['TYPE'] = Catalog\ProductTable::TYPE_PRODUCT;
							if ($checkSets && $product['PRODUCT_ID'] > 0)
							{
								if (CCatalogProductSet::isProductHaveSet($product['PRODUCT_ID'], CCatalogProductSet::TYPE_SET))
								{
									$fields['TYPE'] = Catalog\ProductTable::TYPE_SET;
								}
								$fields['BUNDLE'] = (
									CCatalogProductSet::isProductHaveSet($product['PRODUCT_ID'], CCatalogProductSet::TYPE_GROUP)
									? Catalog\ProductTable::STATUS_YES
									: Catalog\ProductTable::STATUS_NO
								);
							}
							break;
					}
					break;
			}
			if (!empty($fields))
			{
				if ($product['PRODUCT_ID'] == 0)
				{
					$fields['ID'] = $product['ID'];
					$result = Catalog\ProductTable::add($fields);
				}
				else
				{
					$result = Catalog\ProductTable::update($product['ID'], $fields);
				}
				if (!$result->isSuccess())
				{

				}
				unset($result);
			}
			unset($fields);

			$this->allOperationCounter++;
			$this->lastID = $product['ID'];
			if ($this->maxExecutionTime > 0 && (time() - $this->startOperationTime > $this->maxExecutionTime))
				break;
		}
		unset($product, $productIterator, $select, $filter);
		Catalog\Product\Sku::enableUpdateAvailable();
		if ($emptyList)
			$this->finishOperation = true;
	}

	protected function runOperationProductIblock()
	{
		$checkSets = CBXFeatures::IsFeatureEnabled('CatCompleteSet');
		$emptyList = true;
		$filter = ($this->lastID > 0 ? array('>ID' => $this->lastID) : array());
		$select = array();
		Catalog\Product\Sku::disableUpdateAvailable();
		$productIterator = $this->getProductIterator($filter, $select);
		while ($product = $productIterator->fetch())
		{
			$emptyList = false;
			$product['PRODUCT_ID'] = (int)$product['PRODUCT_ID'];
			$fields = Catalog\Product\Sku::getDefaultParentSettings(Catalog\Product\Sku::getOfferState(
				$product['ID'],
				$this->iblockData['PRODUCT_IBLOCK_ID']
			));
			if ($checkSets)
			{
				$fields['BUNDLE'] = (
					CCatalogProductSet::isProductHaveSet($product['ID'], CCatalogProductSet::TYPE_GROUP)
					? Catalog\ProductTable::STATUS_YES
					: Catalog\ProductTable::STATUS_NO
				);
			}
			if ($product['PRODUCT_ID'] == 0)
			{
				$fields['ID'] = $product['ID'];
				$result = Catalog\ProductTable::add($fields);
			}
			else
			{
				$result = Catalog\ProductTable::update($product['ID'], $fields);
			}
			if (!$result->isSuccess())
			{

			}
			unset($result);

			$this->allOperationCounter++;
			$this->lastID = $product['ID'];
			if ($this->maxExecutionTime > 0 && (time() - $this->startOperationTime > $this->maxExecutionTime))
				break;
		}
		unset($product, $productIterator, $select, $filter);
		Catalog\Product\Sku::enableUpdateAvailable();
		if ($emptyList)
			$this->finishOperation = true;
	}

	protected function runOperationCatalog()
	{
		$checkSets = CBXFeatures::IsFeatureEnabled('CatCompleteSet');
		$emptyList = true;
		$filter = ($this->lastID > 0 ? array('>ID' => $this->lastID) : array());
		$select = array();
		Catalog\Product\Sku::disableUpdateAvailable();
		$productIterator = $this->getProductIterator($filter, $select);
		while ($product = $productIterator->fetch())
		{
			$emptyList = false;
			$product['PRODUCT_ID'] = (int)$product['PRODUCT_ID'];
			if ($product['PRODUCT_ID'] == 0)
			{
				$fields = Catalog\ProductTable::getDefaultAvailableSettings();
				$fields['TYPE'] = Catalog\ProductTable::TYPE_PRODUCT;
				$fields['ID'] = $product['ID'];
				$result = Catalog\ProductTable::add($fields);
			}
			else
			{
				$fields = array(
					'AVAILABLE' => Catalog\ProductTable::calculateAvailable($product),
					'TYPE' => Catalog\ProductTable::TYPE_PRODUCT,
				);
				if ($checkSets)
				{
					if (CCatalogProductSet::isProductHaveSet($product['PRODUCT_ID'], CCatalogProductSet::TYPE_SET))
					{
						$fields['TYPE'] = Catalog\ProductTable::TYPE_SET;
					}
					$fields['BUNDLE'] = (
						CCatalogProductSet::isProductHaveSet($product['PRODUCT_ID'], CCatalogProductSet::TYPE_GROUP)
						? Catalog\ProductTable::STATUS_YES
						: Catalog\ProductTable::STATUS_NO
					);
				}
				$result = Catalog\ProductTable::update($product['ID'], $fields);
			}
			if (!$result->isSuccess())
			{

			}
			unset($result);

			$this->allOperationCounter++;
			$this->lastID = $product['ID'];
			if ($this->maxExecutionTime > 0 && (time() - $this->startOperationTime > $this->maxExecutionTime))
				break;
		}
		unset($product, $productIterator, $select, $filter);
		Catalog\Product\Sku::enableUpdateAvailable();
		if ($emptyList)
			$this->finishOperation = true;
	}

	protected function runOperationOfferIblock()
	{
		$checkSets = CBXFeatures::IsFeatureEnabled('CatCompleteSet');
		$emptyList = true;
		$filter = ($this->lastID > 0 ? array('>ID' => $this->lastID) : array());
		$select = array();
		Catalog\Product\Sku::disableUpdateAvailable();
		$productIterator = $this->getProductIterator($filter, $select);
		while ($product = $productIterator->fetch())
		{
			$emptyList = false;

			$parent = CIBlockElement::GetPropertyValues(
				$this->iblockData['IBLOCK_ID'],
				array('ID' => $product['ID']),
				false,
				array('ID' => $this->iblockData['SKU_PROPERTY_ID'])
			)->Fetch();
			$parentId = (!empty($parent) ? (int)$parent[$this->iblockData['SKU_PROPERTY_ID']] : 0);
			if (!isset($this->productList[$parentId]))
			{
				$this->productList[$parentId] = false;
				if ($parentId > 0)
				{
					$existParent = Iblock\ElementTable::getList(array(
						'select' => array('ID'),
						'filter' => array('=ID' => $parentId, '=IBLOCK_ID' => $this->iblockData['PRODUCT_IBLOCK_ID'])
					))->fetch();
					if (!empty($existParent))
						$this->productList[$parentId] = true;
					unset($existParent);
				}
			}
			$type = ($this->productList[$parentId] ? Catalog\ProductTable::TYPE_OFFER : Catalog\ProductTable::TYPE_FREE_OFFER);

			$product['PRODUCT_ID'] = (int)$product['PRODUCT_ID'];
			if ($product['PRODUCT_ID'] == 0)
			{
				$fields = Catalog\ProductTable::getDefaultAvailableSettings();
				$fields['TYPE'] = $type;
				$fields['ID'] = $product['ID'];
				$result = Catalog\ProductTable::add($fields);
			}
			else
			{
				$fields = array(
					'AVAILABLE' => Catalog\ProductTable::calculateAvailable($product),
					'TYPE' => $type,
				);
				if ($checkSets)
				{
					$fields['BUNDLE'] = (
						CCatalogProductSet::isProductHaveSet($product['PRODUCT_ID'], CCatalogProductSet::TYPE_GROUP)
						? Catalog\ProductTable::STATUS_YES
						: Catalog\ProductTable::STATUS_NO
					);
				}
				$result = Catalog\ProductTable::update($product['ID'], $fields);
			}
			if (!$result->isSuccess())
			{

			}
			unset($result);

			$this->allOperationCounter++;
			$this->lastID = $product['ID'];
			if ($this->maxExecutionTime > 0 && (time() - $this->startOperationTime > $this->maxExecutionTime))
				break;
		}
		unset($product, $productIterator, $select, $filter);
		Catalog\Product\Sku::enableUpdateAvailable();
		if ($emptyList)
			$this->finishOperation = true;
	}

	protected function getProductIterator($filter, $select)
	{
		$checkSets = CBXFeatures::IsFeatureEnabled('CatCompleteSet');

		$select[] = 'ID';
		$select[] = 'IBLOCK_ID';
		$select[] = 'ACTIVE';
		$select['PRODUCT_ID'] = 'PRODUCT.ID';
		$select['QUANTITY'] = 'PRODUCT.QUANTITY';
		$select['QUANTITY_TRACE'] = 'PRODUCT.QUANTITY_TRACE';
		$select['CAN_BUY_ZERO'] = 'PRODUCT.CAN_BUY_ZERO';
		$select['TYPE'] = 'PRODUCT.TYPE';
		if ($checkSets)
			$select['BUNDLE'] = 'PRODUCT.BUNDLE';

		$filter['=IBLOCK_ID'] = $this->params['IBLOCK_ID'];
		$filter['=WF_PARENT_ELEMENT_ID'] = null;

		$getListParams = array(
			'select' => $select,
			'filter' => $filter,
			'order' => array('ID' => 'ASC'),
			'runtime' => array(
				'PRODUCT' => new Main\Entity\ReferenceField(
					'PRODUCT',
					'Bitrix\Catalog\Product',
					array('=this.ID' => 'ref.ID'),
					array('join_type' => 'LEFT')
				)
			)
		);
		if ($this->maxOperationCounter > 0)
			$getListParams['limit'] = $this->maxOperationCounter;
		return Iblock\ElementTable::getList($getListParams);
	}
}

class CCatalogProductSettings extends CCatalogStepOperations
{
	const SESSION_PREFIX = 'PS';

	protected $offerIblocks = array();
	protected $productIblocks = array();
	protected $parentIblocks = array();

	public function __construct($sessID, $maxExecutionTime, $maxOperationCounter)
	{
		$sessID = (string)$sessID;
		if ($sessID == '')
			$sessID = self::SESSION_PREFIX.time();
		parent::__construct($sessID, $maxExecutionTime, $maxOperationCounter);
	}

	public function saveStep()
	{
		$result = parent::saveStep();
		$result['parentIblocks'] = array_values($this->parentIblocks);
		return $result;
	}

	public function runOperation()
	{
		$this->loadOfferIblocks();
		$emptyList = true;
		$filter = array(
			array(
				'LOGIC' => 'OR',
				'=QUANTITY_TRACE_ORIG' => Catalog\ProductTable::STATUS_DEFAULT,
				'=CAN_BUY_ZERO_ORIG' => Catalog\ProductTable::STATUS_DEFAULT
			)
		);
		if ($this->lastID > 0)
			$filter['>ID'] = $this->lastID;
		Catalog\Product\Sku::disableUpdateAvailable();
		$productsIterator = Catalog\ProductTable::getList(array(
			'select' => array('ID', 'QUANTITY', 'QUANTITY_TRACE', 'CAN_BUY_ZERO', 'TYPE', 'IBLOCK_ID' => 'IBLOCK_ELEMENT.IBLOCK_ID'),
			'filter' => $filter,
			'order' => array('ID' => 'ASC'),
			'limit' => $this->maxOperationCounter
		));
		while ($product = $productsIterator->fetch())
		{
			$emptyList = false;
			$iblockId = (int)$product['IBLOCK_ID'];
			if (isset($this->offerIblocks[$iblockId]))
				$this->parentIblocks[$this->offerIblocks[$iblockId]] = $this->offerIblocks[$iblockId];
			if (isset($this->productIblocks[$iblockId]))
			{
				$this->parentIblocks[$iblockId] = $iblockId;
			}
			else
			{
				$fields = array(
					'AVAILABLE' => Catalog\ProductTable::calculateAvailable($product)
				);
				$result = Catalog\ProductTable::update($product['ID'], $fields);
				if (!$result->isSuccess())
				{

				}
				unset($result);
				unset($fields);
			}
			$this->allOperationCounter++;
			$this->lastID = $product['ID'];
			if ($this->maxExecutionTime > 0 && (time() - $this->startOperationTime > $this->maxExecutionTime))
				break;
		}
		unset($product, $productsIterator);
		Catalog\Product\Sku::enableUpdateAvailable();
		if ($emptyList)
			$this->finishOperation = true;
	}

	public static function getAllCounter()
	{
		return Catalog\ProductTable::getCount(array(
			array(
				'LOGIC' => 'OR',
				'=QUANTITY_TRACE_ORIG' => Catalog\ProductTable::STATUS_DEFAULT,
				'=CAN_BUY_ZERO_ORIG' => Catalog\ProductTable::STATUS_DEFAULT
			)
		));
	}

	protected function loadOfferIblocks()
	{
		$this->offerIblocks = array();
		$this->productIblocks = array();
		$catalogsIterator = Catalog\CatalogIblockTable::getList(array(
			'select' => array('IBLOCK_ID', 'PRODUCT_IBLOCK_ID'),
			'filter' => array('!=PRODUCT_IBLOCK_ID' => 0)
		));
		while ($catalog = $catalogsIterator->fetch())
		{
			$catalog['IBLOCK_ID'] = (int)$catalog['IBLOCK_ID'];
			$catalog['PRODUCT_IBLOCK_ID'] = (int)$catalog['PRODUCT_IBLOCK_ID'];
			$this->offerIblocks[$catalog['IBLOCK_ID']] = $catalog['PRODUCT_IBLOCK_ID'];
			$this->productIblocks[$catalog['PRODUCT_IBLOCK_ID']] = $catalog['PRODUCT_IBLOCK_ID'];
		}
		unset($catalog, $catalogsIterator);
	}
}