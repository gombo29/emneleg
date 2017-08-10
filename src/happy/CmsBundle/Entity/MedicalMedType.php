<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MedicalMedType
 *
 * @ORM\Table(name="MedicalMedType")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class MedicalMedType
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
     * @ORM\ManyToOne(targetEntity="MedicalType")
     * @ORM\JoinColumn(name="medical_type", referencedColumnName="id", nullable=true)
     */
    private $medicalType;

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
     * @return MedicalMedType
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
     * @return MedicalMedType
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
     * @return MedicalMedType
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
     * Set medicalType
     *
     * @param \happy\CmsBundle\Entity\MedicalType $medicalType
     *
     * @return MedicalMedType
     */
    public function setMedicalType(\happy\CmsBundle\Entity\MedicalType $medicalType = null)
    {
        $this->medicalType = $medicalType;

        return $this;
    }

    /**
     * Get medicalType
     *
     * @return \happy\CmsBundle\Entity\MedicalType
     */
    public function getMedicalType()
    {
        return $this->medicalType;
    }
}
