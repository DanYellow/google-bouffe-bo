<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use SurveyBundle\Entity\Question as Question;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_responses", type="smallint")
     */
    private $nbResponses;

    /**
     * @var string
     *
     * @ORM\Column(name="response", type="string", length=75)
     */
    private $response;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=75)
     */
    private $URL;


    /**
     * 
     */
    public function __construct()
    {
        $this->nbResponses = 0;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'response' => $this->response,
            'URL' => $this->URL,
            'nbResponses' => $this->nbResponses,
        );
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nbResponses
     *
     * @param integer $nbResponses
     * @return Answer
     */
    public function setNbResponses($nbResponses)
    {
        $this->nbResponses = $nbResponses;

        return $this;
    }

    /**
     * Get nbResponses
     *
     * @return integer 
     */
    public function getNbResponses()
    {
        return $this->nbResponses;
    }

    /**
     * Set response
     *
     * @param string $response
     * @return Answer
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return string 
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set question
     *
     * @param \SurveyBundle\Entity\Question $question
     * @return Answer
     */
    public function setQuestion(\SurveyBundle\Entity\Question $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \SurveyBundle\Entity\Question 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set URL
     *
     * @param string $uRL
     * @return Answer
     */
    public function setURL($uRL)
    {
        $this->URL = $uRL;

        return $this;
    }

    /**
     * Get URL
     *
     * @return string 
     */
    public function getURL()
    {
        return $this->URL;
    }

    public function vote()
    {
        $this->nbResponses += 1;

        return $this;
    }
}
