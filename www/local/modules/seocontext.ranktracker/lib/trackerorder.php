<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 03.05.2016
 * Time: 14:39
 */

namespace Seocontext\Ranktracker;


use Bitrix\Main\Entity;

class TrackerOrderTable extends Entity\DataManager
{
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
            new Entity\IntegerField(
                'USER_ID',
                [
                    'required' => true
                ]
            ),
            new Entity\ReferenceField(
                'USER',
                'Bitrix\Main\User',
                [
                    '=this.SEARCH_SERVICE_ID' => 'ref.ID'
                ]
            ),
            new Entity\IntegerField(
                'TRACKER_ID',
                [
                    'required'=>true,
                ]
            ),
            new Entity\ReferenceField(
                'TRACKER',
                '\Seocontext\Ranktracker\RankTracker',
                [
                    '=this.TRACKER_ID' => 'ref.ID'
                ]
            ),

        ];
    }


}