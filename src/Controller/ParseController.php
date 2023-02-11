<?php

namespace App\Controller;

use App\Entity\Companies;
use App\Entity\Process;
use App\Entity\ProcessProduct;
use App\Entity\Products;
use App\Model\Constants\Message;
use App\Model\Constants\ProcessProductReason;
use App\Model\Constants\ProcessProductStatus;
use App\Model\Constants\ProcessStatus;
use App\Parser\Algorithms\HtmlParser;
use App\Parser\Algorithms\JsonParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParseController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/parse', name: 'parse_all_product_prices')]
    public function parseAll(): JsonResponse
    {

        $em = $this->entityManager;

        try {

            $response = [];

            $process = new Process();
            $process->setStatus(ProcessStatus::RUNNING);

            $em->persist($process);
            $em->flush();

            $products = $em->getRepository('App:Products')->findAll();
            if ($products) {
                foreach ($products as $product) {
                    $response[] = json_decode($this->parseSingle($product->getId(), $process)->getContent(), true, 512, JSON_THROW_ON_ERROR);
                }

                $process->setReport($response);
                $process->setStatus(ProcessStatus::DONE);

                $em->persist($process);
                $em->flush();

                // return response
                return new JsonResponse($response);
            }

            $process->setStatus(ProcessStatus::ERROR);
            $process->setReport([Message::PRODUCT_NOT_FOUND]);

            $em->persist($process);
            $em->flush();

            return new JsonResponse(['error' => Message::PRODUCT_NOT_FOUND], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function parseSingle(int $id, Process $process): JsonResponse
    {

        $em = $this->entityManager;

        try {

            $product = $em->getRepository('App:Products')->find($id);
            if (!$product) {
                return new JsonResponse(['error' => Message::PRODUCT_NOT_FOUND, Response::HTTP_INTERNAL_SERVER_ERROR]);
            }

            $htmlParser = new HtmlParser();
            $currentProduct = $htmlParser->singlePage($product->getUrl());
            if (!$currentProduct) {
                return new JsonResponse([Message::PRODUCT_NOT_FOUND]);
            }

            if (!isset($currentProduct['error'])) {

                $currentPrice = $currentProduct['price'];

                $jsonParser = new JsonParser();
                $minimRelatedPrice = $jsonParser->getMinimRelatedPrice($product->getUrl());

                if ($minimRelatedPrice && !isset($minimRelatedPrice['error'])) {
                    $newPrice = $minimRelatedPrice - $minimRelatedPrice * $_ENV['CONCURRENCY_PERCENT_RELATED'] / 100;
                    $minimPrice = $product->getMinimPrice();

                    if ($currentPrice < $minimRelatedPrice) {
                        $status = ProcessProductStatus::PRICE_SHOULD_NOT_BE_UPDATED;
                        $reason = ProcessProductReason::PRICE_IS_LOWEST;
                    } elseif ($minimPrice >= $newPrice) {
                        $status = ProcessProductStatus::PRICE_SHOULD_NOT_BE_UPDATED;
                        $reason = ProcessProductReason::LOWER_THAN_MINIM;
                    } else {
                        $status = ProcessProductStatus::PRICE_SHOULD_BE_UPDATED;
                        $reason = ProcessProductReason::PRICE_UPDATE;
                    }

                } else {
                    $status = ProcessProductStatus::PRICE_SHOULD_NOT_BE_UPDATED;
                    $reason = ProcessProductReason::NO_RELATED_PRODUCT;
                }
            } else {
                $status = ProcessProductStatus::ERROR;
                $reason = $currentProduct['error'];
            }

            $processProduct = new ProcessProduct();
            $processProduct->setProcess($process);
            $processProduct->setProduct($product);
            $processProduct->setStatus($status);
            $processProduct->setReason($reason);

            if (isset($currentPrice)) {
                $processProduct->setCurrentPrice($currentPrice);
            }

            if ($reason === ProcessProductReason::PRICE_UPDATE && isset($newPrice)) {
                $processProduct->setUpdatedPrice(round($newPrice, 2));
            }

            $em->persist($processProduct);
            $em->flush();

            // set response
            $response = [
                'id' => $product->getId(),
                'status' => $status,
                'reason' => $reason
            ];

            // return response
            return new JsonResponse($response);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
