<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/default', name: 'blog_default')]
    public function index(BlogRepository $blogRepository, EntityManagerInterface $em): Response
    {
//        $blog = $blogRepository->findOneBy(['id' => 1]);
////        $blog->setTitle('Title 2');
//        $em->remove($blog);
//        $em->flush();
//        exit();


        $blog = (new Blog())
            ->setTitle('Blog')
            ->setDescription('Blog description')
            ->setText('Blog text')
        ;

        $em->persist($blog);
        $em->flush();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
