<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 18.04.2016
 * Time: 9:47
 */

namespace Seocontext\Ranktracker;

use Bitrix\Main\Entity;

class SiteTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_site';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField("ID",['primary'=>true,'autocomplete'=>true]),
            new Entity\IntegerField('REQUEST_LIMIT'),
            new Entity\StringField('DOMAIN',['required'=>true]),
            new Entity\ReferenceField()
        ];

    }

   
}/* public $request_limit;
    public $domain;
    public $queries;
    public $search_services;*/