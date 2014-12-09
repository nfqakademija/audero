<?php

namespace Audero\WebBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_subscription")
 * @ORM\Entity(repositoryClass="Audero\WebBundle\Repository\UserSubscriptionRepository")
 */
class UserSubscription
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
     * @ORM\ManyToOne(targetEntity="Audero\WebBundle\Entity\UserConnection", inversedBy="subscriptions", cascade={"persist"})
     * @ORM\JoinColumn(name="connection_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $connection;

    /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=255)
     */
    private $topic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="subscribed", type="datetime")
     */
    private $subscribed;

    public function __construct() {
        $this->subscribed = new \DateTime('now');
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
     * Set topic
     *
     * @param string $topic
     * @return UserConnection
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return string 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set subscribed
     *
     * @param \DateTime $subscribed
     * @return UserSubscription
     */
    public function setSubscribed($subscribed)
    {
        $this->subscribed = $subscribed;

        return $this;
    }

    /**
     * Get subscribed
     *
     * @return \DateTime 
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }

    /**
     * Set connection
     *
     * @param \Audero\WebBundle\Entity\UserConnection $connection
     * @return UserSubscription
     */
    public function setConnection(\Audero\WebBundle\Entity\UserConnection $connection = null)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * Get connection
     *
     * @return \Audero\WebBundle\Entity\UserConnection 
     */
    public function getConnection()
    {
        return $this->connection;
    }
}