<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Транслитератор");
?>

    <script type="text/javascript" src="/js/jquery.liTranslit.js"></script>
    <script>
        $(function(){
            $('.demo2_text').liTranslit({
                elAlias: $('.demo2_translit')
            });
        });
    </script>
    <textarea type="text" class="demo2_text"></textarea>
    <textarea type="text" class="demo2_translit"></textarea>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>