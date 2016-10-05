<?php

namespace SuggestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('SuggestionBundle:Default:index.html.twig');
    }

    /**
     *
     * @Route("/create", name="create_survey")
     * 
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
      // if ($request->isMethod('POST')) {
      //   return new JsonResponse(array('ERREUR' => 'ERREUR'));
      // } 

      $question = new Question();

      $em = $this->getDoctrine()->getManager();

      $surveyDatasRaw = $request->query->get('datas');
      $surveyDatas = json_decode(urldecode($surveyDatasRaw));

      // eg : 
      // %5B%7B%22title%22%3A%22Les%20Caves%20Saint%20Gilles%22%2C%22url%22%3A%22%2F%23%2Flist%2Fles-caves-saint-gilles%22%7D%2C%7B%22title%22%3A%22La%20f%C3%A9e%20verte%22%2C%22url%22%3A%22%2F%23%2Flist%2Fla-fe-verte%22%7D%5D

      foreach ($surveyDatas as $index => $value) {
          $answer = new Answer();
          $answer->setResponse($value->title);
          $answer->setURL($value->url);

          $question->addAnswer($answer);
          $answer->setQuestion($question);

          $em->persist($answer);
          // $em->flush();
      }

      $em->persist($question);
      $em->flush();

      $jsonResponse = new Response(json_encode(array(
        'response' => array('url' => '/survey/' . $question->getHash())
      )));
      $jsonResponse->headers->set('Access-Control-Allow-Origin', '*');
      $jsonResponse->headers->set('Content-Type', 'application/json');
      
      return $jsonResponse;
    }
}
