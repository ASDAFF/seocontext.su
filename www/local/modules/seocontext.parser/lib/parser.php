<?php
/**
 * Created by PhpStorm.
 * User: shara
 * Date: 30.04.2016
 * Time: 20:36
 */

namespace Seocontext\Parser;

use Bitrix\Main\Type\DateTime;
use Seocontext\Parser\WebSiteTable;
use Seocontext\Parser\RowDataTable;
use Bitrix\Bizproc\BaseType\Date;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;

use phpQuery;


class ParserTable extends Entity\DataManager
{
    public static function getTableName()
    {
        return 'seocontext_parser';
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
            new Entity\IntegerField(
                'WEBSITE_ID',
                [
                    'required' => true
                ]
            ),
            new Entity\IntegerField(
                'SEARCH_QUERY_ID',
                [
                    'required' => true
                ]
            ),

            new Entity\ReferenceField(
                'SEARCH_QUERY',
                'Seocontext\Parser\SearchQuery',
                [
                    '=this.SEARCH_QUERY_ID' => 'ref.ID'
                ]
            ),
            new Entity\ReferenceField(
                'WEB_SITE',
                'Seocontext\Parser\SearchService',
                [
                    '=this.WEB_SITE_ID' => 'ref.ID'
                ]
            )
        ];
    }

}

class Parser
{
    private $web_site;
    private $search_query;
    private $id;
    private $last_date;

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
    public function getWebsite()
    {
        return $this->web_site;
    }
    /**
     * @return mixed
     */
    public function getSearchQuery()
    {
        return $this->search_query;
    }

    /**
     * @return mixed
     */
    public function getHtmlSelector()
    {
        return $this->html_selector;
    }

    public function process()
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . '/local/modules/seocontext.parser/lib/phpQuery.php');
        require_once($_SERVER["DOCUMENT_ROOT"] . '/local/modules/seocontext.parser/lib/rowdata.php');


        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' =>  "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n"

            )
        );

        $context = stream_context_create($opts);

        $html = file_get_contents($this->web_site->getUrl() . $this->search_query->getSentence(), false, $context);
/*        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/test.html',$html);*/
        $document = phpQuery::newDocument($html);
        $selector = $this->html_selector;
        $row_data = $document->find("code#u_0_h");
        foreach ($row_data as $el){
            $el=pq($el);
            $el->html(str_replace('-->','',str_replace('<!--','',$el->html())));

        }
        $this->last_date = new Type\DateTime();
        $result=RowDataTable::add(
            [
                'PARSER_ID' => $this->id,
                'DATE' => $this->last_date,
                'CONTENT' => $row_data->html(),
            ]

        );
        if ($result->isSuccess())
        {
            echo $row_data->html();
        }
        return $row_data;
    }

    public function __construct(WebSite $web_site,SearchQuery $search_query,$selector)
    {
        require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/seocontext.parser/lib/website.php");
        require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/seocontext.parser/lib/searchquery.php");

        $this->web_site=$web_site;
        $this->search_query=$search_query;
        $this->html_selector=$selector;

        $dbParser = ParserTable::getList(
            [
                'filter' => [
                    'WEBSITE_ID' => $web_site->getId(),
                    'SEARCH_QUERY_ID' => $search_query->getId(),
                ],
            ]
        );
        if(!$arParser = $dbParser->fetch()){
            $result=ParserTable::add(
                [
                    'WEBSITE_ID' => $web_site->getId(),
                    'SEARCH_QUERY_ID' => $search_query->getId(),
                ]
            );
            if($result->isSuccess()){
                $this->id=$result->getId();
            }
        }
        else {
            $this->id=$arParser['ID'];
        }
    }
}