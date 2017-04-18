<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 18.04.2016
 * Time: 9:47
 */

namespace Seocontext\Parser;

use Bitrix\Main\Entity;

class WebSiteTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_parser_site';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField(
                "ID",
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Entity\StringField(
                'URL',
                [
                    'required' => true,
                    'validation' => function () {
                        return [
                            new Entity\Validator\Unique()/*,
                            function ($arg) {
                                return filter_var($arg, FILTER_VALIDATE_URL);
                            }*/
                        ];
                    }
                ]
            ),
            new Entity\StringField('HTML_SELECTOR')
        ];
    }
}

class WebSite
{
    private $id;
    private $url;
    private $html_selector;

    public function __construct($url,$selector)
    {
        $this->url=$url;
        $this->html_selector=$selector;

        $dbWebSite = WebSiteTable::getList(
            [
                'filter' => [
                    'URL' => $url,
                    'HTML_SELECTOR' => $selector
                ]
            ]
        );

        if (!$arWebSite = $dbWebSite->fetch()) {
            AddMessage2Log($url);

            $result = WebSiteTable::add(
                [
                    'URL' => $url,
                    'HTML_SELECTOR' => $selector
                ]
            );
            if ($result->isSuccess()) {

                $this->id = $result->getId();
            }
        }
        else {
            $this->id=$arWebSite['ID'];
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getHtmlSelector()
    {
        return $this->html_selector;
    }

}