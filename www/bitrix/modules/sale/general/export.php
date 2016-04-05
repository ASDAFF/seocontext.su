<?

use Bitrix\Sale\BusinessValue;
use Bitrix\Sale\BusinessValueConsumer1C;

IncludeModuleLangFile(__FILE__);

$GLOBALS["SALE_EXPORT"] = Array();

//class CAllSaleExport
class CSaleExport
{
	const DEFAULT_VERSION = 2.05;
	const PARTIAL_VERSION = 2.1;

	const LAST_ORDER_PREFIX = 'LAST_ORDER_ID';

	static $versionSchema;
	static $crmMode;
	static $currency;
	static $measures;
	static $orderTax;

	static $arResultStat = array();
	static $xmlVersion = "1.0";
	static $xmlEncoding = "windows-1251";
	static $xmlRootName = "<?xml version=\"#version#\" encoding=\"#encoding#\"?>";

	static $typeDocument = "";
	static $deliveryAdr = "";

	static $siteNameByOrder = "";

 	function setXmlVersion($version) {
        self::$xmlVersion = $version;
    }
    function setXmlEncoding($encoding) {
        self::$xmlEncoding = $encoding;
    }

    function getXmlRootName()
    {
        return str_replace(array("#version#","#encoding#"),array(self::$xmlVersion,self::$xmlEncoding),self::$xmlRootName);
    }

	function getCmrXmlRootNameParams()
	{
		return GetMessage("SALE_EXPORT_SHEM_VERSION")."=\"".self::getVersionSchema()."\" ".GetMessage("SALE_EXPORT_SHEM_DATE_CREATE")."=\"".date("Y-m-d")."T".date("G:i:s")."\" ".GetMessage("SALE_EXPORT_DATE_FORMAT")."=\"".GetMessage("SALE_EXPORT_DATE_FORMAT_DF")."=yyyy-MM-dd; ".GetMessage("SALE_EXPORT_DATE_FORMAT_DLF")."=DT\" ".GetMessage("SALE_EXPORT_DATE_FORMAT_DATETIME")."=\"".GetMessage("SALE_EXPORT_DATE_FORMAT_DF")."=".GetMessage("SALE_EXPORT_DATE_FORMAT_TIME")."; ".GetMessage("SALE_EXPORT_DATE_FORMAT_DLF")."=T\" ".GetMessage("SALE_EXPORT_DEL_DT")."=\"T\" ".GetMessage("SALE_EXPORT_FORM_SUMM")."=\"".GetMessage("SALE_EXPORT_FORM_CC")."=18; ".GetMessage("SALE_EXPORT_FORM_CDC")."=2; ".GetMessage("SALE_EXPORT_FORM_CRD")."=.\" ".GetMessage("SALE_EXPORT_FORM_QUANT")."=\"".GetMessage("SALE_EXPORT_FORM_CC")."=18; ".GetMessage("SALE_EXPORT_FORM_CDC")."=2; ".GetMessage("SALE_EXPORT_FORM_CRD")."=.\"";
	}

	function getDeliveryAddress()
	{
		return self::$deliveryAdr;
	}
	function setDeliveryAddress($deliveryAdr)
	{
		self::$deliveryAdr = $deliveryAdr;
	}
	function setVersionSchema($versionSchema=false)
	{
		self::$versionSchema = $versionSchema;
	}
	function setCrmMode($crmMode)
	{
		self::$crmMode = $crmMode;
	}
	function setCurrencySchema($currency)
	{
		self::$currency = $currency;
	}
	function getVersionSchema()
	{
		return doubleval(str_replace(" ", "", str_replace(",", ".", (!empty(self::$versionSchema) ? self::$versionSchema : self::DEFAULT_VERSION))));
	}
	function isExportFromCRM($arOptions)
	{
		return (isset($arOptions["EXPORT_FROM_CRM"]) && $arOptions["EXPORT_FROM_CRM"] === "Y");
	}
	function getEndTime($time_limit)
	{	//This is an optimization. We assume than no step can take more than one year.
		if($time_limit > 0)
			$end_time = time() + $time_limit;
		else
			$end_time = time() + 365*24*3600; // One year

		return $end_time;
	}
	function checkTimeIsOver($time_limit,$end_time)
	{
		if(IntVal($time_limit) > 0 && time() > $end_time )
			return true;
		else
			return false;
	}
	function getOrderPrefix()
	{
		return CSaleExport::LAST_ORDER_PREFIX;
	}
	function getAccountNumberShopPrefix()
	{
		static $accountNumberShopPrefix = null;
		if($accountNumberShopPrefix === null)
		    $accountNumberShopPrefix = COption::GetOptionString("sale", "1C_SALE_ACCOUNT_NUMBER_SHOP_PREFIX", "");

		return $accountNumberShopPrefix;
	}
	function getSalePaySystem()
	{
		$paySystems = array();
		$dbPaySystem = CSalePaySystem::GetList(Array("ID" => "ASC"), Array("ACTIVE" => "Y"), false, false, Array("ID", "NAME", "ACTIVE"));
		while($arPaySystem = $dbPaySystem -> Fetch())
			$paySystems[$arPaySystem["ID"]] = $arPaySystem["NAME"];

		return $paySystems;
	}
	function getSaleDelivery()
	{
		$delivery = array();
		$dbDeliveryList = \Bitrix\Sale\Delivery\Services\Table::GetList();
		while($service = $dbDeliveryList->fetch())
		{
		    $deliveryObj = Bitrix\Sale\Delivery\Services\Manager::createObject($service);
            $delivery[$deliveryObj->GetId()] = ($deliveryObj->isProfile() ? $deliveryObj->getNameWithParent():$deliveryObj->getName());
		}
		return $delivery;
	}
	function getCatalogStore()
	{
		$arStore = array();
		if(CModule::IncludeModule("catalog"))
		{
			$dbList = CCatalogStore::GetList(
				array("SORT" => "DESC", "ID" => "ASC"),
				array("ACTIVE" => "Y", "ISSUING_CENTER" => "Y"),
				false,
				false,
				array("ID", "SORT", "TITLE", "ADDRESS", "DESCRIPTION", "PHONE", "EMAIL", "XML_ID")
			);
			while ($arStoreTmp = $dbList->Fetch())
			{
				if(strlen($arStoreTmp["XML_ID"]) <= 0)
					$arStoreTmp["XML_ID"] = $arStoreTmp["ID"];
				$arStore[$arStoreTmp["ID"]] = $arStoreTmp;
			}
		}
		return $arStore;
	}
	function getOrderDeliveryItem($arOrder, $bVat, $vatRate, $vatSum)
    {

        ?>
        <<?=GetMessage("SALE_EXPORT_ITEM")?>>
            <<?=GetMessage("SALE_EXPORT_ID")?>>ORDER_DELIVERY</<?=GetMessage("SALE_EXPORT_ID")?>>
            <<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_ORDER_DELIVERY")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
            <?
            if(self::getVersionSchema() >= self::DEFAULT_VERSION)
            {
                ?>
                <<?=GetMessage("SALE_EXPORT_UNIT")?>>
                <<?=GetMessage("SALE_EXPORT_CODE")?>>796</<?=GetMessage("SALE_EXPORT_CODE")?>>
                <<?=GetMessage("SALE_EXPORT_FULL_NAME_UNIT")?>><?=htmlspecialcharsbx(self::$measures[796])?></<?=GetMessage("SALE_EXPORT_FULL_NAME_UNIT")?>>
                </<?=GetMessage("SALE_EXPORT_UNIT")?>>
                <<?=GetMessage("SALE_EXPORT_KOEF")?>>1</<?=GetMessage("SALE_EXPORT_KOEF")?>>
            <?
            }
            else
            {
                ?>
                <<?=GetMessage("SALE_EXPORT_BASE_UNIT")?> <?=GetMessage("SALE_EXPORT_CODE")?>="796" <?=GetMessage("SALE_EXPORT_FULL_NAME_UNIT")?>="<?=GetMessage("SALE_EXPORT_SHTUKA")?>" <?=GetMessage("SALE_EXPORT_INTERNATIONAL_ABR")?>="<?=GetMessage("SALE_EXPORT_RCE")?>"><?=GetMessage("SALE_EXPORT_SHT")?></<?=GetMessage("SALE_EXPORT_BASE_UNIT")?>>
                <?
            }
            ?>
            <<?=GetMessage("SALE_EXPORT_PRICE_PER_ITEM")?>><?=(strlen($arOrder["PRICE_DELIVERY"])>0? $arOrder["PRICE_DELIVERY"]:"0.0000")?></<?=GetMessage("SALE_EXPORT_PRICE_PER_ITEM")?>>
            <<?=GetMessage("SALE_EXPORT_QUANTITY")?>>1</<?=GetMessage("SALE_EXPORT_QUANTITY")?>>
            <<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=(strlen($arOrder["PRICE_DELIVERY"])>0? $arOrder["PRICE_DELIVERY"]:"0.0000")?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
            <<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
                <<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
                    <<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_TYPE_NOMENKLATURA")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
                    <<?=GetMessage("SALE_EXPORT_VALUE")?>><?=GetMessage("SALE_EXPORT_SERVICE")?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
                </<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
                <<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
                    <<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_TYPE_OF_NOMENKLATURA")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
                    <<?=GetMessage("SALE_EXPORT_VALUE")?>><?=GetMessage("SALE_EXPORT_SERVICE")?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
                </<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
            </<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
            <?if($bVat)
            {
                $deliveryTax = roundEx((($arOrder["PRICE_DELIVERY"] / ($vatRate+1)) * $vatRate), 2);
                if(self::$orderTax > $vatSum && self::$orderTax == roundEx($vatSum + $deliveryTax, 2))
                {
                    ?>
                    <<?=GetMessage("SALE_EXPORT_TAX_RATES")?>>
                        <<?=GetMessage("SALE_EXPORT_TAX_RATE")?>>
                            <<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_VAT")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
                            <<?=GetMessage("SALE_EXPORT_RATE")?>><?=$vatRate * 100?></<?=GetMessage("SALE_EXPORT_RATE")?>>
                        </<?=GetMessage("SALE_EXPORT_TAX_RATE")?>>
                    </<?=GetMessage("SALE_EXPORT_TAX_RATES")?>>
                    <<?=GetMessage("SALE_EXPORT_TAXES")?>>
                        <<?=GetMessage("SALE_EXPORT_TAX")?>>
                            <<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_VAT")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
                            <<?=GetMessage("SALE_EXPORT_IN_PRICE")?>>true</<?=GetMessage("SALE_EXPORT_IN_PRICE")?>>
                            <<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$deliveryTax?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
                        </<?=GetMessage("SALE_EXPORT_TAX")?>>
                    </<?=GetMessage("SALE_EXPORT_TAXES")?>>
                    <?
                }
            }?>
        </<?=GetMessage("SALE_EXPORT_ITEM")?>>
        <?
    }

