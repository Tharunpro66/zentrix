<?php

// src/Controller/SuperAdminDashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted; // For newer Symfony versions

// #[IsGranted('ROLE_SUPER_ADMIN')] // You can also protect the whole controller
class SuperAdminDashboardController extends AbstractController
{
    #[Route('/superadmin', name: 'superadmin_dashboard')] // Matches default_target_path
    #[IsGranted('ROLE_SUPER_ADMIN')] // Protect this specific action
    public function index(): Response
    {
        return $this->render('super_admin/dashboard/index.html.twig', [
            'controller_name' => 'SuperAdminDashboardController',
        ]);
    }
}
