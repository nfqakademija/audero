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
     * @ORM\Column(name="rate", type="boolean")
     */
    private $rate;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\PhotoResponse", inversedBy="ratings")
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id")
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
     * Set response
     *
     * @param PhotoResponse $response
     * @return Rating
     */
    public function setResponse(PhotoResponse $response = null)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return PhotoResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Rating
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set rate
     *
     * @param boolean $rate
     * @return Rating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return boolean 
     */
    public function getRate()
    {
        return $this->rate;
    }
}
