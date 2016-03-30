<?php

if ( ! function_exists('formatMoney')) {
    function formatMoney($amount = null) {
        if ( ! $amount)
            return;

        $amount = number_format($amount, 2, '.', ' ');

        $amount_arr = explode('.', $amount);
        if (!intval($amount_arr[1]))
            $amount = str_replace('.' . $amount_arr[1], '', $amount);

        return $amount;
    }
}

if ( ! function_exists('replaceUriParams')) {

    function replaceUriParams (array $params) {
        global $APPLICATION;
        $params   = array_replace_recursive($_GET, $params);

        return $APPLICATION->GetCurPage() . '?' . http_build_query($params);
    }

}

if ( ! function_exists('_getDepthLevel')) {
    function _getDepthLevel($arSections = array()) {
        return !empty($arSections['DEPTH_LEVEL']) ? $arSections['DEPTH_LEVEL'] : 99;
    }
}

if ( ! function_exists('get_tree_sections')) {
    function get_tree_sections($arSections = array()) {
        $arr = array();

        $iMinDepth = min(array_map('_getDepthLevel', $arSections));

        foreach($arSections as $arSect) {

            if (count($arr)) {
                $iLastKey = end(array_keys($arr));

                if ($arSect['DEPTH_LEVEL'] > $iMinDepth) {
                    $arr[$iLastKey]['CHILDS'][] = $arSect;
                } else {
                    $arr[] = $arSect;
                }

            } else {
                $arr[] = $arSect;
            }
        }

        return $arr;
    }
}