<?php

function upload_file($file, $upload_dir= '../files/photos', $allowed_types= array('image/png','image/x-png','image/jpeg','image/webp','image/gif'))
{

    $blacklist = array(".php", ".phtml", ".php3", ".php4");

    $upload_dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . $upload_dir . DIRECTORY_SEPARATOR; // Место, куда будут загружаться файлы.
    $max_filesize = 8388608; // Максимальный размер загружаемого файла в байтах (в данном случае он равен 8 Мб).
    $prefix = date('Ymd-is_');
    $filename = $file['name']; // В переменную $filename заносим точное имя файла.

    $filename = lat($filename);

    $ext = substr($filename, strrpos($filename, '.'), strlen($filename) - 1); // В переменную $ext заносим расширение загруженного файла.
    if (in_array($ext, $blacklist)) {
        return array('error' => 'Запрещено загружать исполняемые файлы');
    }

    if (!is_writable($upload_dir))  // Проверяем, доступна ли на запись папка, определенная нами под загрузку файлов.
        return array('error' => 'Невозможно загрузить файл в папку "' . $upload_dir . '". Установите права доступа - 777.');

    if (!in_array($file[ 'type' ], $allowed_types))
        return array('error' => 'Данный тип файла не поддерживается.');

    if (filesize($file[ 'tmp_name' ]) > $max_filesize)
        return array('error' => 'файл слишком большой. максимальный размер ' . intval($max_filesize / (1024 * 1024)) . 'мб');

    if (!move_uploaded_file($file[ 'tmp_name' ], $upload_dir . $prefix . $filename)) // Загружаем файл в указанную папку.
        return array('error' => 'При загрузке возникли ошибки. Попробуйте ещё раз.');

    return Array('filename' => $prefix . $filename);
}

function lat($st)
{
    $char=array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','з'=>'z','и'=>'i',
        'й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t',' '=>'_',
        'у'=>'u','ф'=>'f','х'=>'h',"'"=>'','ы'=>'i','э'=>'e','ж'=>'zh','ц'=>'ts','ч'=>'ch','ш'=>'sh',
        'щ'=>'j','ь'=>'','ю'=>'yu','я'=>'ya','Ж'=>'ZH','Ц'=>'TS','Ч'=>'CH','Ш'=>'SH','Щ'=>'J',
        'Ь'=>'','Ю'=>'YU','Я'=>'YA','ї'=>'i','Ї'=>'Yi','є'=>'ie','Є'=>'Ye','А'=>'A','Б'=>'B','В'=>'V',
        'Г'=>'G','Д'=>'D','Е'=>'E','Ё'=>'E','З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
        'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ъ'=>"'",'Ы'=>'I','Э'=>'E');
    $st=strtr($st,$char);
return $st;
}