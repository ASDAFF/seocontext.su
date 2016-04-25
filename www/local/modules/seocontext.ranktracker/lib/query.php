<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 18.04.2016
 * Time: 9:50
 */

namespace Seocontext\Ranktracker;

use Bitrix\Main\Entity;

class QueryTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_ranktracker_query';
    }

    public static function getMap()
    {
        return [
            new Entity\IntegerField("ID", ['primary' => true, 'autocomplete' => true]),
            new Entity\StringField('SENTENCE', ['required' => true]),
        ];
    }
}