	function getCatalogMeasure()
	{
		$arMeasures = array();
		if(CModule::IncludeModule("catalog"))
		{
			$dbList = CCatalogMeasure::getList(array(), array(), false, false, array("CODE", "MEASURE_TITLE"));
			while($arList = $dbList->Fetch())
			{
				$arMeasures[$arList["CODE"]] = $arList["MEASURE_TITLE"];
			}
		}
		if(empty($arMeasures))
			$arMeasures[796] = GetMessage("SALE_EXPORT_SHTUKA");

		return $arMeasures;
	}
	function setCatalogMeasure($arMeasures)
	{
		self::$measures = $arMeasures;
	}
	function setOrderSumTaxMoney($orderTax)
	{
		self::$orderTax = $orderTax;

	}
	function getSaleExport()
	{
		$arAgent = array();
		$dbExport = CSaleExport::GetList();
		while($arExport = $dbExport->Fetch())
		{
			$arAgent[$arExport["PERSON_TYPE_ID"]] = unserialize($arExport["VARS"]);
		}
		return $arAgent;
	}
	function getSaleProperties(Bitrix\Sale\Order $order, $arOrder, $agentParams, $bExportFromCrm, $bCrmModuleIncluded, $paySystems, $delivery)
	{
		$providersInstance = self::getProvidersInstanceByOrder($order);

        $personTypeId = $order->getPersonTypeId();

		$personTypes = BusinessValue::getPersonTypes();

		if (! $personType = $personTypes[$personTypeId])
		{
			self::logError($order->getId(), 'Undefined DOMAIN for person type id "'.$personTypeId.'"');
			return;
		}

		$systemCodes1C = array_flip(self::$systemCodes[$personType['DOMAIN']]);

		$arProp = Array();
		$arProp["ORDER"] = $arOrder;

		if (IntVal($arOrder["USER_ID"]) > 0)
		{
			$dbUser = CUser::GetByID($arOrder["USER_ID"]);
			if ($arUser = $dbUser->Fetch())
				$arProp["USER"] = $arUser;
		}

		if ($bExportFromCrm)
		{
			$arProp["CRM"] = array();
			$companyID = isset($arOrder["UF_COMPANY_ID"]) ? intval($arOrder["UF_COMPANY_ID"]) : 0;
			$contactID = isset($arOrder["UF_CONTACT_ID"]) ? intval($arOrder["UF_CONTACT_ID"]) : 0;
			if ($companyID > 0)
			{
				$arProp["CRM"]["CLIENT_ID"] = "CRMCO".$companyID;
			}
			else
			{
				$arProp["CRM"]["CLIENT_ID"] = "CRMC".$contactID;
			}

			$clientInfo = array(
				"LOGIN" => "",
				"NAME" => "",
				"LAST_NAME" => "",
				"SECOND_NAME" => ""
			);

			if ($bCrmModuleIncluded)
			{
				if ($companyID > 0)
				{
					$arCompanyFilter = array('=ID' => $companyID);
					$dbCompany = CCrmCompany::GetListEx(
						array(), $arCompanyFilter, false, array("nTopCount" => 1),
						array("TITLE")
					);
					$arCompany = $dbCompany->Fetch();
					unset($dbCompany, $arCompanyFilter);
					if (is_array($arCompany))
					{
						if (isset($arCompany["TITLE"]))
							$clientInfo["NAME"] = $arCompany["TITLE"];
					}
					unset($arCompany);
				}
				else if ($contactID > 0)
				{
					$arContactFilter = array('=ID' => $contactID);
					$dbContact = CCrmContact::GetListEx(
						array(), $arContactFilter, false, array("nTopCount" => 1),
						array("NAME", "LAST_NAME", "SECOND_NAME")
					);
					$arContact = $dbContact->Fetch();
					unset($dbContact, $arContactFilter);
					if (is_array($arContact))
					{
						if (isset($arContact["NAME"]))
							$clientInfo["NAME"] = $arContact["NAME"];
						if (isset($arContact["LAST_NAME"]))
							$clientInfo["LAST_NAME"] = $arContact["LAST_NAME"];
						if (isset($arContact["SECOND_NAME"]))
							$clientInfo["SECOND_NAME"] = $arContact["SECOND_NAME"];
					}
					unset($arContact);
				}
			}

			$arProp["CRM"]["CLIENT"] = $clientInfo;
			unset($clientInfo);
		}
		if(IntVal($arOrder["PAY_SYSTEM_ID"]) > 0)
			$arProp["ORDER"]["PAY_SYSTEM_NAME"] = $paySystems[$arOrder["PAY_SYSTEM_ID"]];
		if(strlen($arOrder["DELIVERY_ID"]) > 0)
			$arProp["ORDER"]["DELIVERY_NAME"] = $delivery[$arOrder["DELIVERY_ID"]];

		$dbOrderPropVals = CSaleOrderPropsValue::GetList(
				array(),
				array("ORDER_ID" => $arOrder["ID"]),
				false,
				false,
				array("ID", "CODE", "VALUE", "ORDER_PROPS_ID", "PROP_TYPE")
			);
		$locationStreetPropertyValue = '';
		while ($arOrderPropVals = $dbOrderPropVals->Fetch())
		{
			if ($arOrderPropVals["PROP_TYPE"] == "CHECKBOX")
			{
				if ($arOrderPropVals["VALUE"] == "Y")
					$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = "true";
				else
					$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = "false";
			}
			elseif ($arOrderPropVals["PROP_TYPE"] == "TEXT" || $arOrderPropVals["PROP_TYPE"] == "TEXTAREA")
			{
				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arOrderPropVals["VALUE"];
			}
			elseif ($arOrderPropVals["PROP_TYPE"] == "SELECT" || $arOrderPropVals["PROP_TYPE"] == "RADIO")
			{
				$arVal = CSaleOrderPropsVariant::GetByValue($arOrderPropVals["ORDER_PROPS_ID"], $arOrderPropVals["VALUE"]);
				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arVal["NAME"];
			}
			elseif ($arOrderPropVals["PROP_TYPE"] == "MULTISELECT")
			{
				$curVal = explode(",", $arOrderPropVals["VALUE"]);
				foreach($curVal as $vm)
				{
					$arVal = CSaleOrderPropsVariant::GetByValue($arOrderPropVals["ORDER_PROPS_ID"], $vm);
					$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] .=  ", ".$arVal["NAME"];
				}
				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = substr($arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]], 2);
			}
			elseif ($arOrderPropVals["PROP_TYPE"] == "LOCATION")
			{
				$arVal = CSaleLocation::GetByID($arOrderPropVals["VALUE"], LANGUAGE_ID);

				if(CSaleLocation::isLocationProEnabled())
				{
					if(intval($arVal['ID']))
					{
						try
						{
							$res = \Bitrix\Sale\Location\LocationTable::getPathToNode($arVal['ID'], array('select' => array('LNAME' => 'NAME.NAME', 'TYPE_ID'), 'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID)));
							$types = \Bitrix\Sale\Location\Admin\TypeHelper::getTypeCodeIdMapCached();
							$path = array();
							while($item = $res->fetch())
							{
								// copy street to STREET property
								if($types['ID2CODE'][$item['TYPE_ID']] == 'STREET')
									$locationStreetPropertyValue = $item['LNAME'];
								$path[] = $item['LNAME'];
							}

							$locationString = implode(' - ', $path);
						}
						catch(\Bitrix\Main\SystemException $e)
						{
							$locationString = '';
						}
					}
					else
						$locationString = '';
				}
				else
					$locationString =  ($arVal["COUNTRY_NAME"].((strlen($arVal["COUNTRY_NAME"])<=0 || strlen($arVal["REGION_NAME"])<=0) ? "" : " - ").$arVal["REGION_NAME"].((strlen($arVal["COUNTRY_NAME"])<=0 || strlen($arVal["CITY_NAME"])<=0) ? "" : " - ").$arVal["CITY_NAME"]);

				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $locationString;

				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]."_CITY"] = $arVal["CITY_NAME"];
				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]."_COUNTRY"] = $arVal["COUNTRY_NAME"];
				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]."_REGION"] = $arVal["REGION_NAME"];
			}
			else
			{
				$arProp["PROPERTY"][$arOrderPropVals["ORDER_PROPS_ID"]] = $arOrderPropVals["VALUE"];

			}
		}

		foreach($agentParams as $k => $v)
		{
			if(strpos($k, "REKV_") !== false)
			{//params
				if(!is_array($v))
				{
					$agent["REKV"][$k] = $v;
				}
				else
				{
					if(strlen($v["TYPE"])<=0)
						$agent["REKV"][$k] = $v["VALUE"];//code
					else
					{
					    switch($v["TYPE"])
					    {
					        case 'CRM':
					            $agent["REKV"][$k] = $arProp[$v["TYPE"]][$v["VALUE"]];//value
					            break;
					        default:
                                if (! ($codeKey = $systemCodes1C[$k])
                                    && substr($k, 0, 5) === 'REKV_'
                                    && ($codeIndex = substr($k, 5)) !== ''
                                    && ($codeKey = BusinessValueConsumer1C::getRekvCodeKey($order->getPersonTypeId(), $codeIndex))
                                    && ($providerInstance = $providersInstance[$order->getId()][$v["TYPE"]])
                                    && is_set($providerInstance))
                                {
                                    $agent["REKV"][$k] = Bitrix\Sale\BusinessValue::getValueFromProvider($providerInstance, $codeKey, BusinessValueConsumer1C::CONSUMER_KEY);
                                }
					    }
					}
				}
			}
			else
			{
				if(!is_array($v))
				{
					$agent[$k] = $v;
				}
				else
				{
					if(strlen($v["TYPE"])<=0)
						$agent[$k] = $v["VALUE"];
					else
					{
                        switch($v["TYPE"])
                        {
                            case 'CRM':
                                $agent[$k] = $arProp[$v["TYPE"]][$v["VALUE"]];
                                break;
                            default:
                                if (($codeKey = $systemCodes1C[$k])
                                    && ($providerInstance = $providersInstance[$order->getId()][$v["TYPE"]])
                                    && is_set($providerInstance))
                                {
                                    $agent[$k] = Bitrix\Sale\BusinessValue::getValueFromProvider($providerInstance, $codeKey, BusinessValueConsumer1C::CONSUMER_KEY);
                                }

                        }
					}

					if($k == 'STREET' && strlen($locationStreetPropertyValue))
						$agent[$k] = $locationStreetPropertyValue.(strlen($agent[$k]) ? ', ' : '').$agent[$k];
				}
			}
		}

		return array('agent'=>$agent,'arProp'=>$arProp);
	}

	function getSite()
	{
		$arCharSets = array();
		$dbSitesList = CSite::GetList(($b=""), ($o=""));
		while ($arSite = $dbSitesList->Fetch())
			$arCharSets[$arSite["ID"]] = $arSite["CHARSET"];

		return $arCharSets;
	}
	function setSiteNameByOrder($arOrder)
	{
		$dbSite = CSite::GetByID($arOrder["LID"]);
		$arSite = $dbSite->Fetch();
		self::$siteNameByOrder = $arSite["NAME"];
	}
	function getPayment($arOrder)
	{
		$result = array();
		$PaymentParam['select'] =
			array(
				"ID",
				"PAID",
				"DATE_BILL",
				"ORDER_ID",
				"CURRENCY",
				"SUM",
				"COMMENTS",
				"DATE_PAID",
				"PAY_SYSTEM_ID",
				"PAY_SYSTEM_NAME",
				"IS_RETURN",
				"PAY_RETURN_COMMENT",
				"PAY_VOUCHER_NUM",
				"PAY_VOUCHER_DATE",

			);


		$PaymentParam['filter']['ORDER_ID'] = $arOrder['ID'];
		$PaymentParam['filter']['!=EXTERNAL_PAYMENT'] = 'F';
		if(self::getVersionSchema() < self::PARTIAL_VERSION)
		{
			$PaymentParam['filter']['!PAY_SYSTEM_ID'] = \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId();
			$PaymentParam['limit'] = 1;
		}

		$resPayment = \Bitrix\Sale\Internals\PaymentTable::getList($PaymentParam);

		while($arPayment = $resPayment->fetch())
		{
			foreach($arPayment as $field=>$value)
			{
			    if(self::isFormattedDateFields('Payment', $field))
			    {
			        $arPayment[$field] = self::getFormatDate($value);
			    }
			}

			$result[] = $arPayment;
		}
		return $result;
	}
	function getShipment($arOrder)
	{
		$result = array();
		$ShipmentParams['select'] =
			array(
				"ID",
				"DATE_INSERT",
				"CURRENCY",
				"PRICE_DELIVERY",
				"DATE_INSERT",
				"COMMENTS",
				"DATE_ALLOW_DELIVERY",
				"STATUS_ID",
				"DEDUCTED",
				"DATE_DEDUCTED",
				"REASON_UNDO_DEDUCTED",
				"RESERVED",
				"DELIVERY_ID",
				"DELIVERY_NAME",
				"CANCELED",
				"DATE_CANCELED",
				"REASON_CANCELED",
				"REASON_MARKED",
				"ORDER_ID",
			);

		$ShipmentParams['filter']['ORDER_ID'] = $arOrder['ID'];
		$ShipmentParams['filter']['=SYSTEM'] = 'N';
		$ShipmentParams['filter']['>PRICE_DELIVERY'] = 0;
		if(self::getVersionSchema() < self::PARTIAL_VERSION)
		{
			$ShipmentParams['limit'] = 1;
		}
		$resShipment = \Bitrix\Sale\Internals\ShipmentTable::getList($ShipmentParams);
		while($arShipment = $resShipment->fetch())
		{
			foreach($arShipment as $field=>$value)
			{
			    if(self::isFormattedDateFields('Shipment', $field))
			    {
			        $arShipment[$field] = self::getFormatDate($value);
			    }
			}
			$result[] = $arShipment;
		}

		return $result;
	}

	function ExportOrders2Xml($arFilter = Array(), $nTopCount = 0, $currency = "", $crmMode = false, $time_limit = 0, $version = false, $arOptions = Array())
	{
		self::setVersionSchema($version);
		self::setCrmMode($crmMode);
		self::setCurrencySchema($currency);

		$count = false;
		if(IntVal($nTopCount) > 0)
			$count = Array("nTopCount" => $nTopCount);

		$end_time = self::getEndTime($time_limit);

		if(IntVal($time_limit) > 0)
		{
			$lastOrderPrefix = self::getOrderPrefix();

			if(self::$crmMode)
			{
				$lastOrderPrefix = md5(serialize($arFilter));
				if(!empty($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) && IntVal($nTopCount) > 0)
					$count["nTopCount"] = $count["nTopCount"]+count($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]);
			}
			else
			{
				if(IntVal($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) > 0)
				{
					$arFilter["<ID"] = $_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix];
				}
			}
		}

		self::$arResultStat = array(
			"ORDERS" => 0,
			"CONTACTS" => 0,
			"COMPANIES" => 0,
		);

		$bExportFromCrm = self::isExportFromCRM($arOptions);
		$paySystems = self::getSalePaySystem();
		$delivery = self::getSaleDelivery();

		$arStore = self::getCatalogStore();
		$arMeasures = self::getCatalogMeasure();
		self::setCatalogMeasure($arMeasures);
		$arAgent = self::getSaleExport();

		if (self::$crmMode)
		{
			self::setXmlEncoding("UTF-8");
			$arCharSets = self::getSite();
		}

		echo self::getXmlRootName();?>

