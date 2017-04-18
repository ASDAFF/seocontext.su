<?php

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}

require_once('style-switcher-conf.php');

$sTemplatePath = SITE_TEMPLATE_PATH;

$aDefaultLayouts = array(
    'wide' => GetMessage('DONINBIZ_FORTIS_FOOTER_1'),
    'boxed' => GetMessage('DONINBIZ_FORTIS_FOOTER_2'),
    'rubber' => GetMessage('DONINBIZ_FORTIS_FOOTER_3')
);

$aDefaultColors = array(
    '#19B7F1', '#7FBA00', '#8b1d83', '#EA2325', '#ffa500', '#2baab1', '#808080', '#e05048', '#734ba9', '#2f2f2f',
    '#996600', '#CC6600', '#CC0033', '#FF3366', '#6600FF', '#006699', '#006666', '#009933', '#3366FF', '#CCCC33'
);

$sTexturesBackgroundPath = $sTemplatePath . '/assets/img/bg/textures/';
$sImagesBackgroundPath   = $sTemplatePath . '/assets/img/bg/images/';

$aBackgrounds = array();

$aTexturesPath = glob($_SERVER["DOCUMENT_ROOT"] . $sTexturesBackgroundPath . "[!s_]*.{jpg,png,gif,bmp,jpeg}", GLOB_BRACE);
asort($aTexturesPath, SORT_NATURAL);
foreach ($aTexturesPath as $image) {
    $sourceImg = $image;
    $smallSourceImg = $sTexturesBackgroundPath.'s_'.basename($image);

    if ( ! file_exists($_SERVER["DOCUMENT_ROOT"] . $smallSourceImg)) {
        $smallSourceImg = $_SERVER["DOCUMENT_ROOT"] . $smallSourceImg;
        CFile::ResizeImageFile($sourceImg, $smallSourceImg, array('width' => 20, 'height' => 20), BX_RESIZE_IMAGE_EXACT);
    }

    $aBackgrounds['textures'][] = array(
        'path' => $sTexturesBackgroundPath,
        'type' => 'texture',
        'filename' => basename($image),
        's_filename' => 's_'.basename($image)
    );
}

$aImagesPath = glob($_SERVER["DOCUMENT_ROOT"] . $sImagesBackgroundPath . "[!s_]*.{jpg,png,gif,bmp,jpeg}", GLOB_BRACE);
asort($aImagesPath, SORT_NATURAL);
foreach ($aImagesPath as $image) {
    $sourceImg = $image;
    $smallSourceImg = $sImagesBackgroundPath.'s_'.basename($image);

    if ( ! file_exists($_SERVER["DOCUMENT_ROOT"] . $smallSourceImg)) {
        $smallSourceImg = $_SERVER["DOCUMENT_ROOT"] . $smallSourceImg;
        CFile::ResizeImageFile($sourceImg, $smallSourceImg, array('width' => 50, 'height' => 50));
    }

    $aBackgrounds['images'][] = array(
        'path' => $sImagesBackgroundPath,
        'type' => 'image',
        'filename' => basename($image),
        's_filename' => 's_'.basename($image)
    );
}


