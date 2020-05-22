<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;



class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{ 
    use TargetPathTrait;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * LoginFormAuthenticator constructor
     *
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserRepository $userRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports(Request $request)
    {
        //Première méthode qui ce déclenchera dans l'app
        //die("Notre process de connection démarre ici !!!");
        return $request->attributes->get('_route') === "app_login" && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        //dd($request->request->all());
        //1) récupération du credential
        $credentials = [
            "email" => $request->request->get('email'),
            "password" => $request->request->get('password'),
            "_csrf_token" => $request->request->get('_csrf_token')
        ];
        //2) on met le credential en session via "email"
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );
        //3) on le retourne
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //dd($credentials);
        //on récupère le token 
        $token = new CsrfToken('authenticate', $credentials['_csrf_token']);
        //on check si le token est valide ou pas
        if(!$this->csrfTokenManager->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        }
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        /*dump($credentials);
        dd($user);*/
        //If true passe a la suite du process
        //return true;
        return $this->userPasswordEncoder->isPasswordValid($user, $credentials['password']);
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return parent::onAuthenticationFailure($request, $exception);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //permet de ciblé la dernière url taper dans le navigateur
        $cheminCible = $this->getTargetPath($request->getSession(), $providerKey);
        if($cheminCible) {
            return new RedirectResponse($cheminCible);
        }
       //dd("Authentification Réussi");
       return new RedirectResponse($this->router->generate('store_accueil'));
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }

}
