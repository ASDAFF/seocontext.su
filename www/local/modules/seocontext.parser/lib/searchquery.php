<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 18.04.2016
 * Time: 9:50
 */

namespace Seocontext\Parser;

use Bitrix\Iblock\SequenceTable;
use Bitrix\Main\Entity;

class SearchQueryTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_parser_search_query';
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
                'SENTENCE',
                [
                    'required' => true,
                    'validation' => function () {
                        return [
                            new Entity\Validator\Unique()
                        ];
                    }
                ]
            )
        ];
    }
}

class SearchQuery
{
    private $id;
    private $sentence;


    public function __construct($sentence)
    {
        $this->sentence=$sentence;
        $dbSearchQuery=SearchQueryTable::getList(
            [
                'filter'=> [
                    'SENTENCE'=>$sentence
                ]
            ]
        );
        if(!$query=$dbSearchQuery->fetch()){
            $result=SearchQueryTable::add(
                [
                    'SENTENCE' =>$sentence
                ]
            );
            if($result->isSuccess())
                $this->id=$result->getId();
            }
        else {
            $this->id=$query['ID'];
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getSentence()
    {
        return $this->sentence;
    }



}