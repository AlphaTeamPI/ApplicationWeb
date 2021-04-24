<?php

namespace App\Controller;

use App\Entity\Compteur;
use App\Form\CompteurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/compteur")
 */
class CompteurController extends AbstractController
{
    /**
     * @Route("/", name="compteur_index", methods={"GET"})
     */
    public function index(): Response
    {
        $compteurs = $this->getDoctrine()
            ->getRepository(Compteur::class)
            ->findAll();

        return $this->render('compteur/index.html.twig', [
            'compteurs' => $compteurs,
        ]);
    }

    /**
     * @Route("/new", name="compteur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $compteur = new Compteur();
        $form = $this->createForm(CompteurType::class, $compteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compteur);
            $entityManager->flush();

            return $this->redirectToRoute('compteur_index');
        }

        return $this->render('compteur/new.html.twig', [
            'compteur' => $compteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compteur_show", methods={"GET"})
     */
    public function show(Compteur $compteur): Response
    {
        return $this->render('compteur/show.html.twig', [
            'compteur' => $compteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="compteur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Compteur $compteur): Response
    {
        $form = $this->createForm(CompteurType::class, $compteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compteur_index');
        }

        return $this->render('compteur/edit.html.twig', [
            'compteur' => $compteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Compteur $compteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($compteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('compteur_index');
    }
}
