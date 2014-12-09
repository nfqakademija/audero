<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @ORM\Column(name="photo_id", type="string", length=255, unique=true)
     */
    private $photoId;

    /**
     * @var string
     *
     * @ORM\Column(name="delete_hash", type="string", length=255)
     */
    private $deleteHash;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $photoLink;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width;

    /**
     * @var integer
     *
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @var boolean
     *
     * @ORM\Column(name="animated", type="boolean")
     */
    private $animated;

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
     * @var string
     */
    private $photoUrl;

    /**
     * @var UploadedFile
     */
    private $photoFile;

    /**
     * @var integer
     *
     * @ORM\Column(name="likes", type="integer")
     */
    private $likes = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="dislikes", type="integer")
     */
    private $dislikes = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="winner", type="boolean")
     */
    private $winner = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ratings = new ArrayCollection();
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
     * Set likes
     *
     * @param integer $likes
     * @return PhotoResponse
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @param int $value
     * @return PhotoResponse
     */
    public function changeLikesValueBy($value)
    {
        $this->likes += $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set dislikes
     *
     * @param integer $dislikes
     * @return PhotoResponse
     */
    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    /**
     * @param int $value
     * @return PhotoResponse
     */
    public function changeDisLikesValueBy($value)
    {
        $this->dislikes += $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getDislikes()
    {
        return $this->dislikes;
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
     * Set photoId
     *
     * @param string $photoId
     * @return PhotoResponse
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;

        return $this;
    }

    /**
     * Get photoId
     *
     * @return string
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set deleteHash
     *
     * @param string $deleteHash
     * @return PhotoResponse
     */
    public function setDeleteHash($deleteHash)
    {
        $this->deleteHash = $deleteHash;

        return $this;
    }

    /**
     * Get deleteHash
     *
     * @return string
     */
    public function getDeleteHash()
    {
        return $this->deleteHash;
    }

    /**
     * Set photoLink
     *
     * @param string $photoLink
     * @return PhotoResponse
     */
    public function setPhotoLink($photoLink)
    {
        $this->photoLink = $photoLink;

        return $this;
    }

    /**
     * Get photoLink
     *
     * @return string
     */
    public function getPhotoLink()
    {
        return $this->photoLink;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return PhotoResponse
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
     * Set request
     *
     * @param PhotoRequest $request
     * @return PhotoResponse
     */
    public function setRequest(PhotoRequest $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return PhotoRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Add ratings
     *
     * @param Rating $ratings
     * @return PhotoResponse
     */
    public function addRating(Rating $ratings)
    {
        $this->ratings[] = $ratings;

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
     * Set height
     *
     * @param integer $height
     * @return PhotoResponse
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return PhotoResponse
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set size
     *
     * @param integer $size
     * @return PhotoResponse
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set animated
     *
     * @param boolean $animated
     * @return PhotoResponse
     */
    public function setAnimated($animated)
    {
        $this->animated = $animated;

        return $this;
    }

    /**
     * Get animated
     *
     * @return boolean
     */
    public function getAnimated()
    {
        return $this->animated;
    }

    /**
     * Set animated
     *
     * @param string $photoUrl
     * @return PhotoResponse
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;

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
     * @return UploadedFile
     */
    public function getPhotoFile()
    {
        return $this->photoFile;
    }

    /**
     * @param UploadedFile $photoFile
     */
    public function setPhotoFile($photoFile)
    {
        $this->photoFile = $photoFile;
    }

    /**
     * Set rate
     *
     * @param boolean $win
     * @return Rating
     */
    public function setWinner($win)
    {
        $this->winner = $win;

        return $this;
    }

    /**
     * Is winner
     *
     * @return boolean
     */
    public function isWinner()
    {
        return $this->winner;
    }
}