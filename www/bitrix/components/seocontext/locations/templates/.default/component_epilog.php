<?php
CJSCore::Init(array("jquery"));
\Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/seocontext.locations/location.js');
\Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/seocontext.locations/devbridge/jquery.autocomplete.min.js');
\Bitrix\Main\Page\Asset::getInstance()->addJs($templateFolder.'/js/jquery.magnific-popup.min.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss($templateFolder.'/js/magnific-popup.css');
