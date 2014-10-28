<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="judgement")
 */
class Judgement
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="decision", type="boolean")
     */
    private $decision;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\Interpretation", inversedBy="judgements")
     * @ORM\JoinColumn(name="interpretation_id", referencedColumnName="id")
     */
    private $interpretation;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\BackendBundle\Entity\User", inversedBy="judgements")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
     * Set decision
     *
     * @param boolean $decision
     * @return Judgement
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Get decision
     *
     * @return boolean 
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * Set interpretation
     *
     * @param \Audero\ShowphotoBundle\Entity\Interpretation $interpretation
     * @return Judgement
     */
    public function setInterpretation(\Audero\ShowphotoBundle\Entity\Interpretation $interpretation = null)
    {
        $this->interpretation = $interpretation;

        return $this;
    }

    /**
     * Get interpretation
     *
     * @return \Audero\ShowphotoBundle\Entity\Interpretation 
     */
    public function getInterpretation()
    {
        return $this->interpretation;
    }

    /**
     * Set user
     *
     * @param \Audero\BackendBundle\Entity\User $user
     * @return Judgement
     */
    public function setUser(\Audero\BackendBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Audero\BackendBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
