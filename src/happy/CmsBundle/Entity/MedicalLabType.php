<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MedicalLabType
 *
 * @ORM\Table(name="MedicalLabType")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class MedicalLabType
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
     * @ORM\ManyToOne(targetEntity="Medicals")
     * @ORM\JoinColumn(name="medical", referencedColumnName="id", nullable=true)
     */
    private $medical;


    /**
     * @ORM\ManyToOne(targetEntity="LaboratoryType")
     * @ORM\JoinColumn(name="lab_type", referencedColumnName="id", nullable=true)
     */
    private $labType;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer", nullable=true)
     */
    private $price;

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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return MedicalLab
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
     * @return MedicalLab
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
     * Set medical
     *
     * @param \happy\CmsBundle\Entity\Medicals $medical
     *
     * @return MedicalLab
     */
    public function setMedical(\happy\CmsBundle\Entity\Medicals $medical = null)
    {
        $this->medical = $medical;

        return $this;
    }

    /**
     * Get medical
     *
     * @return \happy\CmsBundle\Entity\Medicals
     */
    public function getMedical()
    {
        return $this->medical;
    }

    /**
     * Set labType
     *
     * @param \happy\CmsBundle\Entity\LaboratoryType $labType
     *
     * @return MedicalLab
     */
    public function setLabType(\happy\CmsBundle\Entity\LaboratoryType $labType = null)
    {
        $this->labType = $labType;

        return $this;
    }

    /**
     * Get labType
     *
     * @return \happy\CmsBundle\Entity\LaboratoryType
     */
    public function getLabType()
    {
        return $this->labType;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return MedicalLabType
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
}
