<?

$iFortisSkipContentContainer = true;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Контакты компании");
$APPLICATION->SetTitle("Контакты");?>

    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR . "includes/contacts_map.php",
            "EDIT_TEMPLATE" => ""
        ),
        false
    );?>

    <div class="content">
        <div class="container wrapper-container">

            <div class="row">

                <div class="col-sm-6">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => "before_form",
                            "EDIT_TEMPLATE" => "",
                            "AREA_FILE_RECURSIVE" => "Y"
                        )
                    );?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.feedback",
                        "contacts",
                        array(
                            "USE_CAPTCHA" => "Y",
                            "OK_TEXT" => "Спасибо, ваше сообщение принято.",
                            "EMAIL_TO" => "",
                            "REQUIRED_FIELDS" => array(
                                0 => "NAME",
                                1 => "EMAIL",
                                2 => "MESSAGE",
                            ),
                            "EVENT_MESSAGE_ID" => array(
                                0 => "7",
                            )
                        ),
                        false
                    );?>
                </div>

                <div class="col-sm-6">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => "contacts",
                            "EDIT_TEMPLATE" => "",
                            "AREA_FILE_RECURSIVE" => "Y"
                        )
                    );?>
                </div>

            </div>

        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>