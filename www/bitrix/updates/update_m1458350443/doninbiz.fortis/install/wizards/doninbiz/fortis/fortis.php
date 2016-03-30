<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class FortisWizard {

    private static
        $instance,
        $sTexturesBackgroundPath,
        $sImagesBackgroundPath,
        $aDefaultColors = array(
        '#19B7F1', '#7FBA00', '#ff00da', '#EA2325', '#ffa500', '#2baab1', '#808080', '#e05048', '#734ba9', '#000000'
    ),
        $aLayouts = array(
        'wide' => array(
            'name'    => 'DONINBIZ_LAYOUT_WIDE',
            'desc'    => 'DONINBIZ_LAYOUT_WIDE_DESC',
            's_img'   => 's_wide.jpg',
            'img'     => 'wide.jpg',
            'default' => 1
        ),
        'boxed' => array(
            'name'    => 'DONINBIZ_LAYOUT_BOXED',
            'desc'    => 'DONINBIZ_LAYOUT_BOXED_DESC',
            's_img'   => 's_boxed.jpg',
            'img'     => 'boxed.jpg',
            'default' => 0
        )
    );

    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        self::$sTexturesBackgroundPath = '/bitrix/wizards/doninbiz/fortis/site/templates/fortis/assets/img/bg/textures/';
        self::$sImagesBackgroundPath   = '/bitrix/wizards/doninbiz/fortis/site/templates/fortis/assets/img/bg/images/';
    }

    public function getColors() {
        return self::$aDefaultColors;
    }

    public function getDefaultColor() {
        return self::$aDefaultColors[0];
    }

    public function getTextures() {
        $aTexturesPath = glob($_SERVER["DOCUMENT_ROOT"] . self::$sTexturesBackgroundPath . "[!s_]*.{jpg,png,gif,bmp,jpeg}", GLOB_BRACE);
        asort($aTexturesPath, SORT_NATURAL);
        $aTextures = array();
        foreach ($aTexturesPath as $image) {
            $sourceImg = $image;
            $smallSourceImg = self::$sTexturesBackgroundPath.'s_'.basename($image);

            if ( ! file_exists($_SERVER["DOCUMENT_ROOT"] . $smallSourceImg)) {
                $smallSourceImg = $_SERVER["DOCUMENT_ROOT"] . $smallSourceImg;
                CFile::ResizeImageFile($sourceImg, $smallSourceImg, array('width' => 20, 'height' => 20), BX_RESIZE_IMAGE_EXACT);
            }

            $aTextures[] = array(
                'path' => self::$sTexturesBackgroundPath,
                'type' => 'texture',
                'filename' => basename($image),
                's_filename' => 's_'.basename($image)
            );
        }

        return $aTextures;
    }

    public function getDefaultTexture() {
        $aTextures = self::getTextures();
        return $aTextures[0];
    }

    public function getImages() {
        $aImagesPath = glob($_SERVER["DOCUMENT_ROOT"] . self::$sImagesBackgroundPath . "[!s_]*.{jpg,png,gif,bmp,jpeg}", GLOB_BRACE);
        asort($aImagesPath, SORT_NATURAL);
        $aImages = array();
        foreach ($aImagesPath as $image) {
            $sourceImg = $image;
            $smallSourceImg = self::$sImagesBackgroundPath.'s_'.basename($image);

            if ( ! file_exists($_SERVER["DOCUMENT_ROOT"] . $smallSourceImg)) {
                $smallSourceImg = $_SERVER["DOCUMENT_ROOT"] . $smallSourceImg;
                CFile::ResizeImageFile($sourceImg, $smallSourceImg, array('width' => 50, 'height' => 50));
            }

            $aImages[] = array(
                'path' => self::$sImagesBackgroundPath,
                'type' => 'image',
                'filename' => basename($image),
                's_filename' => 's_'.basename($image)
            );
        }

        return $aImages;
    }

    public function getDefaultImage() {
        $aImages = self::getImages();
        return $aImages[0];
    }

    public function getData() {
        return array(
            'colors'   => self::getColors(),
            'textures' => self::getTextures(),
            'images'   => self::getImages()
        );
    }

    public function getLayouts() {
        return self::$aLayouts;
    }

}


class FortisUpdate {

    private $sSiteId = '';
    private $sSiteDir = '';

    private $sTemplatePath = '';
    private $sPublicPath = '';
    private $sComponentsPath = '';

    private $sMasterTemplatePath = '';
    private $sMasterPublicPath = '';
    private $sMasterComponentsPath = '';

    private $file_version = '.last_version_template';

