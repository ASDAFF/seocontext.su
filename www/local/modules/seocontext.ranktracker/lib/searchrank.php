<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 30.04.2016
 * Time: 13:14
 */

namespace Seocontext\Ranktracker;


use Bitrix\Main\Entity;

class SearchRankTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_search_rank_table';
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
                'SEARCH_QUERY_ID',
                [
                    'required' => true,
                ]
            ),
            new Entity\IntegerField(
                'SEARCH_SERVICE_ID',
                [
                    'required' => true,
                ]
            ),
            new Entity\IntegerField(
                'WEB_SITE_ID',
                [
                    'required' => true,
                ]
            ),
            new Entity\ReferenceField(
                'SEARCH_SERVICE',
                'Seocontext\Ranktracker\SearchService',
                [
                    '=this.SEARCH_SERVICE_ID'=>'ref.ID'
                ]
            ),
            new Entity\ReferenceField(
                'SEARCH_QUERY',
                'Seocontext\Ranktracker\SearchQuery',
                [
                    '=this.SEARCH_QUERY_ID'=>'ref.ID'
                ]
            ),
            new Entity\ReferenceField(
                'USER',
                'Bitrix\Main\User',
                [
                    '=this.SEARCH_SERVICE_ID'=>'ref.ID'
                ]
            )
        ];

    }
}