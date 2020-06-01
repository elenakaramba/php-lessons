<?php

include_once __DIR__ . "/../functions.php";

function countWordsFrequency(string $url): array
{
    //Читает содержимое файла в строку
    $text = file_get_contents($url);
    //Преобразует кодировку символов
    $text = mb_convert_encoding($text, "UTF-8", "cp1251");
    $delimiter = [" ", ",", ".", "'", ";", ":", "[", "]"];
    //Заменяет все вхождения строки поиска на строку замены
    $replace = str_replace($delimiter, " ", $text);
    //Разбивает строку с помощью разделителя
    $explode = explode(" ", $replace);
    foreach ($explode as $idx => $word) {
        // Удаляет пробелы (или другие символы) из начала и конца строки
        $word = trim($word);

        if (empty($word)) {
            //удаляет перечисленные переменные
            unset($explode[$idx]);
        }
    //? прчему мы тут не сохраняем отсортированный массив $explode?
    }

    $wordFrequency = [];
    //? это массив после удаления пробелов и пустых значений ключей?
    foreach ($explode as $word) {
        //Проверяет, присутствует ли в массиве указанный ключ или индекс
        if (array_key_exists($word, $wordFrequency)) {
            $wordFrequency[$word] = $wordFrequency[$word] + 1;
        } else {
            $wordFrequency[$word] = 1;
        }
    }

    return $wordFrequency;
}

//знак & оначает не создавать копию массива а изменять существующий(текущий)
function filterWords(array &$wordFrequency): void
{
    $expression = "/^[А-Яа-яЁё]+$/u";
    //? почему тут указан еще и значение? мы же проверяем только по ключу и удаляем по ключу
    foreach ($wordFrequency as $word => $count) {
        if (!preg_match($expression, $word)) {
            unset($wordFrequency[$word]);
        }
    }
}


//указать типы переменных mysqli, string. Возвращает либо int либо null
//функция извлекает url id из базы
function fetchUrl(mysqli $conn, string $url): ?int
{
    $result = $conn->query("select * from web_url where web_url = '$url'");
    if ($result->num_rows === 0) {
        return null;
    }
    // Выбирает все строки из результирующего набора и помещает их в ассоциативный массив, обычный массив или в оба
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows[0]["id"];
}

function fetchWords(mysqli $conn, int $urlId, int $page): array
{
    $rowsOnPage = 20;
    $offset = ($page - 1) * $rowsOnPage;
    $result = $conn->query("select * from word_frequency where url_id = $urlId order by frequency desc limit $rowsOnPage offset $offset");
    if ($result->num_rows === 0) {
        return [];
    }
    $words = [];
    //Извлекает результирующий ряд в виде ассоциативного массива
    while ($row = $result->fetch_assoc()) {
        $words[$row["word"]] = $row["frequency"];
    }
    return $words;
}

function saveWords(mysqli $conn, string $url, array $wordFrequency): int
{
    $conn->query("insert into web_url (web_url) values ('$url')");
    $urlId = $conn->insert_id;
    foreach ($wordFrequency as $word => $count) {
        $conn->query("insert into word_frequency (word, frequency, url_id) values ('$word', $count, $urlId)");
    }
    return $urlId;
}

function pageNavigation(mysqli $conn, int $page, int $urlId) : array
{
    $query = "select count(*) as wordCount from word_frequency where url_id = $urlId ";
    $countedResult = $conn->query($query);
    if ($countedResult === false) {
        $lastError = $conn->error;
        echo $lastError;
    }
    $count = $countedResult->fetch_all(MYSQLI_ASSOC);
    $count = (int)$count[0]["wordCount"];

    $pageCount = ceil($count / 20);

    /*if ($page != 1) {
        $prev = $page - 1;
        echo "<a href=\"?url=$url&page=$prev\">Previous</a> ";
    }
    if ($page < $pageCount) {
        $nextPage = $page + 1;
        echo "<a href=\"?url=$url&page=$nextPage\">Next</a> ";
    }
    echo "Pages -  $pageCount"; */
    return [
        "pageCount"=>$pageCount,
        "page"=>$page
    ];

}

$conn = new mysqli("localhost", "dblinda", "ldko(8Nyd!fg", "test1");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$wordFrequency = null;
$pageData = null;
$url = null;

if (array_key_exists("url", $_GET)) {
    $url = $_GET["url"];
    if (!empty($url)) {
        $urlId = fetchUrl($conn, $url);
        if ($urlId === null) {
            //вызов функции countWordsFrequency с параметром url и возвращает массив
            $wordFrequency = countWordsFrequency($url);
            filterWords($wordFrequency);
            //вывод в виде таблицы значения слова и сколько повторений
            arsort($wordFrequency);
            $urlId = saveWords($conn, $url, $wordFrequency);
        }
        if (isset($_GET['page'])) {
            $page = intval($_GET['page']);
            if ($page <= 0) {
                $page = 1;
            }
        } else {
            $page = 1;
        }
        $wordFrequency = fetchWords($conn, $urlId, $page);
        $pageData = pageNavigation($conn, $page, $urlId);

       /* echo renderTemplate("template.php", [
            'wordFrequency' => $wordFrequency,
            'pageData'=>$pageData,
            'url'=>$url
        ]);*/



    }
}
echo renderTemplate("template.php", [
            'wordFrequency' => $wordFrequency,
            'pageData'=>$pageData,
            'url'=>$url
        ]);





