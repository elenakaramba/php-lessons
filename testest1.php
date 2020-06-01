<?php
function CountWordsFrequency(string $url): array
{
    $text = file_get_contents($url);
    $text = mb_convert_encoding($text, "UTF-8", "cp1251");
    $delimeter = [" ", ".", ",", "!", ":", ";", "[", "]", "(", "0"];
    $replace = str_ireplace($delimeter, " ", $text);
    $explode = explode(" ", $replace);
    foreach ($explode as $idx => $word) {
        if (!empty($word)) {
            $word = trim($word);
        } else {
            unset($explode[$idx]);
        }
    }
    $wordFrequency = [];
    foreach ($explode as $word) {
        if (array_key_exists($word, $explode)) {
            $wordFrequency[$word] = $wordFrequency[$word] + 1;
        } else {
            $wordFrequency[$word] = 1;
        }
    }
    return ($wordFrequency);
}


function filterWords(array &$wordFrequncy): void
{
    $expression = "/^[А-Яа-яЁё]+$/u";
    foreach ($wordFrequncy as $word => $count) {
        if (!preg_match_all($expression, $word)) {
            unset($wordFrequncy[$word]);
        }
    }
}

function printWordFrequency(array $wordFrequency): void
{
    echo "<table>";
    foreach ($wordFrequency as $word => $count) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($word) . "</td>";
        echo "<td>" . $count . "</td>";
        echo "</tr>";
    }
    echo "<table>";
}


function fetchUrl(mysqli $conn, string $url): ?int
{
    $result = $conn->query("select * from web_url where web_url = '$url'");
    if ($result->num_rows === 0) {
        return 0;
    }
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $return = $rows[0]["id"];
}
