<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$iFileID = !empty($_GET['file']) ? intval($_GET['file']) : false;

if ($iFileID) {
    $rsFile = CFile::GetByID($iFileID);
    $arFile = $rsFile->Fetch();

    if ($arFile) {
        $sFileName = rus2translit($arFile["ORIGINAL_NAME"]);

        // тип файла
        $sFileType = $arFile["CONTENT_TYPE"];

        // путь к файлу
        $file = CFile::GetPath($iFileID);
        $file = $_SERVER['DOCUMENT_ROOT'] . $file;

        // Set headers
        header("Content-Disposition: attachment; filename=" . $sFileName);
        header("Content-Description: File Transfer");
        header('Content-Length: ' . $arFile['FILE_SIZE']);
        header("Content-Type: $sFileType");
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        // Read the file from disk
        readfile($file);

        exit;
    }
}

header("HTTP/1.0 404 Not Found");
echo "<h1>File Not Found</h1>";
exit;

function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => "_",  'ы' => 'y',   'ъ' => "_",
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => "_",  'Ы' => 'Y',   'Ъ' => "_",
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    return strtr($string, $converter);
}