<?php

namespace App\Controller\Base;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BaseApiController extends FOSRestController
{
    /**
     * Returns validation errors as an array from provided Form
     * ex: [
     *     'title' => 'Title cannot be empty'
     * ].
     *
     * @author Nadeen Nilanka <ntwobike@gmail.com>
     */
    protected function getErrors(NormalizerInterface $errorNormalizer, FormInterface $form, string $statusCode): array
    {
        return $errorNormalizer->normalize($form, null, ['status_code' => $statusCode]);
    }

    /**
     * Returns contents of the request as array.
     *
     *
     * @return array | null
     */
    protected function getJsonDecodedFromRequest(Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * Reads user id from the JWT token.
     *
     * @author Nadeen Nilanka <ntwobike@gmail.com>
     *
     * @return int
     */
    protected function getUserIdFromToken(Request $request)
    {
        return 1;
    }
}
