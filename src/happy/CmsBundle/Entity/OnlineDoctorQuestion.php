<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OnlineDoctorQuestion
 *
 * @ORM\Table(name="OnlineDoctorQuestion")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class OnlineDoctorQuestion
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
     * @ORM\ManyToOne(targetEntity="OnlineDoctorType")
     * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="OnlineDoctorQuestion")
     * @ORM\JoinColumn(name="child_yes", referencedColumnName="id", nullable=true)
     */
    private $childYes;

    /**
     * @ORM\ManyToOne(targetEntity="OnlineDoctorQuestion")
     * @ORM\JoinColumn(name="child_no", referencedColumnName="id", nullable=true)
     */
    private $childNo;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_last", type="boolean",  nullable=true)
     */
    private $isLast;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_first", type="boolean",  nullable=true)
     */
    private $isFirst;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text", nullable=true)
     */
    private $descr;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;


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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return OnlineDoctorQuestion
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
     * @return OnlineDoctorQuestion
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
     * Set type
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorType $type
     *
     * @return OnlineDoctorQuestion
     */
    public function setType(\happy\CmsBundle\Entity\OnlineDoctorType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \happy\CmsBundle\Entity\OnlineDoctorType
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set isLast
     *
     * @param boolean $isLast
     *
     * @return OnlineDoctorQuestion
     */
    public function setIsLast($isLast)
    {
        $this->isLast = $isLast;

        return $this;
    }

    /**
     * Get isLast
     *
     * @return boolean
     */
    public function getIsLast()
    {
        return $this->isLast;
    }

    /**
     * Set descr
     *
     * @param string $descr
     *
     * @return OnlineDoctorQuestion
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
     * Set isFirst
     *
     * @param boolean $isFirst
     *
     * @return OnlineDoctorQuestion
     */
    public function setIsFirst($isFirst)
    {
        $this->isFirst = $isFirst;

        return $this;
    }

    /**
     * Get isFirst
     *
     * @return boolean
     */
    public function getIsFirst()
    {
        return $this->isFirst;
    }



    /**
     * Set childYes
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $childYes
     *
     * @return OnlineDoctorQuestion
     */
    public function setChildYes(\happy\CmsBundle\Entity\OnlineDoctorQuestion $childYes = null)
    {
        $this->childYes = $childYes;

        return $this;
    }

    /**
     * Get childYes
     *
     * @return \happy\CmsBundle\Entity\OnlineDoctorQuestion
     */
    public function getChildYes()
    {
        return $this->childYes;
    }

    /**
     * Set childNo
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $childNo
     *
     * @return OnlineDoctorQuestion
     */
    public function setChildNo(\happy\CmsBundle\Entity\OnlineDoctorQuestion $childNo = null)
    {
        $this->childNo = $childNo;

        return $this;
    }

    /**
     * Get childNo
     *
     * @return \happy\CmsBundle\Entity\OnlineDoctorQuestion
     */
    public function getChildNo()
    {
        return $this->childNo;
    }
}
