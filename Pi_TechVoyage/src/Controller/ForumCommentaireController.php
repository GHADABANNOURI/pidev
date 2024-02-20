<?php

namespace App\Controller;

use App\Entity\ForumCommentaire;
use App\Entity\Publication;
use App\Form\ForumCommentaireType;
use App\Repository\ForumCommentaireRepository;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum/commentaire')]
class ForumCommentaireController extends AbstractController
{
    private  $publicationRepository ;
    public function __construct(PublicationRepository $publicationRepository){
        $this->publicationRepository = $publicationRepository ;

    }
    #[Route('/b', name: 'app_forum_commentaire_index', methods: ['GET'])]
    public function index(ForumCommentaireRepository $forumCommentaireRepository): Response
    {
        return $this->render('frontoffice/forum_commentaire/index.html.twig', [
            'forum_commentaires' => $forumCommentaireRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_forum_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , Publication $publication ): Response
    {

         $f =new ForumCommentaire();
         $f->setPublication($publication);
        $f->setCreatedAt(new \DateTime());

        $f->setContent($request ->request->get('content'));

        $entityManager->persist($f);

            $entityManager->flush();

            return $this->redirectToRoute('app_publication_indexfront', [], Response::HTTP_SEE_OTHER);



    }

    #[Route('/{id}', name: 'app_forum_commentaire_show', methods: ['GET'])]
    public function show(ForumCommentaire $forumCommentaire): Response
    {
        return $this->render('frontoffice/forum_commentaire/show.html.twig', [
            'forum_commentaire' => $forumCommentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_forum_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ForumCommentaire $forumCommentaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumCommentaireType::class, $forumCommentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forum_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forum_commentaire/edit.html.twig', [
            'forum_commentaire' => $forumCommentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forum_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, ForumCommentaire $forumCommentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forumCommentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forumCommentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forum_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }


}
