<?php

namespace Sale\Handlers\PaySystem;

use Bitrix\Main\Request;
use Bitrix\Sale;
use Bitrix\Sale\PaySystem;

class BillHandler extends PaySystem\BaseServiceHandler
{
	public function initiatePay(Sale\Payment $payment, Request $request = null)
	{
		/** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
		$paymentCollection = $payment->getCollection();

		/** @var \Bitrix\Sale\Order $order */
		$order = $paymentCollection->getOrder();

		$sumPaid = $paymentCollection->getPaidSum();
		$template = 'template';

//		if ($sumPaid + $payment->getSum() < $order->getPrice())
//			$template .= '_prepay';

		if ($request && $request->get('pdf') !== null)
			$template .= '_pdf';

		$accountNumber = (IsModuleInstalled('intranet')) ? $order->getField('ACCOUNT_NUMBER') : $payment->getField('ACCOUNT_NUMBER');
		$this->setExtraParams(array('ACCOUNT_NUMBER' => $accountNumber));

		return $this->showTemplate($payment, $template);
	}

	/**
	 * @return array
	 */
	public function getCurrencyList()
	{
		return array('RUB');
	}

	/**
	 * @return bool
	 */
	public function isAffordPdf()
	{
		return true;
	}


}