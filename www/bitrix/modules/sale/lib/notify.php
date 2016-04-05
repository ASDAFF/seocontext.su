<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage sale
 * @copyright 2001-2012 Bitrix
 */

namespace Bitrix\Sale;

use Bitrix\Main;
use Bitrix\Sale\Compatible;
use Bitrix\Sale\Internals\Fields;

Main\Localization\Loc::loadMessages(__FILE__);

class Notify
{

	const EVENT_ORDER_STATUS_SEND_EMAIL = "OnOrderStatusSendEmail";
	const EVENT_ORDER_STATUS_EMAIL = "OnSaleStatusEMail";

	const EVENT_ON_ORDER_NEW_SEND_EMAIL = "OnOrderNewSendEmail";
	const EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME = "SALE_NEW_ORDER";

	const EVENT_ON_SHIPMENT_DELIVER_SEND_EMAIL = "OnOrderDeliverSendEmail";
	const EVENT_SHIPMENT_DELIVER_SEND_EMAIL_EVENT_NAME = "SALE_ORDER_DELIVERY";

	const EVENT_ON_ORDER_PAID_SEND_EMAIL = "OnOrderPaySendEmail";

	const EVENT_ON_ORDER_CANCEL_SEND_EMAIL = "OnOrderCancelSendEmail";

	const EVENT_ORDER_PAID_SEND_EMAIL_EVENT_NAME = "SALE_ORDER_PAID";
	const EVENT_ORDER_CANCEL_SEND_EMAIL_EVENT_NAME = "SALE_ORDER_CANCEL";
	const EVENT_ORDER_TRACKING_NUMBER_SEND_EMAIL_EVENT_NAME = "SALE_ORDER_TRACKING_NUMBER";
	const EVENT_ORDER_STATUS_CHANGED_SEND_EMAIL_EVENT_NAME = "SALE_STATUS_CHANGED";
	const EVENT_SHIPMENT_TRACKING_NUMBER_SEND_EMAIL_EVENT_NAME = "SALE_ORDER_TRACKING_NUMBER";

	const EVENT_MOBILE_PUSH_ORDER_CREATED = "ORDER_CREATED";
	const EVENT_MOBILE_PUSH_ORDER_STATUS_CHANGE = "ORDER_STATUS_CHANGED";
	const EVENT_MOBILE_PUSH_ORDER_CANCELED = "ORDER_CANCELED";
	const EVENT_MOBILE_PUSH_ORDER_PAID = "ORDER_PAYED";
	const EVENT_MOBILE_PUSH_SHIPMENT_ALLOW_DELIVERY = "ORDER_DELIVERY_ALLOWED";

	private static $cacheUserData = array();

	private static $sentEventList = array();

	private static $disableNotify = false;

	protected function __construct()
	{

	}

	/**
	 * @param Order $order
	 *
	 * @return Result
	 * @throws Main\ArgumentException
	 * @throws Main\ArgumentNullException
	 */
	public static function sendOrderNew(Order $order)
	{
		$result = new Result();

		if (static::isNotifyDisabled())
		{
			return $result;
		}

		if (static::hasSentEvent($order->getId(), static::EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME))
		{
			return $result;
		}

		if (!$order->isNew())
		{
			return $result;
		}

		$basketList = '';
		/** @var Basket $basket */
		if ($basket = $order->getBasket())
		{
			if ($basketTextList = $basket->getListOfFormatText())
			{
				foreach ($basketTextList as $basketItemCode => $basketItemData)
				{
					$basketList .= $basketItemData."\n";
				}
			}
		}

		$fields = Array(
			"ORDER_ID" => $order->getField("ACCOUNT_NUMBER"),
			"ORDER_DATE" => $order->getDateInsert()->toString(),
			"ORDER_USER" => static::getUserName($order),
			"PRICE" => SaleFormatCurrency($order->getPrice(), $order->getCurrency()),
			"BCC" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
			"EMAIL" => static::getUserEmail($order),
			"ORDER_LIST" => $basketList,
			"SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
			"DELIVERY_PRICE" => $order->getDeliveryPrice(),
		);

		$eventName = static::EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME;
		$send = true;

		foreach(GetModuleEvents("sale", static::EVENT_ON_ORDER_NEW_SEND_EMAIL, true) as $oldEvent)
		{
			if (ExecuteModuleEventEx($oldEvent, array($order->getId(), &$eventName, &$fields)) === false)
			{
				$send = false;
			}
		}

		if($send)
		{
			$event = new \CEvent;
			$event->Send($eventName, $order->getField('LID'), $fields, "N");
		}

		static::addSentEvent($order->getId(), static::EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME);

		\CSaleMobileOrderPush::send(static::EVENT_MOBILE_PUSH_ORDER_CREATED, array("ORDER" => static::getOrderFields($order)));

		return $result;
	}

