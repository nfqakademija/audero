<?php

namespace Audero\BackendBundle\Entity;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\Application", mappedBy="user")
     */
    protected $applications;

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\Interpretation", mappedBy="user")
     */
    protected $interpretations;

    public function __construct()
    {
        parent::__construct();
        $this->applications = new ArrayCollection();
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
     * Add applications
     *
     * @param \Audero\ShowphotoBundle\Entity\Application $applications
     * @return User
     */
    public function addApplication(\Audero\ShowphotoBundle\Entity\Application $applications)
    {
        $this->applications[] = $applications;

        return $this;
    }

    /**
     * Remove applications
     *
     * @param \Audero\ShowphotoBundle\Entity\Application $applications
     */
    public function removeApplication(\Audero\ShowphotoBundle\Entity\Application $applications)
    {
        $this->applications->removeElement($applications);
    }

    /**
     * Get applications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Add interpretations
     *
     * @param \Audero\ShowphotoBundle\Entity\Interpretation $interpretations
     * @return User
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
