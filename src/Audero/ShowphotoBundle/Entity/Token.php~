<?php

namespace Audero\ShowphotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 * @ORM\Entity
 * @ORM\Table(name="token")
 */
class Token
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
     * @ORM\Column(name="access_token", type="string", length=255)
     */
    private $accessToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_in", type="datetime")
     */
    private $expiresIn;

    /**
     * @var string
     *
     * @ORM\Column(name="token_type", type="string", length=255)
     */
    private $tokenType;


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
     * Set accessToken
     *
     * @param string $accessToken
     * @return Token
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string 
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set expiresIn
     *
     * @param \DateTime $expiresIn
     * @return Token
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    /**
     * Get expiresIn
     *
     * @return \DateTime 
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    /**
     * Set tokenType
     *
     * @param string $tokenType
     * @return Token
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;

        return $this;
    }

    /**
     * Get tokenType
     *
     * @return string 
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }
}
