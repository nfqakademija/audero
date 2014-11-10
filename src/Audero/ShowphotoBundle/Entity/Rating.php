<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rating")
 */
class Rating
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
     * @ORM\Column(name="like", type="boolean")
     */
    private $like;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\PhotoResponse", inversedBy="likes")
     * @ORM\JoinColumn(name="like_id", referencedColumnName="id")
     */
    private $response;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\User", inversedBy="likes")
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
     * Set like
     *
     * @param boolean $like
     * @return Rating
     */
    public function setLike($like)
    {
        $this->like = $like;

        return $this;
    }

    /**
     * Get like
     *
     * @return boolean 
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set response
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoResponse $response
     * @return Rating
     */
    public function setResponse(\Audero\ShowphotoBundle\Entity\PhotoResponse $response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return \Audero\ShowphotoBundle\Entity\PhotoResponse 
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set user
     *
     * @param \Audero\ShowphotoBundle\Entity\User $user
     * @return Rating
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
