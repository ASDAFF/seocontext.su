<?php
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