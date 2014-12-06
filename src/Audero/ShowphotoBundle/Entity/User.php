<?php

namespace Audero\ShowphotoBundle\Entity;

use Audero\WebBundle\Entity\UserConnection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Audero\ShowphotoBundle\Repository\UserRepository")
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
    protected $ratings;

    /**
     * @ORM\OneToMany(targetEntity="Audero\ShowphotoBundle\Entity\ChatMessage", mappedBy="user")
     */
    protected $chatMessages;

    /**
     * @ORM\OneToOne(targetEntity="Audero\ShowphotoBundle\Entity\Player", mappedBy="user")
     **/
    protected $player;

    /**
     * @ORM\OneToMany(targetEntity="Audero\WebBundle\Entity\UserConnection", mappedBy="user", cascade={"persist", "remove"})
     **/
    protected $connections;

    /**
     * @var integer
     *
     * @ORM\Column(name="rate", type="integer")
     */
    protected $rate = 0;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->requests = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->wishes = new ArrayCollection();
        $this->connections = new ArrayCollection();
        $this->chatMessages = new ArrayCollection();
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
     * Add request
     *
     * @param PhotoRequest $request
     * @return User
     */
    public function addRequest(PhotoRequest $request)
    {
        $this->requests[] = $request;
        return $this;
    }

    /**
     * Remove request
     *
     * @param PhotoRequest $request
     */
    public function removeRequest(PhotoRequest $request)
    {
        $this->requests->removeElement($request);
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
     * Add response
     *
     * @param PhotoResponse $response
     * @return User
     */
    public function addResponse(PhotoResponse $response)
    {
        $this->responses[] = $response;

        return $this;
    }

    /**
     * Remove response
     *
     * @param PhotoResponse $response
     */
    public function removeResponse(PhotoResponse $response)
    {
        $this->responses->removeElement($response);
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
     * Add wish
     *
     * @param Wish $wish
     * @return User
     */
    public function addWish(Wish $wish)
    {
        $this->wishes[] = $wish;

        return $this;
    }

    /**
     * Remove wish
     *
     * @param Wish $wish
     */
    public function removeWish(Wish $wish)
    {
        $this->wishes->removeElement($wish);
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
     * Add chatMessage
     *
     * @param ChatMessage $chatMessage
     * @return User
     */
    public function addChatMessage(ChatMessage $chatMessage)
    {
        $this->chatMessages[] = $chatMessage;

        return $this;
    }

    /**
     * Remove chatMessage
     *
     * @param ChatMessage $chatMessage
     */
    public function removeChatMessage(ChatMessage $chatMessage)
    {
        $this->chatMessages->removeElement($chatMessage);
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
     * @param Player $player
     * @return User
     */
    public function setPlayer(Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Add rating
     *
     * @param Rating $rating
     * @return User
     */
    public function addRating(Rating $rating)
    {
        $this->ratings[] = $rating;

        return $this;
    }

    /**
     * Remove rating
     *
     * @param Rating $rating
     */
    public function removeRating(Rating $rating)
    {
        $this->ratings->removeElement($rating);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * Add connections
     *
     * @param UserConnection $connection
     * @return User
     */
    public function addConnection(UserConnection $connection)
    {
        $this->connections[] = $connection;

        return $this;
    }

    /**
     * Remove connection
     *
     * @param \Audero\WebBundle\Entity\UserConnection $connection
     */
    public function removeConnection(UserConnection $connection)
    {
        $this->connections->removeElement($connection);
    }

    /**
     * Get connections
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConnections()
    {
        return $this->connections;
    }

    /**
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param int $rate
     *
     * @return User
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @param int $value
     *
     * @return User
     */
    public function changeRateBy($value)
    {
        $this->rate += $value;

        return $this;
    }
}
