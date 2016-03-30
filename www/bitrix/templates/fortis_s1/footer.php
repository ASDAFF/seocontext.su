<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>

<?if( ! isset($iFortisSkipContentContainer)):?>
            </div><!-- .col-md-9 -->
        </div><!-- .row -->
    </div><!-- .container wrapper-container -->
</div><!-- .content -->
<?endif?>

<footer class="footer">

    <br/>

    <div class="container wrapper-container">

        <div class="row blocks">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/footer_block_1.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/footer_block_2.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/footer_block_3.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/footer_block_4.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div id="bx-composite-banner-outer">
                    <div id="bx-composite-banner">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="bottom">
        <div class="container wrapper-container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="copyright">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => SITE_DIR . "includes/footer_copyright.php",
                                "EDIT_TEMPLATE" => ""
                            ),
                            false
                        );?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "PATH" => SITE_DIR . "includes/footer_menu.php",
                            "EDIT_TEMPLATE" => ""
                        ),
                        false
                    );?>
                </div>
            </div>
        </div>
    </div>

</footer>

</div>

<a href="#" id="back_to_top">
    <span class="fa fa-chevron-up"></span>
</a>

<?
    if ( ! preg_match("/" . addcslashes(SITE_DIR . 'order/(product|service|call|question|portfolio).php', '/.')."(.*)+/iu", $_SERVER['REQUEST_URI'])) {
        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'order/product.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'order/service.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'order/portfolio.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'order/question.php';
        include_once $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'order/call.php';
    }
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => SITE_DIR . "includes/counters.php",
        "EDIT_TEMPLATE" => ""
    ),
    false
);?>

<?if ($USER->IsAdmin()): ?>
<?
$frame = new \Bitrix\Main\Page\FrameBuffered("fortis_theme_switcher");
$frame->begin();
?>
    <?require_once('style-switcher.php')?>
    <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/theme-switcher.js"></script>
    <script>
        $(function() {
            $('#theme-switcher').themeSwitcher();
        });
    </script>
<?$frame->end()?>
<?endif;?>

<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/assets/js/vendor/inputmask/phone-codes/phone-codes.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/assets/js/vendor/inputmask/jquery.inputmask.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/assets/js/vendor/inputmask/jquery.inputmask.extensions.js"></script>
<script type="text/javascript" src="<?=SITE_TEMPLATE_PATH?>/assets/js/vendor/inputmask/jquery.inputmask.phone.extensions.js"></script>

</body>
</html>