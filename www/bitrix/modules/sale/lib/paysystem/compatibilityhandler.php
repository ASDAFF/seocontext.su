<?php

namespace Bitrix\Sale\PaySystem;

use Bitrix\Main\Application;
use Bitrix\Main\Request;
use Bitrix\Sale\Payment;
use Bitrix\Main\IO;

class CompatibilityHandler extends ServiceHandler
{
	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function getPaymentIdFromRequest(Request $request) {}

	/**
	 * @param Payment $payment
	 * @return mixed
	 */
	protected function isTestMode(Payment $payment = null) {}

	/**
	 * @return mixed
	 */
	protected function getUrlList() {}

	/**
	 * @param Payment $payment
	 * @param Request|null $request
	 * @return string
	 */
	public function initiatePay(Payment $payment, Request $request = null)
	{
		$this->getParamsBusValue($payment);
		$this->includeFile('payment.php');
		return '';
	}

	/**
	 * @param Payment $payment
	 * @return mixed
	 */
	public function getParamsBusValue(Payment $payment)
	{
		/** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
		$paymentCollection = $payment->getCollection();

		$order = $paymentCollection->getOrder();

		\CSalePaySystemAction::InitParamArrays($order->getFieldValues(), $order->getId(), '', array(), $payment->getFieldValues());

		return $GLOBALS['SALE_INPUT_PARAMS'];
	}

	/**
	 * @param Payment $payment
	 * @param Request $request
	 * @return string
	 */
	public function processRequest(Payment $payment, Request $request)
	{
		$this->getParamsBusValue($payment);
		$this->includeFile('result_rec.php');
		die();
	}

	/**
	 * @param $file
	 */
	private function includeFile($file)
	{
		global $APPLICATION, $USER, $DB;
		$documentRoot = Application::getDocumentRoot();

		$path = $documentRoot.$this->service->getField('ACTION_FILE').'/'.$file;
		if (IO\File::isFileExists($path))
			require_once $path;
	}

	/**
	 * @param Request $request
	 * @return mixed
	 */
	public function getEntityIds(Request $request) {}

	/**
	 * @return array
	 */
	public function getCurrencyList()
	{
		return array();
	}

	public function getPrice(Payment $payment)
	{
		$paySystemId = $payment->getPaymentSystemId();
		$psData = Manager::getById($paySystemId);
		$psData['PSA_ACTION_FILE'] = $psData['ACTION_FILE'];
		$psData['PSA_TARIF'] = $psData['TARIF'];

		/** @var \Bitrix\Sale\PaymentCollection $collection */
		$collection = $payment->getCollection();

		/** @var \Bitrix\sale\Order $order */
		$order = $collection->getOrder();

		/** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
		$shipmentCollection = $order->getShipmentCollection();

		$shipment = null;

		/** @var \Bitrix\Sale\Shipment $item */
		foreach ($shipmentCollection as $item)
		{
			if (!$item->isSystem())
			{
				$shipment = $item;
				break;
			}
		}

		/** @var \Bitrix\Sale\PropertyValueCollection $propertyCollection */
		$propertyCollection = $order->getPropertyCollection();

		/** @var \Bitrix\Sale\PropertyValue $deliveryLocation */
		$deliveryLocation = $propertyCollection->getDeliveryLocation();

		if ($shipment)
			return \CSalePaySystemsHelper::getPSPrice($psData, $payment->getSum(), $shipment->getPrice(), $deliveryLocation->getValue());

		return 0;
	}

	/**
	 * @return bool
	 */
	public function isPayableCompatibility()
	{
		$documentRoot = Application::getDocumentRoot();
		$actionFile = $this->service->getField('ACTION_FILE');

		return IO\File::isFileExists($documentRoot.$actionFile.'/tarif.php');
	}

	/**
	 * @return bool
	 */
	public function isCheckableCompatibility()
	{
		$documentRoot = Application::getDocumentRoot();
		$actionFile = $this->service->getField('ACTION_FILE');

		return IO\File::isFileExists($documentRoot.$actionFile.'/result.php');
	}

	/**
	 * @param Payment|null $payment
	 */
	public function check(Payment $payment)
	{
		if ($this->isCheckableCompatibility())
		{
			/** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
			$paymentCollection = $payment->getCollection();

			/** @var \Bitrix\Sale\Order $order */
			$order = $paymentCollection->getOrder();

			\CSalePaySystemAction::InitParamArrays($order->getFieldValues(), $order->getId(), '', array(), $payment->getFieldValues());

			$this->includeFile('result.php');
		}
	}
}