<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="winners")
 * @ORM\Entity(repositoryClass="Audero\ShowphotoBundle\Repository\WinnerRepository")
 */
class Winner
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="Audero\ShowphotoBundle\Entity\PhotoResponse")
     * @ORM\JoinColumn(name="response_id", referencedColumnName="id")
     */
    private $response;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\User", inversedBy="wins")
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
     * Set date
     *
     * @param \DateTime $date
     * @return Winner
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set response
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoResponse $response
     * @return Win
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
     * @return Win
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
