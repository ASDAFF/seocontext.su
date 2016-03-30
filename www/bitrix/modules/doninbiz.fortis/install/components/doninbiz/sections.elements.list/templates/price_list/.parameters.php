<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arTemplateParameters['PRICE_PREFIX'] = array(
    'PARENT' => 'ELEMENTS',
    'NAME' => GetMessage('PL_TPL_PRICE_PREFIX'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
);
$arTemplateParameters['PRICE_SUFFIX'] = array(
    'PARENT' => 'ELEMENTS',
    'NAME' => GetMessage('PL_TPL_PRICE_SUFFIX'),
    'TYPE' => 'STRING',
    'DEFAULT' => GetMessage('PRICE_SUFFIX_DEFAULT')
);

?>