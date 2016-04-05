<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage sale
 * @copyright 2001-2014 Bitrix
 */

namespace Bitrix\Sale;

use Bitrix\Main\Entity;
use Bitrix\Sale\Internals;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BasketPropertyItem
	extends Internals\CollectableEntity
{
	protected static $mapFields = array();

	protected function __construct(array $fields = array())
	{
		parent::__construct($fields);
	}

	/**
	 * @return array
	 */
	public static function getAvailableFields()
	{
		return static::getAllFields();
	}

	/**
	 * @return array
	 */
	public static function getMeaningfulFields()
	{
		return array();
	}

	/**
	 * @return array
	 */
	public static function getAllFields()
	{
		if (empty(static::$mapFields))
		{
			static::$mapFields = parent::getAllFieldsByMap(Internals\BasketPropertyTable::getMap());
		}
		return static::$mapFields;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->getField('ID');
	}

	/**
	 * @param BasketPropertiesCollection $basketPropertiesCollection
	 * @return static
	 */
	public static function create(BasketPropertiesCollection $basketPropertiesCollection)
	{
		$basketPropertyItem = new static();
		$basketPropertyItem->setCollection($basketPropertiesCollection);

		return $basketPropertyItem;
	}

	/**
	 * @return Entity\AddResult|Entity\UpdateResult|Result
	 */
	public function save()
	{
		$result = new Result();
		$id = $this->getId();
		$fields = $this->fields->getValues();

		if ($id > 0)
		{
			$fields = $this->fields->getChangedValues();

			if (!empty($fields) && is_array($fields))
			{
				$r = Internals\BasketPropertyTable::update($id, $fields);
				if (!$r->isSuccess())
				{
					$result->addErrors($r->getErrors());
					return $result;
				}

				if ($resultData = $r->getData())
					$result->setData($resultData);
			}

		}
		else
		{

			$fields['BASKET_ID'] = $this->getCollection()->getBasketId();

			$r = Internals\BasketPropertyTable::add($fields);
			if (!$r->isSuccess())
			{
				$result->addErrors($r->getErrors());
				return $result;
			}

			if ($resultData = $r->getData())
				$result->setData($resultData);

			$id = $r->getId();
			$this->setFieldNoDemand('ID', $id);

		}

		if ($id > 0)
		{
			$result->setId($id);
		}

		return $result;

	}

}