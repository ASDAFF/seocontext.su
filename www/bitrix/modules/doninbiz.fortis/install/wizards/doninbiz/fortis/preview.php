<?php
require_once('lang/ru/preview.php');

$sTemplatePath = empty($_GET['template_path']) ? '/bitrix/wizards/doninbiz/liberty/site/templates/liberty/' : $_GET['template_path'];
$sWizardPath = empty($_GET['wizard_path']) ? '/bitrix/wizards/doninbiz/liberty/' : $_GET['wizard_path'];

function __($name, $aReplace = false)
{
    global $MESS;
    if (isset($MESS[$name])) {
        $s = $MESS[$name];
        if ($aReplace !== false && is_array($aReplace))
            foreach ($aReplace as $search => $replace)
                $s = str_replace($search, $replace, $s);
        return $s;
    }
    return $name;
}

?>

<!DOCTYPE html>
<!--[if lt IE 7]>
<html lang="en-us" class="no-js ie6 oldie"> <![endif]-->
<!--[if IE 7]>
<html lang="en-us" class="no-js ie7 oldie"> <![endif]-->
<!--[if IE 8]>
<html lang="en-us" class="no-js ie8 oldie"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="bx-no-touch bx-no-retina bx-firefox bx-boxshadow bx-borderradius bx-flexwrap bx-boxdirection bx-transition bx-transform js" lang="en-us"><!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Preview Fortis</title>

    <script>
        var template_path = '/bitrix/templates/fortis',
            site_dir = '/',
            magnific_gallery = {};
    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="Keywords">
    <meta name="description" content="Description">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>

    <link href="<?=$sTemplatePath?>assets/css/vendor/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/flaticon.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/jquery.smartmenus.bootstrap.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/socialsprites.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/slick.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/owl.carousel.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/owl.transitions.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/magnific-popup.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/ion.rangeSlider.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/footable.core.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/vendor/bootstrap-multiselect.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/common.css" type="text/css" rel="stylesheet" />
    <link href="<?=$sTemplatePath?>assets/css/common.less" type="text/css" rel="stylesheet/less" />
    <link href="<?=$sTemplatePath?>assets/css/custom.css" type="text/css" rel="stylesheet" />

    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/modernizr.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.easing.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/functions.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/afterresize.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.smartmenus.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/slick.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/bootstrap-multiselect.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/detectmobilebrowser.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.backstretch.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/inputmask/jquery.inputmask.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.eqheight.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/ion.rangeSlider.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/footable/footable.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/footable/footable.sort.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/isotope.pkgd.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/jquery.collapser.min.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/plugins.js"></script>
    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/common.js"></script>

    <script type="text/javascript" src="<?=$sTemplatePath?>assets/js/vendor/less.min.js"></script>

    <!--[if lt IE 9]>
        <link href="<?=$sTemplatePath?>assets/css/ie8.css" rel="stylesheet">
        <script src="<?=$sTemplatePath?>assets/js/vendor/respond.min.js"></script>
    <![endif]-->

    <script>
        /*$(function() {
            $(window).resize();
            setTimeout(function() {
                $(window).resize();
                $('.home-slider-init').slick('setPosition');
            }, 5000);
        });*/
    </script>

</head>

<body>


