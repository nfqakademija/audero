<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="application")
 */
class Application
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\BackendBundle\Entity\User", inversedBy="applications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Interpretation", mappedBy="application")
     */
    private $interpretations;

    public function __construct()
    {
        $this->interpretations = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Application
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set user
     *
     * @param \Audero\BackendBundle\Entity\User $user
     * @return Application
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
     * Add interpretations
     *
     * @param \Audero\ShowphotoBundle\Entity\Interpretation $interpretations
     * @return Application
     */
    public function addInterpretation(\Audero\ShowphotoBundle\Entity\Interpretation $interpretations)
    {
        $this->interpretations[] = $interpretations;

        return $this;
    }

    /**
     * Remove interpretations
     *
     * @param \Audero\ShowphotoBundle\Entity\Interpretation $interpretations
     */
    public function removeInterpretation(\Audero\ShowphotoBundle\Entity\Interpretation $interpretations)
    {
        $this->interpretations->removeElement($interpretations);
    }

    /**
     * Get interpretations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInterpretations()
    {
        return $this->interpretations;
    }
}
