<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="interpretation")
 */
class Interpretation
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
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\BackendBundle\Entity\User", inversedBy="interpretations")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="interpretations")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    private $application;

    /**
     * @ORM\OneToMany(targetEntity="Judgement", mappedBy="interpretation")
     */
    private $judgements;

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
     * Constructor
     */
    public function __construct()
    {
        $this->judgements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Interpretation
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set user
     *
     * @param \Audero\BackendBundle\Entity\User $user
     * @return Interpretation
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

    /**
     * Set application
     *
     * @param \Audero\ShowphotoBundle\Entity\Application $application
     * @return Interpretation
     */
    public function setApplication(\Audero\ShowphotoBundle\Entity\Application $application = null)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return \Audero\ShowphotoBundle\Entity\Application 
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Add judgements
     *
     * @param \Audero\ShowphotoBundle\Entity\Judgement $judgements
     * @return Interpretation
     */
    public function addJudgement(\Audero\ShowphotoBundle\Entity\Judgement $judgements)
    {
        $this->judgements[] = $judgements;

        return $this;
    }

    /**
     * Remove judgements
     *
     * @param \Audero\ShowphotoBundle\Entity\Judgement $judgements
     */
    public function removeJudgement(\Audero\ShowphotoBundle\Entity\Judgement $judgements)
    {
        $this->judgements->removeElement($judgements);
    }

    /**
     * Get judgements
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJudgements()
    {
        return $this->judgements;
    }
}
