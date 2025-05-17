<?php
// src/Controller/SuperAdminSecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SuperAdminSecurityController extends AbstractController
{
    #[Route(path: '/superadmin/login', name: 'superadmin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // ---- ADD THIS ----
        if ($this->getUser() && $this->isGranted('ROLE_SUPER_ADMIN')) {
            $this->addFlash('info', 'You are already logged in.'); // Optional flash message
            return $this->redirectToRoute('superadmin_dashboard');
        }
        // ---- END ADD ----

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('super_admin/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/superadmin/logout', name: 'superadmin_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}