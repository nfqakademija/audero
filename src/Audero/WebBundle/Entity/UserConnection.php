<?php

namespace Audero\WebBundle\Entity;

use Audero\ShowphotoBundle\Entity\User;
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
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

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
     * @param User $user
     * @return UserConnection
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
     * Add subscription
     *
     * @param UserSubscription $subscription
     * @return UserConnection
     */
    public function addSubscription(UserSubscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }

    /**
     * Remove subscription
     *
     * @param UserSubscription $subscription
     */
    public function removeSubscription(UserSubscription $subscription)
    {
        $this->subscriptions->removeElement($subscription);
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

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }
}
