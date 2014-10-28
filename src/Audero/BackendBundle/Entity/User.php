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

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\Judgement", mappedBy="user")
     */
    protected $judgements;

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
}
