<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 30.04.2016
 * Time: 12:41
 */

namespace Seocontext\Ranktracker;

use Bitrix\Main\Entity;

class OrganicSearchResultTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_search_result';
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
            new Entity\DatetimeField(
                'DATE',
                [
                    'required' => true
                ]
            ),
            new Entity\IntegerField(
                'SEARCH_SEVICE_ACCOUNT_ID'
            ),
            new Entity\TextField(
                'CONTENT'
            ),
            new Entity\ReferenceField(
                'SEARCH_SERVICE_ACCOUNT',
                'Seocontext\Ranktracker\SearchServiceAccount',
                [
                    '=this.SEARCH_SEVICE_ACCOUNT_ID'=>'ref.ID'
                ]
            )
        ];
    }

}