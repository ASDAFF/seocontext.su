<?php

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

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

if ( ! function_exists('leftAgoOffer')) {
    function leftAgoOffer($datetime, $slice = 2, $full = false) {
        $la_config = GetMessage('OFFER_LEFT_AGO');

        if ($datetime instanceof \DateTime) {
            $datetime = $datetime->format('Y-m-d H:i:s');
        } elseif (is_string($datetime)) {
            $datetime = strtotime($datetime);
        }

        $now = new \DateTime;
        $ago = new \DateTime("@$datetime");
        $diff = $now->diff($ago);

        if ($ago < $now)
            return $la_config['completed'];

        // если нужны недели
        //$diff->w = floor($diff->d / 7);
        //$diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            //'w' => // если нужны недели 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {

            if ($diff->$k) {
                $plural = $la_config[$k];
                $n = leftAgoOfferRules($diff->$k);

                if ( ! empty($plural))
                    $v = preg_replace('/%d/i', $diff->$k, $plural[$n]);
                else
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');

            } else {
                unset($string[$k]);
            }
        }

        $la_config['prefixAgo'] = !empty($la_config['prefixAgo']) ? '<span class="prefixAgo">' . $la_config['prefixAgo'] . '</span> ' : '';
        $la_config['suffixAgo'] = !empty($la_config['suffixAgo']) ? ' <span class="suffixAgo">' . $la_config['suffixAgo'] . '</span>' : '';

        if ( ! $full) $string = array_slice($string, 0, $slice);
        return $string ? $la_config['prefixAgo'] . implode(' ', $string) . $la_config['suffixAgo'] : $la_config['justNow'];
    }
}

if ( ! function_exists('leftAgoOfferRules')) {
    function leftAgoOfferRules($n) {
        $n10 = $n % 10;
        if ( ($n10 == 1) && ( ($n == 1) || ($n > 20) ) ) {
            return 0;
        } else if ( ($n10 > 1) && ($n10 < 5) && ( ($n > 20) || ($n < 10) ) ) {
            return 1;
        }
        return 2;
    }
}