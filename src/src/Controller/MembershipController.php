<?php

namespace App\Controller;

use App\Entity\Membership;
use App\Form\MembershipType;
use App\Repository\MembershipRepository;
use App\Services\CreditService;
use App\Services\MembershipService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/membership')]
class MembershipController extends AbstractController
{
    #[Route('/', name: 'app_membership_index', methods: ['GET'])]
    public function index(MembershipRepository $membershipRepository): Response
    {
        return $this->render('membership/index.html.twig', [
            'memberships' => $membershipRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_membership_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MembershipRepository $membershipRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EMPLOYEE');
        $membership = new Membership();
        $form = $this->createForm(MembershipType::class, $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membershipRepository->add($membership, true);

            return $this->redirectToRoute('app_membership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('membership/new.html.twig', [
            'membership' => $membership,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membership_show', methods: ['GET'])]
    public function show(Membership $membership): Response
    {
        return $this->render('membership/show.html.twig', [
            'membership' => $membership,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_membership_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Membership $membership, MembershipRepository $membershipRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EMPLOYEE');
        $form = $this->createForm(MembershipType::class, $membership);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $membershipRepository->add($membership, true);

            return $this->redirectToRoute('app_membership_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('membership/edit.html.twig', [
            'membership' => $membership,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_membership_delete', methods: ['POST'])]
    public function delete(Request $request, Membership $membership, MembershipRepository $membershipRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_EMPLOYEE');
        if ($this->isCsrfTokenValid('delete'.$membership->getId(), $request->request->get('_token'))) {
            $membershipRepository->remove($membership, true);
        }

        return $this->redirectToRoute('app_membership_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/buy', name: 'app_membership_buy', methods: ['GET'])]
    public function buy(Membership $membership, MembershipService $membershipService, CreditService $creditService): Response
    {
        $creditService->buy($membership);
//        $membershipService->buyMembership($membership);
        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }
}