	/**
	 * @param Order $order
	 *
	 * @return Result
	 * @throws Main\ArgumentNullException
	 */
	public static function sendOrderCancel(Order $order)
	{
		$result = new Result();

		if (static::isNotifyDisabled())
		{
			return $result;
		}

		if (static::hasSentEvent($order->getId(), static::EVENT_ORDER_CANCEL_SEND_EMAIL_EVENT_NAME))
		{
			return $result;
		}

		if (!$order->isCanceled())
		{
			return $result;
		}

		$fields = Array(
			"ORDER_ID" => $order->getField("ACCOUNT_NUMBER"),
			"ORDER_DATE" => $order->getDateInsert()->toString(),
			"EMAIL" => static::getUserEmail($order),
			"ORDER_CANCEL_DESCRIPTION" => $order->getField('REASON_CANCELED'),
			"SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER["SERVER_NAME"])
		);

		$eventName = static::EVENT_ORDER_CANCEL_SEND_EMAIL_EVENT_NAME;
		$send = true;

		foreach(GetModuleEvents("sale", static::EVENT_ON_ORDER_CANCEL_SEND_EMAIL, true) as $oldEvent)
		{
			if (ExecuteModuleEventEx($oldEvent, array($order->getId(), &$eventName, &$fields)) === false)
			{
				$send = false;
			}
		}

		if($send)
		{
			$event = new \CEvent;
			$event->Send($eventName, $order->getField('LID'), $fields, "N");
		}

		\CSaleMobileOrderPush::send(static::EVENT_MOBILE_PUSH_ORDER_CANCELED, array("ORDER" => static::getOrderFields($order)));

		static::addSentEvent($order->getId(), static::EVENT_ORDER_CANCEL_SEND_EMAIL_EVENT_NAME);

		return $result;
	}

	/**
	 * @param Order $order
	 *
	 * @return Result
	 * @throws Main\ArgumentNullException
	 */
	public static function sendOrderPaid(Order $order)
	{
		$result = new Result();

		if (static::isNotifyDisabled())
		{
			return $result;
		}

		if (static::hasSentEvent($order->getId(), static::EVENT_ORDER_PAID_SEND_EMAIL_EVENT_NAME))
		{
			return $result;
		}

		if (!$order->isPaid())
		{
			return $result;
		}

		$fields = Array(
			"ORDER_ID" => $order->getField("ACCOUNT_NUMBER"),
			"ORDER_DATE" => $order->getDateInsert()->toString(),
			"EMAIL" => static::getUserName($order),
			"SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER["SERVER_NAME"])
		);

		$eventName = static::EVENT_ORDER_PAID_SEND_EMAIL_EVENT_NAME;
		$send = true;

		foreach(GetModuleEvents("sale", static::EVENT_ON_ORDER_PAID_SEND_EMAIL, true) as $oldEvent)
		{
			if (ExecuteModuleEventEx($oldEvent, array($order->getId(), &$eventName, &$fields)) === false)
			{
				$send = false;
			}
		}

		if($send)
		{
			$event = new \CEvent;
			$event->Send($eventName, $order->getField('LID'), $fields, "N");
		}

		\CSaleMobileOrderPush::send(static::EVENT_MOBILE_PUSH_ORDER_PAID, array("ORDER" => static::getOrderFields($order)));

		static::addSentEvent($order->getId(), static::EVENT_ORDER_PAID_SEND_EMAIL_EVENT_NAME);

		return $result;
	}

