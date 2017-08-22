<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * DEvice
 *
 * @ORM\Table(name="Device")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Device
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
     * @ORM\Column(name="device_id", type="string", length=255, nullable=true)
     */
    private $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="device_token", type="string", length=255, nullable=true, unique=false)
     */
    private $deviceToken;

    /**
     * @var string
     *
     * @ORM\Column(name="device_name", type="string", length=255, nullable=true)
     */
    private $deviceName;

    /**
     * @var string
     *
     * @ORM\Column(name="device_model", type="string", length=255, nullable=true)
     */
    private $deviceModel;

    /**
     * @var string
     *
     * @ORM\Column(name="app_type", type="string", length=255, nullable=true)
     */
    private $appType;

    /**
     * @var string
     *
     * @ORM\Column(name="osVersion", type="string", length=255, nullable=true)
     */
    private $osVersion;

    /**
     * @var boolean
     * @ORM\Column(name="is_ios", type="boolean",nullable=true)
     */
    private $isIOS;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="alert", type="string", length=255, nullable=true)
     */
    private $alert;

    /**
     * @var string
     *
     * @ORM\Column(name="badge", type="string", length=255, nullable=true)
     */
    private $badge;

    /**
     * @var string
     *
     * @ORM\Column(name="sound", type="string", length=255, nullable=true)
     */
    private $sound;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */

    private $updatedDate;


    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedDate(new \DateTime('now'));
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->setUpdatedDate(new \DateTime('now'));
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
     * Set deviceId
     *
     * @param string $deviceId
     *
     * @return Device
     */
    public function setDeviceId($deviceId)
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    /**
     * Get deviceId
     *
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * Set deviceToken
     *
     * @param string $deviceToken
     *
     * @return Device
     */
    public function setDeviceToken($deviceToken)
    {
        $this->deviceToken = $deviceToken;

        return $this;
    }

    /**
     * Get deviceToken
     *
     * @return string
     */
    public function getDeviceToken()
    {
        return $this->deviceToken;
    }

    /**
     * Set deviceName
     *
     * @param string $deviceName
     *
     * @return Device
     */
    public function setDeviceName($deviceName)
    {
        $this->deviceName = $deviceName;

        return $this;
    }

    /**
     * Get deviceName
     *
     * @return string
     */
    public function getDeviceName()
    {
        return $this->deviceName;
    }

    /**
     * Set deviceModel
     *
     * @param string $deviceModel
     *
     * @return Device
     */
    public function setDeviceModel($deviceModel)
    {
        $this->deviceModel = $deviceModel;

        return $this;
    }

    /**
     * Get deviceModel
     *
     * @return string
     */
    public function getDeviceModel()
    {
        return $this->deviceModel;
    }

    /**
     * Set osVersion
     *
     * @param string $osVersion
     *
     * @return Device
     */
    public function setOsVersion($osVersion)
    {
        $this->osVersion = $osVersion;

        return $this;
    }

    /**
     * Get osVersion
     *
     * @return string
     */
    public function getOsVersion()
    {
        return $this->osVersion;
    }

    /**
     * Set isIOS
     *
     * @param boolean $isIOS
     *
     * @return Device
     */
    public function setIsIOS($isIOS)
    {
        $this->isIOS = $isIOS;

        return $this;
    }

    /**
     * Get isIOS
     *
     * @return boolean
     */
    public function getIsIOS()
    {
        return $this->isIOS;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Device
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set alert
     *
     * @param string $alert
     *
     * @return Device
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;

        return $this;
    }

    /**
     * Get alert
     *
     * @return string
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * Set badge
     *
     * @param string $badge
     *
     * @return Device
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get badge
     *
     * @return string
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * Set sound
     *
     * @param string $sound
     *
     * @return Device
     */
    public function setSound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Device
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return Device
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set appType
     *
     * @param string $appType
     *
     * @return Device
     */
    public function setAppType($appType)
    {
        $this->appType = $appType;

        return $this;
    }

    /**
     * Get appType
     *
     * @return string
     */
    public function getAppType()
    {
        return $this->appType;
    }
}
