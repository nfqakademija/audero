<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="Audero\ShowphotoBundle\Repository\PlayerRepository")
 */
class Player
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
     * @ORM\OneToOne(targetEntity="Audero\ShowphotoBundle\Entity\User", inversedBy="player")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
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
     * Set user
     *
     * @param \Audero\ShowphotoBundle\Entity\User $user
     * @return Player
     */
    public function setUser(\Audero\ShowphotoBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Audero\ShowphotoBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