	/**
	 * @param Order $order
	 *
	 * @return Result
	 * @throws Main\ArgumentNullException
	 */
	public static function sendOrderStatusChange(Order $order)
	{
		$result = new Result();

		if (static::isNotifyDisabled())
		{
			return $result;
		}

		$statusEventName = "SALE_STATUS_CHANGED_".$order->getField("STATUS_ID");

		if (static::hasSentEvent($order->getId(), $statusEventName))
		{
			return $result;
		}

		/** @var Fields $fields */
		$fields = $order->getFields();
		$originalValues = $fields->getOriginalValues();

		if (array_key_exists('STATUS_ID', $originalValues) && $originalValues['STATUS_ID'] == $order->getField("STATUS_ID"))
		{
			return $result;
		}

		static $cacheSiteData = array();

		if (!isset($cacheSiteData[$order->getSiteId()]))
		{
			$siteRes = \CSite::GetByID($order->getSiteId());
			$siteData = $siteRes->Fetch();
		}
		else
		{
			$siteData = $cacheSiteData[$order->getSiteId()];
		}

		if (($statusData = \CSaleStatus::GetByID($order->getField("STATUS_ID"), $siteData['LANGUAGE_ID'])) && $statusData['NOTIFY'] == "Y")
		{
			$fields = Array(
				"ORDER_ID" => $order->getField("ACCOUNT_NUMBER"),
				"ORDER_DATE" => $order->getField("DATE_INSERT")->toString(),
				"ORDER_STATUS" => $statusData["NAME"],
				"EMAIL" => static::getUserEmail($order),
				"ORDER_DESCRIPTION" => $statusData["DESCRIPTION"],
				"TEXT" => "",
				"SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER["SERVER_NAME"])
			);

			foreach(GetModuleEvents("sale", static::EVENT_ORDER_STATUS_EMAIL, true) as $oldEvent)
			{
				$fields["TEXT"] = ExecuteModuleEventEx($oldEvent, array($order->getId(), $statusData["ID"]));
			}

			$eventName = $statusEventName;

			$isSend = true;
			foreach(GetModuleEvents("sale", static::EVENT_ORDER_STATUS_SEND_EMAIL, true) as $oldEvent)
			{
				if (ExecuteModuleEventEx($oldEvent, Array($order->getId(), &$eventName, &$fields, $order->getField("STATUS_ID")))===false)
				{
					$isSend = false;
				}
			}

			if($isSend)
			{
				$b = '';
				$o = '';
				$eventMessage = new \CEventMessage;
				$eventMessageRes = $eventMessage->GetList(
					$b,
					$o,
					array(
						"EVENT_NAME" => $eventName,
						"SITE_ID" => $order->getSiteId(),
						'ACTIVE' => 'Y'
					)
				);
				if (!($eventMessageData = $eventMessageRes->Fetch()))
				{
					$eventName = static::EVENT_ORDER_STATUS_CHANGED_SEND_EMAIL_EVENT_NAME;
				}

				unset($o, $b);
				$event = new \CEvent;
				$event->Send($eventName, $order->getSiteId(), $fields, "N");
			}
		}

		\CSaleMobileOrderPush::send(static::EVENT_MOBILE_PUSH_ORDER_STATUS_CHANGE, array("ORDER" => static::getOrderFields($order)));

		static::addSentEvent($order->getId(), $statusEventName);

		return $result;
	}

