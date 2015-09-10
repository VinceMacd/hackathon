<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @ExclusionPolicy("all")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     *
     * @Expose
     */
    protected $token;

    /**
     * @ORM\Column(type="date", nullable=false)
     *
     * @Expose
     */
    protected $expiryDate;

    /**
     * @ORM\Column(type="string", length=16, nullable=false)
     *
     * @Expose
     */
    protected $ip;

    /**
     * @ORM\OneToMany(targetEntity="Notification_User", mappedBy="user")
     */
    protected $userNotifications;

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
     * Set token
     *
     * @param string $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     * @return User
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }
}
