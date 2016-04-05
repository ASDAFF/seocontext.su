<?php
namespace Bitrix\Sale\Services\PaySystem\Restrictions;

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Delivery\Services;
use Bitrix\Sale\Internals\DeliveryPaySystemTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\PaymentCollection;
use Bitrix\Sale\Services\Base\Restriction;
use Bitrix\Sale\Services\Base\RestrictionManager;
use Bitrix\Sale\ShipmentCollection;

Loc::loadMessages(__FILE__);

/**
 * Class Delivery
 * @package Bitrix\Sale\Services\PaySystem\Restrictions
 */
class Delivery extends Restriction
{
	public static $easeSort = 200;
	protected static $preparedData = array();

	/**
	 * @return string
	 */
	public static function getClassTitle()
	{
		return Loc::getMessage("SALE_SRV_RSTR_BY_DELIVERY_NAME");
	}

	/**
	 * @return string
	 */
	public static function getClassDescription()
	{
		return Loc::getMessage("SALE_SRV_RSTR_BY_DELIVERY_DESC");
	}

	/**
	 * @param $deliveryIds
	 * @param array $restrictionParams
	 * @param int $paySystemId
	 * @return bool
	 */
	public static function check($deliveryIds, array $restrictionParams, $paySystemId = 0)
	{
		if(intval($paySystemId) <= 0)
			return true;

		if(empty($deliveryIds))
			return true;

		$deliveries = self::getDeliveryByPaySystemsId($paySystemId);

		if(empty($deliveries))
			return true;

		$diff = array_diff($deliveryIds, $deliveries);

		return empty($diff);
	}

	/**
	 * @param Payment $payment
	 * @return array
	 */
	protected static function extractParams(Payment $payment)
	{
		$result = array();
		/** @var PaymentCollection $paymentCollection */
		$paymentCollection = $payment->getCollection();

		/** @var Order $order */
		$order = $paymentCollection->getOrder();

		/** @var ShipmentCollection $shipmentCollection */
		$shipmentCollection = $order->getShipmentCollection();

		/** @var \Bitrix\Sale\Shipment $shipment */
		foreach ($shipmentCollection as $shipment)
		{
			if (!$shipment->isSystem())
			{
				$deliveryId = $shipment->getDeliveryId();
				if ($deliveryId)
					$result[] = $deliveryId;
			}
		}

		return $result;
	}

	/**
	 * @return array
	 */
	protected static function getDeliveryServiceList()
	{
		static $result = null;

		if ($result !== null)
			return $result;

		$serviceList = array();
		$dbRes = Services\Table::getList(array('select' => array('ID', 'NAME', 'PARENT_ID')));
		while ($service = $dbRes->fetch())
			$serviceList[$service['ID']] = $service;

		foreach ($serviceList as $service)
		{
			if ((int)$service['PARENT_ID'] > 0)
				$name = $serviceList[$service['PARENT_ID']]['NAME'].': '.$service['NAME'].' ['.$service['ID'].']';
			else
				$name = $service['NAME'].' ['.$service['ID'].']';

			$result[$service['ID']] = $name;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	public static function getParamsStructure()
	{
		$result =  array(
			"DELIVERY" => array(
				"TYPE" => "ENUM",
				'MULTIPLE' => 'Y',
				"LABEL" => Loc::getMessage("SALE_SRV_RSTR_BY_DELIVERY_PRM_PS"),
				"OPTIONS" => self::getDeliveryServiceList()
			)
		);

		return $result;
	}

	/**
	 * @param int $paySystemId
	 * @return array|\int[]
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	protected static function getDeliveryByPaySystemsId($paySystemId = 0)
	{
		if ($paySystemId == 0)
			return array();

		$result = DeliveryPaySystemTable::getLinks($paySystemId, DeliveryPaySystemTable::ENTITY_TYPE_PAYSYSTEM, self::$preparedData);
		return $result;
	}

	/**
	 * @param array $params
	 * @param int $paySystemId
	 * @return array
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	protected static function prepareParamsForSaving(array $params = array(), $paySystemId = 0)
	{
		if(intval($paySystemId) <= 0)
			return $params;

		if(isset($params["DELIVERY"]) && is_array($params["DELIVERY"]))
		{
			DeliveryPaySystemTable::setLinks(
				$paySystemId,
				DeliveryPaySystemTable::ENTITY_TYPE_PAYSYSTEM,
				$params["DELIVERY"],
				true
			);

			unset($params["DELIVERY"]);
		}

		return $params;
	}

	/**
	 * @param array $fields
	 * @param int $restrictionId
	 * @return \Bitrix\Main\Entity\AddResult|\Bitrix\Main\Entity\UpdateResult
	 */
	public static function save(array $fields, $restrictionId = 0)
	{
		$params = $fields["PARAMS"];
		$fields["PARAMS"] = array();

		$result = parent::save($fields, $restrictionId);

		self::prepareParamsForSaving($params, $fields["SERVICE_ID"]);
		return $result;
	}

	/**
	 * @param array $paramsValues
	 * @param int $paySystemId
	 * @return array
	 */
	public static function prepareParamsValues(array $paramsValues, $paySystemId = 0)
	{
		return array("DELIVERY" => self::getDeliveryByPaySystemsId($paySystemId));
	}

	/**
	 * @param $restrictionId
	 * @param int $paySystemId
	 * @return \Bitrix\Main\Entity\DeleteResult
	 * @throws \Bitrix\Main\ArgumentNullException
	 * @throws \Bitrix\Main\ArgumentOutOfRangeException
	 */
	public static function delete($restrictionId, $paySystemId)
	{
		DeliveryPaySystemTable::setLinks(
			$paySystemId,
			DeliveryPaySystemTable::ENTITY_TYPE_PAYSYSTEM,
			array(),
			true
		);

		return parent::delete($restrictionId);
	}

	/**
	 * @param array $paySystemIds
	 * @return string
	 */
	public static function prepareData(array $paySystemIds)
	{
		if(empty($paySystemIds))
			return;

		self::$preparedData = DeliveryPaySystemTable::prepareData($paySystemIds, DeliveryPaySystemTable::ENTITY_TYPE_PAYSYSTEM);
	}
} 