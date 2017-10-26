<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DoctorQpay
 *
 * @ORM\Table(name="DoctorQpay")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class DoctorQpay
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
     * @ORM\ManyToOne(targetEntity="DoctorType")
     * @ORM\JoinColumn(name="doctor_type", referencedColumnName="id", nullable=true)
     */
    private $doctorType;

    /**
     * @ORM\ManyToOne(targetEntity="Doctors")
     * @ORM\JoinColumn(name="doctor", referencedColumnName="id", nullable=true)
     */
    private $doctor;


    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255,  nullable=true)
     */
    private $photo;

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
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $imagefile;


    /**
     * Get Image
     *
     * @return UploadedFile
     */
    public function getImageFile()
    {
        return $this->imagefile;
    }

    /**
     * Set Image
     *
     * @param UploadedFile $file
     */
    public function setImageFile(UploadedFile $file = null)
    {
        $this->imagefile = $file;
    }

    /**
     *
     * @param $container
     */
    public function uploadImage(Container $container)
    {

        if (null === $this->getImageFile()) {
            return;
        }

        $resources = $container->getParameter('localstatfolder');

        $dir = 'doctors/qpay';
        $filename = $this->getImageFile()->getFilename() . '.' . $this->getImageFile()->guessExtension();
        $this->getImageFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->photo = $path;
        $this->imagefile = null;
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return DoctorQpay
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
     * Set photo
     *
     * @param string $photo
     *
     * @return DoctorQpay
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return DoctorQpay
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
     * @return DoctorQpay
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
     * Set doctorType
     *
     * @param \happy\CmsBundle\Entity\DoctorType $doctorType
     *
     * @return DoctorQpay
     */
    public function setDoctorType(\happy\CmsBundle\Entity\DoctorType $doctorType = null)
    {
        $this->doctorType = $doctorType;

        return $this;
    }

    /**
     * Get doctorType
     *
     * @return \happy\CmsBundle\Entity\DoctorType
     */
    public function getDoctorType()
    {
        return $this->doctorType;
    }

    /**
     * Set doctor
     *
     * @param \happy\CmsBundle\Entity\Doctors $doctor
     *
     * @return DoctorQpay
     */
    public function setDoctor(\happy\CmsBundle\Entity\Doctors $doctor = null)
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * Get doctor
     *
     * @return \happy\CmsBundle\Entity\Doctors
     */
    public function getDoctor()
    {
        return $this->doctor;
    }
}
