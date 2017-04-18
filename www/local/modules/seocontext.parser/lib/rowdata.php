<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 30.04.2016
 * Time: 12:41
 */

namespace Seocontext\Parser;

use Bitrix\Main\Entity;

class RowDataTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_parser_row_data';
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
                'PARSER_ID',
                [
                    'required' => true
                ]
            ),
            new Entity\TextField(
                'CONTENT'
            ),
            new Entity\ReferenceField(
                'PARSER',
                'Seocontext\Parser\SearchService',
                [
                    '=this.WEB_SITE_ID'=>'ref.ID'
                ]
            )
        ];
    }

}