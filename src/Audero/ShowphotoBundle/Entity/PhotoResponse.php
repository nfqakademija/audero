<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="photo_response")
 */
class PhotoResponse
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
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="Audero\ShowphotoBundle\Entity\User", inversedBy="responses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="PhotoRequest", inversedBy="responses")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="response")
     */
    private $ratings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->likes = new ArrayCollection();
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
     * Set photo
     *
     * @param string $photo
     * @return PhotoResponse
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set user
     *
     * @param \Audero\ShowphotoBundle\Entity\User $user
     * @return PhotoResponse
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
     * Set request
     *
     * @param \Audero\ShowphotoBundle\Entity\PhotoRequest $request
     * @return PhotoResponse
     */
    public function setRequest(\Audero\ShowphotoBundle\Entity\PhotoRequest $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return \Audero\ShowphotoBundle\Entity\PhotoRequest 
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Add ratings
     *
     * @param \Audero\ShowphotoBundle\Entity\Rating $ratings
     * @return PhotoResponse
     */
    public function addRating(\Audero\ShowphotoBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Audero\ShowphotoBundle\Entity\Rating $ratings
     */
    public function removeRating(\Audero\ShowphotoBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
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
     * Set slug
     *
     * @param string $slug
     * @return PhotoResponse
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
