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
     * @ORM\ManyToOne(targetEntity="OnlineDoctorQuestion", inversedBy="children")
     * @ORM\JoinColumn(name="parent_yes_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="OnlineDoctorQuestion", mappedBy="parent" )
     */
    private $children;

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
     * Set parent
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $parent
     *
     * @return OnlineDoctorQuestion
     */
    public function setParent(\happy\CmsBundle\Entity\OnlineDoctorQuestion $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \happy\CmsBundle\Entity\OnlineDoctorQuestion
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $child
     *
     * @return OnlineDoctorQuestion
     */
    public function addChild(\happy\CmsBundle\Entity\OnlineDoctorQuestion $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \happy\CmsBundle\Entity\OnlineDoctorQuestion $child
     */
    public function removeChild(\happy\CmsBundle\Entity\OnlineDoctorQuestion $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }
}
