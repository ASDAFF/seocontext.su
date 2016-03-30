<?if (!isset($_GET["referer1"]) || strlen($_GET["referer1"])<=0) $_GET["referer1"] = "yandext"?><? $strReferer1 = htmlspecialchars($_GET["referer1"]); ?><?if (!isset($_GET["referer2"]) || strlen($_GET["referer2"])<=0) $_GET["referer2"] = "";?><? $strReferer2 = htmlspecialchars($_GET["referer2"]); ?><? header("Content-Type: text/xml; charset=windows-1251");?><?echo "<?xml version=\"1.0\" encoding=\"windows-1251\"?>"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="2016-03-30 22:20">
<shop>
<name></name>
<company></company>
<url>http://</url>
<platform>1C-Bitrix</platform>
<currencies>
<currency id="RUB" rate="1" />
<currency id="USD" rate="64.36" />
<currency id="EUR" rate="70.6" />
<currency id="UAH" rate="2.769" />
<currency id="BYR" rate="0.00369" />
</currencies>
<categories>
<category id="56">Готовые решения</category>
<category id="55">Лецензии 1С-Битрикс</category>
<category id="57" parentId="55">1C-Битрикс: Управление сайтом</category>
<category id="58" parentId="55">1C-Битрикс24 (Корпоративный портал)</category>
</categories>
<offers>
<offer id="179" available="true">
<url>http:///katalog/letsenzii-1s-bitriks/1c-bitriks-upravlenie-saytom/pervyy-sayt/?r1=<?echo $strReferer1; ?>&amp;r2=<?echo $strReferer2; ?></url>
<price>1990</price>
<currencyId>RUB</currencyId>
<categoryId>57</categoryId>
<name>Первый сайт</name>
<description></description>
</offer>
</offers>
</shop>
</yml_catalog>
