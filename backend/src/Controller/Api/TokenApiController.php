<?php

namespace App\Controller\Api;

use App\Entity\HubspotToken;
use App\Form\Api\ExchangeCodeApiType;
use App\Hubspot\HubspotManager;
use App\Queue\HubspotTokenQueue;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactory;
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
     * @var HubspotManager
     */
    private $hubspotManager;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var HubspotTokenQueue
     */
    private $hubspotTokenQueue;

    /**
     * @param HubspotManager $hubspotManager
     * @param FormFactory $formFactory
     * @param HubspotTokenQueue $hubspotTokenQueue
     */
    public function __construct(
        HubspotManager $hubspotManager,
        FormFactory $formFactory,
        HubspotTokenQueue $hubspotTokenQueue
    ) {
        $this->hubspotManager = $hubspotManager;
        $this->formFactory = $formFactory;
        $this->hubspotTokenQueue = $hubspotTokenQueue;
    }

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

        if (!($form->isSubmitted() && $form->isValid())) {
            return $this->errorResponse(
                Response::HTTP_BAD_REQUEST,
                'Invalid form',
                $form
            );
        }

        $hubspotToken = $this->hubspotManager->getTokenByCode($form->getData());

        if ($hubspotToken === null) {
            return $this->errorResponse(
                Response::HTTP_BAD_REQUEST,
                'Token was not retrieved.'
            );
        }

        $em = $this->getDoctrine()->getManager();

        if ($token = $this->getLastToken()) {
            $em->remove($token);
            $em->flush();
        }

        $em->persist($hubspotToken);
        $em->flush();

        $this->hubspotTokenQueue->enqueue($hubspotToken);

        return new JsonResponse([
            'code' => $hubspotToken->getCode(),
            'token' => $hubspotToken->getToken(),
        ]);
    }

    /**
     * @param null $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createNamelessForm($type = null, $data = null, array $options = [])
    {
        return $this->formFactory->createNamed(null, $type, $data, $options);
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
