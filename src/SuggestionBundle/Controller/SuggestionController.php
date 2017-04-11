<?php

namespace SuggestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use SuggestionBundle\Entity\Suggestion as Suggestion;

class SuggestionController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
      return $this->render('SuggestionBundle:Default:index.html.twig');
    }

    /**
     * @Route("/create", name="create_suggestion")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
      $suggestion = new Suggestion();

      $em = $this->getDoctrine()->getManager();

      $suggestion->setName($request->query->get('name'));
      $suggestion->setDescription($request->query->get('description'));
      $suggestion->setAddress($request->query->get('address'));
      $suggestion->setBudget($request->query->get('budget'));

      $em->persist($suggestion);
      $em->flush();
      
      $jsonResponse = new JsonResponse(array('response' => 'Suggestion enregistrée !'));
      $jsonResponse->headers->set('Access-Control-Allow-Origin', '*');



      return $jsonResponse;
    }
}
