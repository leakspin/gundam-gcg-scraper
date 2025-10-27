<?php

const URL = 'https://www.gundam-gcg.com/en/cards/detail.php';
const LOCAL = false;

function fetchCardData() {
    if (LOCAL) {
        $html = file_get_contents('card.html');
    } else {
        $html = file_get_contents(URL);
    }

    if ($html === false) {
        throw new Exception("No Detaiils :(");
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $cardData = [];

    // Extract card name
    $details = $xpath->query('//div[@class="cardDetailPageContent"]');

    foreach ($details as $detail) {
        preg_match('/([a-zA-Z\-\d\_]+)\.webp/', $xpath->query('.//div[@class="cardImage"]/img', $detail)->item(0)->getAttribute('src'), $idMatch);
        $description = trim($xpath->query('.//div[@class="dataTxt isRegular"]', $detail)->item(0)->textContent);
        $cardData[] = [
            'id' => $idMatch[1],
            'number' => trim($xpath->query('.//div[@class="cardNo"]', $detail)->item(0)->textContent),
            'name' => trim($xpath->query('.//h1[@class="cardName"]', $detail)->item(0)->textContent),
            'rarity' => str_replace(' ', '', trim($xpath->query('.//div[@class="rarity"]', $detail)->item(0)->textContent)),
            'image' => str_replace('..', 'https://www.gundam-gcg.com/en', $xpath->query('.//div[@class="cardImage"]/img', $detail)->item(0)->getAttribute('src')),
            'level' => trim($xpath->query('.//div[@class="cardDataRow side"]/dl[@class="dataBox side"]/dd[@class="dataTxt"]', $detail)->item(0)->textContent),
            'cost' => trim($xpath->query('.//div[@class="cardDataRow side"]/dl[@class="dataBox side"]/dd[@class="dataTxt"]', $detail)->item(1)->textContent),
            'color' => trim($xpath->query('.//div[@class="cardDataRow side"]/dl[@class="dataBox side"]/dd[@class="dataTxt"]', $detail)->item(2)->textContent),
            'type' => ucfirst(strtolower(trim($xpath->query('.//div[@class="cardDataRow side"]/dl[@class="dataBox side"]/dd[@class="dataTxt"]', $detail)->item(3)->textContent))),
            'ap' => trim($xpath->query('.//div[@class="cardDataRow side"]/dl[@class="dataBox side"]/dd[@class="dataTxt"]', $detail)->item(4)->textContent),
            'hp' => trim($xpath->query('.//div[@class="cardDataRow side"]/dl[@class="dataBox side"]/dd[@class="dataTxt"]', $detail)->item(5)->textContent),
            'zone' => trim($xpath->query('.//div[@class="cardDataRow"]/dl[@class="dataBox"]/dd[@class="dataTxt"]', $detail)->item(0)->textContent),
            'traits' => trim($xpath->query('.//div[@class="cardDataRow"]/dl[@class="dataBox"]/dd[@class="dataTxt"]', $detail)->item(1)->textContent),
            'link' => (trim($xpath->query('.//div[@class="cardDataRow"]/dl[@class="dataBox"]/dd[@class="dataTxt"]', $detail)->item(2)->textContent)),
            'series' => (trim($xpath->query('.//div[@class="cardDataRow"]/dl[@class="dataBox"]/dd[@class="dataTxt"]', $detail)->item(3)->textContent)),
            // 'description' => trim($xpath->query('.//div[@class="dataTxt isRegular"]', $detail)->item(0)->textContent),
            'description' => $description,
        ];
    }

    file_put_contents('cards.json', json_encode($cardData));
}

function fixDescription($description) {
    $conversionTable = [
        '\u00e3\u0080\u0090' => '[',
        '\u00e3\u0080\u0091' => ']',
    ];

    var_dump(($description));die;
    var_dump(strtr($description, $conversionTable));die;
}

fetchCardData();