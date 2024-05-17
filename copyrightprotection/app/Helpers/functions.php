<?php

function splitStringByMultipleDelimiters($string, $delimiters) {
    $result = [];
    $word = '';

    foreach (str_split($string) as $char) {
        if (in_array($char, $delimiters)) {
            if (strlen($word) > 0) {
                $result[] = $word;
            }
            $word = '';
        } else {
            $word .= $char;
        }
    }

    if (strlen($word) > 0) {
        $result[] = $word;
    }

    return $result;
}

/** Shows that current page is loaded from $urlToCheck
 * @param $urlToCheck
 * @return bool
 */
function isCurrentPage($urlToCheck) {
    $urlToCheckShort = str_replace(url('/'), '', $urlToCheck);
    $urlToCheckItems = splitStringByMultipleDelimiters($urlToCheckShort, ['/', '?', '&']);

    $currentPageItems = splitStringByMultipleDelimiters($_SERVER['REQUEST_URI'], ['/', '?', '&']);

    $result = true;

    foreach ($urlToCheckItems as $item) {
        if (!in_array($item, $currentPageItems)) {
            $result = false;
            break;
        }
    }

    return $result;
}

function months() {
    return [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];
}
