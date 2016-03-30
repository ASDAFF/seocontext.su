<div class="home-first-block full-width-block">
    <div class="container wrapper-container">

        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => SITE_DIR."includes/home/intro.php",
                "EDIT_TEMPLATE" => ""
            ),
            false
        );?>

    </div>
</div>