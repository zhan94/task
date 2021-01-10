<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client;
use Illuminate\Support\Facades\DB;

class Articles extends Model {

    public $timestamps = false;

    static function getData() {
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.spiegel.de/politik/');

        return $crawler;
    }

    static function getDataByLink($link) {
        $client = new Client();
        $crawler = $client->request('GET', $link);

        return $crawler;
    }

    static function add($articles) {
        Self::insert($articles); // Eloquent approach
    }

}
