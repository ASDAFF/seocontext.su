<?php
namespace Bitrix\Sale\PaySystem;

use Bitrix\Main\Application;
use Bitrix\Main\IO\File;
use Bitrix\Main\Request;
use Bitrix\Sale\BusinessValue;
use Bitrix\Sale\Payment;
use Bitrix\Sale\Result;

abstract class BaseServiceHandler
{
	protected $handlerType = '';
	protected $service = null;
	protected $extraParams = array();

	/**
	 * @param Payment $payment
	 * @param Request|null $request
	 * @return mixed
	 */
	abstract public function initiatePay(Payment $payment, Request $request = null);

	/**
	 * @param $type
	 * @param Service $service
	 */
	public function __construct($type, Service $service)
	{
		$this->handlerType = $type;
		$this->service = $service;
	}

	/**
	 * @param Payment $payment
	 * @param string $template
	 * @return ServiceResult
	 */
	public function showTemplate(Payment $payment = null, $template = '')
	{
		global $APPLICATION, $USER, $DB;
		$result = new ServiceResult();

		$templatePath = $this->searchTemplate($template);

		if ($templatePath != '' && File::isFileExists($templatePath))
		{
			$params = array_merge($this->getParamsBusValue($payment), $this->getExtraParams());

			return require_once($templatePath);
		}

		return $result;
	}

	/**
	 * @param $template
	 * @return string
	 */
	private function searchTemplate($template)
	{
		$documentRoot = Application::getDocumentRoot();
		$siteTemplate = \CSite::GetCurTemplate();
		$template = Manager::sanitize($template);
		$handlerName = $this->getName();

		$folders = array();

		$folders[] = '/bitrix/templates/'.$siteTemplate.'/payment/'.$handlerName.'/template';
		if ($siteTemplate !== '.default')
			$folders[] = '/bitrix/templates/.default/payment/'.$handlerName.'/template';

		$baseFolders = Manager::getHandlerDirectories();
		$folders[] = $baseFolders[$this->handlerType].$handlerName.'/template';

		foreach ($folders as $folder)
		{
			$templatePath = $documentRoot.$folder.'/'.$template.'.php';

			if (file_exists($templatePath))
				return $templatePath;
		}

		return '';
	}


	/**
	 * @param Payment $payment
	 * @return array
	 */
	public function getParamsBusValue(Payment $payment = null)
	{
		$params = array();
		$codes = $this->getBusinessCodes();

		if ($codes)
		{
			foreach ($codes as $code)
				$params[$code] = $this->getBusinessValue($payment, $code);
		}

		return $params;
	}

	/**
	 * @return mixed|string
	 */
	static protected function getName()
	{
		return Manager::getFolderFromClassName(get_called_class());
	}

	/**
	 * @param Payment $payment
	 * @param $code
	 * @return mixed
	 */
	protected function getBusinessValue(Payment $payment = null, $code)
	{
		return BusinessValue::getValueFromProvider($payment, $code, $this->service->getConsumerName());
	}

	/**
	 * @return array
	 */
	public function getDescription()
	{
		$data = array();
		$documentRoot = Application::getDocumentRoot();
		$dirs = Manager::getHandlerDirectories();
		$handlerDir = $dirs[$this->handlerType];
		$file = $documentRoot.$handlerDir.$this->getName().'/.description.php';

		if (File::isFileExists($file))
			require $file;

		return $data;
	}

	/**
	 * @return array
	 */
	protected function getBusinessCodes()
	{
		static $data = array();

		if (!$data)
		{
			$result = $this->getDescription();
			if ($result['CODES'])
				$data = array_keys($result['CODES']);
		}

		return $data;
	}

	/**
	 * @return array
	 */
	private function getExtraParams()
	{
		return $this->extraParams;
	}

	/**
	 * @param array $values
	 */
	public function setExtraParams(array $values)
	{
		$this->extraParams = $values;
	}

	/**
	 * @return array
	 */
	public abstract function getCurrencyList();

	/**
	 * @param Payment $payment
	 * @return Result
	 */
	public function creditNoDemand(Payment $payment)
	{
		return new Result();
	}

	/**
	 * @param Payment $payment
	 * @return Result
	 */
	public function debitNoDemand(Payment $payment)
	{
		return new Result();
	}

	/**
	 * @return bool
	 */
	public function isAffordPdf()
	{
		return false;
	}

	/**
	 * @return array
	 */
	public static function getHandlerModeList()
	{
		return array();
	}
}