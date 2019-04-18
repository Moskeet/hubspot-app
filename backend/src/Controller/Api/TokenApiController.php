<?php

namespace App\Controller\Api;

use App\Entity\HubspotToken;
use App\Form\Api\ExchangeCodeApiType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/token")
 */
class TokenApiController extends AbstractController
{
    /**
     * @Route(
     *     "/get",
     *     name="app_api_token_get",
     *     methods={"GET"}
     * )
     *
     * @return JsonResponse
     */
    public function getToken(): JsonResponse
    {
        $token = $this->getLastToken();

        if (!$token) {
            return $this->errorResponse(
                Response::HTTP_NOT_FOUND,
                'Token not found.'
            );
        }

        return new JsonResponse([
            'code' => $token->getCode(),
            'token' => $token->getToken(),
        ]);
    }

    /**
     * @Route(
     *     "/exchange-code",
     *     name="app_api_token_exchange_code",
     *     methods={"POST"}
     * )
     *
     * @return JsonResponse
     */
    public function exchangeCode(Request $request): JsonResponse
    {
        $form = $this->createNamelessForm(ExchangeCodeApiType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hubspotManager = $this->container->get('app.hubspot.hubspot_manager');
            $hubspotToken = $hubspotManager->getTokenByCode($form->getData());

            if ($hubspotToken !== null) {
                $em = $this->getDoctrine()->getManager();
                $token = $this->getLastToken();

                if ($token) {
                    $em->remove($token);
                    $em->flush();
                }

                $em->persist($hubspotToken);
                $em->flush();

                return new JsonResponse([
                    'code' => $hubspotToken->getCode(),
                    'token' => $hubspotToken->getToken(),
                ]);
            }
        }

        return $this->errorResponse(
            Response::HTTP_BAD_REQUEST,
            'Invalid form',
            $form
        );
    }

    /**
     * @param null $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createNamelessForm($type = null, $data = null, array $options = [])
    {
        return $this->container->get('form.factory')->createNamed(null, $type, $data, $options);
    }

    /**
     * @param int $statusCode
     * @param string $message
     * @param FormInterface|null $form
     *
     * @return JsonResponse
     */
    private function errorResponse(int $statusCode, string $message, FormInterface $form = null): JsonResponse
    {
        if ($form && ($errorText = $form->getErrors(true, false))) {
            $message .= "\n" . $errorText;
        }

        return new JsonResponse(
            [
                'code' => $statusCode,
                'message' => $message,
            ],
            $statusCode
        );
    }

    /**
     * @return HubspotToken|null
     */
    private function getLastToken(): ?HubspotToken
    {
        return $this->getDoctrine()->getRepository(HubspotToken::class)->getLatestToken();
    }
}
