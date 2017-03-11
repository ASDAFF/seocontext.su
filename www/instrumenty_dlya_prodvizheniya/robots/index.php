<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php"); ?>
    <pre>
    <?
    $APPLICATION->SetTitle("Теcтирование доступности страниц для индексирования");
    $ch = curl_init();
    if (is_set($_FILES['file'])) {
        $file = file($_SERVERS['DOCUMENT_ROOT'] . $_FILES['file']['tmp_name']);
        $file = array_filter($file, function ($url) use ($ch) {
            $url_arr = parse_url($url);
            $host = $url_arr['scheme'] . '://' . $url_arr['host'];
            $robot_url = $host . '/robots.txt';
            curl_setopt($ch, CURLOPT_URL, $robot_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            ob_start();
            curl_exec($ch);
            $robot = ob_get_clean();
            $robot = explode("\n", $robot);
            $robot = array_filter(
                $robot,
                function ($item) use ($url_arr) {
                    $path = str_replace('Disallow: ', '', $item);
                    return $path != $item &&
                    (str_replace($url_arr['path'], '', $item) != $item || str_replace($path, '', $url_arr['path']) != $url_arr['path']);
                });
            return count($robot) > 0;
        });

// завершение сеанса и освобождение ресурсов
        curl_close($ch);
        $file = implode($file);
        echo $file;
        file_put_contents('result.txt', $file);
        echo ' <a href="result.txt" >Скачать результат</a>';
    }
    ?>

    </pre>
    <div>
        <form method="POST" action="<?= POST_FORM_ACTION_URI ?>" enctype="multipart/form-data">
            <label for="file">Выберите файл для обработки</label>
            <input name="file" type="file" id="file">
            <input type="submit">
        </form>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>