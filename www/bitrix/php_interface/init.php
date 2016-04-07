<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 06.04.2016
 * Time: 16:05
 */

function test_dump($arg){
     global  $USER;
    if ($USER->IsAdmin()){
        echo '<pre>';
            var_dump($arg);
        echo '</pre>';
    }
}