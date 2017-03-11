<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 18.04.2016
 * Time: 9:53
 */

namespace Seocontext\Ranktracker;

use Bitrix\Main\Entity;

class SearchServiceTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_search_service';
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
                            new Entity\Validator\Unique(),
                            function ($arg) {
                                return filter_var($arg, FILTER_VALIDATE_URL);
                            }
                        ];
                    }
                ]
            ),
            new Entity\StringField(
                'NAME'
            ),
        ];
    }

    /* private $adaptor;
 
     public function request($adaptor, $query)
     {
         return $adaptor($query);
     }*/
}