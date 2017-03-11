<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 18.04.2016
 * Time: 9:47
 */

namespace Seocontext\Ranktracker;

use Bitrix\Main\Entity;

class WebSiteTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_site';
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
            new Entity\IntegerField('REQUEST_LIMIT'),
            new Entity\StringField(
                'WEBROOT',
                [
                    'required' => true,
                    'validation' => function () {
                        return [
                            new Entity\Validator\Unique(),
                            function ($arg) {
                                return filter_var($arg, FILTER_VALIDATE_URL);
                            }
                        ];
                    }
                ]
            ),
        ];
    }

}/* public $request_limit;
    public $domain;
    public $queries;
    public $search_services;*/
