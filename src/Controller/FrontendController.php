<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Controller to render a basic "homepage".
 */
class FrontendController extends AbstractController
{
  
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('base.html.twig', []);
    }
  
  /**
   * @Route("/", name="home_FE")
   * @Route("/{route}", name="vue_pages", requirements={"route"="^(?!.*_wdt|_profiler|api|login).+"})
   */
    public function homepage(SerializerInterface $serializer)
    {
        return $this->render('frontend/homepage.html.twig', [
            'user' => $serializer->serialize($this->getUser(), 'jsonld')
        ]);
    }
}
