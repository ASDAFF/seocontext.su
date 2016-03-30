<div class="full-width-block">
    <div class="container wrapper-container">

        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR."includes/home/offers.php",
            "EDIT_TEMPLATE" => ""
        ),
            false,
            array(
                "ACTIVE_COMPONENT" => "Y"
            )
        );?>

    </div>
</div>