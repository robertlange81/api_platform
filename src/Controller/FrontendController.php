<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
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
    public function indexAction(SerializerInterface $serializer): Response
    {
        return $this->render('base.html.twig', $this->setUser($serializer));
    }
  
  /**
   * @Route("/", name="home_FE")
   * @Route("/{route}", name="vue_pages", requirements={"route"="^(?!.*_wdt|_profiler|api|login).+"})
   */
    public function homepage(SerializerInterface $serializer)
    {
        return $this->render('frontend/homepage.html.twig', $this->setUser($serializer));
    }
    
    private function setUser(SerializerInterface $serializer): array
    {
        /** @var User|null $user */
        $user = $this->getUser();
        $data = null;
        if (! empty($user)) {
            $data = $serializer->serialize($user, JsonEncoder::FORMAT);
        }
        
        return [
            'isAuthenticated' => json_encode(!empty($user)),
            'user' => $data ?? json_encode($user),
        ];
    }
}
