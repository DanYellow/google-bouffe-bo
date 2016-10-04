<?php

namespace SurveyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

use SurveyBundle\Entity\Answer as Answer;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="SurveyBundle\Repository\QuestionRepository")
 */
class Question
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
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=150, nullable=true)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=100, unique=true)
     */
    private $hash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;


    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     */
    private $answers;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="limit_date", type="datetime")
     */
    private $limitDate; 

    /**
     * 
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->hash      = uniqid();
        $this->answers = new ArrayCollection();

        $date = new \DateTime();
        $date->modify('+1 day');
        $this->limitDate = $date;
    }


    public function jsonSerialize()
    {
        // var_dump($this->answers->toArray());
        return array(
            'hash' => $this->hash,
            'question' => $this->question,
            'answers'=> array_map(function($answer) {
                return $answer->jsonSerialize();
            }, $this->answers->toArray()),
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
     * Set question
     *
     * @param string $question
     * @return Question
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Question
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Question
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add answers
     *
     * @param \SurveyBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\SurveyBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \SurveyBundle\Entity\Answer $answers
     */
    public function removeAnswer(\SurveyBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set limitDate
     *
     * @param \DateTime $limitDate
     * @return Question
     */
    public function setLimitDate($limitDate)
    {
        $this->limitDate = $limitDate;

        return $this;
    }

    /**
     * Get limitDate
     *
     * @return \DateTime 
     */
    public function getLimitDate()
    {
        return $this->limitDate;
    }

    public function getResults()
    {
        return array_map(function($answer) {
            $obj = array(
                'title' => $answer->getResponse(),
                'nbResponses' => $answer->getNbResponses()
            );
            return $obj;
        }, $this->answers->toArray());
    }
}
