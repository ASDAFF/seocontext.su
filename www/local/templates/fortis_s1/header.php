<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Page\Asset;

Loc::loadLanguageFile(dirname(__FILE__).'/header.php');
Loc::loadLanguageFile(dirname(__FILE__).'/footer.php');

require_once('style-switcher-vars.php');

/*$rsSite = CSite::GetByID(SITE_ID);
$arSite = $rsSite->Fetch();
<title><?
        if ($arSite['SITE_NAME']) {
            echo $APPLICATION->ShowTitle() . ' - ' . $arSite['SITE_NAME'];
        } else {
            $APPLICATION->ShowTitle();
        }
        ?></title>*/

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />

    <title><?$APPLICATION->ShowTitle()?></title>

    <script>
        var template_path = '<?=SITE_TEMPLATE_PATH?>',
            site_dir      = '<?=SITE_DIR?>',
            magnific_gallery = {
                enabled: true,
                tPrev: "<?=GetMessage('DONINBIZ_FORTIS_HEADER_1')?>",
                tNext: "<?=GetMessage('DONINBIZ_FORTIS_HEADER_2')?>",
                tCounter: "<?=GetMessage('DONINBIZ_FORTIS_HEADER_3')?>"
            };
    </script>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>

    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/bootstrap.min.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/font-awesome.min.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/flaticon.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/jquery.smartmenus.bootstrap.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/socialsprites.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/slick.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/owl.carousel.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/owl.transitions.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/magnific-popup.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/ion.rangeSlider.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/footable.core.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/vendor/bootstrap-multiselect.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/common.css');?>
    <?Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/custom.css');?>

    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/modernizr.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.smartmenus.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/navs.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.easing.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/functions.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/bootstrap.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/slick.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/owl.carousel.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.magnific-popup.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/bootstrap-multiselect.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/detectmobilebrowser.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.backstretch.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.eqheight.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/ion.rangeSlider.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/footable/footable.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/footable/footable.sort.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/isotope.pkgd.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/vendor/jquery.collapser.min.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/plugins.js')?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/common.js')?>

    <?$APPLICATION->ShowHead();?>

    <!--[if lt IE 9]>
        <link href="<?=SITE_TEMPLATE_PATH?>/assets/css/ie8.css" rel="stylesheet">
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/vendor/respond.min.js"></script>
    <![endif]-->

    <?if(!empty($aFortisConfig['background']) && $aFortisConfig['background']['type'] == 'image'):?>
        <script>
            $(function() {
                $.backstretch('<?=$aFortisConfig['background']['path'].$aFortisConfig['background']['filename']?>');
            });
        </script>
    <?endif?>
    
</head>

<body<?=(!empty($aFortisConfig['background']) && $aFortisConfig['background']['type'] == 'texture' ?
    ' style="background-image : url('.$aFortisConfig['background']['path'].$aFortisConfig['background']['filename'].')"' : '')?>>
<?$APPLICATION->ShowPanel();?>

<div id="wrapper" class="<?=(!empty($aFortisConfig['layout']) && in_array($aFortisConfig['layout'], array('boxed', 'rubber'))) ? $aFortisConfig['layout'] : 'wide'?>
<?=(!empty($aFortisConfig['header_color']) && in_array($aFortisConfig['header_color'], array('dark', 'light', 'colored'))) ? ' ' . $aFortisConfig['header_color'] : ' colored'?>
<?=(!empty($aFortisConfig['zebra']) && $aFortisConfig['zebra'] == 1) ? ' use-zebra' : ''?>">

<script>
    <?if(!empty($aFortisConfig['layout']) && $aFortisConfig['layout'] == 'rubber'):?>
        var fwrapper = document.getElementById('wrapper');
        fwrapper.className = fwrapper.className + " hidden";
    <?endif?>
</script>


    <?
        $header_type = ! empty($aFortisConfig['header_type']) && in_array($aFortisConfig['header_type'], array(1, 2)) ? $aFortisConfig['header_type'] : 1;
    ?>
    <div class="header-type-<?=$header_type?>">
        <div class="top-header">
            <div class="container wrapper-container">

                <div class="outer">
                    <div class="inner">
                        <div class="left">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR . "includes/top_header_left.php",
                                    "EDIT_TEMPLATE" => ""
                                ),
                                false
                            );?>
                        </div>

                        <div class="right">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:main.include",
                                "",
                                Array(
                                    "AREA_FILE_SHOW" => "file",
                                    "PATH" => SITE_DIR . "includes/top_header_right.php",
                                    "EDIT_TEMPLATE" => ""
                                ),
                                false
                            );?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?include_once dirname(__FILE__) . '/headers/' . $header_type . '.php';?>
    </div>


<?if( ! isset($iFortisSkipHeading)):?>
    <div class="heading">
        <div class="container wrapper-container">
            <div class="row">
                <div class="div col-xs-12">
                    <h1><?$APPLICATION->ShowTitle('h1')?></h1>

                    <div class="right">
                        <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "breadcrumbs", Array(
                                "START_FROM" => "0",
                                "PATH" => "",
                                "SITE_ID" => "",
                            ),
                            false
                        );?>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?endif?>

<?if( ! isset($iFortisSkipContentContainer)):?>
<div class="content">
    <div class="container wrapper-container">
        <div class="row">
            <?if($sFortisColSidebar != 'hidden'):?>
            <div class="<?=$sFortisColSidebar?>">

                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/sidebar_top.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>

                <?$APPLICATION->IncludeComponent("bitrix:menu", "left_multilevel", Array(
                    "ROOT_MENU_TYPE" => "left",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MAX_LEVEL" => "4",
                    "CHILD_MENU_TYPE" => "leftchild",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N",
                ),
                    false
                );?>

                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => SITE_DIR . "includes/sidebar_bottom.php",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                );?>

            </div>
            <?endif?>

            <div class="<?=$sFortisColContent?>">
<?endif?>