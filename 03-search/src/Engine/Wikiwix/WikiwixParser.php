<?php

namespace App\Engine\Wikiwix;

use App\Result;
use App\ResultItem;
use Symfony\Component\DomCrawler\Crawler;

class WikiwixParser
{
    public function parse(string $content): Result
    {
        $crawler = new Crawler($content);
        $stats = $crawler->filter('.main-column p.top-bar strong')->eq(1)->text();

        preg_match_all('/(\d+)/', $stats, $matches);
        $count = (int) $matches[0][2];

        $items = [];
        $list = $crawler->filter('.search-resulr-box');

        foreach ($list as $itemNode) {
            $itemNode = new Crawler($itemNode);
            if ((int) $itemNode->filter('h2')->count() === 0) {
                continue;
            }
            $item = [
                'title' => trim($itemNode->filter('h2')->first()->text()),
                'preview' => trim($itemNode->filter('.text-box p')->first()->text())
            ];

            $items[] = ResultItem::fromArray($item);
        }

        return new Result($count, $items);
    }
}