	/**
	 * @param Shipment $shipment
	 *
	 * @return bool
	 * @throws Main\ArgumentNullException
	 */
	public static function sendShipmentTrackingNumberChange(Shipment $shipment)
	{
		$result = new Result();

		if (static::isNotifyDisabled())
		{
			return $result;
		}

		if (static::hasSentEvent('s'.$shipment->getId(), static::EVENT_SHIPMENT_TRACKING_NUMBER_SEND_EMAIL_EVENT_NAME))
		{
			return $result;
		}

		/** @var ShipmentCollection $shipmentCollection */
		if (!$shipmentCollection = $shipment->getCollection())
		{
			$result->addError(new ResultError(Main\Localization\Loc::getMessage("SALE_NOTIFY_SHIPMENT_COLLECTION_NOT_FOUND")));
			return $result;
		}

		/** @var Order $order */
		if (!$order = $shipmentCollection->getOrder())
		{
			$result->addError(new ResultError(Main\Localization\Loc::getMessage("SALE_NOTIFY_ORDER_NOT_FOUND")));
			return $result;
		}

		/** @var Fields $fields */
		$fields = $order->getFields();
		$originalValues = $fields->getOriginalValues();

		if (array_key_exists('ACCOUNT_NUMBER', $originalValues) && $originalValues['ACCOUNT_NUMBER'] == $order->getField("ACCOUNT_NUMBER"))
		{
			return $result;
		}

		$accountNumber = $order->getField("ACCOUNT_NUMBER");

		$emailFields = Array(
			"ORDER_ID" => $accountNumber,
			"ORDER_DATE" => $order->getDateInsert()->toString(),
			"ORDER_USER" => static::getUserName($order),
			"ORDER_TRACKING_NUMBER" => $shipment->getField('TRACKING_NUMBER'),
			"BCC" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER['SERVER_NAME']),
			"EMAIL" => static::getUserEmail($order),
			"SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER['SERVER_NAME'])
		);

		$event = new \CEvent;
		$event->send(static::EVENT_SHIPMENT_TRACKING_NUMBER_SEND_EMAIL_EVENT_NAME, $order->getField("LID"), $emailFields, "N");

		static::addSentEvent('s'.$shipment->getId(), static::EVENT_SHIPMENT_TRACKING_NUMBER_SEND_EMAIL_EVENT_NAME);

