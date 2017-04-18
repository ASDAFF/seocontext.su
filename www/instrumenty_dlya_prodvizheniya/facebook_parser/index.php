<?
use Seocontext\Parser\Parser;
use Seocontext\Parser\WebSite;
use Seocontext\Parser\SearchQuery;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/seocontext.parser/lib/parser.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/seocontext.parser/lib/website.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/local/modules/seocontext.parser/lib/searchquery.php");
$APPLICATION->SetTitle("Facebook parser");

if(is_set($request->get('tel'))) {
    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $from = (int)($request->get('tel')) - 50;
    $to = (int)($request->get('tel')) + 50;
}
$web_site = new WebSite(
    'https://www.facebook.com/search/top/?init=quick&q=',
    ''
);

for ($phone_number=$from;$phone_number<$to;$phone_number++){
    $query=new SearchQuery('+'.$phone_number);
    $parser = new Parser($web_site, $query);
    $parser->process();
}


?>
    <form action="<?= POST_FORM_ACTION_URI ?>">
        <input type="tel" name="tel">
        <input type="submit">
    </form>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>