<?php

namespace SuggestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
     * @Route("/create", name="create_survey")
     * 
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
      $suggestion = new Suggestion();

      $em = $this->getDoctrine()->getManager();

      $surveyDatasRaw = $request->query->get('datas');
      $surveyDatas = json_decode(urldecode($surveyDatasRaw));
      
      return $jsonResponse;
    }
}