if(!empty($_POST['switcher'])) {

    // сохранение в конфиг файл данных стиля
    $aConfSwitcher = $_POST['switcher'];
    // убираем лишнее
    if (!empty($aConfSwitcher['css'])) unset($aConfSwitcher['css']);
    if (!empty($aConfSwitcher['save'])) unset($aConfSwitcher['save']);
    // редактируем нужное
    if (!empty($aConfSwitcher['texture'])) {
        $aConfSwitcher['background'] = array(
            'path' => $sTexturesBackgroundPath,
            'filename' => $aConfSwitcher['texture'],
            's_filename' => 's_'.$aConfSwitcher['texture'],
            'type' => 'texture'
        );
    } elseif (!empty($aConfSwitcher['image'])) {
        $aConfSwitcher['background'] = array(
            'path' => $sImagesBackgroundPath,
            'filename' => $aConfSwitcher['image'],
            's_filename' => 's_'.$aConfSwitcher['image'],
            'type' => 'image'
        );
    }

    // Сохраняем цвет
    if ( ! empty($aConfSwitcher['custom_color']) && ! in_array($aConfSwitcher['custom_color'], $aDefaultColors)) {
        $aConfSwitcher['color'] = $aConfSwitcher['custom_color'];
        unset($aConfSwitcher['custom_color']);
    }

    // экспортируем массив с форматированием
    if ($aConfSwitcher['type'] == 'default') {
        $sSave = var_export(array(
            'layout' => 'wide',
            'color' => $aDefaultColors[0],
        ), true);
    } else {
        $sSave = var_export($aConfSwitcher, true);
    }
    // сохраняем
    file_put_contents(dirname(__FILE__) . '/style-switcher-conf.php', "<?php\n\n\$aFortisConfig = {$sSave};\n\n?>");

    // генерация файла common.css
    if (!empty($_POST['switcher']['css'])) {

        $css_code = base64_decode($_POST['switcher']['css']);

        if ( ! empty($css_code)) {
            file_put_contents(dirname(__FILE__) . "/assets/css/common.css", str_replace(array(
                '\\\\', '\\"', '\\\''
            ), array(
                '\\', '"', '\''
            ), $css_code));
        }
    }

    // Очистка кеша, только для демо
    $list = array(
        "/bitrix/cache/css/" => 'CSS файлы',
        "/bitrix/html_pages/".$_SERVER["SERVER_NAME"]."/" => GetMessage('DONINBIZ_FORTIS_FOOTER_21')
    );
    foreach ($list as $directory => $content) {
        DeleteDirFilesEx($directory);
    }

} else {

    // ПРОВЕРКИ

    // макет темы
    if (empty($aFortisConfig['layout'])) {
        $aFortisConfig['layout'] = !empty($aFortisConfig['layout']) ? in_array($aFortisConfig['layout'], array('wide', 'boxed', 'rubber')) ? $aFortisConfig['layout'] : 'wide' : 'wide';
    }

    // Цвет шапки
    $aFortisConfig['header_color'] = !empty($aFortisConfig['header_color']) && in_array($aFortisConfig['header_color'], array('dark', 'light', 'colored')) ? $aFortisConfig['header_color'] : 'colored';

    // Цвет шапки
    $aFortisConfig['header_type'] = !empty($aFortisConfig['header_type']) && in_array($aFortisConfig['header_type'], array(1, 2)) ? $aFortisConfig['header_type'] : 1;

    // Основной цвет темы
    $aFortisConfig['color'] = !empty($aFortisConfig['color']) ? $aFortisConfig['color'] : $aDefaultColors[0];

    // Изображение и текстура
    if ( ! empty($aFortisConfig['background']) && ! empty($aFortisConfig['background']['type'])) {
        $sCurrentTexture = $sCurrentImage = null;

        switch($aFortisConfig['background']['type']) {
            case 'texture':
                $sCurrentTexture = $aFortisConfig['background']['filename'];
                break;
            case 'image':
                $sCurrentImage = $aFortisConfig['background']['filename'];
                break;
        }
    }

    // Сайдбар
    $aFortisConfig['sidebar_align'] = !empty($aFortisConfig['sidebar_align']) && in_array($aFortisConfig['sidebar_align'], array('left', 'right')) ? $aFortisConfig['sidebar_align'] : 'left';

?>

    <div id="theme-switcher" class="hidden">
    <h4>
        <?=GetMessage('DONINBIZ_FORTIS_FOOTER_4')?>
        <a href="#" id="switcher-toggle">
            <i class="fa fa-paint-brush icon-open"></i>
            <i class="fa fa-close icon-close"></i>
        </a>
    </h4>
    <form class="theme-switcher-form" action="">

    <div class="item change-layout">
        <div class="name">
            <?=GetMessage('DONINBIZ_FORTIS_FOOTER_5')?>
        </div>

        <div class="btn-group" data-toggle="buttons">
            <?foreach($aDefaultLayouts as $aDefLayout => $aDefLayoutName):?>
                <label class="btn <?=$aDefLayout?><?=$aFortisConfig['layout'] == $aDefLayout ? ' active' : ''?>">
                    <input type="radio" name="switcher[layout]" value="<?=$aDefLayout?>"<?=$aFortisConfig['layout'] == $aDefLayout ? ' checked' : ''?> />
                    <i class="fa fa-check"></i> <?=$aDefLayoutName?>
                </label>
            <?endforeach?>
        </div>

    </div>

    <hr />

    <div class="item change-header-type">
        <div class="name">
            <?=GetMessage('DONINBIZ_FORTIS_FOOTER_22')?>
        </div>

        <div class="btn-group header-type" data-toggle="buttons">
            <label class="btn<?=$aFortisConfig['header_type'] == 1 ? ' active' : ''?>">
                <input type="radio" name="switcher[header_type]" value="1"<?=$aFortisConfig['header_type'] == 1 ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_23')?>
            </label>
            <label class="btn<?=$aFortisConfig['header_type'] == 2 ? ' active' : ''?>">
                <input type="radio" name="switcher[header_type]" value="2"<?=$aFortisConfig['header_type'] == 2 ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_24')?>
            </label>
        </div>
    </div>

    <hr />

    <div class="item change-header-color">
        <div class="name">
            <?=GetMessage('DONINBIZ_FORTIS_FOOTER_6')?>
        </div>

        <div class="btn-group header-color" data-toggle="buttons">
            <label class="btn light<?=$aFortisConfig['header_color'] == 'light' ? ' active' : ''?>">
                <input type="radio" name="switcher[header_color]" value="light"<?=$aFortisConfig['header_color'] == 'light' ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_7')?>
            </label>
            <label class="btn colored<?=$aFortisConfig['header_color'] == 'colored' ? ' active' : ''?>">
                <input type="radio" name="switcher[header_color]" value="colored"<?=$aFortisConfig['header_color'] == 'colored' ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_25')?>
            </label>
            <label class="btn dark<?=$aFortisConfig['header_color'] == 'dark' ? ' active' : ''?>">
                <input type="radio" name="switcher[header_color]" value="dark"<?=$aFortisConfig['header_color'] == 'dark' ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_8')?>
            </label>
        </div>
    </div>

    <hr />

    <div class="item change-home-zebra">
        <div class="name">
            <?=GetMessage('DONINBIZ_FORTIS_FOOTER_9')?>
        </div>

        <div class="btn-group header-color" data-toggle="buttons">
            <label class="btn left<?=$aFortisConfig['zebra'] == 1 ? ' active' : ''?>">
                <input type="radio" name="switcher[zebra]" value="1"<?=$aFortisConfig['zebra'] == 1 ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_10')?>
            </label>
            <label class="btn right<?=$aFortisConfig['zebra'] == 0 ? ' active' : ''?>">
                <input type="radio" name="switcher[zebra]" value="0"<?=$aFortisConfig['zebra'] == 0 ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_11')?>
            </label>
        </div>
    </div>

    <hr />

    <div class="item change-color">
        <div class="name">
            <?=GetMessage('DONINBIZ_FORTIS_FOOTER_12')?>
        </div>
        <div class="btn-group def-colors clearfix" data-toggle="buttons">
            <?foreach($aDefaultColors as $sDefColor):?>
                <label class="btn<?=$aFortisConfig['color'] == $sDefColor ? ' active' : ''?>">
                    <span><i class="fa fa-check"></i></span>
                    <input type="radio" name="switcher[color]" value="<?=$sDefColor?>"<?=$aFortisConfig['color'] == $sDefColor ? ' checked' : ''?> />
                </label>
            <?endforeach?>
        </div>

        <div class="custom-color clearfix">
            <label class="name" for="field-custom-color"><?=GetMessage('DONINBIZ_FORTIS_FOOTER_13')?></label>
            <input type="text" name="switcher[custom_color]" class="field-custom-color" id="field-custom-color"
                   value="<?=in_array($aFortisConfig['color'], $aDefaultColors) ? '' : $aFortisConfig['color']?>"
                    <?=in_array($aFortisConfig['color'], $aDefaultColors) ? '' : ' style="background: '.$aFortisConfig['color'].'"'?>
                />
            <a href="#" class="btn change-custom-color"><?=GetMessage('DONINBIZ_FORTIS_FOOTER_14')?></a>
        </div>

    </div>

    <hr />

    <div class="item change-sidebar-align">

        <div class="name"><?=GetMessage('DONINBIZ_FORTIS_FOOTER_15')?></div>

        <div class="btn-group sidebar-align" data-toggle="buttons">
            <label class="btn left<?=$aFortisConfig['sidebar_align'] == 'left' ? ' active' : ''?>">
                <input type="radio" name="switcher[sidebar_align]" value="left"<?=$aFortisConfig['sidebar_align'] == 'left' ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_16')?>
            </label>
            <label class="btn right<?=$aFortisConfig['sidebar_align'] == 'right' ? ' active' : ''?>">
                <input type="radio" name="switcher[sidebar_align]" value="right"<?=$aFortisConfig['sidebar_align'] == 'right' ? ' checked' : ''?> /><i class="fa fa-check"></i> <?=GetMessage('DONINBIZ_FORTIS_FOOTER_17')?>
            </label>
        </div>

    </div>

    <hr />

    <div class="item change-textures">

        <div class="name"><?=GetMessage('DONINBIZ_FORTIS_FOOTER_18')?></div>

        <div class="btn-group clearfix" data-toggle="buttons">
            <?foreach($aBackgrounds['textures'] as $iKT => $aTexture):?>
                <label class="btn<?=$sCurrentTexture == $aTexture['filename'] ? ' active' : ''?>">
                    <span><i class="fa fa-check <?=in_array($iKT, array(2, 3, 13, 14, 16, 18)) ? 's' : 'b'?>"></i></span>
                    <input type="radio" name="switcher[texture]" value="<?=$aTexture['filename']?>"<?=$sCurrentTexture == $aTexture['filename'] ? ' checked' : ''?> />
                </label>
            <?endforeach?>
        </div>

    </div>

    <hr />

    <div class="item change-images">

        <div class="name"><?=GetMessage('DONINBIZ_FORTIS_FOOTER_19')?></div>

        <div class="btn-group clearfix" data-toggle="buttons">
            <?foreach($aBackgrounds['images'] as $iKI => $aImage):?>
                <label class="btn<?=$sCurrentImage == $aImage['filename'] ? ' active' : ''?>">
                    <span><i class="fa fa-check <?=in_array($iKI, array(0, 7, 18, 21, 25, 26, 27, 28)) ? 'b' : 's'?>"></i></span>
                    <input type="radio" name="switcher[image]" value="<?=$aImage['filename']?>"<?=$sCurrentImage == $aImage['filename'] ? ' checked' : ''?> />
                </label>
            <?endforeach?>
        </div>

    </div>

    <hr />

    <div class="button-default">
        <a href="#" class="btn change-set-default"><?=GetMessage('DONINBIZ_FORTIS_FOOTER_20')?></a>
    </div>

    </form>
    </div><!-- /#theme-switcher -->

<?php } ?>