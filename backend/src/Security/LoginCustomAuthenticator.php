<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private UserRepository $repository,
        private RouterInterface $router,
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);

        return new Passport(
            new UserBadge($credentials['email'], function ($userIdentifier) {
                $user = $this->repository->findOneBy(['email' => $userIdentifier]);
                if (!$user) {
                    throw new UserNotFoundException();
                }

                return $user;
            }),
            new PasswordCredentials($credentials['password'])
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($target = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($target);
        }

        return new RedirectResponse(
            $this->router->generate('app_home'),
        );
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            $this->router->generate('app_login')
        );
    }

    public function getCredentials(Request $request): array
    {
        return [
            'email' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ];
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
}
