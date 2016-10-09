<?php

namespace SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use SurveyBundle\Entity\Answer as Answer;
use SurveyBundle\Entity\Question as Question;


class SurveyController extends Controller
{
  /**
   * @Route("/")
   */
  public function indexAction()
  {
    return $this->render('SurveyBundle:Default:index.html.twig');
  }

  /**
   *
   * @Route("/create", name="create_survey")
   * 
   * @Method({"POST"})
   */
  public function createAction(Request $request)
  {
    $question = new Question();

    $em = $this->getDoctrine()->getManager();

    $surveyDatasRaw = $request->query->get('datas');
    $surveyDatas = json_decode(urldecode($surveyDatasRaw));

    foreach ($surveyDatas as $index => $value) {
      $answer = new Answer();
      $answer->setResponse($value->title);
      $answer->setURL($value->url);

      $question->addAnswer($answer);
      $answer->setQuestion($question);

      $em->persist($answer);
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

  /**
   *
   * @Route("/{hash}", name="get_survey")
   */
  public function displayAction(Request $request) {
    if ($request->isMethod('POST')) {
      return new JsonResponse(array('ERREUR' => 'ERREUR'));
    }

    $hash = $this->getRequest()->get('hash');
    $em = $this->getDoctrine()->getManager();

    $repository = $em->getRepository('SurveyBundle\Entity\Question');

    $survey = $repository->findOneByHash($hash);

    if (!isset($survey)) {
      $jsonResponse = new Response(json_encode(array( 'error' => 'Ce sondage n\'existe plus' )) );
    } else {
      $jsonResponse = new Response(json_encode(array( 'response' => $survey->jsonSerialize() )) );
    }

    
    $jsonResponse->headers->set('Access-Control-Allow-Origin', '*');
    $jsonResponse->headers->set('Content-Type', 'application/json');
    
    return $jsonResponse;
  }
  
  /**
   *
   * @Route("/{hash}/{voteID}", name="vote_survey_for")
   * @Method({"POST"})
   */
  public function voteAction(Request $request) {
    if ($request->isMethod('GET')) {
      return new JsonResponse(array('ERREUR' => 'ERREUR'));
    }

    $hash = $this->getRequest()->get('hash');
    $voteID = $this->getRequest()->get('voteID');

    $em = $this->getDoctrine()->getManager();

    $repository = $em->getRepository('SurveyBundle\Entity\Question');

    $survey = $repository->findOneByHash($hash);

    $repositoryAnswer = $em->getRepository('SurveyBundle\Entity\Answer');
    $answer = $repositoryAnswer->findOneBy(
        array('question' => $survey->getID(), 'id' => $voteID)
    );

    $answer->vote();

    $em->persist($answer);
    $em->flush();

    $jsonResponse = new Response(json_encode(array( 'response' => 'Vous avez voté pour ' . $answer->getResponse() )) );
    $jsonResponse->headers->set('Access-Control-Allow-Origin', '*');
    $jsonResponse->headers->set('Content-Type', 'application/json');

    return $jsonResponse;
  }

  /**
   *
   * @Route("/results/{hash}", name="results_survey_for")
   */
  public function resultsAction(Request $request) {
    if ($request->isMethod('POST')) {
      return new JsonResponse(array('ERREUR' => 'ERREUR'));
    }

    $hash = $this->getRequest()->get('hash');
    
    $em = $this->getDoctrine()->getManager();

    $repository = $em->getRepository('SurveyBundle\Entity\Question');

    $survey = $repository->findOneByHash($hash);

    $jsonResponse = new Response(json_encode(array( 'response' => $survey->getResults() )) );
    $jsonResponse->headers->set('Access-Control-Allow-Origin', '*');
    $jsonResponse->headers->set('Content-Type', 'application/json');

    return $jsonResponse;
  }
}
