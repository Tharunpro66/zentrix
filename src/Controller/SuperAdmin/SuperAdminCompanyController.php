<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/superadmin/company')]
#[IsGranted('ROLE_SUPER_ADMIN')] // Ensures only Super Admins can access these routes
class SuperAdminCompanyController extends AbstractController
{
    #[Route('/', name: 'superadmin_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findAll();
        // dd($companies); // Uncomment this first to see if you get company data. If so, comment it out.

        // dd('About to render company index. Companies count: ' . count($companies)); // Then try this.

        return $this->render('super_admin/company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/new', name: 'superadmin_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash('success', 'Company created successfully!');
            return $this->redirectToRoute('superadmin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_admin/company/new.html.twig', [
            'company' => $company,
            'form' => $form, // Pass the form view to the template
        ]);
    }

    // For MVP, a dedicated 'show' page might be overkill.
    // We can view details in the 'edit' form or list.
    // Let's comment it out or remove it if not needed for MVP.
    /*
    #[Route('/{id}', name: 'superadmin_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('super_admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }
    */

    #[Route('/{id}/edit', name: 'superadmin_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        // Company $company is automatically fetched by its {id} thanks to param converter
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // No need to persist, it's already managed

            $this->addFlash('success', 'Company updated successfully!');
            return $this->redirectToRoute('superadmin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_admin/company/edit.html.twig', [
            'company' => $company,
            'form' => $form, // Pass the form view to the template
        ]);
    }

    // For MVP, a hard delete might be too risky.
    // Toggling 'isActive' is often preferred. We can add a separate toggle action.
    // Let's remove the default 'delete' form for now.
    /*
    #[Route('/{id}', name: 'superadmin_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();
            $this->addFlash('success', 'Company deleted successfully.');
        }

        return $this->redirectToRoute('superadmin_company_index', [], Response::HTTP_SEE_OTHER);
    }
    */

    // Let's add a simple toggle active status action instead of hard delete for MVP
    #[Route('/{id}/toggle-active', name: 'superadmin_company_toggle_active', methods: ['POST'])]
    public function toggleActive(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        // Simple CSRF protection for POST actions
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('toggle_active'.$company->getId(), $submittedToken)) {
             $this->addFlash('error', 'Invalid CSRF token.');
             return $this->redirectToRoute('superadmin_company_index');
        }

        $company->setIsActive(!$company->isIsActive()); // Toggle the status
        $entityManager->flush();

        $this->addFlash('success', 'Company status updated successfully!');
        return $this->redirectToRoute('superadmin_company_index');
    }
}