		return $result;
	}

	/**
	 * @param Shipment $shipment
	 *
	 * @return bool
	 * @throws Main\ArgumentNullException
	 */
	public static function sendShipmentAllowDelivery(Shipment $shipment)
	{
		$result = new Result();

		if (static::isNotifyDisabled())
		{
			return $result;
		}

		if (static::hasSentEvent('s'.$shipment->getId(), static::EVENT_SHIPMENT_DELIVER_SEND_EMAIL_EVENT_NAME))
		{
			return $result;
		}

		/** @var ShipmentCollection $shipmentCollection */
		if (!$shipmentCollection = $shipment->getCollection())
		{
			$result->addError(new ResultError(Main\Localization\Loc::getMessage("SALE_NOTIFY_SHIPMENT_COLLECTION_NOT_FOUND")));
			return $result;
		}

		/** @var Order $order */
		if (!$order = $shipmentCollection->getOrder())
		{
			$result->addError(new ResultError(Main\Localization\Loc::getMessage("SALE_NOTIFY_ORDER_NOT_FOUND")));
			return $result;
		}

		if (!$order->isAllowDelivery())
		{
			return $result;
		}


		$fields = Array(
			"ORDER_ID" => $order->getField("ACCOUNT_NUMBER"),
			"ORDER_DATE" => $order->getDateInsert()->toString(),
			"EMAIL" => static::getUserEmail($order),
			"SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
		);

		$eventName = static::EVENT_SHIPMENT_DELIVER_SEND_EMAIL_EVENT_NAME;
		$send = true;

		foreach(GetModuleEvents("sale", static::EVENT_ON_SHIPMENT_DELIVER_SEND_EMAIL, true) as $oldEvent)
		{
			if (ExecuteModuleEventEx($oldEvent, array($order->getId(), &$eventName, &$fields)) === false)
			{
				$send = false;
			}
		}

		if($send)
		{
			$event = new \CEvent;
			$event->Send($eventName, $order->getField('LID'), $fields, "N");
		}

		\CSaleMobileOrderPush::send(static::EVENT_MOBILE_PUSH_SHIPMENT_ALLOW_DELIVERY, array("ORDER" => static::getOrderFields($order)));

		static::addSentEvent('s'.$shipment->getId(), static::EVENT_SHIPMENT_DELIVER_SEND_EMAIL_EVENT_NAME);

		return $result;
	}

	/**
	 * @param Order $order
	 *
	 * @return null|string
	 * @throws Main\ArgumentException
	 */
	protected static function getUserEmail(Order $order)
	{
		$userEmail = "";

		if (!empty(static::$cacheUserData[$order->getUserId()]))
		{
			$userData = static::$cacheUserData[$order->getUserId()];
			if (!empty($userData['EMAIL']))
			{
				$userEmail = $userData['EMAIL'];
			}
		}


		if (empty($userEmail))
		{
			/** @var PropertyValueCollection $propertyCollection */
			if ($propertyCollection = $order->getPropertyCollection())
			{
				if ($propUserEmail = $propertyCollection->getUserEmail())
				{
					$userEmail = $propUserEmail->getValue();
					static::$cacheUserData[$order->getUserId()]['EMAIL'] = $userEmail;
				}
			}
		}

		if (empty($userEmail))
		{
			$userRes = Main\UserTable::getList(array(
												   'select' => array('ID', 'LOGIN', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'EMAIL'),
												   'filter' => array('=ID' => $order->getUserId()),
											   ));
			if ($userData = $userRes->fetch())
			{
				static::$cacheUserData[$order->getUserId()] = $userData;
				$userEmail = $userData['EMAIL'];
			}
		}

		return $userEmail;
	}

	/**
	 * @param Order $order
	 *
	 * @return mixed|null|string
	 * @throws Main\ArgumentException
	 */
	protected static function getUserName(Order $order)
	{
		$userName = "";

		if (!empty(static::$cacheUserData[$order->getUserId()]))
		{
			$userData = static::$cacheUserData[$order->getUserId()];
			if (!empty($userData['USER_NAME']))
			{
				$userName = $userData['USER_NAME'];
			}
		}


		if (empty($userName))
		{
			/** @var PropertyValueCollection $propertyCollection */
			if ($propertyCollection = $order->getPropertyCollection())
			{
				if ($propPayerName = $propertyCollection->getPayerName())
				{
					$userName = $propPayerName->getValue();
					static::$cacheUserData[$order->getUserId()]['PAYER_NAME'] = $userName;
				}
			}
		}

		if (empty($userName))
		{
			$userRes = Main\UserTable::getList(array(
												   'select' => array('ID', 'LOGIN', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'EMAIL'),
												   'filter' => array('=ID' => $order->getUserId()),
											   ));
			if ($userData = $userRes->fetch())
			{
				$userData['PAYER_NAME'] = \CUser::FormatName(\CSite::GetNameFormat(null, $order->getSiteId()), $userData, true);
				static::$cacheUserData[$order->getUserId()] = $userData;
				$userName = $userData['PAYER_NAME'];
			}
		}

		return $userName;
	}

	/**
	 * @param Order $order
	 *
	 * @return Result
	 */
	protected static function getAllFieldsFromOrder(Order $order)
	{
		$result = new Result();

		$paymentSystemId = false;
		$deliveryId = false;

		/** @var PaymentCollection $paymentCollection */
		if ($paymentCollection = $order->getPaymentCollection())
		{
			/** @var Payment $payment */
			if ($payment = $paymentCollection->rewind())
			{
				$paymentSystemId = $payment->getPaymentSystemId();
			}
		}

		/** @var ShipmentCollection $shipe */
		if ($shipmentCollection = $order->getShipmentCollection())
		{
			/** @var Shipment $shipment */
			foreach ($shipmentCollection as $shipment)
			{
				if ($shipment->getDeliveryId() > 0)
				{
					$deliveryId = $shipment->getDeliveryId();
					break;
				}
			}
		}

		$fields = array(
			"SITE_ID" => $order->getSiteId(),
			"LID" => $order->getSiteId(),
			"PERSON_TYPE_ID" => $order->getPersonTypeId(),
			"PRICE" => $order->getPrice(),
			"CURRENCY" => $order->getCurrency(),
			"USER_ID" => $order->getUserId(),
			"PAY_SYSTEM_ID" => $paymentSystemId,
			"PRICE_DELIVERY" => $order->getDeliveryPrice(),
			"DELIVERY_ID" => $deliveryId,
			"DISCOUNT_VALUE" => $order->getDiscountPrice(),
			"TAX_VALUE" => $order->getTaxValue(),
			"TRACKING_NUMBER" => $order->getField('TRACKING_NUMBER'),
			"PAYED" => $order->getField('PAYED'),
			"CANCELED" => $order->getField('CANCELED'),
			"STATUS_ID" => $order->getField('STATUS_ID'),
			"RESERVED" => $order->getField('RESERVED'),
		);

		$orderFields = static::getOrderFields($order);
		if (is_array($orderFields))
		{
			$orderFields = $fields + $orderFields;
			$orderFields = static::convertDateFieldsToOldFormat($orderFields);
		}

		$result->setData(array(
							 'FIELDS' => $fields,
							 'ORDER_FIELDS' => $orderFields,
						 ));

		return $result;
	}

	/**
	 * @param Order $order
	 *
	 * @return array
	 * @throws Main\ObjectNotFoundException
	 */
	protected static function getOrderFields(Order $order)
	{
		$fields = $order->getFieldValues();
		$fields = array_merge($fields,
							  array(
								  'ORDER_ID' => $order->getId(),
								  'ORDER_WEIGHT' => 0,
								  'BASKET_ITEMS' => array(),
								  'ORDER_PROP' => array(),
								  'DISCOUNT_LIST' => array(),
								  'TAX_LIST' => array(),
								  'VAT_RATE' => $order->getVatRate(),
								  'VAT_SUM' => $order->getVatSum(),
							  ));

		/** @var Basket $basket */
		if ($basket = $order->getBasket())
		{
			/** @var BasketItem $basketItem */
			foreach ($basket as $basketItem)
			{
				$fields['BASKET_ITEMS'][] = static::getBasketItemFields($basketItem);
			}
		}

		/** @var PropertyValueCollection $basket */
		if ($propertyCollection = $order->getPropertyCollection())
		{
			/** @var PropertyValue $property */
			foreach ($propertyCollection as $property)
			{
				$fields['ORDER_PROP'][$property->getPropertyId()] = $property->getValue();
			}
		}


		if ($propProfileName = $propertyCollection->getProfileName())
		{
			$fields['PROFILE_NAME'] = $propProfileName->getValue();
		}

		if ($propPayerName = $propertyCollection->getPayerName())
		{
			$fields['PAYER_NAME'] = $propPayerName->getValue();
		}

		if ($propUserEmail = $propertyCollection->getUserEmail())
		{
			$fields['USER_EMAIL'] = $propUserEmail->getValue();
		}

		if ($propDeliveryLocationZip = $propertyCollection->getDeliveryLocationZip())
		{
			$fields['DELIVERY_LOCATION_ZIP'] = $propDeliveryLocationZip->getValue();
		}

		if ($propDeliveryLocation = $propertyCollection->getDeliveryLocation())
		{
			$fields['DELIVERY_LOCATION'] = $propDeliveryLocation->getValue();
		}

		if ($propTaxLocation = $propertyCollection->getTaxLocation())
		{
			$fields['TAX_LOCATION'] = $propTaxLocation->getValue();
		}

		/** @var ShipmentCollection $shipmentCollection */
		if ($shipmentCollection = $order->getShipmentCollection())
		{
			$fields['ORDER_WEIGHT'] = $shipmentCollection->getWeight();
		}

		$fields['DISCOUNT_LIST'] = Compatible\DiscountCompatibility::getOldDiscountResult();

		/** @var Tax $tax */
		if ($tax = $order->getTax())
		{
			$fields['TAX_LIST'] = $tax->getTaxList();
		}

		return $fields;
	}

	/**
	 * @param BasketItem $basketItem
	 *
	 * @return array
	 * @throws Main\ObjectNotFoundException
	 */
	protected static function getBasketItemFields(BasketItem $basketItem)
	{
		$fields = $basketItem->getFieldValues();

		/** @var Basket $basket */
		if (!$basket = $basketItem->getCollection())
		{
			throw new Main\ObjectNotFoundException('Entity "Basket" not found');
		}

		if (empty($fields['LID']))
		{
			$fields['LID'] = $basket->getSiteId();
		}

		if (empty($fields['LID']))
		{
			if ($order = $basket->getOrder())
			{
				$fields['LID'] = $order->getField('LID');
			}
		}

		if (empty($fields['FUSER_ID']))
		{
			$fields['FUSER_ID'] = $basket->getFUserId(true);
		}


		/** @var BasketPropertiesCollection $propertyCollection */
		if ($propertyCollection = $basketItem->getPropertyCollection())
		{
			$fields['PROPS'] = $propertyCollection->getPropertyValues();
		}

		return $fields;
	}



	/**
	 * Convert an array of dates from the object to a string
	 *
	 * @param array $fields   The array of dates
	 * @return array
	 */
	public static function convertDateFieldsToOldFormat(array $fields)
	{
		$resultList = array();
		foreach ($fields as $k => $value)
		{
			$valueString = static::convertDateFieldToOldFormat($value);
			$resultList[$k] = $valueString;
		}

		return $resultList;
	}

	/**
	 * Convert date object to a string
	 *
	 * @param string $value    Field value
	 * @return string
	 */
	protected static function convertDateFieldToOldFormat($value)
	{
		$setValue = $value;

		if ($value instanceof Main\Type\Date)
		{
			$setValue = $value->toString();
		}

		return $setValue;
	}

	/**
	 * @param $code
	 * @param $event
	 *
	 * @return bool
	 */
	private static function hasSentEvent($code, $event)
	{
		if (!array_key_exists($code, static::$sentEventList))
		{
			return false;
		}

		if (in_array($event, static::$sentEventList[$code]))
		{
			return true;
		}

		return false;
	}

	/**
	 * @param $code
	 * @param $event
	 *
	 * @return bool
	 */
	private static function addSentEvent($code, $event)
	{
		if (!static::hasSentEvent($code, $event))
		{
			static::$sentEventList[$code][] = $event;
			return true;
		}

		return false;
	}

	/**
	 * @param Order $order
	 * @param $eventName
	 */
	public static function callNotify(Order $order, $eventName)
	{
		if (($eventNotifyMap = EventActions::getEventNotifyMap()) && !empty($eventNotifyMap) && is_array($eventNotifyMap))
		{
			if (array_key_exists($eventName, $eventNotifyMap) && !empty($eventNotifyMap[$eventName]))
			{
				call_user_func_array($eventNotifyMap[$eventName], array($order));
			}
		}
	}

	/**
	 * @param $value
	 */
	public static function setNotifyDisable($value)
	{
		static::$disableNotify = ($value === true);
	}

	/**
	 * @return bool
	 */
	public static function isNotifyDisabled()
	{
		return static::$disableNotify;
	}
}