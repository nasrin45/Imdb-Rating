<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminMovieController extends AbstractController
{
    private MovieRepository $movieRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager)
    {
        $this->movieRepository= $movieRepository;
        $this->entityManager = $entityManager;
    }

    #[Route(path: 'admin/movies', name:'admin_movies')]
    public function index(): Response
    {
        $movies= $this->movieRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'movies' => $movies,
        ]);
    }


    #[Route(path: 'admin/add', name:'add_movies')]
    public function add(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();
            $image = $form->get('image')->getData();

            if ($image) {
                $newFileName = uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('kernel.project_dir') . '/public/images',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newMovie->setImage('/images/' . $newFileName);
            }

            $this->entityManager->persist($newMovie);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_movies');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: 'admin/edit/{id}', name:'edit_movies')]
    public function edit(int $id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);

        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        $image = $form->get('image')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($image) {
                if ($movie->getImage() !== null) {
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . $movie->getImage()
                    )) {
                        $this->GetParameter('kernel.project_dir') . $movie->getImage();
                    }
                    $newFileName = uniqid() . '.' . $image->guessExtension();

                    try {
                        $image->move(
                            $this->getParameter('kernel.project_dir') . '/public/images',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $movie->setImage('/images/' . $newFileName);
                    $this->entityManager->flush();

                    return $this->redirectToRoute('admin_movies');
                }
            } else {
                $movie->setName($form->get('name')->getData());
                $movie->setBudget($form->get('budget')->getData());
                $movie->setCrew($form->get('crew')->getData());
                $movie->setCategory($form->get('category')->getData());
                $movie->setDescription($form->get('description')->getData());

                $this->entityManager->flush();
                return $this->redirectToRoute('admin_movies');
            }
        }

        return $this->render('admin/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
    }

    #[Route(path: 'admin/delete/{id}', name: 'admin_delete', methods: ['GET','DELETE'])]
    public function delete(int $id): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->entityManager->remove($movie);
        $this->entityManager->flush();


        return $this->redirectToRoute('admin_movies');
    }
}
