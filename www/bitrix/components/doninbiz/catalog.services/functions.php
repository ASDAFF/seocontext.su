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