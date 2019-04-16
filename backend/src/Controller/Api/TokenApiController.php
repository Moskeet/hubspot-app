<?php

namespace App\Controller\Api;

use App\Entity\HubspotToken;
use App\Form\Api\ExchangeCodeApiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class TokenApiController extends AbstractController
{
    /**
     * @Route(
     *     "/exchange-code",
     *     name="app_api_exchange_code",
     *     methods={"POST"}
     * )
     *
     * @return JsonResponse
     */
    public function exchangeCode(Request $request): JsonResponse
    {
        $form = $this->createForm(ExchangeCodeApiType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var HubspotToken $hubspotToken */
            $hubspotToken = $form->getData();

            return new JsonResponse([
                'code' => $hubspotToken->getCode(),
                'token' => $hubspotToken->getToken(),
            ]);
        }

        return new JsonResponse([
            'error' => '',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
