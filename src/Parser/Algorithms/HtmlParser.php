<?php

namespace App\Parser\Algorithms;

use App\Model\Constants\HttpCodeResult;
use App\Model\Constants\Message;
use Symfony\Component\DomCrawler\Crawler;


class HtmlParser
{

    public function singlePage($singlePageUrl): array
    {

        try {

            $html = file_get_contents($singlePageUrl, false, stream_context_create(["http" => ["ignore_errors" => true]]));

            switch ($http_response_header[0]) {
                case HttpCodeResult::HTTP_AUTH_REQUIRED:
                    return ['error' => Message::CAPTCHA];
                case HttpCodeResult::HTTP_OK:
                    $status = 'ok';
                    break;
                default:
                    return ['error' => $http_response_header[0]];
            }

            $response = [];

            $crawler = new Crawler($html);

            // Parse image
            $productGalleryImages = $crawler->filter('.product-gallery-image')->each(function ($node) {
                return $node->attr('href');
            });
            $response['imageUrl'] = $productGalleryImages[0] ?? false;

            // Parse product name
            $response['title'] = $crawler->filter('h1.page-title')->text();

            // Parse product code
            $productCodeDisplay = $crawler->filter('span.product-code-display')->text();
            preg_match('/Cod produs: (.*)/', $productCodeDisplay, $productCodePart);
            $response['code'] = $productCodePart[1] ?? false;

            // Parse product price
            $productNewPriceHtml = $crawler->filter('.row p.product-new-price')->html();
            preg_match_all('/^(.*)<sup>(.\d)<\/sup>/', $productNewPriceHtml, $output);
            $response['price'] = (float)(str_replace('.', '', $output[1][0]) . '.' . $output[2][0]);

            // Parse vendor
            $highlightVendor = $crawler->filter('.row div.highlight-vendor a');
            $response['vendorUrl'] = $_ENV['EMAG_URL'] . $highlightVendor->attr('href');
            $response['vendorName'] = $highlightVendor->text();

            // return response
            return $response;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

    }

}
