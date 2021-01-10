<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Articles;
use Illuminate\Support\Carbon;

class ArticleController extends Controller {

    public $articles = [];
    private $title = [];
    private $links = [];
    private $author = [];
    private $date = [];
    private $excerpt = [];
    private $fullText = [];

    public function index() {
        $this->getInfo();

        $articleList = Articles::get();

        return view('index', compact('articleList'));
    }

    public function getInfo() {
        $crawler = Articles::getData();

        $crawler->filter('#Inhalt > section > div.bg-white.shadow.rounded > section > div > article > div > div > header > h2 > a')->each(function ($node) {
            array_push($this->links, $node->filter('a')->attr('href'));
        });

        foreach ($this->links as $k => $link) {

            $crawler = Articles::getDataByLink($link);

            $crawler->filter('#Inhalt > article > header > div > div > h2 > span > span.align-middle')->each(function ($titles) {
                if (!str_contains($titles->text(), 'Icon:')) {
                    $chars = array("«", "»");
                    $titleText = str_replace($chars, "", $titles->text());
                    array_push($this->title, $titleText);
                }
            });

            $crawler->filter('#Inhalt > article > header > div > div')->each(function ($articleDiv, $i) {

                $author = '';
                $date = '';
                if ($articleDiv->text() !== '') {
                    $date = preg_replace("[ Uhr]", "", $articleDiv->children()->last()->text());
                    $date = Carbon::parse($date)->format('d F Y, H.i');
                    array_push($this->date, $date);
                    if ($articleDiv->children()->count() > 3) {
                        $author = $articleDiv->filter('a')->text();
                    } else {
                        $author = 0;
                    }
                }
                if ($author !== '') {
                    array_push($this->author, $author);
                }
            });

            $crawler->filter('#Inhalt > article > header > div > div > div.RichText')->each(function ($excerpts) {
                array_push($this->excerpt, $excerpts->text());
            });

            $crawler->filter('#Inhalt > article > div > section.relative > div.clearfix')->each(function ($fullTexts, $i) {
                $input = $fullTexts->text();
                $lines_arr = explode(".", $input);


                $string = '';
                for ($i = 0; $i < (count($lines_arr) - 1); $i++) {
                    if (strpos($lines_arr[$i], "Icon:") == false) {
                        $string .= $lines_arr[$i] . ". ";
                    }
                }
                $chars = array("«", "»");
                $stringText = str_replace($chars, "", $string);
                array_push($this->fullText, $stringText);
            });



            $this->articles[$k]['title'] = $this->title[$k];
            $this->articles[$k]['link'] = $link;
            $this->articles[$k]['author'] = $this->author[$k];
            $this->articles[$k]['excerpt'] = $this->excerpt[$k];
            $this->articles[$k]['fullText'] = $this->fullText[$k];
            $this->articles[$k]['date'] = $this->date[$k];
        }



        Articles::truncate();
        Articles::add($this->articles);
    }

}
