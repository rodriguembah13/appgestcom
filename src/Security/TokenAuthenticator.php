<?php
/**
 * Created by PhpStorm.
 * User: smartworld
 * Date: 6/26/20
 * Time: 1:15 PM.
 */

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $encoder;
    private $userRepository;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        if (false !== strpos($request->getRequestUri(), '/api/doc')) {
            return false;
        }
        if (false !== strpos($request->getRequestUri(), '/api')) {
            //return $request->headers->has('X-AUTH-TOKEN');
            return [
                'user' => $request->headers->has('X-AUTH-USER'),
                'token' => $request->headers->has('X-AUTH-TOKEN'),
            ];
        }

        return false;
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        // return $request->headers->get('X-AUTH-TOKEN');
        return [
            'user' => $request->headers->get('X-AUTH-USER'),
            'token' => $request->headers->get('X-AUTH-TOKEN'),
        ];
    }

    /**
     * @param mixed $credentials
     *
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'] ?? null;
        $user = $credentials['user'] ?? null;
        $finalUser = $this->userRepository->findOneBy(['username' => $user]);
        if (empty($token) || empty($finalUser)) {
            return null;
        }
        /*if ($finalUser->getToken() !== $token) {
            return null;
        }*/
        /*$isValid = $this->encoder->isPasswordValid($finalUser, $token);
        if (!$isValid){
            return null;
        }*/
        return $userProvider->loadUserByUsername($user);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!empty($credentials['user']) && $user instanceof User && !empty($user->getPassword())) {
            return $this->encoder->isPasswordValid($user, $credentials['token']);
        }

        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if (!$request->headers->has('X-AUTH-TOKEN') || !$request->headers->has('X-AUTH-USER')) {
            return new JsonResponse(['message' => 'Authentification required,missing headers: X-AUTH-USER ,X-AUTH-TOKEN'], Response::HTTP_FORBIDDEN);
        }

        $data = [
            // you may want to customize or obfuscate the message first
            'message' => 'Authentification '.strtr($exception->getMessageKey(), $exception->getMessageData()),

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
