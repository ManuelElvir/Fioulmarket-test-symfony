<?php

namespace App\Service;

final class ImageService {

    public function fetchImageUrls(string $url): array
    {
        $imageUrls = [];

        $rssFeed = $this->getRssFeed($url);
        $imageUrls = array_merge($imageUrls, $this->extractImageUrlsFromRss($rssFeed));

        $newsApiUrls = $this->getNewsApiUrls();
        $imageUrls = array_merge($imageUrls, $newsApiUrls);

        $imageUrls = array_unique($imageUrls);

        return $imageUrls;
    }

    private function getRssFeed(string $url): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $rssFeed = curl_exec($ch);
        curl_close($ch);

        if ($rssFeed === false) {
            throw new \Exception('Failed to fetch RSS feed');
        }

        return $rssFeed;
    }

    private function extractImageUrlsFromRss(string $rssFeed): array
    {
        $imageUrls = [];

        $xml = simplexml_load_string($rssFeed, 'SimpleXMLElement', LIBXML_NOCDATA);
        $items = $xml->channel->item;

        foreach ($items as $item) {
            $content = (string)$item->children('content', true);
            if (preg_match('/\.(jpg|jpeg|gif|png)/i', $content)) {
                $link = (string)$item->link;
                $imageUrls[] = $link;
            }
        }

        return $imageUrls;
    }

    private function getNewsApiUrls(): array
    {
        $imageUrls = [];

        $newsApiUrl = 'https://newsapi.org/v2/top-headlines?country=us&apiKey=c782db1cd730403f88a544b75dc2d7a0';
        $ch = curl_init($newsApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $newsApiResponse = curl_exec($ch);
        curl_close($ch);

        if ($newsApiResponse === false) {
            throw new \Exception('Failed to fetch data from NewsApi');
        }

        $newsApiData = json_decode($newsApiResponse);

        foreach ($newsApiData->articles as $article) {
            if (!empty($article->urlToImage)) {
                $imageUrls[] = $article->url;
            }
        }

        return $imageUrls;
    }

    public function getImageFromUrl(string $url): ?string
    {
        $doc = new \DomDocument();
        @$doc->loadHTMLFile($url);
        $xpath = new \DomXpath($doc);

        // Rechercher l'élément img
        $imageElement = $xpath->query('//img[contains(@class,"size-full")]/@src');
        
        if ($imageElement->length > 0) {
            return $imageElement[0]->value;
        }

        return null;
    }
}