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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PopulateController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/populate/{limit}/{search}', name: 'populate')]
    public function populate(int $limit, string $search)
    {

        $em = $this->entityManager;

        try {

            $jsonParser = new JsonParser();
            $data = $jsonParser->search($limit, $search);
            if (isset($data['error'])) {
                return new JsonResponse(['error' => $data['error']]);
            }

            $total = count($data);

            foreach ($data as $key => $value) {
                $vendor = $value['offer']['vendor'];

                // check if company exist
                $company = $em->getRepository('App:Companies')->findOneByName($vendor['name']['default']);
                if (!$company) {
                    $company = new Companies();
                    $company->setName($vendor['name']['default']);
                    $company->setUrl($vendor['url']['desktop_base'] . $vendor['url']['path']);

                    $em->persist($company);
                    $em->flush();
                }

                // check if product exist
                $product = $em->getRepository('App:Products')->findOneBySku($value['part_number_key']);
                if (!$product) {

                    $product = new Products();
                    $product->setSku($value['part_number_key']);
                    $product->setName($value['name']);
                    $product->setUrl($value['url']['desktop_base'] . $value['url']['path']);
                    $product->setCompanies($company);

                    // Generate random currentPrice & minimPrice ofr testing purpose only
                    $currentPrice = $value['offer']['price']['current'];
                    $randomMinimPrice = $currentPrice - $currentPrice * random_int(1, 10) / 100;

                    $product->setMinimPrice($randomMinimPrice);

                    $em->persist($product);
                    $em->flush();
                }

            }

            return new JsonResponse(['total' => $total]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

}
