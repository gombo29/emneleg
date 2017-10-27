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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="OnlineDoctorType")
     * @ORM\JoinColumn(name="type", referencedColumnName="id", nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="OnlineDoctorQuestion", inversedBy="childrenYes")
     * @ORM\JoinColumn(name="parent_yes_id", referencedColumnName="id", nullable=true)
     */
    private $parentYes;

    /**
     * @ORM\OneToMany(targetEntity="OnlineDoctorQuestion", mappedBy="parentYes" )
     */
    private $childrenYes;

    /**
     * @ORM\ManyToOne(targetEntity="OnlineDoctorQuestion", inversedBy="childrenNo")
     * @ORM\JoinColumn(name="parent_no_id", referencedColumnName="id", nullable=true)
     */
    private $parentNo;

    /**
     * @ORM\OneToMany(targetEntity="OnlineDoctorQuestion", mappedBy="parentYes")
     */
    private $childrenNo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_done", type="boolean",  nullable=true)
     */
    private $isLast;

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
     * Set name
     *
     * @param string $name
     *
     * @return OnlineDoctorQuestion
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
        $this->childrenYes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->childrenNo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set parentYes
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $parentYes
     *
     * @return OnlineDoctorQuestion
     */
    public function setParentYes(\happy\CmsBundle\Entity\OnlineDoctorQuestion $parentYes = null)
    {
        $this->parentYes = $parentYes;

        return $this;
    }

    /**
     * Get parentYes
     *
     * @return \happy\CmsBundle\Entity\OnlineDoctorQuestion
     */
    public function getParentYes()
    {
        return $this->parentYes;
    }

    /**
     * Add childrenYe
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenYe
     *
     * @return OnlineDoctorQuestion
     */
    public function addChildrenYe(\happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenYe)
    {
        $this->childrenYes[] = $childrenYe;

        return $this;
    }

    /**
     * Remove childrenYe
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenYe
     */
    public function removeChildrenYe(\happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenYe)
    {
        $this->childrenYes->removeElement($childrenYe);
    }

    /**
     * Get childrenYes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenYes()
    {
        return $this->childrenYes;
    }

    /**
     * Set parentNo
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $parentNo
     *
     * @return OnlineDoctorQuestion
     */
    public function setParentNo(\happy\CmsBundle\Entity\OnlineDoctorQuestion $parentNo = null)
    {
        $this->parentNo = $parentNo;

        return $this;
    }

    /**
     * Get parentNo
     *
     * @return \happy\CmsBundle\Entity\OnlineDoctorQuestion
     */
    public function getParentNo()
    {
        return $this->parentNo;
    }

    /**
     * Add childrenNo
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenNo
     *
     * @return OnlineDoctorQuestion
     */
    public function addChildrenNo(\happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenNo)
    {
        $this->childrenNo[] = $childrenNo;

        return $this;
    }

    /**
     * Remove childrenNo
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenNo
     */
    public function removeChildrenNo(\happy\CmsBundle\Entity\OnlineDoctorQuestion $childrenNo)
    {
        $this->childrenNo->removeElement($childrenNo);
    }

    /**
     * Get childrenNo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrenNo()
    {
        return $this->childrenNo;
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
}
