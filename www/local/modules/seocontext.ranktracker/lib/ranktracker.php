<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 30.04.2016
 * Time: 20:36
 */

namespace Seocontext\Ranktracker;


use Bitrix\Main\Entity;

class RankTrackerTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_rank_tracker';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true
                ]
            ),
            new Entity\DatetimeField(
                'DATE',
                [
                    'required' => true
                ]
            ),
            new Entity\IntegerField(
                'SEARCH_SERVICE_ID',
                [
                    'required' => true,
                ]
            ),
            new Entity\IntegerField(
                'SEARCH_QUERY_ID',
                [
                    'required' => true
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
           
        ];
    }

}