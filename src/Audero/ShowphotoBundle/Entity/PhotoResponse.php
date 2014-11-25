<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="photo_response")
 * @ORM\Entity(repositoryClass="Audero\ShowphotoBundle\Repository\PhotoResponseRepository")
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255)
     */
    private $photo;

    /**
     * @var string
     */
    private $photoUrl;

    /**
     * @var string
     */
    private $photoFile;
    
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
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="response")
     */
    private $ratings;

    /**
     * @ORM\OneToOne(targetEntity="Audero\ShowphotoBundle\Entity\Win", mappedBy="response")
     */
    private $win;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->date = new \DateTime('now');
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
     * Set photoUrl
     *
     * @param string $url
     * @return PhotoResponse
     */
    public function setPhotoUrl($url)
    {
        $this->photoUrl = $url;

        return $this;
    }

    /**
     * Get photoUrl
     *
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * Set photoFile
     * @return PhotoResponse
     */
    public function setPhotoFile($file)
    {
        $this->photoFile = $file;

        return $this;
    }

    /**
     * Get photoFile
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * Set win
     *
     * @param \Audero\ShowphotoBundle\Entity\Win $win
     * @return PhotoResponse
     */
    public function setWin(\Audero\ShowphotoBundle\Entity\Win $win = null)
    {
        $this->win = $win;

        return $this;
    }

    /**
     * Get win
     *
     * @return \Audero\ShowphotoBundle\Entity\Win 
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return PhotoResponse
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
     * Get positive ratings
     *
     * @return array
     */
    public function getPositiveRatingsCount()
    {
        $count = 0;
        foreach($this->ratings as $rating) {
            if($rating->getRate() == 1) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Get positive ratings
     *
     * @return array
     */
    public function getNegativeRatingsCount()
    {
        $count = 0;
        foreach($this->ratings as $rating) {
            if($rating->getRate() == 0) {
                $count++;
            }
        }

        return $count;
    }
}