    private $aVersions = array(
        '1.0.0' => 'N',
        '1.0.1' => array(
            'template' => array(
                'header.php', 'footer.php',
                'assets/css/common.css', 'assets/css/common.less',
                'assets/js/common.js', 'assets/js/navs.js', 'assets/js/plugins.js', 'assets/js/vendor/ion.rangeSlider.min.js', 'assets/js/vendor/jquery.min.js',
                'components/bitrix/menu/general_horizontal_multilevel/template.php'
            ),
            'public' => 'N',
            'components' => 'N',
        ),
        '1.0.2' => array(
            'template' => array(
                'header.php'
            ),
            'public' => 'N',
            'components' => 'N',
        ),
        '1.0.3' => array(
            'template' => array(
                'header.php', 'footer.php',
                'assets/css/common.css', 'assets/css/common.less',
                'assets/js/navs.js', 'assets/js/plugins.js'
            ),
            'public' => 'N',
            'components' => 'N',
        ),
        '1.0.4' => array(
            'template' => array(
                'style-switcher.php', 'header.php', 'lang', 'headers',
                'components/bitrix/menu/general_horizontal_multilevel_1', 'components/bitrix/menu/general_horizontal_multilevel_2',
                'assets/css/colored.less', 'assets/css/common.css', 'assets/css/common.less', 'assets/css/dark.less', 'assets/css/headers.less', 'assets/css/vars.less',
                'assets/css/theme-switcher.css', 'assets/css/theme-switcher.less',
                'assets/js/common.js', 'assets/js/navs.js', 'assets/js/plugins.js', 'assets/js/theme-switcher.js',
            ),
            'public' => array(
                'includes/company_logo_right.php', 'includes/company_logo_slogan.php'
            ),
            'components' => array(
                'catalog.items.slider', 'catalog.services', 'home.slider', 'menu.sections.elements', 'order_forms'
            ),
        ),
        '1.0.5' => array(
            'template' => array(
                'assets/css/common.css', 'assets/css/common.less', 'assets/css/headers.less',
                'assets/js/plugins.js'
            ),
            'components' => array(
                'home.slider'
            ),
        )
    );
    private $sCurVersion = '';
    private $sLastVersion = '';

    public function __construct($sSiteId, $sWisSiteDir) {

        $this->sSiteId = $sSiteId;
        $this->sSiteDir = $sWisSiteDir;

        $this->sPublicPath = str_replace(array("///", "//"), "/", $_SERVER["DOCUMENT_ROOT"] . '/' . $this->sSiteDir . '/');
        $this->sTemplatePath = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/templates/fortis_'.$this->sSiteId.'/';
        $this->sComponentsPath = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/doninbiz/';

        $sWisGetPath = '/bitrix/modules/doninbiz.fortis/install/wizards/doninbiz/fortis/';
        $this->sMasterPublicPath = $_SERVER["DOCUMENT_ROOT"] . $sWisGetPath . 'site/public/ru/';
        $this->sMasterTemplatePath = $_SERVER["DOCUMENT_ROOT"] . $sWisGetPath . 'site/templates/fortis/';
        $this->sMasterComponentsPath = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/doninbiz.fortis/install/components/doninbiz/';

        $this->sCurVersion = $this->getTemplateVersion();
        $aVersions = $this->aVersions;
        end($aVersions);
        $this->sLastVersion = key($aVersions);

        /*var_dump($this->sMasterPublicPath);
        var_dump($this->sMasterTemplatePath);
        var_dump($this->sMasterComponentsPath);
        echo '<hr />';
        var_dump($this->sPublicPath);
        var_dump($this->sTemplatePath);
        var_dump($this->sComponentsPath);
        exit;*/
    }

    public function setLastVersion() {
        RewriteFile($this->sTemplatePath . $this->file_version, $this->getLastVersion());
    }

    public function setSiteId($sSiteId) {
        $this->sSiteId = $sSiteId;
    }

    public function getSiteId() {
        return $this->sSiteId;
    }
    public function getCurVersion() {
        return $this->sCurVersion;
    }
    public function getLastVersion() {
        return $this->sLastVersion;
    }

    /**
     * Проверка обновлений
     *
     * @return array|bool
     */
    public function check() {

        if ( ! $this->sCurVersion || ! $this->sLastVersion)
            return false;

        if ($this->sCurVersion == $this->sLastVersion)
            return false;

        $aVersions = $this->aVersions;
        foreach ($aVersions as $version => $list_files) {
            if ($this->sCurVersion != $version) {
                unset($aVersions[$version]);
                continue;
            }
            unset($aVersions[$version]);
            return $aVersions;
        }

        return false;
    }

    /**
     * Процесс обновления
     *
     * @return bool
     */
    public function process() {
        if ( ! ($aVersions = $this->check())) {
            return false;
        }

        $status = false;

        foreach ($aVersions as $ver => $list) {

            // Замена файлов и директорий
            $this->replaceFiles($list);

            // Запросы к БД и прочие изменения
            switch($ver) {
                case '1.0.1':

                break;
                case '1.0.2':

                break;
                case '1.0.3':

                break;
                case '1.0.4':

                break;
                case '1.0.5':

                break;
            }

            RewriteFile($this->sTemplatePath . $this->file_version, $ver);
            $status = true;
        }

        return $status;
    }

    private function replaceFiles($list) {
        if ( ! empty($list) && is_array($list)) {
            foreach($list as $type => $files) {
                $type = ucfirst($type);
                //echo '<br><br>' . $type . '<br><br>';
                if ( ! empty($files) && is_array($files)) {
                    foreach ($files as $file_or_dir) {
                        $from = $this->{"sMaster{$type}Path"} . $file_or_dir;
                        $to = $this->{"s{$type}Path"} . $file_or_dir;
                        //echo 'copy ' . $from . ' to ' . $to . '<br />';
                        //echo 'copy ' . file_exists($from) . ' to ' . file_exists($to) . '<br /><br />';

                        if (file_exists($from)) {
                            CopyDirFiles($from, $to, true, true);
                        }
                    }
                }
            }
        }
    }

    /**
     * Получает версию решения
     *
     * @return bool|string
     */
    public function getTemplateVersion() {
        $filename = $this->sTemplatePath . $this->file_version;

        if ( ! is_dir($this->sTemplatePath))
            return false;

        if ( ! file_exists($filename)) {
            $fp = fopen($filename, 'w');
            fwrite($fp, '1.0.0');
            fclose($fp);
        }

        $handle   = fopen($filename, "r");
        $version  = fread($handle, filesize($filename));
        fclose($handle);

        return !empty($version) ? trim($version) : false;
    }

}