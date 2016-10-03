<?php

namespace SurveyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * Matches /blog/*
     *
     * @Route("/create", name="create_survey")
     * @Route("/create/", name="create_survey")
     *
     * /survey/create/?datas=
     */
    public function createAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            return new JsonResponse(array('ERREUR' => 'ERREUR'));
        }

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
            $em->persist($answer);
        }

        $em->persist($question);
        $em->flush();

        return new JsonResponse(array('name' => 'hello'));
    }

    /**
     * Matches /blog/*
     *
     * @Route("/{hash}", name="get_survey")
     */
    public function displayAction(Request $request) {
        //survey_57f268f49c9b2
        if ($request->isMethod('POST')) {
            return new JsonResponse(array('ERREUR' => 'ERREUR'));
        }

        $hash = $this->getRequest()->get('hash');
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('SurveyBundle\Entity\Question');

        $survey = $repository->findOneByHash($hash);
        
        
        var_dump($survey->jsonSerialize());
        // $jsonContent = $serializer->serialize($survey, 'json');

        return new JsonResponse(array('name' => $survey->getCreatedAt()));
    }
    
}
