<?php
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
    $code = $APPLICATION->CaptchaGetCode();
    echo json_encode(array(
        'sid' => $code,
        'src' => '/bitrix/tools/captcha.php?captcha_sid=' . $code . '&t=' . time()
    ));
?>