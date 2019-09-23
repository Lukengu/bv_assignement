<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\ApiUser;
use App\Forms\UserForm;
use Symfony\Component\HttpFoundation\Request;
use App\Service\ApiConnection;

class AuthController extends AbstractController
{

    /**
     *
     * @Route("/auth")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     *
     * @Route("/logout")
     */
    public function logout()
    {}

    /**
     *
     * @Route("/register", name="register")
     */
    public function register(Request $request, ApiConnection $apiConnection)
    {
        $user = new ApiUser();
        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $result = $apiConnection->registerUser([
                'name' => $user->getName(),
                'username' => $user->getUsername(),
                'password' => $user->getPassword()
            ]);

            return $result[0] ? $this->redirectToRoute('auth') : $this->render('auth/register.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