<<?=GetMessage("SALE_EXPORT_COM_INFORMATION")?> <?=self::getCmrXmlRootNameParams()?>><?

		$arOrder = array("ID" => "DESC");

		if (self::$crmMode)
			$arOrder = array("DATE_UPDATE" => "ASC");

		$arSelect = array(
			"ID", "LID", "PERSON_TYPE_ID", "PAYED", "DATE_PAYED", "EMP_PAYED_ID", "CANCELED", "DATE_CANCELED",
			"EMP_CANCELED_ID", "REASON_CANCELED", "STATUS_ID", "DATE_STATUS", "PAY_VOUCHER_NUM", "PAY_VOUCHER_DATE", "EMP_STATUS_ID",
			"PRICE_DELIVERY", "ALLOW_DELIVERY", "DATE_ALLOW_DELIVERY", "EMP_ALLOW_DELIVERY_ID", "PRICE", "CURRENCY", "DISCOUNT_VALUE",
			"SUM_PAID", "USER_ID", "PAY_SYSTEM_ID", "DELIVERY_ID", "DATE_INSERT", "DATE_INSERT_FORMAT", "DATE_UPDATE", "USER_DESCRIPTION",
			"ADDITIONAL_INFO",
			"COMMENTS", "TAX_VALUE", "STAT_GID", "RECURRING_ID", "ACCOUNT_NUMBER", "SUM_PAID", "DELIVERY_DOC_DATE", "DELIVERY_DOC_NUM", "TRACKING_NUMBER", "STORE_ID",
			"ID_1C", "VERSION",
			"USER.XML_ID"
		);

		$bCrmModuleIncluded = false;
		if ($bExportFromCrm)
		{
			$arSelect[] = "UF_COMPANY_ID";
			$arSelect[] = "UF_CONTACT_ID";
			if (IsModuleInstalled("crm") && CModule::IncludeModule("crm"))
				$bCrmModuleIncluded = true;
		}

        $dbOrderList = \Bitrix\Sale\Internals\OrderTable::getList(array(
            'select' => $arSelect,
            'filter' => $arFilter,
            'order'  => $arOrder,
            'limit'  => $count["nTopCount"]
        ));
		//$dbOrderList = CSaleOrder::GetList($arOrder, $arFilter, false, $count, $arSelect);

		while($arOrder = $dbOrderList->Fetch())
		{
		    $order = \Bitrix\Sale\Order::load($arOrder['ID']);
		    /* @var $order \Bitrix\Sale\Order*/

		    $arOrder['DATE_STATUS'] = $arOrder['DATE_STATUS']->toString();
		    $arOrder['DATE_INSERT'] = $arOrder['DATE_INSERT']->toString();
		    $arOrder['DATE_UPDATE'] = $arOrder['DATE_UPDATE']->toString();

			foreach($arOrder as $field=>$value)
			{
			    if(self::isFormattedDateFields('Order', $field))
			    {
			        $arOrder[$field] = self::getFormatDate($value);
			    }
			}

			if (self::$crmMode)
			{
				if(self::getVersionSchema() >= self::DEFAULT_VERSION && is_array($_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) && in_array($arOrder["ID"], $_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix]) && empty($arFilter["ID"]))
					continue;
				ob_start();
			}

			self::$arResultStat["ORDERS"]++;

			$agentParams = $arAgent[$arOrder["PERSON_TYPE_ID"]];

			$saleProperties = self::getSaleProperties($order, $arOrder, $agentParams, $bExportFromCrm, $bCrmModuleIncluded, $paySystems, $delivery);

			$arProp = $saleProperties['arProp'];
			$agent = $saleProperties['agent'];

			$arPayment = self::getPayment($arOrder);
			$arShipment = self::getShipment($arOrder);

			self::setDeliveryAddress('');
			self::setSiteNameByOrder($arOrder);

			$arOrderTax = CSaleExport::getOrderTax($arOrder["ID"]);
			$xmlResult['OrderTax'] = self::getXMLOrderTax($arOrderTax);
			self::setOrderSumTaxMoney(self::getOrderSumTaxMoney($arOrderTax));

			$xmlResult['Contragents'] = self::getXmlContragents($arOrder, $arProp, $agent, $bExportFromCrm ? array("EXPORT_FROM_CRM" => "Y") : array());
			$xmlResult['OrderDiscount'] = self::getXmlOrderDiscount($arOrder);
			$xmlResult['SaleStore']	= self::getXmlSaleStore($arOrder,$arStore);
			// self::getXmlSaleStoreBasket($arOrder,$arStore);
			$basketItems = self::getXmlBasketItems('Order', $arOrder, array('ORDER_ID'=>$arOrder['ID']), array(), $arShipment);
			$xmlResult['BasketItems'] = $basketItems['outputXML'];
			$xmlResult['SaleProperties'] = self::getXmlSaleProperties($arOrder, $arShipment, $arPayment, $paySystems, $delivery, $agent, $agentParams, $bExportFromCrm);

			self::OutputXmlDocument('Order', $xmlResult, $arOrder);


			if(self::getVersionSchema() >= self::PARTIAL_VERSION)
			{
				self::OutputXmlDocumentsByType('Payment',$xmlResult,$arOrder, $arPayment);
				self::OutputXmlDocumentsByType('Shipment',$xmlResult,$arOrder, $arShipment);
				self::OutputXmlDocumentRemove('Shipment',$arOrder);
			}

			if (self::$crmMode)
			{
				$c = ob_get_clean();
				$c = CharsetConverter::ConvertCharset($c, $arCharSets[$arOrder["LID"]], "utf-8");
				echo $c;
				$_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix][] = $arOrder["ID"];
			}
			else
			{
				$_SESSION["BX_CML2_EXPORT"][$lastOrderPrefix] = $arOrder["ID"];
			}

			if(self::checkTimeIsOver($time_limit,$end_time))
			{
				break;
			}
		}
		?>

	</<?=GetMessage("SALE_EXPORT_COM_INFORMATION")?>><?

		return self::$arResultStat;
	}

	function UnZip($file_name, $last_zip_entry = "", $interval = 0)
	{
		global $APPLICATION;
		$start_time = time();

		$io = CBXVirtualIo::GetInstance();

		//Function and securioty checks
		if(!function_exists("zip_open"))
			return false;
		$dir_name = substr($file_name, 0, strrpos($file_name, "/")+1);
		if(strlen($dir_name) <= strlen($_SERVER["DOCUMENT_ROOT"]))
			return false;

		$hZip = zip_open($file_name);
		if(!$hZip)
			return false;
		//Skip from last step
		if($last_zip_entry)
		{
			while($entry = zip_read($hZip))
				if(zip_entry_name($entry) == $last_zip_entry)
					break;
		}

		$io = CBXVirtualIo::GetInstance();
		//Continue unzip
		while($entry = zip_read($hZip))
		{
			$entry_name = zip_entry_name($entry);
			//Check for directory
			zip_entry_open($hZip, $entry);
			if(zip_entry_filesize($entry))
			{

				$file_name = trim(str_replace("\\", "/", trim($entry_name)), "/");
				$file_name = $APPLICATION->ConvertCharset($file_name, "cp866", LANG_CHARSET);

				$bBadFile = HasScriptExtension($file_name)
					|| IsFileUnsafe($file_name)
					|| !$io->ValidatePathString("/".$file_name)
				;

				if(!$bBadFile)
				{
					$file_name =  $io->GetPhysicalName($dir_name.rel2abs("/", $file_name));
					CheckDirPath($file_name);
					$fout = fopen($file_name, "wb");
					if(!$fout)
						return false;
					while($data = zip_entry_read($entry, 102400))
					{
						$data_len = function_exists('mb_strlen') ? mb_strlen($data, 'latin1') : strlen($data);
						$result = fwrite($fout, $data);
						if($result !== $data_len)
							return false;
					}
				}
			}
			zip_entry_close($entry);

			//Jump to next step
			if($interval > 0 && (time()-$start_time) > ($interval))
			{
				zip_close($hZip);
				return $entry_name;
			}
		}
		zip_close($hZip);
		return true;
	}
	function getOrderTax($orderId)
	{
		$arResult = array();
		if($orderId>0)
		{
			$dbOrderTax = CSaleOrderTax::GetList(
				array(),
				array("ORDER_ID" => $orderId),
				false,
				false,
				array("ID", "TAX_NAME", "VALUE", "VALUE_MONEY", "CODE", "IS_IN_PRICE")
			);
			$i=-1;
			$orderTax = 0;
			while ($arOrderTax = $dbOrderTax->Fetch())
			{
				$arResult[] = $arOrderTax;
			}
		}

		return $arResult;
	}

	function getOrderSumTaxMoney($arOrderTaxAll)
	{
		$orderTax = 0;
		if(is_array($arOrderTaxAll) && count($arOrderTaxAll)>0)
		{
			foreach ($arOrderTaxAll as $arOrderTax )
			{
				$arOrderTax["VALUE_MONEY"] = roundEx($arOrderTax["VALUE_MONEY"], 2);
				$orderTax += $arOrderTax["VALUE_MONEY"];
			}
		}
		return $orderTax;
	}

	function getXmlOrderTax($arOrderTaxAll)
	{
		$strResult = "";
		if(is_array($arOrderTaxAll) && count($arOrderTaxAll)>0)
		{
			$orderTax = 0;
			$strResult .= "<".GetMessage("SALE_EXPORT_TAXES").">";
			foreach ($arOrderTaxAll as $arOrderTax )
			{
				$arOrderTax["VALUE_MONEY"] = roundEx($arOrderTax["VALUE_MONEY"], 2);
				$orderTax += $arOrderTax["VALUE_MONEY"];

				$strResult .= "<".GetMessage("SALE_EXPORT_TAX").">".
					"<".GetMessage("SALE_EXPORT_ITEM_NAME").">".htmlspecialcharsbx($arOrderTax["TAX_NAME"])."</".GetMessage("SALE_EXPORT_ITEM_NAME").">".
					"<".GetMessage("SALE_EXPORT_IN_PRICE").">".(($arOrderTax["IS_IN_PRICE"]=="Y") ? "true" : "false")."</".GetMessage("SALE_EXPORT_IN_PRICE").">".
					"<".GetMessage("SALE_EXPORT_AMOUNT").">".$arOrderTax["VALUE_MONEY"]."</".GetMessage("SALE_EXPORT_AMOUNT").">".
				"</".GetMessage("SALE_EXPORT_TAX").">";
			}
			$strResult .= "</".GetMessage("SALE_EXPORT_TAXES").">";
		}

		return $strResult;
	}
	function getXmlOrderDiscount($arOrder)
	{
		$strResult='';
		if(DoubleVal($arOrder["DISCOUNT_VALUE"]) > 0)
		{
			$strResult = "<".GetMessage("SALE_EXPORT_DISCOUNTS").">
						<".GetMessage("SALE_EXPORT_DISCOUNT").">
							<".GetMessage("SALE_EXPORT_ITEM_NAME").">".GetMessage("SALE_EXPORT_ORDER_DISCOUNT")."</".GetMessage("SALE_EXPORT_ITEM_NAME").">
							<".GetMessage("SALE_EXPORT_AMOUNT").">".$arOrder["DISCOUNT_VALUE"]."</".GetMessage("SALE_EXPORT_AMOUNT").">
							<".GetMessage("SALE_EXPORT_IN_PRICE").">false</".GetMessage("SALE_EXPORT_IN_PRICE").">
						</".GetMessage("SALE_EXPORT_DISCOUNT").">
					</".GetMessage("SALE_EXPORT_DISCOUNTS").">";
		}
		return $strResult;
	}
	function getXmlSaleStore($arOrder,$arStore)
	{
		$bufer = '';
		if(IntVal($arOrder["STORE_ID"]) > 0 && !empty($arStore[$arOrder["STORE_ID"]]))
		{
			ob_start();
			?>
			<<?=GetMessage("SALE_EXPORT_STORIES")?>>
				<<?=GetMessage("SALE_EXPORT_STORY")?>>
					<<?=GetMessage("SALE_EXPORT_ID")?>><?=$arStore[$arOrder["STORE_ID"]]["XML_ID"]?></<?=GetMessage("SALE_EXPORT_ID")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($arStore[$arOrder["STORE_ID"]]["TITLE"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_ADDRESS")?>>
						<<?=GetMessage("SALE_EXPORT_PRESENTATION")?>><?=htmlspecialcharsbx($arStore[$arOrder["STORE_ID"]]["ADDRESS"])?></<?=GetMessage("SALE_EXPORT_PRESENTATION")?>>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_STREET")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arStore[$arOrder["STORE_ID"]]["ADDRESS"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					</<?=GetMessage("SALE_EXPORT_ADDRESS")?>>
					<<?=GetMessage("SALE_EXPORT_CONTACTS")?>>
						<<?=GetMessage("SALE_EXPORT_CONTACT")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=(self::getVersionSchema() >= self::DEFAULT_VERSION ? GetMessage("SALE_EXPORT_WORK_PHONE_NEW") : GetMessage("SALE_EXPORT_WORK_PHONE"))?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arStore[$arOrder["STORE_ID"]]["PHONE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_CONTACT")?>>
					</<?=GetMessage("SALE_EXPORT_CONTACTS")?>>
				</<?=GetMessage("SALE_EXPORT_STORY")?>>
			</<?=GetMessage("SALE_EXPORT_STORIES")?>>
			<?
			$bufer = ob_get_clean();
		}
		return $bufer;
	}
	function getXmlSaleStoreBasket($arOrder,$arStore)
	{
		$bufer = "";

		ob_start();
		$storeBasket = "
			<".GetMessage("SALE_EXPORT_STORIES").">
				<".GetMessage("SALE_EXPORT_STORY").">
					<".GetMessage("SALE_EXPORT_ID").">".$arStore[$arOrder["STORE_ID"]]["XML_ID"]."</".GetMessage("SALE_EXPORT_ID").">
					<".GetMessage("SALE_EXPORT_ITEM_NAME").">".htmlspecialcharsbx($arStore[$arOrder["STORE_ID"]]["TITLE"])."</".GetMessage("SALE_EXPORT_ITEM_NAME").">
				</".GetMessage("SALE_EXPORT_STORY").">
			</".GetMessage("SALE_EXPORT_STORIES").">
			";
		$bufer = ob_get_clean();

		return $bufer;
	}
	function getXmlBasketItems($type, $arOrder, $arFilter, $arSelect=array(), $arShipment=array())
	{
		$bufer = '';
		$result = array();
		ob_start();
		?><<?=GetMessage("SALE_EXPORT_ITEMS")?>><?

		$select = array("ID", "NOTES", "PRODUCT_XML_ID", "CATALOG_XML_ID", "NAME", "PRICE", "QUANTITY", "DISCOUNT_PRICE", "VAT_RATE", "MEASURE_CODE", "SET_PARENT_ID", "TYPE");
		if(count($arSelect)>0)
		    $select = array_merge($arSelect,$select);
		$dbBasket = \Bitrix\Sale\Internals\BasketTable::getList(array(
			'select' => $select,
			'filter' => $arFilter,
			'order' => array("NAME" => "ASC")
		));

		$basketSum = 0;
		$priceType = "";
		$bVat = false;
		$vatRate = 0;
		$vatSum = 0;
		while ($arBasket = $dbBasket->fetch())
		{
			if(strval($arBasket['TYPE'])!='' && $arBasket['TYPE']== \Bitrix\Sale\BasketItemBase::TYPE_SET)
			    continue;

			$result[] = $arBasket;

			if(strlen($priceType) <= 0)
				$priceType = $arBasket["NOTES"];
			?>
			<<?=GetMessage("SALE_EXPORT_ITEM")?>>
				<<?=GetMessage("SALE_EXPORT_ID")?>><?=htmlspecialcharsbx($arBasket["PRODUCT_XML_ID"])?></<?=GetMessage("SALE_EXPORT_ID")?>>
				<<?=GetMessage("SALE_EXPORT_CATALOG_ID")?>><?=htmlspecialcharsbx($arBasket["CATALOG_XML_ID"])?></<?=GetMessage("SALE_EXPORT_CATALOG_ID")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($arBasket["NAME"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<?
				if(self::getVersionSchema() >= self::DEFAULT_VERSION)
				{
					if(IntVal($arBasket["MEASURE_CODE"]) <= 0)
						$arBasket["MEASURE_CODE"] = 796;
					?>
					<<?=GetMessage("SALE_EXPORT_UNIT")?>>
						<<?=GetMessage("SALE_EXPORT_CODE")?>><?=$arBasket["MEASURE_CODE"]?></<?=GetMessage("SALE_EXPORT_CODE")?>>
						<<?=GetMessage("SALE_EXPORT_FULL_NAME_UNIT")?>><?=htmlspecialcharsbx(self::$measures[$arBasket["MEASURE_CODE"]])?></<?=GetMessage("SALE_EXPORT_FULL_NAME_UNIT")?>>
					</<?=GetMessage("SALE_EXPORT_UNIT")?>>
					<<?=GetMessage("SALE_EXPORT_KOEF")?>>1</<?=GetMessage("SALE_EXPORT_KOEF")?>>
					<?
				}
				else
				{
					?>
					<<?=GetMessage("SALE_EXPORT_BASE_UNIT")?> <?=GetMessage("SALE_EXPORT_CODE")?>="796" <?=GetMessage("SALE_EXPORT_FULL_NAME_UNIT")?>="<?=GetMessage("SALE_EXPORT_SHTUKA")?>" <?=GetMessage("SALE_EXPORT_INTERNATIONAL_ABR")?>="<?=GetMessage("SALE_EXPORT_RCE")?>"><?=GetMessage("SALE_EXPORT_SHT")?></<?=GetMessage("SALE_EXPORT_BASE_UNIT")?>>
					<?
				}
				if(DoubleVal($arBasket["DISCOUNT_PRICE"]) > 0)
				{
					?>
					<<?=GetMessage("SALE_EXPORT_DISCOUNTS")?>>
						<<?=GetMessage("SALE_EXPORT_DISCOUNT")?>>
							<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_ITEM_DISCOUNT")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
							<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$arBasket["DISCOUNT_PRICE"]?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
							<<?=GetMessage("SALE_EXPORT_IN_PRICE")?>>true</<?=GetMessage("SALE_EXPORT_IN_PRICE")?>>
						</<?=GetMessage("SALE_EXPORT_DISCOUNT")?>>
					</<?=GetMessage("SALE_EXPORT_DISCOUNTS")?>>
					<?
				}
				?>
				<?if(self::getVersionSchema() >= self::PARTIAL_VERSION && $type == 'Shipment')
				{?>
				<<?=GetMessage("SALE_EXPORT_PRICE_PER_ITEM")?>><?=$arBasket["PRICE"]?></<?=GetMessage("SALE_EXPORT_PRICE_PER_ITEM")?>>
				<<?=GetMessage("SALE_EXPORT_QUANTITY")?>><?=$arBasket["SALE_INTERNALS_BASKET_SHIPMENT_ITEM_QUANTITY"]?></<?=GetMessage("SALE_EXPORT_QUANTITY")?>>
				<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$arBasket["PRICE"]*$arBasket["SALE_INTERNALS_BASKET_SHIPMENT_ITEM_QUANTITY"]?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
				<?}
				else{
				?>
				<<?=GetMessage("SALE_EXPORT_PRICE_PER_ITEM")?>><?=$arBasket["PRICE"]?></<?=GetMessage("SALE_EXPORT_PRICE_PER_ITEM")?>>
				<<?=GetMessage("SALE_EXPORT_QUANTITY")?>><?=$arBasket["QUANTITY"]?></<?=GetMessage("SALE_EXPORT_QUANTITY")?>>
				<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$arBasket["PRICE"]*$arBasket["QUANTITY"]?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
				<?}?>
				<<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
					<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
						<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_TYPE_NOMENKLATURA")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=GetMessage("SALE_EXPORT_ITEM")?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
					</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
						<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_TYPE_OF_NOMENKLATURA")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=GetMessage("SALE_EXPORT_ITEM")?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
					</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<?
					$dbProp = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "ID" => "ASC"), Array("BASKET_ID" => $arBasket["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")), false, false, array("NAME", "VALUE", "CODE"));
					while($arPropBasket = $dbProp->Fetch())
					{
						?>
						<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
							<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($arPropBasket["NAME"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arPropBasket["VALUE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
						<?
					}
					?>
				</<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
				<?if(DoubleVal($arBasket["VAT_RATE"]) > 0)
				{
					$bVat = true;
					$vatRate = DoubleVal($arBasket["VAT_RATE"]);
					$basketVatSum = (($arBasket["PRICE"] / ($arBasket["VAT_RATE"]+1)) * $arBasket["VAT_RATE"]);
					$vatSum += roundEx($basketVatSum * $arBasket["QUANTITY"], 2);
					?>
					<<?=GetMessage("SALE_EXPORT_TAX_RATES")?>>
						<<?=GetMessage("SALE_EXPORT_TAX_RATE")?>>
							<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_VAT")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
							<<?=GetMessage("SALE_EXPORT_RATE")?>><?=$arBasket["VAT_RATE"] * 100?></<?=GetMessage("SALE_EXPORT_RATE")?>>
						</<?=GetMessage("SALE_EXPORT_TAX_RATE")?>>
					</<?=GetMessage("SALE_EXPORT_TAX_RATES")?>>
					<<?=GetMessage("SALE_EXPORT_TAXES")?>>
						<<?=GetMessage("SALE_EXPORT_TAX")?>>
							<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_VAT")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
							<<?=GetMessage("SALE_EXPORT_IN_PRICE")?>>true</<?=GetMessage("SALE_EXPORT_IN_PRICE")?>>
							<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=roundEx($basketVatSum, 2)?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
						</<?=GetMessage("SALE_EXPORT_TAX")?>>
					</<?=GetMessage("SALE_EXPORT_TAXES")?>>
					<?
				}
				?>
				<?//=self::getXmlSaleStoreBasket($arOrder,$arStore)?>
			</<?=GetMessage("SALE_EXPORT_ITEM")?>>
			<?
			$basketSum += $arBasket["PRICE"]*$arBasket["QUANTITY"];
		}

        if(self::getVersionSchema() >= self::PARTIAL_VERSION)
        {
            if(count($arShipment)>0)
            {
                foreach($arShipment as $shipment)
                {
                    self::getOrderDeliveryItem($shipment, $bVat, $vatRate, $vatSum);
                }
            }
        }
        else
		    self::getOrderDeliveryItem($arOrder, $bVat, $vatRate, $vatSum);

		?>
		</<?=GetMessage("SALE_EXPORT_ITEMS")?>><?

		$bufer = ob_get_clean();
		return array('outputXML'=>$bufer,'result'=>$result);
	}
	function getXmlSaleProperties($arOrder, $arShipment, $arPayment, $paySystems, $delivery, $agent, $agentParams, $bExportFromCrm)
	{
		$bufer = '';
		ob_start();

		if(self::getVersionSchema() < self::PARTIAL_VERSION || $bExportFromCrm)
		{
			$arShipment = $arShipment[0];
			$arPayment = $arPayment[0];
		}
        //$arShipment["DATE_ALLOW_DELIVERY"]


		?><<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>><?
		if(strlen($arOrder["DATE_PAYED"])>0)
		{
			?>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_PAID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$arOrder["DATE_PAYED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<?
		}

		if(self::getVersionSchema() < self::PARTIAL_VERSION || $bExportFromCrm) // #version# < 2.10      ? || $bExportFromCrm
		{
			if(strlen($arPayment["PAY_VOUCHER_NUM"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_NUMBER")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arPayment["PAY_VOUCHER_NUM"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}
			if(IntVal($arPayment["PAY_SYSTEM_ID"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_SYSTEM")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($paySystems[$arPayment["PAY_SYSTEM_ID"]])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_SYSTEM_ID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arPayment["PAY_SYSTEM_ID"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}
			if(strlen($arShipment["DATE_ALLOW_DELIVERY"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_ALLOW_DELIVERY")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$arShipment["DATE_ALLOW_DELIVERY"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}
			if(strlen($arShipment["DELIVERY_ID"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_SERVICE")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($delivery[$arShipment["DELIVERY_ID"]])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}

		}
		else
		{
		?>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_ALLOW_DELIVERY_LAST")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$arOrder["DATE_ALLOW_DELIVERY"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>><?

		}
		?>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_ORDER_PAID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($arOrder["PAYED"]=="Y")?"true":"false";?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
		<?
		if(self::getVersionSchema() < self::PARTIAL_VERSION || $bExportFromCrm)
		{
		?>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_ALLOW_DELIVERY")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($arShipment["ALLOW_DELIVERY"]=="Y")?"true":"false";?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>><?
		}
		?>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_CANCELED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($arOrder["CANCELED"]=="Y")?"true":"false";?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_FINAL_STATUS")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($arOrder["STATUS_ID"]=="F")?"true":"false";?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_ORDER_STATUS")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?$arStatus = CSaleStatus::GetLangByID($arOrder["STATUS_ID"]); echo htmlspecialcharsbx("[".$arOrder["STATUS_ID"]."] ".$arStatus["NAME"]);?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_ORDER_STATUS_ID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
			<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arOrder["STATUS_ID"]);?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<?if(strlen($arOrder["DATE_CANCELED"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_CANCEL")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$arOrder["DATE_CANCELED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_CANCEL_REASON")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arOrder["REASON_CANCELED"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}
			if(strlen($arOrder["DATE_STATUS"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_STATUS")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$arOrder["DATE_STATUS"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}
			if(strlen($arOrder["USER_DESCRIPTION"])>0)
			{
				?>
				<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_USER_DESCRIPTION")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($arOrder["USER_DESCRIPTION"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
				</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<?
			}
			self::OutputXmlSiteName($arOrder);

			if(!empty($agent["REKV"]))
			{
				foreach($agent["REKV"] as $k => $v)
				{
					if(strlen($agentParams[$k]["NAME"]) > 0 && strlen($v) > 0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
							<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agentParams[$k]["NAME"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($v)?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
						<?
					}
				}
			}

			self::OutputXmlDeliveryAddress();

			?>
		</<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
	<?
	$bufer = ob_get_clean();
	return $bufer;
}
	function getXmlContragents($arOrder = array(), $arProp = array(), $agent = array(), $arOptions = array())
	{
		ob_start();
		self::ExportContragents($arOrder, $arProp, $agent, $arOptions);
		$ec_bufer = ob_get_clean();
		return $ec_bufer;
	}
	function OutputXmlDocumentsByType($typeDocument, $xmlResult, $arOrder, $documents)
	{
		if(is_array($documents) && count($documents)>0)
		{
			foreach($documents as $document)
			{
				$document['LID'] = $arOrder['LID'];
				$document['VERSION'] = $arOrder['VERSION'];

				switch($typeDocument)
				{
					case 'Payment':
						self::OutputXmlDocument('Payment',$xmlResult, $document);
					break;
					case 'Shipment':

						 $basketItems = self::getXmlBasketItems('Shipment', $document, array(
							'ORDER_ID'=>$document['ORDER_ID'],
							'SHIPMENT_ITEM.ORDER_DELIVERY_ID'=>$document['ID'],
							),
							array(
							'SHIPMENT_ITEM.QUANTITY'
							),
							array(
							    array('PRICE_DELIVERY'=>$document['PRICE_DELIVERY'])
							)
						);
						$xmlResult['BasketItems'] = $basketItems['outputXML'];
                        $document['BasketResult'] = $basketItems['result'];

						self::OutputXmlDocument('Shipment',$xmlResult, $document);
					break;
				}
			}
		}
	}
	function OutputXmlSiteName($arOrder)
	{
		?>
		<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_SITE_NAME")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
			<<?=GetMessage("SALE_EXPORT_VALUE")?>>[<?=$arOrder["LID"]?>] <?=htmlspecialcharsbx(self::$siteNameByOrder)?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
		</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
		<?
	}
	function OutputXmlDeliveryAddress()
	{
		if(strlen(self::getDeliveryAddress()) > 0)
		{
			?>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_ADDRESS")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
			<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx(self::getDeliveryAddress())?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>

			<?
		}
	}
	function OutputXmlDocumentRemove($typeDocument, $document)
    { global $DB;
        switch($typeDocument)
        {
            case 'Shipment':
                if($document['ID']>0)
                {
                    $result = CSaleOrderChange::GetList(array("ID"=>"DESC"),array('ORDER_ID'=>$document['ID'], 'ENTITY' => 'SHIPMENT', 'TYPE' => 'SHIPMENT_REMOVED'));
                    while($resultChange = $result->Fetch())
                    {?>
                       <<?=GetMessage("SALE_EXPORT_DOCUMENT")?>>
                            <<?=GetMessage("SALE_EXPORT_ID")?>><?=$resultChange["ENTITY_ID"]?></<?=GetMessage("SALE_EXPORT_ID")?>>
                            <<?=GetMessage("SALE_EXPORT_NUMBER")?>><?=$resultChange["ENTITY_ID"]?></<?=GetMessage("SALE_EXPORT_NUMBER")?>>
                            <<?=GetMessage("SALE_EXPORT_DATE")?>><?=$DB->FormatDate($resultChange["DATE_CREATE"], CSite::GetDateFormat("FULL"), "YYYY-MM-DD")?></<?=GetMessage("SALE_EXPORT_DATE")?>>
                            <<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>><?=GetMessage("SALE_EXPORT_ITEM_SHIPMENT")?></<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>>
                            <<?=GetMessage("SALE_EXPORT_ROLE")?>><?=GetMessage("SALE_EXPORT_SELLER")?></<?=GetMessage("SALE_EXPORT_ROLE")?>>
                            <<?=GetMessage("SALE_EXPORT_CURRENCY")?>><?=htmlspecialcharsbx(substr($document["CURRENCY"], 0, 3))?></<?=GetMessage("SALE_EXPORT_CURRENCY")?>>
                            <<?=GetMessage("SALE_EXPORT_NUMBER_BASE")?>><?=$resultChange['ORDER_ID']?></<?=GetMessage("SALE_EXPORT_NUMBER_BASE")?>>
                            <<?=GetMessage("SALE_EXPORT_REMOVED")?>>true</<?=GetMessage("SALE_EXPORT_REMOVED")?>>
                            <<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>></<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
                            <<?=GetMessage("SALE_EXPORT_CONTRAGENTS")?>></<?=GetMessage("SALE_EXPORT_CONTRAGENTS")?>>
                            <<?=GetMessage("CC_BSC1_ITEMS")?>></<?=GetMessage("CC_BSC1_ITEMS")?>>
                       </<?=GetMessage("SALE_EXPORT_DOCUMENT")?>>
                    <?}
                }

            break;
        }
    }
	function OutputXmlDocument($typeDocument,$xmlResult, $document=array())
	{
		global $DB;
		?>

	<<?=GetMessage("SALE_EXPORT_DOCUMENT")?>><?
		switch($typeDocument)
		{
			case 'Order':
		?>
		<<?=GetMessage("SALE_EXPORT_ID")?>><?=$document["ID"]?></<?=GetMessage("SALE_EXPORT_ID")?>>
		<<?=GetMessage("SALE_EXPORT_NUMBER")?>><?=self::getAccountNumberShopPrefix();?><?=$document["ACCOUNT_NUMBER"]?></<?=GetMessage("SALE_EXPORT_NUMBER")?>>
		<<?=GetMessage("SALE_EXPORT_DATE")?>><?=$DB->FormatDate($document["DATE_INSERT_FORMAT"], CSite::GetDateFormat("FULL"), "YYYY-MM-DD")?></<?=GetMessage("SALE_EXPORT_DATE")?>>
		<<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>><?=GetMessage("SALE_EXPORT_ITEM_ORDER")?></<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>>
		<<?=GetMessage("SALE_EXPORT_ROLE")?>><?=GetMessage("SALE_EXPORT_SELLER")?></<?=GetMessage("SALE_EXPORT_ROLE")?>>
		<<?=GetMessage("SALE_EXPORT_CURRENCY")?>><?=htmlspecialcharsbx(((strlen(self::$currency)>0)?substr(self::$currency, 0, 3):substr($document["CURRENCY"], 0, 3)))?></<?=GetMessage("SALE_EXPORT_CURRENCY")?>>
		<<?=GetMessage("SALE_EXPORT_CURRENCY_RATE")?>>1</<?=GetMessage("SALE_EXPORT_CURRENCY_RATE")?>>
		<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$document["PRICE"]?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
				<?
				if(self::getVersionSchema() >= self::DEFAULT_VERSION)
				{
					?>
		<<?=GetMessage("SALE_EXPORT_VERSION")?>><?=(IntVal($document["VERSION"]) > 0 ? $document["VERSION"] : 0)?></<?=GetMessage("SALE_EXPORT_VERSION")?>><?
					if(strlen($document["ID_1C"]) > 0)
					{
						?>
		<<?=GetMessage("SALE_EXPORT_ID_1C")?>><?=htmlspecialcharsbx($document["ID_1C"])?></<?=GetMessage("SALE_EXPORT_ID_1C")?>><?
					}
				}
				if (self::$crmMode)
				{
			?><DateUpdate><?=$DB->FormatDate($document["DATE_UPDATE"], CSite::GetDateFormat("FULL"), "YYYY-MM-DD HH:MI:SS");?></DateUpdate><?
				}
				echo $xmlResult['Contragents'];
			?>
		<<?=GetMessage("SALE_EXPORT_TIME")?>><?=$DB->FormatDate($document["DATE_INSERT_FORMAT"], CSite::GetDateFormat("FULL"), "HH:MI:SS")?></<?=GetMessage("SALE_EXPORT_TIME")?>>
		<<?=GetMessage("SALE_EXPORT_COMMENTS")?>><?=htmlspecialcharsbx($document["COMMENTS"])?></<?=GetMessage("SALE_EXPORT_COMMENTS")?>>
			<?	echo $xmlResult['OrderTax'];
				echo $xmlResult['OrderDiscount'];
				echo $xmlResult['SaleStore'];
				//$storeBasket = self::getXmlSaleStoreBasket($document,$arStore);
				echo $xmlResult['BasketItems'];
				echo $xmlResult['SaleProperties'];
			break;

			case 'Payment':
			case 'Shipment':
			?>
		<<?=GetMessage("SALE_EXPORT_ID")?>><?=$document["ID"]?></<?=GetMessage("SALE_EXPORT_ID")?>>
		<<?=GetMessage("SALE_EXPORT_NUMBER")?>><?=$document["ID"]?></<?=GetMessage("SALE_EXPORT_NUMBER")?>>
		<?	switch($typeDocument)
			{
				case 'Payment':
		?>

		<<?=GetMessage("SALE_EXPORT_DATE")?>><?=$DB->FormatDate($document["DATE_BILL"], CSite::GetDateFormat("FULL"), "YYYY-MM-DD")?></<?=GetMessage("SALE_EXPORT_DATE")?>>
		<<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>><?=GetMessage("SALE_EXPORT_ITEM_PAYMENT_".\Bitrix\Sale\PaySystem\Manager::getPsType($document['PAY_SYSTEM_ID']))?></<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>>
		<?		break;
				case 'Shipment':?>
		<<?=GetMessage("SALE_EXPORT_DATE")?>><?=$DB->FormatDate($document["DATE_INSERT"], CSite::GetDateFormat("FULL"), "YYYY-MM-DD")?></<?=GetMessage("SALE_EXPORT_DATE")?>>
		<<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>><?=GetMessage("SALE_EXPORT_ITEM_SHIPMENT")?></<?=GetMessage("SALE_EXPORT_HOZ_OPERATION")?>>
		<?		break;
			}?>
		<<?=GetMessage("SALE_EXPORT_ROLE")?>><?=GetMessage("SALE_EXPORT_SELLER")?></<?=GetMessage("SALE_EXPORT_ROLE")?>>
		<<?=GetMessage("SALE_EXPORT_CURRENCY")?>><?=htmlspecialcharsbx(((strlen(self::$currency)>0)?substr(self::$currency, 0, 3):substr($document["CURRENCY"], 0, 3)))?></<?=GetMessage("SALE_EXPORT_CURRENCY")?>>
		<<?=GetMessage("SALE_EXPORT_CURRENCY_RATE")?>>1</<?=GetMessage("SALE_EXPORT_CURRENCY_RATE")?>>
		<?	switch($typeDocument)
			{
				case 'Payment':
		?>
		<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$document['SUM']?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
		<?		break;
				case 'Shipment':
                    $price = 0;
                    if(count($document['BasketResult'])>0)
                    {
                        foreach($document['BasketResult'] as $basketItem)
                        {
                            $price = $price + $basketItem['PRICE'] * $basketItem['SALE_INTERNALS_BASKET_SHIPMENT_ITEM_QUANTITY'];
                        }
                    }
		?>
		<<?=GetMessage("SALE_EXPORT_AMOUNT")?>><?=$price+intval($document['PRICE_DELIVERY'])?></<?=GetMessage("SALE_EXPORT_AMOUNT")?>>
		<?		break;
			}?>
		<<?=GetMessage("SALE_EXPORT_VERSION")?>><?=(IntVal($document["VERSION"]) > 0 ? $document["VERSION"] : 0)?></<?=GetMessage("SALE_EXPORT_VERSION")?>>
		<<?=GetMessage("SALE_EXPORT_NUMBER_BASE")?>><?=$document['ORDER_ID']?></<?=GetMessage("SALE_EXPORT_NUMBER_BASE")?>>
		<?=$xmlResult['Contragents'];?>
		<?	switch($typeDocument)
			{
				case 'Payment':
		?>
		<<?=GetMessage("SALE_EXPORT_TIME")?>><?=$DB->FormatDate($document["DATE_BILL"], CSite::GetDateFormat("FULL"), "HH:MI:SS")?></<?=GetMessage("SALE_EXPORT_TIME")?>>
		<?		break;
				case 'Shipment':?>
				<?=$xmlResult['OrderTax'];?>
		<<?=GetMessage("SALE_EXPORT_TIME")?>><?=$DB->FormatDate($document["DATE_INSERT"], CSite::GetDateFormat("FULL"), "HH:MI:SS")?></<?=GetMessage("SALE_EXPORT_TIME")?>>
		<?		break;
			}?>
		<<?=GetMessage("SALE_EXPORT_COMMENTS")?>><?=htmlspecialcharsbx($document["COMMENTS"])?></<?=GetMessage("SALE_EXPORT_COMMENTS")?>>

		<?	switch($typeDocument)
			{
				case 'Payment':
		?>
		<<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_PAID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DATE_PAID"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_CANCELED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($document["CANCELED"]=='Y'? 'true':'false')?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_SYSTEM_ID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["PAY_SYSTEM_ID"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_SYSTEM")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["PAY_SYSTEM_NAME"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_PAID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($document["PAID"]=='Y'? 'true':'false')?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_RETURN")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($document["IS_RETURN"]=='Y'? 'true':'false')?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PAY_RETURN_REASON")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["PAY_RETURN_COMMENT"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<?self::OutputXmlSiteName($document);?>
		</<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
			<?	break;

				case 'Shipment':
			?>

			<?
			echo $xmlResult['BasketItems'];
			?>

		<<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
		    <<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_PRICE_DELIVERY")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=(strlen($document["PRICE_DELIVERY"])>0? $document["PRICE_DELIVERY"]:"0.0000")?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_ALLOW_DELIVERY")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DATE_ALLOW_DELIVERY"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_LOCATION")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DELIVERY_LOCATION"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_STATUS")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["STATUS_ID"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_DEDUCTED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($document["DEDUCTED"]=='Y'? 'true':'false')?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DATE_DEDUCTED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DATE_DEDUCTED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_REASON_UNDO_DEDUCTED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["REASON_UNDO_DEDUCTED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_RESERVED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($document["RESERVED"]=='Y'? 'true':'false')?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_ID")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DELIVERY_ID"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DELIVERY_NAME"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_CANCELED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=($document["CANCELED"]=='Y'? 'true':'false')?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_DELIVERY_DATE_CANCEL")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["DATE_CANCELED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_CANCEL_REASON")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["REASON_CANCELED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=GetMessage("SALE_EXPORT_REASON_MARKED")?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=$document["REASON_MARKED"]?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
			</<?=GetMessage("SALE_EXPORT_PROPERTY_VALUE")?>>
			<?self::OutputXmlSiteName($document);?>
			<?self::OutputXmlDeliveryAddress();?>
	</<?=GetMessage("SALE_EXPORT_PROPERTIES_VALUES")?>>
			<?
				break;
			}
		}
		?>
	</<?=GetMessage("SALE_EXPORT_DOCUMENT")?>>
	<?
	}


	function ExportContragents($arOrder = array(), $arProp = array(), $agent = array(), $arOptions = array())
	{
		$bExportFromCrm = (isset($arOptions["EXPORT_FROM_CRM"]) && $arOptions["EXPORT_FROM_CRM"] === "Y");
		?>

		<<?=GetMessage("SALE_EXPORT_CONTRAGENTS")?>>
			<<?=GetMessage("SALE_EXPORT_CONTRAGENT")?>>
		<?
		if ($bExportFromCrm): ?>
				<<?=GetMessage("SALE_EXPORT_ID")?>><?=htmlspecialcharsbx(substr($arProp["CRM"]["CLIENT_ID"]."#".$arProp["CRM"]["CLIENT"]["LOGIN"]."#".$arProp["CRM"]["CLIENT"]["LAST_NAME"]." ".$arProp["CRM"]["CLIENT"]["NAME"]." ".$arProp["CRM"]["CLIENT"]["SECOND_NAME"], 0, 80))?></<?=GetMessage("SALE_EXPORT_ID")?>><?
		else: ?>
				<?if(strlen($arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"])>0):?>
				    <<?=GetMessage("SALE_EXPORT_ID")?>><?=htmlspecialcharsbx($arOrder["SALE_INTERNALS_ORDER_USER_XML_ID"])?></<?=GetMessage("SALE_EXPORT_ID")?>>
                <?else:?>
				    <<?=GetMessage("SALE_EXPORT_ID")?>><?=htmlspecialcharsbx(substr($arOrder["USER_ID"]."#".$arProp["USER"]["LOGIN"]."#".$arProp["USER"]["LAST_NAME"]." ".$arProp["USER"]["NAME"]." ".$arProp["USER"]["SECOND_NAME"], 0, 80))?></<?=GetMessage("SALE_EXPORT_ID")?>>
				<?endif;?><?
		endif; ?>

				<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agent["AGENT_NAME"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
				<?
				self::setDeliveryAddress($agent["ADDRESS_FULL"]);
				$address = "";
				if(strlen($agent["ADDRESS_FULL"])>0)
				{
				    $address .= "<".GetMessage("SALE_EXPORT_PRESENTATION").">".htmlspecialcharsbx($agent["ADDRESS_FULL"])."</".GetMessage("SALE_EXPORT_PRESENTATION").">";
				}
				if(strlen($agent["INDEX"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_POST_CODE")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["INDEX"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["COUNTRY"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
									<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_COUNTRY")."</".GetMessage("SALE_EXPORT_TYPE").">
									<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["COUNTRY"])."</".GetMessage("SALE_EXPORT_VALUE").">
								</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["REGION"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_REGION")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["REGION"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["STATE"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_STATE")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["STATE"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["TOWN"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_SMALL_CITY")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["TOWN"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["CITY"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_CITY")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["CITY"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["STREET"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_STREET")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["STREET"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["HOUSE"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_HOUSE")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["HOUSE"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["BUILDING"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_BUILDING")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["BUILDING"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}
				if(strlen($agent["FLAT"])>0)
				{
					$address .= "<".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">
								<".GetMessage("SALE_EXPORT_TYPE").">".GetMessage("SALE_EXPORT_FLAT")."</".GetMessage("SALE_EXPORT_TYPE").">
								<".GetMessage("SALE_EXPORT_VALUE").">".htmlspecialcharsbx($agent["FLAT"])."</".GetMessage("SALE_EXPORT_VALUE").">
							</".GetMessage("SALE_EXPORT_ADDRESS_FIELD").">";
				}

				if($agent["IS_FIZ"]=="Y")
				{
					self::$arResultStat["CONTACTS"]++;
					?>
					<<?=GetMessage("SALE_EXPORT_FULL_NAME")?>><?=htmlspecialcharsbx($agent["FULL_NAME"])?></<?=GetMessage("SALE_EXPORT_FULL_NAME")?>>
					<?
					if(strlen($agent["SURNAME"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_SURNAME")?>><?=htmlspecialcharsbx($agent["SURNAME"])?></<?=GetMessage("SALE_EXPORT_SURNAME")?>><?
					}
					if(strlen($agent["NAME"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_NAME")?>><?=htmlspecialcharsbx($agent["NAME"])?></<?=GetMessage("SALE_EXPORT_NAME")?>><?
					}
					if(strlen($agent["SECOND_NAME"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_MIDDLE_NAME")?>><?=htmlspecialcharsbx($agent["SECOND_NAME"])?></<?=GetMessage("SALE_EXPORT_MIDDLE_NAME")?>><?
					}
					if(strlen($agent["BIRTHDAY"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_BIRTHDAY")?>><?=htmlspecialcharsbx($agent["BIRTHDAY"])?></<?=GetMessage("SALE_EXPORT_BIRTHDAY")?>><?
					}
					if(strlen($agent["MALE"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_SEX")?>><?=htmlspecialcharsbx($agent["MALE"])?></<?=GetMessage("SALE_EXPORT_SEX")?>><?
					}
					if(strlen($agent["INN"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_INN")?>><?=htmlspecialcharsbx($agent["INN"])?></<?=GetMessage("SALE_EXPORT_INN")?>><?
					}
					if(strlen($agent["KPP"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_KPP")?>><?=htmlspecialcharsbx($agent["KPP"])?></<?=GetMessage("SALE_EXPORT_KPP")?>><?
					}
					?>
					<<?=GetMessage("SALE_EXPORT_REGISTRATION_ADDRESS")?>>
					<?=$address?>
					</<?=GetMessage("SALE_EXPORT_REGISTRATION_ADDRESS")?>>
				<?
				}
				else
				{
					self::$arResultStat["COMPANIES"]++;
					?>
					<<?=GetMessage("SALE_EXPORT_OFICIAL_NAME")?>><?=htmlspecialcharsbx($agent["FULL_NAME"])?></<?=GetMessage("SALE_EXPORT_OFICIAL_NAME")?>>
					<<?=GetMessage("SALE_EXPORT_UR_ADDRESS")?>>
					<?=$address?>
					</<?=GetMessage("SALE_EXPORT_UR_ADDRESS")?>>
					<?
					if(strlen($agent["INN"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_INN")?>><?=htmlspecialcharsbx($agent["INN"])?></<?=GetMessage("SALE_EXPORT_INN")?>><?
					}
					if(strlen($agent["KPP"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_KPP")?>><?=htmlspecialcharsbx($agent["KPP"])?></<?=GetMessage("SALE_EXPORT_KPP")?>><?
					}
					if(strlen($agent["EGRPO"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_EGRPO")?>><?=htmlspecialcharsbx($agent["EGRPO"])?></<?=GetMessage("SALE_EXPORT_EGRPO")?>><?
					}
					if(strlen($agent["OKVED"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_OKVED")?>><?=htmlspecialcharsbx($agent["OKVED"])?></<?=GetMessage("SALE_EXPORT_OKVED")?>><?
					}
					if(strlen($agent["OKDP"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_OKDP")?>><?=htmlspecialcharsbx($agent["OKDP"])?></<?=GetMessage("SALE_EXPORT_OKDP")?>><?
					}
					if(strlen($agent["OKOPF"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_OKOPF")?>><?=htmlspecialcharsbx($agent["OKOPF"])?></<?=GetMessage("SALE_EXPORT_OKOPF")?>><?
					}
					if(strlen($agent["OKFC"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_OKFC")?>><?=htmlspecialcharsbx($agent["OKFC"])?></<?=GetMessage("SALE_EXPORT_OKFC")?>><?
					}
					if(strlen($agent["OKPO"])>0)
					{
						?><<?=GetMessage("SALE_EXPORT_OKPO")?>><?=htmlspecialcharsbx($agent["OKPO"])?></<?=GetMessage("SALE_EXPORT_OKPO")?>><?
					}
					if(strlen($agent["ACCOUNT_NUMBER"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_MONEY_ACCOUNTS")?>>
						<<?=GetMessage("SALE_EXPORT_MONEY_ACCOUNT")?>>
						<<?=GetMessage("SALE_EXPORT_ACCOUNT_NUMBER")?>><?=htmlspecialcharsbx($agent["ACCOUNT_NUMBER"])?></<?=GetMessage("SALE_EXPORT_ACCOUNT_NUMBER")?>>
						<<?=GetMessage("SALE_EXPORT_BANK")?>>
						<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agent["B_NAME"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
						<<?=GetMessage("SALE_EXPORT_ADDRESS")?>>
						<<?=GetMessage("SALE_EXPORT_PRESENTATION")?>><?=htmlspecialcharsbx($agent["B_ADDRESS_FULL"])?></<?=GetMessage("SALE_EXPORT_PRESENTATION")?>>
						<?
						if(strlen($agent["B_INDEX"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_POST_CODE")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_INDEX"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_COUNTRY"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_COUNTRY")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_COUNTRY"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_REGION"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_REGION")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_REGION"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_STATE"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_STATE")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_STATE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_TOWN"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_SMALL_CITY")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_TOWN"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_CITY"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_CITY")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_CITY"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_STREET"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_STREET")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_STREET"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_HOUSE"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_HOUSE")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_HOUSE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_BUILDING"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_BUILDING")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_BUILDING"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						if(strlen($agent["B_FLAT"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
							<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_FLAT")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
							<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["B_FLAT"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
							</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>><?
						}
						?>
						</<?=GetMessage("SALE_EXPORT_ADDRESS")?>>
						<?
						if(strlen($agent["B_BIK"])>0)
						{
							?><<?=GetMessage("SALE_EXPORT_BIC")?>><?=htmlspecialcharsbx($agent["B_BIK"])?></<?=GetMessage("SALE_EXPORT_BIC")?>><?
						}
						?>
						</<?=GetMessage("SALE_EXPORT_BANK")?>>
						</<?=GetMessage("SALE_EXPORT_MONEY_ACCOUNT")?>>
						</<?=GetMessage("SALE_EXPORT_MONEY_ACCOUNTS")?>>
					<?
					}
				}
				if(strlen($agent["F_ADDRESS_FULL"])>0)
				{
					self::setDeliveryAddress($agent["F_ADDRESS_FULL"]);
					?>
					<<?=GetMessage("SALE_EXPORT_ADDRESS")?>>
					<<?=GetMessage("SALE_EXPORT_PRESENTATION")?>><?=htmlspecialcharsbx($agent["F_ADDRESS_FULL"])?></<?=GetMessage("SALE_EXPORT_PRESENTATION")?>>
					<?
					if(strlen($agent["F_INDEX"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_POST_CODE")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_INDEX"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_COUNTRY"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_COUNTRY")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_COUNTRY"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_REGION"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_REGION")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_REGION"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_STATE"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_STATE")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_STATE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_TOWN"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_SMALL_CITY")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_TOWN"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_CITY"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_CITY")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_CITY"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_STREET"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_STREET")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_STREET"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_HOUSE"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_HOUSE")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_HOUSE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_BUILDING"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_BUILDING")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_BUILDING"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					if(strlen($agent["F_FLAT"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=GetMessage("SALE_EXPORT_FLAT")?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["F_FLAT"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_ADDRESS_FIELD")?>>
					<?
					}
					?>
					</<?=GetMessage("SALE_EXPORT_ADDRESS")?>>
				<?
				}
				if(strlen($agent["PHONE"])>0 || strlen($agent["EMAIL"])>0)
				{
					?>
					<<?=GetMessage("SALE_EXPORT_CONTACTS")?>>
					<?
					if(strlen($agent["PHONE"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_CONTACT")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=(self::getVersionSchema() >= self::DEFAULT_VERSION ? GetMessage("SALE_EXPORT_WORK_PHONE_NEW") : GetMessage("SALE_EXPORT_WORK_PHONE"))?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["PHONE"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_CONTACT")?>>
					<?
					}
					if(strlen($agent["EMAIL"])>0)
					{
						?>
						<<?=GetMessage("SALE_EXPORT_CONTACT")?>>
						<<?=GetMessage("SALE_EXPORT_TYPE")?>><?=(self::getVersionSchema() >= self::DEFAULT_VERSION ? GetMessage("SALE_EXPORT_MAIL_NEW") : GetMessage("SALE_EXPORT_MAIL"))?></<?=GetMessage("SALE_EXPORT_TYPE")?>>
						<<?=GetMessage("SALE_EXPORT_VALUE")?>><?=htmlspecialcharsbx($agent["EMAIL"])?></<?=GetMessage("SALE_EXPORT_VALUE")?>>
						</<?=GetMessage("SALE_EXPORT_CONTACT")?>>
					<?
					}
					?>
					</<?=GetMessage("SALE_EXPORT_CONTACTS")?>>
				<?
				}
				if(strlen($agent["CONTACT_PERSON"])>0)
				{
					?>
					<<?=GetMessage("SALE_EXPORT_REPRESENTATIVES")?>>
					<<?=GetMessage("SALE_EXPORT_REPRESENTATIVE")?>>
					<<?=GetMessage("SALE_EXPORT_CONTRAGENT")?>>
					<<?=GetMessage("SALE_EXPORT_RELATION")?>><?=GetMessage("SALE_EXPORT_CONTACT_PERSON")?></<?=GetMessage("SALE_EXPORT_RELATION")?>>
					<<?=GetMessage("SALE_EXPORT_ID")?>><?=md5($agent["CONTACT_PERSON"])?></<?=GetMessage("SALE_EXPORT_ID")?>>
					<<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>><?=htmlspecialcharsbx($agent["CONTACT_PERSON"])?></<?=GetMessage("SALE_EXPORT_ITEM_NAME")?>>
					</<?=GetMessage("SALE_EXPORT_CONTRAGENT")?>>
					</<?=GetMessage("SALE_EXPORT_REPRESENTATIVE")?>>
					</<?=GetMessage("SALE_EXPORT_REPRESENTATIVES")?>>
				<?
				}?>
				<<?=GetMessage("SALE_EXPORT_ROLE")?>><?=GetMessage("SALE_EXPORT_BUYER")?></<?=GetMessage("SALE_EXPORT_ROLE")?>>
			</<?=GetMessage("SALE_EXPORT_CONTRAGENT")?>>
		</<?=GetMessage("SALE_EXPORT_CONTRAGENTS")?>>
		<?
	}

    public static function getFormatDate($value)
    {
        if(!is_set($value))
            return null;

        $setValue = $value;

        if (($value instanceof DateTime)
			|| ($value instanceof Date))
		{
			$setValue = $value->toString();
		}

        /** @var \Bitrix\Main\Type\DateTime $time */
        $time = new Bitrix\Main\Type\DateTime($setValue);
        if(self::getVersionSchema() >= self::PARTIAL_VERSION )
            $format = 'Y-m-d\TH:i:s';
        else
            $format = 'd.m.Y H:i:s';

        return $time->format($format);
    }
    public static function isFormattedDateFields($type, $field)
    {
        $formattedDateFields = self::getFormattedDateFields();
        return in_array($field, $formattedDateFields[$type]);
    }

    public static function getFormattedDateFields()
    {
        return array(
            'Order'     =>  array(
                'DATE_PAYED',
		        'DATE_CANCELED',
		        'DATE_STATUS',
		        'DATE_ALLOW_DELIVERY',

            ),
            'Shipment'  =>  array(
                'DATE_ALLOW_DELIVERY',
                'DATE_DEDUCTED',
                'DATE_CANCELED',
            ),
            'Payment'   =>  array(
                'DATE_PAID',
            ),
        );
    }

	/** @deprecated */
	private static $systemCodes = array(
		// !!! Make sure these codes are in sync with system codes in BusinessValueConsumer1C !!!
		//  'new bizval name'            => 'old 1c name'
		BusinessValue::INDIVIDUAL_DOMAIN => array(
			'BUYER_PERSON_NAME'          => 'FULL_NAME'     ,
			'BUYER_PERSON_NAME_FIRST'    => 'NAME'          ,
			'BUYER_PERSON_NAME_SECOND'   => 'SECOND_NAME'   ,
			'BUYER_PERSON_NAME_LAST'     => 'SURNAME'       ,
			'BUYER_PERSON_NAME_AGENT'    => 'AGENT_NAME'    ,
			'BUYER_PERSON_NAME_CONTACT'  => 'CONTACT_PERSON',
			'BUYER_PERSON_BIRTHDAY'      => 'BIRTHDAY'      ,
			'BUYER_PERSON_GENDER'        => 'MALE'          ,
			'BUYER_PERSON_INN'           => 'INN'           ,
			'BUYER_PERSON_KPP'           => 'KPP'           ,
			'BUYER_PERSON_ADDRESS'       => 'ADDRESS_FULL'  ,
			'BUYER_PERSON_ZIP'           => 'INDEX'         ,
			'BUYER_PERSON_COUNTRY'       => 'COUNTRY'       ,
			'BUYER_PERSON_REGION'        => 'REGION'        ,
			'BUYER_PERSON_STATE'         => 'STATE'         ,
			'BUYER_PERSON_TOWN'          => 'TOWN'          ,
			'BUYER_PERSON_CITY'          => 'CITY'          ,
			'BUYER_PERSON_STREET'        => 'STREET'        ,
			'BUYER_PERSON_HOUSING'       => 'BUILDING'      ,
			'BUYER_PERSON_BUILDING'      => 'HOUSE'         ,
			'BUYER_PERSON_APARTMENT'     => 'FLAT'          ,
			'BUYER_PERSON_PHONE'         => 'PHONE'         ,
			'BUYER_PERSON_EMAIL'         => 'EMAIL'         ,
			'BUYER_PERSON_F_ADDRESS_FULL'=> 'F_ADDRESS_FULL',
			'BUYER_PERSON_F_INDEX'		 => 'F_INDEX'		,
			'BUYER_PERSON_F_COUNTRY'	 => 'F_COUNTRY'		,
			'BUYER_PERSON_F_REGION'		 => 'F_REGION'		,
			'BUYER_PERSON_F_STATE'		 => 'F_STATE'		,
			'BUYER_PERSON_F_TOWN'		 => 'F_TOWN'		,
			'BUYER_PERSON_F_CITY'		 => 'F_CITY'		,
			'BUYER_PERSON_F_STREET'		 => 'F_STREET'		,
			'BUYER_PERSON_F_BUILDING'	 => 'F_BUILDING'	,
			'BUYER_PERSON_F_HOUSE'		 => 'F_HOUSE'		,
			'BUYER_PERSON_F_FLAT'		 => 'F_FLAT'		,
		),
		BusinessValue::ENTITY_DOMAIN => array(
			'BUYER_COMPANY_NAME'         => 'FULL_NAME'     ,
			'BUYER_COMPANY_NAME_AGENT'   => 'AGENT_NAME'    ,
			'BUYER_COMPANY_NAME_CONTACT' => 'CONTACT_PERSON',
			'BUYER_COMPANY_INN'          => 'INN'           ,
			'BUYER_COMPANY_KPP'          => 'KPP'           ,
			'BUYER_COMPANY_ADDRESS'      => 'ADDRESS_FULL'  ,
			'BUYER_COMPANY_ZIP'          => 'INDEX'         ,
			'BUYER_COMPANY_COUNTRY'      => 'COUNTRY'       ,
			'BUYER_COMPANY_REGION'       => 'REGION'        ,
			'BUYER_COMPANY_STATE'        => 'STATE'         ,
			'BUYER_COMPANY_TOWN'         => 'TOWN'          ,
			'BUYER_COMPANY_CITY'         => 'CITY'          ,
			'BUYER_COMPANY_STREET'       => 'STREET'        ,
			'BUYER_COMPANY_HOUSING'      => 'BUILDING'      ,
			'BUYER_COMPANY_BUILDING'     => 'HOUSE'         ,
			'BUYER_COMPANY_APARTMENT'    => 'FLAT'          ,
			'BUYER_COMPANY_PHONE'        => 'PHONE'         ,
			'BUYER_COMPANY_EMAIL'        => 'EMAIL'         ,
			'BUYER_COMPANY_EGRPO'        => 'EGRPO'         ,
			'BUYER_COMPANY_OKVED'        => 'OKVED'         ,
			'BUYER_COMPANY_OKDP'         => 'OKDP'          ,
			'BUYER_COMPANY_OKOPF'        => 'OKOPF'         ,
			'BUYER_COMPANY_OKFC'         => 'OKFC'          ,
			'BUYER_COMPANY_OKPO'         => 'OKPO'          ,
			'BUYER_COMPANY_BANK_ACCOUNT' => 'ACCOUNT_NUMBER',
			'BUYER_COMPANY_F_ADDRESS_FULL'=> 'F_ADDRESS_FULL',
			'BUYER_COMPANY_F_INDEX'		 => 'F_INDEX'		,
			'BUYER_COMPANY_F_COUNTRY'	 => 'F_COUNTRY'		,
			'BUYER_COMPANY_F_REGION'	 => 'F_REGION'		,
			'BUYER_COMPANY_F_STATE'		 => 'F_STATE'		,
			'BUYER_COMPANY_F_TOWN'		 => 'F_TOWN'		,
			'BUYER_COMPANY_F_CITY'		 => 'F_CITY'		,
			'BUYER_COMPANY_F_STREET'	 => 'F_STREET'		,
			'BUYER_COMPANY_F_BUILDING'	 => 'F_BUILDING'	,
			'BUYER_COMPANY_F_HOUSE'		 => 'F_HOUSE'		,
			'BUYER_COMPANY_F_FLAT'		 => 'F_FLAT'		,
		),
	);
	function GetList($order = Array("ID" => "DESC"), $filter = Array(), $group = false, $arNavStartParams = false, $select = array())
	{
		if (! ($select && is_array($select)))
			$select = array("ID", "PERSON_TYPE_ID", "VARS");

		$select = array_flip($select);

		$personTypes = BusinessValue::getPersonTypes();

		if ($filter && is_array($filter))
		{
			if ($filter['PERSON_TYPE_ID'])
			{
				if (! is_array($filter['PERSON_TYPE_ID']))
					$filter['PERSON_TYPE_ID'] = array($filter['PERSON_TYPE_ID']);

				$personTypes = array_intersect_key($personTypes, array_flip($filter['PERSON_TYPE_ID']));
			}

			if (isset($filter['ID']))
			{
				$personTypes = isset($personTypes[$filter['ID']])
					? array($filter['ID'] => $personTypes[$filter['ID']])
					: array();
			}
		}

		$rows = array();

		if ($personTypes
			&& ($consumers = BusinessValue::getConsumers())
			&& ($consumer = $consumers[BusinessValueConsumer1C::CONSUMER_KEY])
			&& is_array($consumer)
			&& ($codes = $consumer['CODES'])
			&& is_array($codes))
		{
			foreach ($personTypes as $personTypeId => $personType)
			{
				$systemCodes = self::$systemCodes[$personType['DOMAIN']];
				$vars = array();

				foreach ($codes as $codeKey => $code)
				{
					if ($mapping = BusinessValue::getMapping($codeKey, BusinessValueConsumer1C::CONSUMER_KEY, $personTypeId, array('GET_VALUE' => array('PROPERTY' => 'BY_ID'))))
					{
						$mapping1C = array('VALUE' => $mapping['PROVIDER_VALUE']);

						switch ($mapping['PROVIDER_KEY'])
						{
							case 'VALUE':
								$mapping1C['TYPE'] = '';
								break;

							case 'USER':
							case 'ORDER':
							case 'PROPERTY':
							case 'COMPANY':
							case 'PAYMENT':
							case 'SHIPMENT':
								$mapping1C['TYPE'] = $mapping['PROVIDER_KEY'];
								break;

							default: continue; // other types aren't present in old version
						}

						if (isset($code['CODE_INDEX']))
						{
							$codeKey1C = 'REKV_'.$code['CODE_INDEX'];
							$mapping1C['NAME'] = $code['NAME'];
						}
						else
						{
							$codeKey1C = $systemCodes[$codeKey];
						}

						$vars[$codeKey1C] = $mapping1C;
					}
				}

				if ($vars)
				{
					$vars['IS_FIZ'] = $personTypes[$personTypeId]['DOMAIN'] === BusinessValue::INDIVIDUAL_DOMAIN ? 'Y' : 'N';

					$rows []= array_intersect_key(array(
						'ID'             => $personTypeId,
						'PERSON_TYPE_ID' => $personTypeId,
						'VARS'           => serialize($vars),
					), $select);
				}
			}
		}

		if (! $group && is_array($group))
		{
			return count($rows);
		}
		else
		{
			$result = new CDBResult();
			$result->InitFromArray($rows);
			return $result;
		}
	}
    protected function getProvidersInstanceByOrder(Bitrix\Sale\Order $order)
    {
        static $providersInstance = array();

        if(! is_set($providersInstance, $order->getId()))
        {
            $providersInstance[$order->getId()] = array(
                'ORDER'     =>  self::getProviderInstanceByProviderCode($order, 'ORDER'     ),
                'USER'      =>  self::getProviderInstanceByProviderCode($order, 'USER'      ),
                'COMPANY'   =>  self::getProviderInstanceByProviderCode($order, 'COMPANY'   ),
                'SHIPMENT'  =>  self::getProviderInstanceByProviderCode($order, 'SHIPMENT'  ),
                'PAYMENT'   =>  self::getProviderInstanceByProviderCode($order, 'PAYMENT'   ),
                'PROPERTY'  =>  self::getProviderInstanceByProviderCode($order, 'PROPERTY'  ),
            );
        }

        return $providersInstance;
    }
    protected function getProviderInstanceByProviderCode(Bitrix\Sale\Order $order, $providerCode)
	{
		$providerInstance = null;

		switch($providerCode)
		{
			case 'ORDER':
			case 'USER':
			case 'PROPERTY':
				$providerInstance = $order;
				break;
			case 'COMPANY':
				$paymentCollection = $order->getPaymentCollection();
				foreach($paymentCollection as $payment)
				{
					if($payment->getField('COMPANY_ID')>0)
					{
					    $providerInstance = $payment;
					    break;
					}
				}
				if(!$providerInstance)
				{
					$shipmentCollection = $order->getShipmentCollection();
					foreach($shipmentCollection as $shipment)
					{
					    if($shipment->getField('COMPANY_ID')>0)
					    {
					        $providerInstance = $shipment;
					        break;
					    }
					}
				}
				break;
			case 'PAYMENT':
				$paymentCollection = $order->getPaymentCollection();
				foreach($paymentCollection as $payment)
				{
					$providerInstance = $payment;
					break;
				}
				break;
			case 'SHIPMENT':
				$shipmentCollection = $order->getShipmentCollection();
				foreach($shipmentCollection as $shipment)
				{
					$providerInstance = $shipment;
					break;
				}
				break;
		}

        return $providerInstance;
	}
	function GetByID($ID)
	{
		$ID = IntVal($ID);

		if (isset($GLOBALS["SALE_EXPORT"]["SALE_EXPORT_CACHE_".$ID]) && is_array($GLOBALS["SALE_EXPORT"]["SALE_EXPORT_CACHE_".$ID]) && is_set($GLOBALS["SALE_EXPORT_CACHE_".$ID], "ID"))
		{
			return $GLOBALS["SALE_EXPORT"]["SALE_EXPORT_CACHE_".$ID];
		}
		else
		{
			$dbResult = self::GetList(array(), array('ID' => $ID));

			if ($arResult = $dbResult->Fetch())
			{
				$GLOBALS["SALE_EXPORT"]["SALE_EXPORT_CACHE_".$ID] = $arResult;
				return $arResult;
			}
		}

		return False;
	}

	/** @deprecated */
	private static function logError($itemId, $message, Bitrix\Main\Result $result = null)
	{
		if ($result)
			$message .= "\n".implode("\n", $result->getErrorMessages());

		CEventLog::Add(array(
			'SEVERITY' => 'ERROR',
			'AUDIT_TYPE_ID' => 'SALE_1C_TO_BUSINESS_VALUE_ERROR',
			'MODULE_ID' => 'sale',
			'ITEM_ID' => $itemId,
			'DESCRIPTION' => $message,
		));
	}

	/** @deprecated */
	private static function setMap($personTypeId, array $map1C, $itemId)
	{
		BusinessValue::INDIVIDUAL_DOMAIN; // make sure BusinessValueCode1CTable loaded since it in the same file as BusinessValue
		BusinessValueConsumer1C::getConsumers(); // initialize 1C codes

		$personTypes = BusinessValue::getPersonTypes();

		if (! $personType = $personTypes[$personTypeId])
		{
			self::logError($itemId, 'Undefined DOMAIN for person type id "'.$personTypeId.'"');
			return;
		}

		$systemCodes1C = array_flip(self::$systemCodes[$personType['DOMAIN']]);

		foreach ($map1C as $codeKey1C => $mapping1C)
		{
			if ($codeKey1C && is_array($mapping1C))
			{
				if (! $mapping1C['VALUE'])
					continue; // TODO maybe??

				$mapping = array('PROVIDER_VALUE' => $mapping1C['VALUE']);

				if (! ($codeKey = $systemCodes1C[$codeKey1C])
					&& substr($codeKey1C, 0, 5) === 'REKV_'
					&& ($codeIndex = substr($codeKey1C, 5)) !== ''
					&& $mapping1C['NAME'])
				{
					$codeKey = BusinessValueConsumer1C::getRekvCodeKey($personTypeId, $codeIndex);
					$mapping['NAME'] = $mapping1C['NAME'];
				}

				if (! $codeKey)
					continue;

				switch ($mapping1C['TYPE'])
				{
					case '':
						$mapping['PROVIDER_KEY'] = 'VALUE';
						break;

					case 'USER':
					case 'ORDER':
					case 'PROPERTY':
						$mapping['PROVIDER_KEY'] = $mapping1C['TYPE'];
						break;

					default: continue; // other types should not be there
				}

				$r = BusinessValueConsumer1C::setMapping($codeKey, $personTypeId, $mapping);

				if (! $r->isSuccess())
					self::logError($itemId, 'Cannot set mapping with code key "'.$codeKey.'"', $r);
			}
		}
	}

	/** @deprecated */
	public static function migrateToBusinessValues()
	{
		$allPersonTypes = BusinessValue::getPersonTypes(true);

		Bitrix\Main\Application::getConnection()->query('DELETE FROM b_sale_bizval_code_1C');

		$result = Bitrix\Main\Application::getConnection()->query('SELECT * FROM b_sale_export');

		while ($row = $result->fetch())
		{
			if (! (($map1C = unserialize($row['VARS'])) && is_array($map1C)))
				continue;

			$personTypeId = $row['PERSON_TYPE_ID'];
			$domain = $map1C['IS_FIZ'] === 'Y' ? BusinessValue::INDIVIDUAL_DOMAIN : BusinessValue::ENTITY_DOMAIN;
			unset($map1C['IS_FIZ']);

			if (! isset($allPersonTypes[$personTypeId]))
			{
				self::logError($row['ID'], 'Undefined person type "'.$personTypeId.'"');
				continue;
			}
			elseif (isset($allPersonTypes[$personTypeId]['DOMAIN']))
			{
				if ($allPersonTypes[$personTypeId]['DOMAIN'] !== $domain)
				{
					self::logError($row['ID'], 'Person type "'.$personTypeId.'" domain is "'.$allPersonTypes[$personTypeId]['DOMAIN'].'", but in 1C is "'.$domain.'"');
					continue;
				}
			}
			else
			{
				$r = Bitrix\Sale\Internals\BusinessValuePersonDomainTable::add(array(
					'PERSON_TYPE_ID' => $personTypeId,
					'DOMAIN'         => $domain,
				));

				if ($r->isSuccess())
				{
					$allPersonTypes[$personTypeId]['DOMAIN'] = $domain;
					BusinessValue::getPersonTypes(true, $allPersonTypes);
				}
				else
				{
					self::logError($row['ID'], 'Unable to set person type "'.$personTypeId.'" domain', $r);
					continue;
				}
			}

			self::setMap($personTypeId, $map1C, 'Migrate:'.$personTypeId.':'.$row['ID']);
		}
	}

	function CheckFields($ACTION, &$arFields, $ID = 0)
	{
		if ((is_set($arFields, "PERSON_TYPE_ID") || $ACTION=="ADD") && IntVal($arFields["PERSON_TYPE_ID"]) <= 0)
		{
			$GLOBALS["APPLICATION"]->ThrowException(GetMessage("SALE_EXPORT_NO_PERSON_TYPE_ID"), "EMPTY_PERSON_TYPE_ID");
			return false;
		}

		if (is_set($arFields, "PERSON_TYPE_ID"))
		{
			$arResult = CSalePersonType::GetByID($arFields["PERSON_TYPE_ID"]);
			if (!$arResult)
			{
				$GLOBALS["APPLICATION"]->ThrowException(str_replace("#ID#", $arFields["PERSON_TYPE_ID"], GetMessage("SALE_EXPORT_ERROR_PERSON_TYPE_ID")), "ERROR_NO_PERSON_TYPE_ID");
				return false;
			}
		}

		return True;
	}

	function Add($arFields)
	{
		if (! CSaleExport::CheckFields('ADD', $arFields))
			return false;

		foreach ($arFields as $key => $value)
		{
			if (substr($key, 0, 1) == "=")
			{
				$arFields[substr($key, 1)] = $value;
				unset($arFields[$key]);
			}
		}

		if (($map1C = unserialize($arFields['VARS'])) && is_array($map1C))
		{
			self::setMap($arFields['PERSON_TYPE_ID'], $map1C, 'Add:'.$arFields['PERSON_TYPE_ID']);
		}

		return $arFields['PERSON_TYPE_ID'];
	}

	function Update($ID, $arFields)
	{
		$ID = IntVal($ID);

		if (! CSaleExport::CheckFields('UPDATE', $arFields, $ID))
			return false;

		foreach ($arFields as $key => $value)
		{
			if (substr($key, 0, 1) == "=")
			{
				$arFields[substr($key, 1)] = $value;
				unset($arFields[$key]);
			}
		}

		if (($map1C = unserialize($arFields['VARS'])) && is_array($map1C))
		{
			self::setMap($arFields['PERSON_TYPE_ID'], $map1C, 'Update:'.$arFields['PERSON_TYPE_ID'].':'.$ID);
		}

		return $arFields['PERSON_TYPE_ID'];
	}

	function Delete($ID)
	{
		$ID = IntVal($ID);

		unset($GLOBALS["SALE_EXPORT"]["SALE_EXPORT_CACHE_".$ID]);

		BusinessValue::INDIVIDUAL_DOMAIN; // make sure BusinessValueCode1CTable loaded since it in the same file as BusinessValue
		$consumers = BusinessValueConsumer1C::getConsumers(); // initialize 1C codes
		$consumer = $consumers[BusinessValueConsumer1C::CONSUMER_KEY];

		if (is_array($consumer['CODES']))
		{
			foreach ($consumer['CODES'] as $codeKey => $code)
			{
				BusinessValueConsumer1C::setMapping($codeKey, $ID);
			}
		}

		return new CDBResult();
	}
}