<div id="wrapper" class="wide light use-zebra">

    <script>
    </script>

    <div class="top-header">
        <div class="container wrapper-container">

            <div class="outer">
                <div class="inner">
                    <div class="left">
                        <ul class="nav nav-pills">
                            <li>
                                <i class="fa fa-at"></i>
                                <a href="javascript:void(0)">info@donin.biz</a>
                            </li>
                            <li>
                                <i class="fa fa-phone"></i>
                                +7 (922) 829-53-05
                            </li>
                            <li>
                                <i class="fa fa-skype"></i>
                                doninbiz
                            </li>
                            <li>
                                <i class="fa fa-phone-square"></i>
                                <a href="javascript:void(0)" class="get-call-form"><?=__('DB_PV_1')?></a>
                            </li>
                            <li>
                                <i class="fa fa-question-circle"></i>
                                <a href="javascript:void(0)" class="get-question-form"><?=__('DB_PV_2')?></a>
                            </li>
                        </ul>                </div>

                    <div class="right">
                        <ul class="ssm top-social-icons">
                            <li class="vkontakte">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                            <li class="odnoklassniki">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                            <li class="facebook">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                            <li class="mail">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                            <li class="googleplus">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                            <li class="twitter">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                            <li class="instagram">
                                <a href="javascript:void(0)" rel="nofollow" target="_blank" data-toggle="tooltip" data-placement="bottom"></a>
                            </li>
                        </ul>                </div>
                </div>
            </div>

        </div>
    </div>

    <header class="header">
        <div class="container wrapper-container relative-pos">

            <a href="javascript:void(0)" class="logo">
                <img src="<?=$sWizardPath?>images/preview/logo.png" alt="">        </a>

            <div class="header-navigation load-nav">

                <nav class="navbar" role="navigation">

                    <div class="navbar-header">
                        <div class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navigation">
                            <div class="inner">
                                <i class="fa fa-arrow-circle-down"></i>
                                <span class="text"><?=__('DB_PV_3')?></span>
                                <span class="sr-only"><?=__('DB_PV_3')?></span>
                                <i class="fa fa-arrow-circle-down"></i>
                            </div>
                        </div>
                    </div>

                    <div class="collapse navbar-collapse" id="header-navigation">





                        <ul class="nav navbar-nav navbar-right search-item">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle search-link" data-toggle="dropdown">
                                    <i class="fa fa-search"></i>
		<span class="hidden-sm hidden-md hidden-lg">
			<?=__('DB_PV_4')?>		</span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <div id="title-search">
                                            <form action="javascript:void(0)" class="navbar-form search-form" role="search">

                                                <input id="title-search-input" class="field" type="text" name="q" value="" size="40" maxlength="50" autocomplete="off" placeholder="<?=__('DB_PV_4')?>" />

                                                <button type="submit" name="s" class="button">
                                                    <i class="fa fa-search"></i>
                                                </button>

                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right hidden-items hidden">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle circles" data-toggle="dropdown">
                                    <i class="fa fa-circle"></i>
                                    <i class="fa fa-circle"></i>
                                    <i class="fa fa-circle"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu"></ul>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right visible-items">





                            <li class="active"><a href="javascript:void(0)"><?=__('DB_PV_5')?></a></li>







                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_6')?></a>
                                <ul class="dropdown-menu" role="menu">







                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_7')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_8')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_9')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_10')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_11')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_12')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_13')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_14')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_15')?></a></li>





                                </ul></li>

                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_16')?></a>
                                <ul class="dropdown-menu" role="menu">






                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_17')?></a>
                                        <ul class="dropdown-menu" role="menu">






                                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_18')?></a>
                                                <ul class="dropdown-menu" role="menu">







                                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_19')?></a></li>








                                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_20')?></a></li>





                                                </ul></li>


                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_21')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_22')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_23')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_24')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_25')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_26')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_27')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_28')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_29')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_30')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_31')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_32')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_33')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_34')?></a></li>





                                        </ul></li></ul></li>

                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_35')?></a>
                                <ul class="dropdown-menu" role="menu">






                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_36')?></a>
                                        <ul class="dropdown-menu" role="menu">






                                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_37')?></a>
                                                <ul class="dropdown-menu" role="menu">







                                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_38')?></a></li>





                                                </ul></li>


                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_39')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_40')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_41')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_42')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_43')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_44')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_45')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_46')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_47')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_48')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_49')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_50')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_51')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_52')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_53')?></a></li>





                                        </ul></li></ul></li>

                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_54')?></a>
                                <ul class="dropdown-menu" role="menu">






                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_55')?></a>
                                        <ul class="dropdown-menu" role="menu">






                                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_56')?></a>
                                                <ul class="dropdown-menu" role="menu">







                                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_57')?></a></li>





                                                </ul></li>


                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_58')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_59')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_60')?></a></li>





                                        </ul></li>

                                    <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_61')?></a>
                                        <ul class="dropdown-menu" role="menu">







                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_62')?></a></li>








                                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_63')?></a></li>





                                        </ul></li>


                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_64')?></a></li>





                                </ul></li>

                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_65')?></a>
                                <ul class="dropdown-menu" role="menu">







                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_66')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_67')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_68')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_69')?></a></li>





                                </ul></li>

                            <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown"><?=__('DB_PV_70')?></a>
                                <ul class="dropdown-menu" role="menu">







                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_71')?></a></li>








                                    <li class=""><a href="javascript:void(0)"><?=__('DB_PV_72')?></a></li>





                                </ul></li>


                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_73')?></a></li>








                            <li class=""><a href="javascript:void(0)"><?=__('DB_PV_74')?></a></li>






                        </ul>

                    </div>

                </nav>

            </div>

        </div>
    </header>


    <div class="home-slider">
        <div class="home-slider-init">
            <div class="slide light" style="background: url(<?=$sWizardPath?>images/preview/2e85b5e43689d185fe88de2126c1f240.jpg) center top no-repeat!important;">

                <div class="wrapper-container container">
                    <div class="row">

                        <div class="hidden-xs hidden-sm col-md-6 col-md-push-6">
                            <div class="image-outer">
                                <div class="image">
                                    <a href="javascript:void(0)"><img src="<?=$sWizardPath?>images/preview/bd8a55431f3b5a85da57ebf8e5247494.png"></a>                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <div class="text">

                                <a href="javascript:void(0)" class="name"><?=__('DB_PV_75')?></a>
                                <div class="txt"><?=__('DB_PV_76')?></div>
                                <div class="buttons">
                                    <a href="javascript:void(0)" class="btn btn-bordered-white"><?=__('DB_PV_77')?></a>                                        <a href="javascript:void(0)" class="btn btn-bordered-white"><?=__('DB_PV_78')?></a>                                                                            </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="slide light" style="background: url(<?=$sWizardPath?>images/preview/6d7a7e5b11c3645fd47dca6b749029e9.jpg) center top no-repeat!important;">

                <div class="wrapper-container container">
                    <div class="row">

                        <div class="hidden-xs hidden-sm col-md-6">
                            <div class="image-outer">
                                <div class="image">
                                    <img src="<?=$sWizardPath?>images/preview/2c73d94289c144681cb34d938dcbc542.png">                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="text">

                                <span class="name"><?=__('DB_PV_79')?></span>
                                <div class="txt"><?=__('DB_PV_80')?></div>
                                <div class="buttons">
                                    <a href="javascript:void(0)" class="btn btn-success"><?=__('DB_PV_77')?></a>                                        <a href="javascript:void(0)" class="btn btn-primary"><?=__('DB_PV_78')?></a>                                        <a href="javascript:void(0)" class="btn btn-danger"><?=__('DB_PV_81')?></a>                                    </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="slide dark" style="background: url(<?=$sWizardPath?>images/preview/b6e07141b02688c0ef105d4b90db663e.jpg) center top no-repeat!important;">

                <div class="wrapper-container container">
                    <div class="row">

                        <div class="hidden-xs hidden-sm col-md-6">
                            <div class="image-outer">
                                <div class="image">
                                    <a href="javascript:void(0)"><img src="<?=$sWizardPath?>images/preview/35704fb87f6daea898a8b39debee0796.png"></a>                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <div class="text">

                                <a href="javascript:void(0)" class="name"><?=__('DB_PV_82')?></a>
                                <div class="txt"><?=__('DB_PV_83')?></div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <div class="slide light" style="background: url(<?=$sWizardPath?>images/preview/d1704cca56228aae6e825f0674a56f73.jpg) center top no-repeat!important;">

                <div class="wrapper-container container">
                    <div class="row">


                        <div class="col-xs-12 no-image">
                            <div class="text">

                                <span class="name"><?=__('DB_PV_84')?></span>
                                <div class="txt"><?=__('DB_PV_85')?></div>
                                <div class="buttons">
                                    <a href="javascript:void(0)" class="btn btn-primary"><?=__('DB_PV_77')?></a>                                        <a href="javascript:void(0)" class="btn btn-success"><?=__('DB_PV_78')?></a>                                                                            </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="content no-margin-top no-margin-bottom">

        <div class="home-first-block full-width-block">
            <div class="container wrapper-container">

                <div class="row">

                    <div class="col-sm-9">
                        <h1><?=__('DB_PV_86')?></h1>
                        <p>
                            <?=__('DB_PV_87')?>
                        </p>
                    </div>

                    <div class="col-sm-3 text-center">
                        <br>
                        <a href="javascript:void(0)" class="btn btn-primary btn-lg get-service-form"><?=__('DB_PV_88')?></a>
                    </div>

                </div>
            </div>
        </div>
        <div class="full-width-block">
            <div class="container wrapper-container">

                <br>
                <h2 style="text-align: center;"><?=__('DB_PV_89')?></h2>
                <p style="text-align: center;" class="lead">
                    <span style="font-size: 14pt;"><?=__('DB_PV_90')?></span>
                </p>
                <p style="text-align: center;">
                    <br>
                </p>

                <div class="home-features-v1">

                    <div class="row">

                        <div class="col-sm-4">


                            <div class="item">
                                <div class="icon-block">
                                    <div class="outer">
                                        <div class="inner">
                                            <i class="fa fa-font"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-button">

                                    <div class="name">
                                        <?=__('DB_PV_91')?>                                                            </div>

                                    <div class="text">
                                        <?=__('DB_PV_92')?>                            </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-4">


                            <div class="item">
                                <div class="icon-block">
                                    <div class="outer">
                                        <div class="inner">
                                            <img src="<?=$sWizardPath?>images/preview/ab4fbceffb79e8fc774d4112f5d8b62e.png" alt="" title="">
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-button">

                                    <div class="name">
                                        <?=__('DB_PV_93')?>                                                            </div>

                                    <div class="text">
                                        <?=__('DB_PV_94')?>                            </div>

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-4">


                            <div class="item last-item">
                                <div class="icon-block">
                                    <div class="outer">
                                        <div class="inner">
                                            <i class="fa fa-link"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts">

                                    <div class="name">
                                        <?=__('DB_PV_95')?>                                                            </div>

                                    <div class="text">
                                        <?=__('DB_PV_96')?>                            </div>

                                </div>

                                <div class="button">
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm"><?=__('DB_PV_97')?> <i class='fa fa-angle-double-right'></i></a>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
                <br>
            </div>
        </div>
        <div class="full-width-block">
            <div class="container wrapper-container">

                <br>
                <h2 style="text-align: center;"></h2>
                <h2 style="text-align: left;"></h2>
                <h2 style="text-align: center;"><?=__('DB_PV_98')?></h2>
                <p style="text-align: center;">
                </p>
                <p style="text-align: center;">
                </p>
                <p style="text-align: center;">
                </p>
                <p style="text-align: center;" class="lead">
                    <span style="font-size: 14pt;"><?=__('DB_PV_99')?></span>
                </p>
                <p style="text-align: left;" class="lead">
                </p>
                <p style="text-align: center;" class="lead">
                </p>
                <p style="text-align: center;">
                    <br>
                </p>

                <div class="home-features-v2 columns-3"">

                <div class="row">

                    <div class="col-sm-4">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-font"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-button">

                                    <div class="name">
                                        <?=__('DB_PV_91')?>                                                     </div>

                                    <div class="text">
                                        <?=__('DB_PV_92')?>                                </div>


                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-sm-4">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <img src="<?=$sWizardPath?>images/preview/ab4fbceffb79e8fc774d4112f5d8b62e.png" alt="" title="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-button">

                                    <div class="name">
                                        <?=__('DB_PV_93')?>                                                              </div>

                                    <div class="text">
                                        <?=__('DB_PV_94')?>                               </div>


                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="col-sm-4">


                        <div class="item last-item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-link"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts">

                                    <div class="name">
                                        <?=__('DB_PV_95')?>                                                               </div>

                                    <div class="text">
                                        <?=__('DB_PV_96')?>                               </div>

                                    <div class="button">
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm"><?=__('DB_PV_97')?> <i class='fa fa-angle-double-right'></i></a>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <br>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_100')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_101')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>

            <div class="services-section-list home-services-section-list">
                <div class="row">

                    <div class="col-sm-6">

                        <div class="section clearfix">
                            <a href="javascript:void(0)" class="thumbnail">
                                <img src="<?=$sWizardPath?>images/preview/e3e2fb2187fba6fa2308757c6cb728ce.jpg">
                            </a>
                            <div class="right">
                                <h2 class="title"><a href="javascript:void(0)"><?=__('DB_PV_102')?></a>
                                </h2><div class="description"><?=__('DB_PV_103')?></div>
                                <ul class="ul-gray-arrow">
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_104')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_105')?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="visible-xs" />
                    </div>


                    <div class="col-sm-6">

                        <div class="section clearfix">
                            <a href="javascript:void(0)" class="thumbnail">
                                <img src="<?=$sWizardPath?>images/preview/a3caeb6abe4f7da185caf19d1a751497.jpg">
                            </a>
                            <div class="right">
                                <h2 class="title"><a href="javascript:void(0)"><?=__('DB_PV_106')?></a>
                                </h2><div class="description"><?=__('DB_PV_107')?></div>
                                <ul class="ul-gray-arrow">
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_108')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_109')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_110')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_112')?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="visible-xs" />
                    </div>

                </div><hr class="hidden-xs" /><div class="row">
                    <div class="col-sm-6">

                        <div class="section clearfix">
                            <a href="javascript:void(0)" class="thumbnail">
                                <img src="<?=$sWizardPath?>images/preview/c35089598ae87a81f022fe8eb0ff7415.jpg">
                            </a>
                            <div class="right">
                                <h2 class="title"><a href="javascript:void(0)"><?=__('DB_PV_113')?></a>
                                </h2><div class="description"><?=__('DB_PV_114')?></div>
                                <ul class="ul-gray-arrow">
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_115')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_116')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_117')?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="visible-xs" />
                    </div>


                    <div class="col-sm-6">

                        <div class="section clearfix">
                            <a href="javascript:void(0)" class="thumbnail">
                                <img src="<?=$sWizardPath?>images/preview/b1228556b1e76a7c439d28d5021facfc.jpg">
                            </a>
                            <div class="right">
                                <h2 class="title"><a href="javascript:void(0)"><?=__('DB_PV_118')?></a>
                                </h2><div class="description"><?=__('DB_PV_119')?></div>
                                <ul class="ul-gray-arrow">
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_120')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_121')?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><?=__('DB_PV_122')?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="visible-xs" />
                    </div>

                </div><div class="row">                    </div>
            </div>
            <br>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_123')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_124')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>

            <div class="home-services-icons columns-4">

                <div class="row">


                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-magic"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_125')?></a>
                                    </div>

                                    <div class="text">
                                        <?=__('DB_PV_126')?>                                </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-file-text-o"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_127')?></a>
                                    </div>

                                    <div class="text">
                                        <?=__('DB_PV_128')?>                                </div>

                                </div>

                            </div>

                        </div>

                    </div>





                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-pagelines"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_129')?></a>
                                    </div>

                                    <div class="text">
                                        <?=__('DB_PV_130')?>                                </div>

                                </div>

                            </div>

                        </div>

                    </div>





                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-credit-card"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_131')?></a>
                                    </div>

                                    <div class="text">
                                        <?=__('DB_PV_132')?>                                </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div><div class="row">

                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-archive"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-text">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_133')?></a>
                                    </div>

                                    <div class="text">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-puzzle-piece"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-text">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_134')?></a>
                                    </div>

                                    <div class="text">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-3">


                        <div class="item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-support"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-text">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_135')?></a>
                                    </div>

                                    <div class="text">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>




                    <div class="col-sm-3">


                        <div class="item last-item">

                            <div class="inner">

                                <div class="icon-outer">
                                    <div class="icon-block">
                                        <div class="outer">
                                            <div class="inner">
                                                <i class="fa fa-cc-visa"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="texts no-text">

                                    <div class="name">
                                        <a href="javascript:void(0)"><?=__('DB_PV_136')?></a>
                                    </div>

                                    <div class="text">
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div><div class="row">                    </div>

            </div>
            <br>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_137')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_138')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>

            <div class="list-catalog home-services-photos columns-4">

                <div class="row">


                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image" style="background: url('<?=$sWizardPath?>images/preview/no_photo.png') center center no-repeat; min-height: 100px;"></div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_125')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text">
                                    <div class="inner">
                                        <?=__('DB_PV_126')?>                                </div>
                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/321e3ad5f1d87cd2cbbf25190adc1e64.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_127')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text">
                                    <div class="inner">
                                        <?=__('DB_PV_128')?>                                </div>
                                </div>

                            </div>

                        </div>

                    </div>





                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/0b23e9c8843cd679412383a3e6368ae7.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_129')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text">
                                    <div class="inner">
                                        <?=__('DB_PV_130')?>                                </div>
                                </div>

                            </div>

                        </div>

                    </div>





                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/5b49ed3d3c874b485e3a5af14d638061.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_131')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text">
                                    <div class="inner">
                                        <?=__('DB_PV_132')?>                                </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div><div class="row">

                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/e00fe902000d0a97019dcb912930612c.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_133')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text no-text">
                                    <div class="inner">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/3643d76bd57a7518c1c08a8aa5592d60.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_134')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text no-text">
                                    <div class="inner">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-sm-3">


                        <div class="item grid">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/48cae68fce1f4e4d1a9a40b5bc6d3ec9.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_135')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text no-text">
                                    <div class="inner">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>




                    <div class="col-sm-3">


                        <div class="item grid last-item">

                            <div class="inner">

                                <div class="outer-image">
                                    <a class="image" href="javascript:void(0)" title="">
                                        <img src="<?=$sWizardPath?>images/preview/b1e55412a1f72b634e9c48177e446c9a.jpg" alt="">
                                    </a>
                                </div>

                                <a href="javascript:void(0)" class="name">
                                    <div class="inner">
                                        <h3>
                                            <span><?=__('DB_PV_136')?></span>
                                        </h3>
                                    </div>
                                </a>

                                <div class="text no-text">
                                    <div class="inner">
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div><div class="row">                    </div>

            </div>
            <br>
        </div>
    </div>
    <div class="full-width-block home-order-primary-block">
        <div class="container wrapper-container">

            <h2></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_139')?></h2>
            <p style="text-align: center;" class="lead">
                <?=__('DB_PV_140')?>
            </p>

            <div class="buttons">
                <a href="javascript:void(0)" class="btn btn-lg btn-order-white-border get-service-form"><?=__('DB_PV_141')?></a>
                <a href="javascript:void(0)" class="btn btn-lg btn-order-primary-dark get-question-form"><?=__('DB_PV_142')?></a>
            </div>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_143')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_144')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>

            <ul class="nav nav-tabs light home-portfolio-categories">

                <li class="active">
                    <a href="javascript:void(0)" data-filter="*" class="all">
                        <?=__('DB_PV_145')?>        </a>
                </li>



                <li>
                    <a href="javascript:void(0)" data-filter=".iso-section-47">
                        <?=__('DB_PV_146')?>                </a>
                </li>


                <li>
                    <a href="javascript:void(0)" data-filter=".iso-section-48">
                        <?=__('DB_PV_147')?>                </a>
                </li>



            </ul>


            <div class="portfolio-list home-portfolio-list">



                <div class="row">
                    <ul class="grid items columns-3">
                        <li class="col-md-4 col-sm-6 col-xs-12 iso-item iso-section-47">

                            <div class="item-grid">

                                <div class="inner">

                                    <div class="left-col">

                                        <div class="aimg-hover magnific-gallery">
                                            <div class="aimg-overlay"></div>
                                            <div class="image-background hidden-xs" style="background-image: url(<?=$sWizardPath?>images/preview/no_photo.png); background-size: auto;">
                                                &nbsp;
                                            </div>
                                            <img src="<?=$sWizardPath?>images/preview/no_photo.png" class="img-responsive center-block visible-xs">
                                            <div class="aimg-row">
                                                <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="right-col">

                                        <div class="name">
                                            <a href="javascript:void(0)">
                                                <?=__('DB_PV_148')?>                                    </a>
                                        </div>



                                    </div>

                                </div>

                            </div>
                        </li>
                        <li class="col-md-4 col-sm-6 col-xs-12 iso-item iso-section-47">

                            <div class="item-grid">

                                <div class="inner">

                                    <div class="left-col">

                                        <div class="aimg-hover magnific-gallery">
                                            <div class="aimg-overlay"></div>
                                            <div class="image-background hidden-xs" style="background-image: url(<?=$sWizardPath?>images/preview/9d936e5bcd305c4dfe8cd83695675a67.jpg);">
                                                &nbsp;
                                            </div>
                                            <img src="<?=$sWizardPath?>images/preview/9d936e5bcd305c4dfe8cd83695675a67.jpg" class="img-responsive center-block visible-xs">
                                            <div class="aimg-row">
                                                <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                                <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="right-col">

                                        <div class="name">
                                            <a href="javascript:void(0)">
                                                <?=__('DB_PV_149')?>                                    </a>
                                        </div>



                                    </div>

                                </div>

                            </div>
                        </li>
                        <li class="col-md-4 col-sm-6 col-xs-12 iso-item iso-section-47">

                            <div class="item-grid">

                                <div class="inner">

                                    <div class="left-col">

                                        <div class="aimg-hover magnific-gallery">
                                            <div class="aimg-overlay"></div>
                                            <div class="image-background hidden-xs" style="background-image: url(<?=$sWizardPath?>images/preview/4b63d4d0e98aaafaab1f81987be92958.jpg);">
                                                &nbsp;
                                            </div>
                                            <img src="<?=$sWizardPath?>images/preview/4b63d4d0e98aaafaab1f81987be92958.jpg" class="img-responsive center-block visible-xs">
                                            <div class="aimg-row">
                                                <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                                <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="right-col">

                                        <div class="name">
                                            <a href="javascript:void(0)">
                                                <?=__('DB_PV_150')?>                                    </a>
                                        </div>



                                    </div>

                                </div>

                            </div>
                        </li>
                        <li class="col-md-4 col-sm-6 col-xs-12 iso-item iso-section-47">

                            <div class="item-grid">

                                <div class="inner">

                                    <div class="left-col">

                                        <div class="aimg-hover magnific-gallery">
                                            <div class="aimg-overlay"></div>
                                            <div class="image-background hidden-xs" style="background-image: url(<?=$sWizardPath?>images/preview/cfcb277d9c7c0905943337b64e616667.jpg);">
                                                &nbsp;
                                            </div>
                                            <img src="<?=$sWizardPath?>images/preview/cfcb277d9c7c0905943337b64e616667.jpg" class="img-responsive center-block visible-xs">
                                            <div class="aimg-row">
                                                <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                                <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="right-col">

                                        <div class="name">
                                            <a href="javascript:void(0)">
                                                <?=__('DB_PV_151')?>                                    </a>
                                        </div>



                                    </div>

                                </div>

                            </div>
                        </li>
                        <li class="col-md-4 col-sm-6 col-xs-12 iso-item iso-section-48">

                            <div class="item-grid">

                                <div class="inner">

                                    <div class="left-col">

                                        <div class="aimg-hover magnific-gallery">
                                            <div class="aimg-overlay"></div>
                                            <div class="image-background hidden-xs" style="background-image: url(<?=$sWizardPath?>images/preview/32e34b72fc7be93d553285b93e2f3f49.jpg);">
                                                &nbsp;
                                            </div>
                                            <img src="<?=$sWizardPath?>images/preview/32e34b72fc7be93d553285b93e2f3f49.jpg" class="img-responsive center-block visible-xs">
                                            <div class="aimg-row">
                                                <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                                <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="right-col">

                                        <div class="name">
                                            <a href="javascript:void(0)">
                                                <?=__('DB_PV_152')?>                                    </a>
                                        </div>



                                    </div>

                                </div>

                            </div>
                        </li>
                        <li class="col-md-4 col-sm-6 col-xs-12 iso-item iso-section-48">

                            <div class="item-grid">

                                <div class="inner">

                                    <div class="left-col">

                                        <div class="aimg-hover magnific-gallery">
                                            <div class="aimg-overlay"></div>
                                            <div class="image-background hidden-xs" style="background-image: url(<?=$sWizardPath?>images/preview/0a1a81e83708f6f89b1a1f0acdcc6899.jpg);">
                                                &nbsp;
                                            </div>
                                            <img src="<?=$sWizardPath?>images/preview/0a1a81e83708f6f89b1a1f0acdcc6899.jpg" class="img-responsive center-block visible-xs">
                                            <div class="aimg-row">
                                                <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                                <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="right-col">

                                        <div class="name">
                                            <a href="javascript:void(0)">
                                                <?=__('DB_PV_153')?>                                    </a>
                                        </div>



                                    </div>

                                </div>

                            </div>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>

            </div>

            <br>
        </div>
    </div>
    <div class="full-width-block home-order-primary-block">
        <div class="container wrapper-container">

            <h2></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_154')?></h2>
            <p style="text-align: center;" class="lead">
                <?=__('DB_PV_155')?>
            </p>

            <div class="buttons">
                <a href="javascript:void(0)" class="btn btn-lg btn-order-white-border get-portfolio-form"><?=__('DB_PV_156')?></a>
                <a href="javascript:void(0)" class="btn btn-lg btn-order-primary-dark get-question-form"><?=__('DB_PV_157')?></a>
            </div>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_158')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_159')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>


            <div class="home-offers-slider">

                <div class="slide">
                    <div class="outer">
                        <div class="inner">
                            <div class="left">
                                <div class="aimg-hover">
                                    <div class="aimg-overlay"></div>
                                    <img
                                        class="img-thumbnail"
                                        border="0"
                                        src="<?=$sWizardPath?>images/preview/adbe43308fb0fcd32b152e711dde575e.png"
                                        />
                                    <div class="aimg-row">
                                        <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                        <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="right">
                                <h4 class="name">
                                    <a href="javascript:void(0)">
                                        <?=__('DB_PV_160')?>                                </a>
                                </h4>


                                <div class="dates">
                            <span class="label label-primary">
                                04.04.2015                                                                    &mdash; 25.09.2015                                                            </span>
                                    <small><?=__('DB_PV_161')?></small>
                                </div>

                                <?=__('DB_PV_162')?>
                                <div class="read-more">
                                    <a href="javascript:void(0)" class="btn btn-primary">
                                        <?=__('DB_PV_97')?> <i class="fa fa-angle-double-right"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="slide">
                    <div class="outer">
                        <div class="inner">
                            <div class="left">
                                <div class="aimg-hover">
                                    <div class="aimg-overlay"></div>
                                    <img
                                        class="img-thumbnail"
                                        border="0"
                                        src="<?=$sWizardPath?>images/preview/033b36a78dcb7b7cf3115cadb917b9ad.jpg"
                                        />
                                    <div class="aimg-row">
                                        <a href="javascript:void(0)" class="aimg-link"><i class="fa fa-link"></i></a>
                                        <a href="javascript:void(0)" target="_blank" class="aimg-fullscreen"><i class="fa fa-search-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="right">
                                <h4 class="name">
                                    <a href="javascript:void(0)">
                                        <?=__('DB_PV_163')?>                                </a>
                                </h4>


                                <div class="dates">
                            <span class="label label-primary">
                                04.03.2015                                                                    &mdash; 04.04.2015                                                            </span>
                                    <small><?=__('DB_PV_164')?></small>
                                </div>

                                <?=__('DB_PV_165')?>
                                <div class="read-more">
                                    <a href="javascript:void(0)" class="btn btn-primary">
                                        <?=__('DB_PV_97')?> <i class="fa fa-angle-double-right"></i>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div> <br>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_166')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_167')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>

            <div class="fortis-catalog-sections home-catalog-categories">


                <div class="sections-table">


                    <div class="section-tr">
                        <div class="section-td">

                            <table class="img-links">

                                <td class="parent-img">
                                    <a href="javascript:void(0)">
                                        <img class="img-thumbnail" src="<?=$sWizardPath?>images/preview/e0a479c5373976cf34f229aa5051d5b1.png">
                                    </a>
                                </td>

                                <td class="right-links is-image">
                                    <a href="javascript:void(0)" class="parent-link">
                                        <?=__('DB_PV_168')?> <span class="count">(12)</span>								</a>

                                    <ul class="sections-ul">
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_169')?> <span class="count">(6)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_170')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_171')?> <span class="count">(3)</span>												</a>
                                        </li>
                                    </ul>
                                </td>

                            </table>

                            <hr/><div class="catalog-description"><?=__('DB_PV_172')?></div>
                        </div>

                        <div class="section-td last-child">

                            <table class="img-links">

                                <td class="parent-img">
                                    <a href="javascript:void(0)">
                                        <img class="img-thumbnail" src="<?=$sWizardPath?>images/preview/21c020495a64555ca3aa731ff7eb424f.jpg">
                                    </a>
                                </td>

                                <td class="right-links is-image">
                                    <a href="javascript:void(0)" class="parent-link">
                                        <?=__('DB_PV_173')?> <span class="count">(9)</span>								</a>

                                    <ul class="sections-ul">
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_174')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_175')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_176')?> <span class="count">(3)</span>												</a>
                                        </li>
                                    </ul>
                                </td>

                            </table>

                            <hr/><div class="catalog-description"><?=__('DB_PV_177')?></div>
                        </div>

                    </div><div class="section-tr">														<div class="section-td">

                            <table class="img-links">

                                <td class="parent-img">
                                    <a href="javascript:void(0)">
                                        <img class="img-thumbnail" src="<?=$sWizardPath?>images/preview/f4a3ac322297afe446e7d4adaa8f2373.jpg">
                                    </a>
                                </td>

                                <td class="right-links is-image">
                                    <a href="javascript:void(0)" class="parent-link">
                                        <?=__('DB_PV_178')?> <span class="count">(9)</span>								</a>

                                    <ul class="sections-ul">
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_179')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_180')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_181')?> <span class="count">(3)</span>												</a>
                                        </li>
                                    </ul>
                                </td>

                            </table>

                            <hr/><div class="catalog-description"><?=__('DB_PV_182')?></div>
                        </div>

                        <div class="section-td last-child">

                            <table class="img-links">

                                <td class="parent-img">
                                    <a href="javascript:void(0)">
                                        <img class="img-thumbnail" src="<?=$sWizardPath?>images/preview/ac267b720b6aabf4e55f82c8b95683ae.jpg">
                                    </a>
                                </td>

                                <td class="right-links is-image">
                                    <a href="javascript:void(0)" class="parent-link">
                                        <?=__('DB_PV_183')?> <span class="count">(9)</span>								</a>

                                    <ul class="sections-ul">
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_184')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_185')?> <span class="count">(3)</span>												</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="children-link">
                                                <?=__('DB_PV_186')?> <span class="count">(3)</span>												</a>
                                        </li>
                                    </ul>
                                </td>

                            </table>

                            <hr/><div class="catalog-description"><?=__('DB_PV_187')?></div>
                        </div>

                    </div><div class="section-tr">							</div>

                </div>


            </div> <br>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <h2 style="text-align: center;"></h2>
            <h2 style="text-align: left;"></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_188')?></h2>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;">
            </p>
            <p style="text-align: center;" class="lead">
                <span style="font-size: 14pt;"><?=__('DB_PV_189')?></span>
            </p>
            <p style="text-align: left;" class="lead">
            </p>
            <p style="text-align: center;" class="lead">
            </p>
            <p style="text-align: center;">
                <br>
            </p>



            <div class="home-best-products-tabs">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs light" role="tablist">


                    <li role="presentation" class="active">
                        <a href="javascript:void(0)" aria-controls="tab_new" role="tab" data-toggle="tab" data-to="home-best-products-slider-new">
                            <i class="flaticon-new105"></i>
                            <?=__('DB_PV_190')?>                    </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content light">


                    <div role="tabpanel" class="tab-pane active" id="tab_new">

                        <div class="list-catalog">
                            <div class="home-best-products-slider" id="home-best-products-slider-new">
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)">
                                            <img src="<?=$sWizardPath?>images/preview/b1a2bc65fb98d59ef92a0ec373148b63.jpg">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_198')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                80 000<small><?=__('DB_PV_197')?></small>                                            </div>


                                            <div>
                                            <span class="label label-success">
                                                <?=__('DB_PV_194')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)">
                                            <img src="<?=$sWizardPath?>images/preview/e012d7077022ded83401e3c842baa2c3.jpg">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_199')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                220 500<small><?=__('DB_PV_197')?></small>                                            </div>


                                            <div>
                                            <span class="label label-info">
                                                <?=__('DB_PV_195')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)">
                                            <img src="<?=$sWizardPath?>images/preview/c16a2f92772081f9f6fc320bffcdf98b.jpg">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                                <li class="hit">
                                                    <div class="sticker-outer">
                                                        <i class="icon hit"></i>
                                                        <span><?=__('DB_PV_191')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_200')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                32 000<small><?=__('DB_PV_197')?></small>                                            </div>


                                            <div>
                                            <span class="label label-info">
                                                <?=__('DB_PV_195')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)" title="Apple iMac 27&quot; MF886RU/A">
                                            <img src="<?=$sWizardPath?>images/preview/78c306a004c448c2b00bb842d7253a0f.png" alt="Apple iMac 27&quot; MF886RU/A">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_201')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                200 000<small><?=__('DB_PV_197')?></small>                                            </div>


                                            <div>
                                            <span class="label label-info">
                                                <?=__('DB_PV_195')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)" title="Nokia 108 Dual Sim">
                                            <img src="<?=$sWizardPath?>images/preview/079416993ef985cceeac4b0745f03d7e.png" alt="Nokia 108 Dual Sim">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                                <li class="best_price">
                                                    <div class="sticker-outer">
                                                        <i class="icon best_price"></i>
                                                        <span><?=__('DB_PV_193')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_202')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                1 900<small><?=__('DB_PV_197')?></small>                                            </div>


                                            <div>
                                            <span class="label label-warning">
                                                <?=__('DB_PV_196')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)" title="Sony Xperia Z2">
                                            <img src="<?=$sWizardPath?>images/preview/87c5dc765f8fa2f7c75d056d677819ed.png" alt="Sony Xperia Z2">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_203')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                27 000<small><?=__('DB_PV_197')?></small>                                            </div>


                                            <div>
                                            <span class="label label-info">
                                                <?=__('DB_PV_195')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                                <div class="item grid stickers-outer">

                                    <div class="outer-image stickers-relative">
                                        <a class="image" href="javascript:void(0)" title="Apple iPhone 6 Plus 16GB">
                                            <img src="<?=$sWizardPath?>images/preview/e0a51c4e323ad08ff600851a901e5f57.png" alt="Apple iPhone 6 Plus 16GB">

                                            <ul class="stickers">
                                                <li class="new">
                                                    <div class="sticker-outer">
                                                        <i class="icon new"></i>
                                                        <span><?=__('DB_PV_190')?></span>
                                                    </div>
                                                </li>
                                                <li class="hit">
                                                    <div class="sticker-outer">
                                                        <i class="icon hit"></i>
                                                        <span><?=__('DB_PV_191')?></span>
                                                    </div>
                                                </li>
                                                <li class="discount">
                                                    <div class="sticker-outer">
                                                        <i class="icon discount"></i>
                                                        <span><?=__('DB_PV_192')?></span>
                                                    </div>
                                                </li>
                                                <li class="best_price">
                                                    <div class="sticker-outer">
                                                        <i class="icon best_price"></i>
                                                        <span><?=__('DB_PV_193')?></span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </a>
                                    </div>

                                    <a href="javascript:void(0)" class="name">
                                        <div class="inner">
                                            <h3>
                                        <span>
                                            <?=__('DB_PV_204')?>                                        </span>
                                            </h3>
                                        </div>
                                    </a>

                                    <a href="javascript:void(0)" class="price-status">
                                        <div class="inner">


                                            <div class="new-price">
                                                69 990<small><?=__('DB_PV_197')?></small>                                            </div>
                                            <div class="old-price">
                                                75 000<?=__('DB_PV_197')?>                                                </div>


                                            <div>
                                            <span class="label label-success">
                                                <?=__('DB_PV_194')?>                                            </span>
                                            </div>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>


        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">

            <br>
            <div class="row">
                <div class="col-sm-6">
                    <h3><?=__('DB_PV_205')?></h3>

                    <div class="home-news-slider-outer">
                        <div class="row">
                            <ul class="home-news-slider">
                                <li>

                                    <div class="col-md-6">

                                        <div class="item">
                                            <div class="date-block">
                                                <div class="day">4</div>
                                                <div class="month"><?=__('DB_PV_206')?></div>
                                            </div>

                                            <h4><a href="javascript:void(0)"><?=__('DB_PV_207')?></a></h4>

                                            <div class="text" data-show-text="" data-hide-text="" data-mode="chars" data-truncate="150">
                                                <?=__('DB_PV_208')?>                                </div>

                                            <div class="view-more">
                                                <a href="javascript:void(0)">
                                                    <?=__('DB_PV_97')?> <i class="fa fa-angle-double-right"></i>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="item">
                                            <div class="date-block">
                                                <div class="day">4</div>
                                                <div class="month"><?=__('DB_PV_206')?></div>
                                            </div>

                                            <h4><a href="javascript:void(0)"><?=__('DB_PV_209')?></a></h4>

                                            <div class="text" data-show-text="" data-hide-text="" data-mode="chars" data-truncate="150">
                                                <?=__('DB_PV_210')?>                                </div>

                                            <div class="view-more">
                                                <a href="javascript:void(0)">
                                                    <?=__('DB_PV_97')?> <i class="fa fa-angle-double-right"></i>
                                                </a>
                                            </div>

                                        </div>

                                    </div>

                                </li><li>
                                    <div class="col-md-6">

                                        <div class="item">
                                            <div class="date-block">
                                                <div class="day">4</div>
                                                <div class="month"><?=__('DB_PV_206')?></div>
                                            </div>

                                            <h4><a href="javascript:void(0)"><?=__('DB_PV_211')?></a></h4>

                                            <div class="text" data-show-text="" data-hide-text="" data-mode="chars" data-truncate="150">
                                                <?=__('DB_PV_212')?>                                </div>

                                            <div class="view-more">
                                                <a href="javascript:void(0)">
                                                    <?=__('DB_PV_97')?> <i class="fa fa-angle-double-right"></i>
                                                </a>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="item">
                                            <div class="date-block">
                                                <div class="day">4</div>
                                                <div class="month"><?=__('DB_PV_206')?></div>
                                            </div>

                                            <h4><a href="javascript:void(0)"><?=__('DB_PV_213')?></a></h4>

                                            <div class="text" data-show-text="" data-hide-text="" data-mode="chars" data-truncate="150">
                                                <?=__('DB_PV_214')?>                                </div>

                                            <div class="view-more">
                                                <a href="javascript:void(0)">
                                                    <?=__('DB_PV_97')?> <i class="fa fa-angle-double-right"></i>
                                                </a>
                                            </div>

                                        </div>

                                    </div>

                                </li>
                            </ul>
                        </div>

                        <a href="javascript:void(0)" class="btn btn-bordered-default btn-all-items">
                            <?=__('DB_PV_215')?>                <i class="fa fa-angle-double-right"></i>
                        </a>

                    </div>

                </div>
                <div class="col-sm-6">
                    <h3><?=__('DB_PV_216')?></h3>

                    <div class="home-reviews-slider-outer">
                        <ul class="home-reviews-slider">

                            <li>
                                <div class="item">

                                    <div class="review">

                                        <span class="left-quote"><i class="fa fa-quote-left"></i></span>
                                        <span class="right-quote"><i class="fa fa-quote-right"></i></span>

                                        <div class="text" data-show-text="<?=__('DB_PV_217')?>" data-hide-text="<?=__('DB_PV_218')?>" data-mode="lines" data-truncate="3">
                                            <?=__('DB_PV_219')?>                            </div>

                                    </div>

                                    <div class="autor">
                                        <div class="photo">
                                            <img src="<?=$sWizardPath?>images/preview/f69806dbad89b4140f3254eb98c9ccd7.png" class="img-thumbnail">
                                        </div>
                                        <div class="person">
                                    <span>
                                                                                    <a href="javascript:void(0)"><?=__('DB_PV_220')?></a>
                                                                            </span>
                                            <small><?=__('DB_PV_221')?></small>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>
                            </li>
                        </ul>

                        <div class="btn-group">
                            <a href="javascript:void(0)" class="btn btn-bordered-default btn-all-items">
                                <i class="fa fa-comments"></i>
                                <?=__('DB_PV_222')?>                </a>
                            <a href="javascript:void(0)" class="btn btn-bordered-primary btn-all-items">
                                <i class="fa fa-plus-circle"></i>
                                <?=__('DB_PV_223')?>                </a>
                        </div>

                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
    <div class="full-width-block">
        <div class="container wrapper-container">


            <ul class="home-partners-slider">
                <li>
                    <div class="outer">
                        <a href="javascript:void(0)">
                            <img src="<?=$sWizardPath?>images/preview/d5fea77874e275d86d3c569dae84004f.png">
                        </a>
                    </div>
                </li>
                <li>
                    <div class="outer">
                        <a href="javascript:void(0)">
                            <img src="<?=$sWizardPath?>images/preview/da9e9b3079ccb6e89357ca6e32c93b23.jpg">
                        </a>
                    </div>
                </li>
                <li>
                    <div class="outer">
                        <a href="javascript:void(0)">
                            <img src="<?=$sWizardPath?>images/preview/c081c79b22f149aa276dc21740b11106.jpg">
                        </a>
                    </div>
                </li>
                <li>
                    <div class="outer">
                        <a href="javascript:void(0)">
                            <img src="<?=$sWizardPath?>images/preview/bb5cc275a9cb8be8b7d20085be218c70.jpg">
                        </a>
                    </div>
                </li>
                <li>
                    <div class="outer">
                        <a href="javascript:void(0)">
                            <img src="<?=$sWizardPath?>images/preview/e0f17a94f347b7b4970e78607b33bc7d.jpg">
                        </a>
                    </div>
                </li>
                <li>
                    <div class="outer">
                        <a href="javascript:void(0)">
                            <img src="<?=$sWizardPath?>images/preview/0db7ef323e69fdf111d37288d3fe048a.jpg">
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="full-width-block home-order-primary-block">
        <div class="container wrapper-container">

            <h2></h2>
            <h2 style="text-align: center;"><?=__('DB_PV_224')?></h2>
            <p style="text-align: center;" class="lead">
                <?=__('DB_PV_225')?>
            </p>

            <div class="buttons">
                <a href="javascript:void(0)" class="btn btn-lg btn-order-white-border get-question-form"><?=__('DB_PV_226')?></a>
                <a href="javascript:void(0)" class="btn btn-lg btn-order-primary-dark get-call-form"><?=__('DB_PV_227')?></a>
            </div>
        </div>
    </div>
