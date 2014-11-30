<?php

namespace Audero\WebBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_connection")
 * @ORM\Entity(repositoryClass="Audero\WebBundle\Repository\UserConnectionRepository")
 */
class UserConnection
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
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\User", inversedBy="connections", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="resource_id", type="integer", unique=true)
     */
    private $resourceId;

    /**
     * @ORM\OneToMany(targetEntity="Audero\WebBundle\Entity\UserSubscription", mappedBy="connection", cascade={"persist","remove"})
     */
    private $subscriptions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="connected", type="datetime")
     */
    private $connected;

    public function __construct() {
        $this->subscriptions = new ArrayCollection();
        $this->connected = new \DateTime('now');
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
     * Set resourceId
     *
     * @param integer $resourceId
     * @return UserConnection
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * Get resourceId
     *
     * @return integer 
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * Set connected
     *
     * @param \DateTime $connected
     * @return UserConnection
     */
    public function setConnected($connected)
    {
        $this->connected = $connected;

        return $this;
    }

    /**
     * Get connected
     *
     * @return \DateTime 
     */
    public function getConnected()
    {
        return $this->connected;
    }

    /**
     * Set user
     *
     * @param \Audero\ShowphotoBundle\Entity\User $user
     * @return UserConnection
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

    /**
     * Add subscriptions
     *
     * @param \Audero\WebBundle\Entity\UserSubscription $subscriptions
     * @return UserConnection
     */
    public function addSubscription(\Audero\WebBundle\Entity\UserSubscription $subscriptions)
    {
        $this->subscriptions[] = $subscriptions;

        return $this;
    }

    /**
     * Remove subscriptions
     *
     * @param \Audero\WebBundle\Entity\UserSubscription $subscriptions
     */
    public function removeSubscription(\Audero\WebBundle\Entity\UserSubscription $subscriptions)
    {
        $this->subscriptions->removeElement($subscriptions);
    }

    /**
     * Get subscriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }
}
