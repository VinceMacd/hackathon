<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="notification_user")
 */
class Notification_User
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="users")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", nullable=false)
     */
    protected $notification;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\Column(name="created_time", type="date", nullable=false)
     */
    protected $createdTime;

    /**
     * @ORM\Column(name="pushed_time", type="date", nullable=true)
     */
    protected $pushedTime;

    /**
     * @ORM\Column(name="action_url", type="string", length=255, nullable=true)
     */
    protected $actionUrl;

    /**
     * @ORM\Column(name="is_clicked", type="boolean", nullable=false)
     */
    protected $isClicked;

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return Notification_User
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set pushedTime
     *
     * @param \DateTime $pushedTime
     * @return Notification_User
     */
    public function setPushedTime($pushedTime)
    {
        $this->pushedTime = $pushedTime;

        return $this;
    }

    /**
     * Get pushedTime
     *
     * @return \DateTime 
     */
    public function getPushedTime()
    {
        return $this->pushedTime;
    }

    /**
     * Set actionUrl
     *
     * @param string $actionUrl
     * @return Notification_User
     */
    public function setActionUrl($actionUrl)
    {
        $this->actionUrl = $actionUrl;

        return $this;
    }

    /**
     * Get actionUrl
     *
     * @return string 
     */
    public function getActionUrl()
    {
        return $this->actionUrl;
    }

    /**
     * Set isClicked
     *
     * @param boolean $isClicked
     * @return Notification_User
     */
    public function setIsClicked($isClicked)
    {
        $this->isClicked = $isClicked;

        return $this;
    }

    /**
     * Get isClicked
     *
     * @return boolean 
     */
    public function getIsClicked()
    {
        return $this->isClicked;
    }

    /**
     * Set notification
     *
     * @param \AppBundle\Entity\Notification $notification
     * @return Notification_User
     */
    public function setNotification(\AppBundle\Entity\Notification $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \AppBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Notification_User
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