</div>



<footer class="footer">

    <br/>

    <div class="container wrapper-container">

        <div class="row blocks">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <h4><?=__('DB_PV_228')?></h4>
                <div class="box">

                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
                    <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla</p>

                </div>            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <h4><?=__('DB_PV_229')?></h4>
                <div class="box">

                    <table class="info">
                        <tr>
                            <td colspan="2" class="company-name"><?=__('DB_PV_230')?></td>
                        </tr>
                        <tr class="tr">
                            <td class="left"><i class="fa fa-phone"></i></td>
                            <td class="right">+7 (900) 000-00-00</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="right">+7 (900) 000-00-00</td>
                        </tr>
                        <tr class="tr">
                            <td class="left"><i class="fa fa-envelope"></i></td>
                            <td class="right">
                                <a href="javascript:void(0)">info@test.ru</a>
                            </td>
                        </tr>
                        <tr class="tr">
                            <td class="left"><i class="fa fa-map-marker"></i></td>
                            <td class="right"><?=__('DB_PV_231')?></td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <h4><?=__('DB_PV_232')?></h4>
                <div class="box">


                    <ul class="box-links">

                        <li><a href="javascript:void(0)"><i class="fa fa-caret-right"></i> <?=__('DB_PV_233')?></a></li>

                        <li><a href="javascript:void(0)"><i class="fa fa-caret-right"></i> <?=__('DB_PV_234')?></a></li>

                        <li><a href="javascript:void(0)"><i class="fa fa-caret-right"></i> <?=__('DB_PV_235')?></a></li>

                        <li><a href="javascript:void(0)"><i class="fa fa-caret-right"></i> <?=__('DB_PV_236')?></a></li>


                    </ul>

                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <h4><?=__('DB_PV_237')?></h4>
                <div class="box">

                    <div id="vk_groups"></div>
                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
                    <script type="text/javascript">
                        VK.Widgets.Group("vk_groups", {mode: 1, width: "150", height: "200", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 60444445);
                    </script>

                </div>
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
                        Copyright © 2014 - <a href="javascript:void(0)">Company Name</a>                    </div>
                </div>
                <div class="col-sm-6">

                    <ul class="nav nav-pills clearfix">

                        <li><a href="javascript:void(0)"><i class="fa fa-caret-right"></i> <?=__('DB_PV_238')?></a></li>

                        <li><a href="javascript:void(0)"><i class="fa fa-caret-right"></i> <?=__('DB_PV_239')?></a></li>


                    </ul>
                </div>
            </div>
        </div>
    </div>

</footer>

</div>

<a href="javascript:void(0)" id="back_to_top">
    <span class="fa fa-chevron-up"></span>
</a>

</body>
</html>