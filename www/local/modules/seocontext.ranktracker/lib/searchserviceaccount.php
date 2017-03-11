<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 30.04.2016
 * Time: 16:22
 */

namespace Seocontext\Ranktracker;


use Bitrix\Main\Entity;

class SearchServiceAccountTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_search_service_account';
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
            new Entity\IntegerField(
                'SERVICE_ID',
                [
                    'required' => true
                ]
            ),
            new Entity\StringField(
                'CONNECTION_STRING',
                [
                    'required' => true
                ]
            ),
            new Entity\ReferenceField(
                'SEARCH_SERVICE_ID',
                'Seocontext\Ranktracker\SearchService',
                [
                    '=this.SEARCH_SERVICE_ID' =>'ref.ID'
                ]
            )
        ];
    }
}