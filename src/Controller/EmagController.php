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

class EmagController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/emag/update', name: 'emag-update-price')]
    public function update()
    {

        $em = $this->entityManager;

        try {

            $result = [];

            $processProducts = $em->getRepository('App:ProcessProduct')->findByStatus(ProcessProductStatus::PRICE_SHOULD_BE_UPDATED);
            if ($processProducts) {
                foreach ($processProducts as $processProduct) {

                    try {

                        // TODO | make request to API eMag to update with new price
                        $data = [
                            'part_number_key' => $processProduct->getProduct()->getSku(),
                            'sale_price' => $processProduct->getUpdatedPrice()
                        ];

                        $httpClient = HttpClient::create();
                        $response = $httpClient->request('POST', $_ENV['EMAG_API_URL'] . '/product_offer/save', [
                            'auth_basic' => base64_encode($_ENV['EMAG_API_USERNAME'] . ':' . $_ENV['EMAG_API_PASSWORD']),
                            'body' => $data
                        ]);

                        if ($response->getStatusCode() === Response::HTTP_OK) {
                            $status = ProcessProductStatus::PRICE_UPDATED;
                        } else {
                            $status = ProcessProductStatus::ERROR;
                        }

                        $result[] = [
                            'id' => $processProduct->getProduct()->getId(),
                            'status' => $status,
                            'statusCode' => $response->getStatusCode(),
                            'result' => $response->getContent()
                        ];

                    } catch (\Exception $e) {
                        $result[] = [
                            'id' => $processProduct->getProduct()->getId(),
                            'status' => ProcessProductStatus::ERROR,
                            'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'error' => $e->getMessage()
                        ];
                    }

                    $processProduct->setStatus(ProcessProductStatus::PRICE_UPDATED);

                    $em->persist($processProduct);
                    $em->flush();
                }
            }

            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
