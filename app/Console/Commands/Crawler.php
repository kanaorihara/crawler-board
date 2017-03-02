<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Program;
use Goutte\Client;

class Crawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        require_once __DIR__ . '/../../../vendor/autoload.php';
        Program::truncate();

        $url = 'http://s.mxtv.jp/anime/';

        $crawler = $this->setCrawler($url);
        $links = [];
        $crawler->filter('.week_area li dt a')->each(function ($link) use (&$links) {
            $href = $link->attr('href');
            if (preg_match('/^http:/', $href)) {
                $links[] = $href;
            }
        });

        $links = array_unique($links);
        $crawler->clear();
        $parts = [];
        foreach ($links as $key => $link) {
            $parts = [];
            $crawler = $this->setCrawler($link);
            $title = $this->getTitle($crawler);
            if (!$title) {
                continue;
            }
            $time = $this->getTime($crawler);
            $story = $this->getStory($crawler);
            $staff = $this->getStaff($crawler);
            $imageUrl = $this->getImageUrl($crawler);

            $parts['title'] = $title;
            $parts['air_time'] = $time;
            $parts['story'] = $story;
            $parts['staff'] = $staff;
            $parts['image_url'] = $imageUrl;

            $moreEpisodeLink = $this->getMoreEpisodeLink($crawler, $link);
            $parts['episodes'] = null;
            if ($moreEpisodeLink) {
                $crawler = $this->setCrawler($moreEpisodeLink);
                $episodeLinks = $this->getOtherEpisode($crawler);
                if ($episodeLinks) {
                    $episodes = [];
                    foreach ($episodeLinks as $episodeLink) {
                        $crawler = $this->setCrawler($link . "/" . $episodeLink);
                        $episodes[] = $this->getEpisode($crawler);
                    }
                    $parts['episodes'] = json_encode($episodes);
                }
            }
            dump($parts);
            Program::insert($parts);
        }
        return $parts;
    }

    public function setCrawler($url) {
        $cli = new Client();
        $crawler = $cli->request('GET', $url);
        return $crawler;
    }

    public function getMoreEpisodeLink($crawler, $link) {
        $crawler->filter('.ep_more a')->each(function ($moreLink) use (&$moreEpisodeLinks) {
            $moreEpisodeLinks = $moreLink->attr('href');
            if (!$moreEpisodeLinks) {
                return;
            }
        });
        return $link . "/" . $moreEpisodeLinks;
    }

    public function getOtherEpisode($crawler) {
        $crawler->filter('.box_arc li a')->each(function ($episodeLink) use (&$episodeLinks) {
            $episodeLinks[] = $episodeLink->attr('href');
        });
        return $episodeLinks;
    }

    public function getEpisode($crawler){
        $crawler->filter('.box_s')->each(function ($episode) use (&$episodeParts) {
            $episodeParts['num'] = $episode->filter('.subtitle b')->text();
            $episodeParts['subtitle'] = $episode->filter('.subtitle span')->text();
            $episodeParts['date'] = $episode->filter('.ep_date')->text();
            $episodeParts['summary'] = $episode->filter('p')->eq(1)->text();
        });
        return $episodeParts;
    }

    public function getTitle($crawler) {
        $crawler->filter('h2.title')->each(function ($title) use (&$titleText) {
            $titleText = $title->text();
            if (!$titleText) {
                return;
            }
        });
        return $titleText;
    }

    public function getTime($crawler) {
        $crawler->filter('h3.time')->each(function ($time) use (&$timeText) {
            $timeText = $time->text();
            if (!$timeText) {
                return;
            }
        });
        return $timeText;
    }

    public function getStory($crawler) {
        $crawler->filter('.box_story .box_s')->each(function ($boxS) use (&$storyText) {
            $story = $boxS->filter('p');
            if ($story->count() == 0) {
                return;
            }
            $storyText = $story->text();
            if (!$storyText) {
                return;
            }
        });
        return $storyText;
    }

    public function getStaff($crawler) {
        $crawler->filter('.box_staff .hack')->each(function ($hack) use (&$staffText) {
            $staff = $hack->filter('p');
            if ($staff->count() === 0) {
                return;
            }
            $staffText = $staff->text();
            if (!$staffText) {
                return;
            }
        });
        return $staffText;
    }

    public function getImageUrl($crawler) {
        $crawler->filter('div.main_area h4')->each(function ($imageWrap) use (&$imageUrl) {
            $img = $imageWrap->filter('img');
            if ($img->count() === 0) {
                return;
            }
            $imageUrl = $img->attr('src');
            if (!$imageUrl) {
                return;
            }
        });
        return $imageUrl;
    }
}
