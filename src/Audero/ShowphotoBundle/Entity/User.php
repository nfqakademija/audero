<?php

namespace Audero\ShowphotoBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
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
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\PhotoRequest", mappedBy="user")
     */
    protected $requests;

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\PhotoResponse", mappedBy="user")
     */
    protected $responses;

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\Wish", cascade={"persist"}, mappedBy="user")
     */
    protected $wishes;

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\Rating", mappedBy="user")
     */
    protected $likes;

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\Chat", mappedBy="user")
     */
    protected $chatMessages;

    /**
     * @ORM\OneToOne(targetEntity="Audero\ShowphotoBundle\Entity\Player", mappedBy="user")
     **/
    protected $player;

    public function __construct()
    {
        parent::__construct();
        $this->requests = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->wishes = new ArrayCollection();
        $this->messages = new ArrayCollection();
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
     * Add requests
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoRequest $requests
     * @return User
     */
    public function addRequest(\Audero\ShowphotoBundle\Entity\PhotoRequest $requests)
    {
        $this->requests[] = $requests;

        return $this;
    }

    /**
     * Remove requests
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoRequest $requests
     */
    public function removeRequest(\Audero\ShowphotoBundle\Entity\PhotoRequest $requests)
    {
        $this->requests->removeElement($requests);
    }

    /**
     * Get requests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * Add responses
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoResponse $responses
     * @return User
     */
    public function addResponse(\Audero\ShowphotoBundle\Entity\PhotoResponse $responses)
    {
        $this->responses[] = $responses;

        return $this;
    }

    /**
     * Remove responses
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoResponse $responses
     */
    public function removeResponse(\Audero\ShowphotoBundle\Entity\PhotoResponse $responses)
    {
        $this->responses->removeElement($responses);
    }

    /**
     * Get responses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * Add wishes
     *
     * @param \Audero\ShowphotoBundle\Entity\Wish $wishes
     * @return User
     */
    public function addWish(\Audero\ShowphotoBundle\Entity\Wish $wishes)
    {
        $this->wishes[] = $wishes;

        return $this;
    }

    /**
     * Remove wishes
     *
     * @param \Audero\ShowphotoBundle\Entity\Wish $wishes
     */
    public function removeWish(\Audero\ShowphotoBundle\Entity\Wish $wishes)
    {
        $this->wishes->removeElement($wishes);
    }

    /**
     * Get wishes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWishes()
    {
        return $this->wishes;
    }

    /**
     * Add likes
     *
     * @param \Audero\ShowphotoBundle\Entity\Rating $likes
     * @return User
     */
    public function addLike(\Audero\ShowphotoBundle\Entity\Rating $likes)
    {
        $this->likes[] = $likes;

        return $this;
    }

    /**
     * Remove likes
     *
     * @param \Audero\ShowphotoBundle\Entity\Rating $likes
     */
    public function removeLike(\Audero\ShowphotoBundle\Entity\Rating $likes)
    {
        $this->likes->removeElement($likes);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Add chatMessages
     *
     * @param \Audero\ShowphotoBundle\Entity\Chat $chatMessages
     * @return User
     */
    public function addChatMessage(\Audero\ShowphotoBundle\Entity\Chat $chatMessages)
    {
        $this->chatMessages[] = $chatMessages;

        return $this;
    }

    /**
     * Remove chatMessages
     *
     * @param \Audero\ShowphotoBundle\Entity\Chat $chatMessages
     */
    public function removeChatMessage(\Audero\ShowphotoBundle\Entity\Chat $chatMessages)
    {
        $this->chatMessages->removeElement($chatMessages);
    }

    /**
     * Get chatMessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChatMessages()
    {
        return $this->chatMessages;
    }

    /**
     * Set player
     *
     * @param \Audero\ShowphotoBundle\Entity\Player $player
     * @return User
     */
    public function setPlayer(\Audero\ShowphotoBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \Audero\ShowphotoBundle\Entity\Player 
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
