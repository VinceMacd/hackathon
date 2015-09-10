<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification")
 */
class Notification
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80, nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(name="action_caption", type="string", length=32)
     */
    protected $actionCaption;

    /**
     * @ORM\OneToMany(targetEntity="Notification_User", mappedBy="notification")
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
     * Set title
     *
     * @param string $title
     * @return Notification
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Notification
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set actionCaption
     *
     * @param string $actionCaption
     * @return Notification
     */
    public function setActionCaption($actionCaption)
    {
        $this->actionCaption = $actionCaption;

        return $this;
    }

    /**
     * Get actionCaption
     *
     * @return string
     */
    public function getActionCaption()
    {
        return $this->actionCaption;
    }
}
