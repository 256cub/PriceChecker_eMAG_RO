<?php

namespace App\Parser\Algorithms;

use App\Model\Constants\HttpCodeResult;
use App\Model\Constants\Message;


class JsonParser
{

    public function search($limit, $searchTerm)
    {

        try {

            $url = str_replace(['{SEARCH_TERM}', '{SEARCH_LIMIT}'], [urlencode($searchTerm), $limit], $_ENV['EMAG_JSON_SEARCH_PAGE_URL']);

            $html = file_get_contents($url, false, stream_context_create(["http" => ["ignore_errors" => true]]));

            switch ($http_response_header[0]) {
                case HttpCodeResult::HTTP_AUTH_REQUIRED:
                    return ['error' => Message::CAPTCHA];
                    break;
                case HttpCodeResult::HTTP_OK:
                    $result = json_decode($html, true, 512, JSON_THROW_ON_ERROR);
                    return $result['data']['items'];
                default:
                    return ['error' => $http_response_header[0]];
            }

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

    }

    public function getMinimRelatedPrice($url)
    {

        try {

            preg_match('/https:\/\/www.emag.ro\/.*\/pd\/(.*)\//', $url, $output);
            $productId = $output[1];

            $url = str_replace('{PRODUCT_ID}', $productId, $_ENV['EMAG_JSON_MULTIPLE_OFFER']);

            $html = file_get_contents($url, false, stream_context_create(["http" => ["ignore_errors" => true]]));

            switch ($http_response_header[0]) {
                case HttpCodeResult::HTTP_AUTH_REQUIRED:
                    return ['error' => Message::CAPTCHA];
                    break;
                case HttpCodeResult::HTTP_OK:
                    $result = json_decode($html, true, 512, JSON_THROW_ON_ERROR);
                    break;
                default:
                    return ['error' => $http_response_header[0]];
            }

            if (isset($result['data']['items'])) {
                $items = $result['data']['items'];

                $multipleOffersPrices = [];
                foreach ($items as $item) {
                    $multipleOffersPrices[] = $item['price']['current'];
                }
                sort($multipleOffersPrices);

                // return response
                return $multipleOffersPrices[0];
            }

            // return response
            return ['error' => 'Not found'];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

    }

}
