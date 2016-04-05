<?

CCurrencyLang::disableUseHideZero();


$orderId = (int)$GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"];

/** @var \Bitrix\Sale\Order $order */
$order = \Bitrix\Sale\Order::load($orderId);

/** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
$paymentCollection = $order->getPaymentCollection();

/** @var \Bitrix\Sale\Payment $payment */
foreach ($paymentCollection as $payment)
{
	if (!$payment->isInner())
		break;
}

if ($payment)
{
	$context = \Bitrix\Main\Application::getInstance()->getContext();
	$service = \Bitrix\Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
	return $service->initiatePay($payment, $context->getRequest());
}

CCurrencyLang::enableUseHideZero();

?>