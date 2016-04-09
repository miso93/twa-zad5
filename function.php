<?php
/**
 * Created by PhpStorm.
 * User: Michal
 * Date: 08.03.2016
 * Time: 22:07
 */
if (session_id() == "") {
    session_start();
}

require_once "config.php";
require __DIR__ . '/vendor/autoload.php';

use Sunra\PhpSimple\HtmlDomParser;


function dd($arr)
{

    ?>
    <pre>
    <?php
    print_r($arr);
    ?>
    </pre>
    <?php
    die();
}

function getCityByPscPsc($psc)
{

    $city = "";

    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'http://www.pscpsc.sk/index.php',
        [
            'query' => ['input_txt_psc' => $psc]
        ]
    );

    $body_html = $res->getBody();
    $dom = HtmlDomParser::str_get_html($body_html);

    $table = $dom->find("#wrapper", 0)->find("table", 0);
    foreach ($table->find(".tddposta") as $okres) {
        if ($okres->innertext != "") {
            $city = $okres->innertext;
            $city = preg_replace('/[0-9]+/', '', $city);
            $city = str_replace(' ', '', $city);
            break;
        }
    }

    return $city;
}

function getFillingStationByCityName($cityName, $fuel_id = 2)
{
    $stations = [];

    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'http://www.benzin.sk/index.php',
        [
            'query' => [
                'price_search_town'   => iconv("UTF-8", "windows-1250", $cityName),
                'price_search_fuel'   => $fuel_id,
                'price_search_day'    => 7,
                'selected_id'         => 118,
                'article_id'          => -1,
                'price_search_brand'  => -1,
                'price_search_region' => -1,
            ],
        ]
    );
    $body_html = $res->getBody();
//    dd($res->getHeader('content-type'));
    $dom = HtmlDomParser::str_get_html($body_html);
//    dd($dom->find('#article_text', 0)->innertext);

    $pump_list_row1 = $dom->find(".pump_list_row1");
    $pump_list_row2 = $dom->find(".pump_list_row2");

    foreach ($pump_list_row1 as $row) {
        $company = $row->find('td', 1)->find('a', 0)->innertext;
        $place = $row->find('td', 3)->find('a', 0)->find('b', 0)->innertext;

        $street = substr($row->find('td', 3)->find('a', 0)->innertext, strpos($row->find('td', 3)->find('a', 0)->innertext, "<br />") + 6);
        $price_img_url = $row->find('td', 5)->find('img', 0)->getAttribute('src');
        $updated_at = $row->find('td', 6)->find('a', 0)->innertext;
        $stations[] = Station::create([
            'company'       => $company,
            'place'         => iconv("windows-1250", "UTF-8", $place),
            'street'        => iconv("windows-1250", "UTF-8", $street),
            'price_img_url' => $price_img_url,
            'updated_at'    => $updated_at,
        ]);
    }
    foreach ($pump_list_row2 as $row) {
        $company = $row->find('td', 1)->find('a', 0)->innertext;
        $place = $row->find('td', 3)->find('a', 0)->find('b', 0)->innertext;

        $street = substr($row->find('td', 3)->find('a', 0)->innertext, strpos($row->find('td', 3)->find('a', 0)->innertext, "<br />") + 6);
        $price_img_url = $row->find('td', 5)->find('img', 0)->getAttribute('src');
        $updated_at = $row->find('td', 6)->find('a', 0)->innertext;
        $stations[] = Station::create([
            'company'       => $company,
            'place'         => iconv("windows-1250", "UTF-8", $place),
            'street'        => iconv("windows-1250", "UTF-8", $street),
            'price_img_url' => $price_img_url,
            'updated_at'    => $updated_at,
        ]);
    }

    return $stations;
}

class Station
{

    public $company;
    public $place;
    public $street;
    public $price_img_url;
    public $updated_at;

    public static function create($arr_data)
    {
        $station = new Station;
        foreach ($arr_data as $key => $value) {
            if (property_exists(Station::class, $key)) {
                $station->{$key} = $value;
            }

        }

        return $station;
    }
}