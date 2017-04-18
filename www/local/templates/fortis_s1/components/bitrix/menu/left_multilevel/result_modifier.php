<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//determine if child selected

$bWasSelected = false;
$arParents = array();
$depth = 1;
foreach($arResult as $i=>$arMenu)
{
	$depth = $arMenu['DEPTH_LEVEL'];

	if($arMenu['IS_PARENT'] == true)
	{
		$arParents[$arMenu['DEPTH_LEVEL']-1] = $i;
	}
	elseif($arMenu['SELECTED'] == true)
	{
		$bWasSelected = true;
		break;
	}
}

if($bWasSelected)
{
	for($i=0; $i<$depth-1; $i++)
		$arResult[$arParents[$i]]['CHILD_SELECTED'] = true;
}

/*$sCurrentUrl = $APPLICATION->GetCurPageParam();
$aCurrentUrl = array_values(array_filter(explode('/', $sCurrentUrl)));
$sFirstParam = !empty($aCurrentUrl[0]) ? $aCurrentUrl[0] : '';

foreach($arResult as $i => $arMenu) {
	$arResult[$i]['DEPTH_LEVEL'] = $arMenu['DEPTH_LEVEL'] - 1;
}
foreach($arResult as $i => $arMenu) {
	if ( ! preg_match("/^\/?{$sFirstParam}/iu", $arMenu['LINK']) || $arMenu['DEPTH_LEVEL'] == 0)
		unset($arResult[$i]);
}*/

?>
