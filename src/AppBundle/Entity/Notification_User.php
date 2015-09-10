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
}
