<?php
namespace App\Security\User;

use App\Service\ApiConnection;
use App\Entity\ApiUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $container;
    private $restClient;
    private $router;

    public function __construct(ContainerInterface  $container, ApiConnection $restClient, RouterInterface $router)
    {
        $this->container = $container;
        $this->restClient = $restClient;
        $this->router = $router;
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new RedirectResponse($this->getLoginUrl());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->getDefaultSuccessRedirectUrl());
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('auth');
        
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //dump($credentials); die();
        if (array_key_exists('username', $credentials) == false) {
            return null;
        }
        $username = $credentials['username'];
        $password = $credentials['password'];
        if ($username == '') {
            return null;
        }
        
        $access_token = $this->restClient->login($username, $password);
        
        if (strlen($access_token) > 0) {
            return new ApiUser($username, $password,$access_token, ['ROLE_USER']);
        } else {
            throw new CustomUserMessageAuthenticationException('Invalid credentials');
        }
    }

    public function supports(Request $request)
    {
        return 'auth' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        
       
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token')
        ];
        
        $request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);

        return $credentials;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('dashboard');
    }
}

