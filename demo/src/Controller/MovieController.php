<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Rating;
use App\Form\MovieFormType;
use App\Form\RatingFormType;
use App\Repository\MovieRepository;
use App\Repository\RatingRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    private MovieRepository $movieRepository;
    private EntityManagerInterface $entityManager;
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager)
    {
        $this->movieRepository= $movieRepository;
        $this->entityManager= $entityManager;
    }
    #[Route('/movies/dashboard',name: 'movie.dashboard')]
    public function index(): Response
    {
        $movies= $this->movieRepository->findAll();

        return $this->render('movies/dashboard.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route('/movies/{id}/show', name: 'show_rating')]
    public function showRating(Movie $movie): Response
    {
        $rating = $movie->getRating();

        return $this->render('movies/showrating.html.twig',[
            'movie' => $movie,
            'rating' => $rating,
        ]);
    }

    #[Route('/movies/rating/{id}',name: 'movie_rating')]
    public function rateMovie(Request $request, int $id): Response
    {
        $movie = $this->entityManager->getRepository(Movie::class)->find($id);
        $rating = new Rating();
        $form = $this->createForm(RatingFormType::class, $rating);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rating->setUser($this->getUser());
            $rating->setMovie($movie);

            $this->entityManager->persist($rating);
            $this->entityManager->flush();


            return $this->redirectToRoute('movie.dashboard');
        }

        return $this->render('movies/rating.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }

}