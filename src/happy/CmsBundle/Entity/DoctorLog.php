<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;

/**
 * DoctorLog
 *
 * @ORM\Table(name="DoctorLog")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DoctorLog
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
     * @ORM\Column(name="doctor_name", type="string", length=255,  nullable=true)
     */
    private $doctorName;

    /**
     * @var string
     *
     * @ORM\Column(name="doctor_id", type="string", length=255,  nullable=true)
     */
    private $doctorId;

    /**
     * @var int
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255,  nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255,  nullable=true)
     */
    private $position;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_confirm", type="boolean",  nullable=true)
     */
    private $isConfirm;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=255,  nullable=true)
     */
    private $descr;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_back", type="boolean",  nullable=true)
     */
    private $isBack;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approve_date", type="datetime", nullable=true)
     */
    private $aprroveDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedDate(new \DateTime("now"));
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->setUpdatedDate(new \DateTime("now"));
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
     * Set doctorName
     *
     * @param string $doctorName
     *
     * @return DoctorLog
     */
    public function setDoctorName($doctorName)
    {
        $this->doctorName = $doctorName;

        return $this;
    }

    /**
     * Get doctorName
     *
     * @return string
     */
    public function getDoctorName()
    {
        return $this->doctorName;
    }

    /**
     * Set doctorId
     *
     * @param string $doctorId
     *
     * @return DoctorLog
     */
    public function setDoctorId($doctorId)
    {
        $this->doctorId = $doctorId;

        return $this;
    }

    /**
     * Get doctorId
     *
     * @return string
     */
    public function getDoctorId()
    {
        return $this->doctorId;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return DoctorLog
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return DoctorLog
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return DoctorLog
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return DoctorLog
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
     * Set aprroveDate
     *
     * @param \DateTime $aprroveDate
     *
     * @return DoctorLog
     */
    public function setAprroveDate($aprroveDate)
    {
        $this->aprroveDate = $aprroveDate;

        return $this;
    }

    /**
     * Get aprroveDate
     *
     * @return \DateTime
     */
    public function getAprroveDate()
    {
        return $this->aprroveDate;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return DoctorLog
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
     * Set isConfirm
     *
     * @param boolean $isConfirm
     *
     * @return DoctorLog
     */
    public function setIsConfirm($isConfirm)
    {
        $this->isConfirm = $isConfirm;

        return $this;
    }

    /**
     * Get isConfirm
     *
     * @return boolean
     */
    public function getIsConfirm()
    {
        return $this->isConfirm;
    }

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return DoctorLog
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set isBack
     *
     * @param boolean $isBack
     *
     * @return DoctorLog
     */
    public function setIsBack($isBack)
    {
        $this->isBack = $isBack;

        return $this;
    }

    /**
     * Get isBack
     *
     * @return boolean
     */
    public function getIsBack()
    {
        return $this->isBack;
    }
}
