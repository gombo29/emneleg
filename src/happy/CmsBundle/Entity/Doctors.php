<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Doctors
 *
 * @ORM\Table(name="Doctors")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Doctors
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
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=100, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="namtar", type="text",  nullable=true)
     */
    private $namtar;

    /**
     * @var string
     *
     * @ORM\Column(name="turshlaga", type="text",  nullable=true)
     */
    private $turshlaga;

    /**
     * @var string
     *
     * @ORM\Column(name="uzleg_torol", type="text",  nullable=true)
     */
    private $uzlegTorol;

    /**
     * @var string
     *
     * @ORM\Column(name="surguuli", type="text",  nullable=true)
     */
    private $surguuli;

    /**
     * @var string
     *
     * @ORM\Column(name="mergeshil", type="text",  nullable=true)
     */
    private $mergeshil;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_doctor", type="boolean",  nullable=true)
     */
    private $isDoctor;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show", type="boolean",  nullable=true)
     */
    private $isShow;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_require", type="boolean",  nullable=true)
     */
    private $isRequire;

    /**
     * @ORM\ManyToOne(targetEntity="Medicals")
     * @ORM\JoinColumn(name="medical_id", referencedColumnName="id", nullable=true)
     */
    private $medical;

    /**
     * @var string
     *
     * @ORM\Column(name="time_table", type="text",  nullable=true)
     */
    private $timeTable;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255,  nullable=true)
     */
    private $photo;

    /**
     * @var int
     * @ORM\Column(name="star", type="integer", nullable=true)
     */
    private $star;


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
     * @var \DateTime
     *
     */
    public $ehlehDate;

    /**
     * @var \DateTime
     */
    public $duusahDate;


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

        $dir = 'doctors/img';
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
        $this->setIsShow(1);
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
     * @return Doctors
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
     * Set namtar
     *
     * @param string $namtar
     *
     * @return Doctors
     */
    public function setNamtar($namtar)
    {
        $this->namtar = $namtar;

        return $this;
    }

    /**
     * Get namtar
     *
     * @return string
     */
    public function getNamtar()
    {
        return $this->namtar;
    }

    /**
     * Set turshlaga
     *
     * @param string $turshlaga
     *
     * @return Doctors
     */
    public function setTurshlaga($turshlaga)
    {
        $this->turshlaga = $turshlaga;

        return $this;
    }

    /**
     * Get turshlaga
     *
     * @return string
     */
    public function getTurshlaga()
    {
        return $this->turshlaga;
    }

    /**
     * Set uzlegTorol
     *
     * @param string $uzlegTorol
     *
     * @return Doctors
     */
    public function setUzlegTorol($uzlegTorol)
    {
        $this->uzlegTorol = $uzlegTorol;

        return $this;
    }

    /**
     * Get uzlegTorol
     *
     * @return string
     */
    public function getUzlegTorol()
    {
        return $this->uzlegTorol;
    }

    /**
     * Set surguuli
     *
     * @param string $surguuli
     *
     * @return Doctors
     */
    public function setSurguuli($surguuli)
    {
        $this->surguuli = $surguuli;

        return $this;
    }

    /**
     * Get surguuli
     *
     * @return string
     */
    public function getSurguuli()
    {
        return $this->surguuli;
    }

    /**
     * Set mergeshil
     *
     * @param string $mergeshil
     *
     * @return Doctors
     */
    public function setMergeshil($mergeshil)
    {
        $this->mergeshil = $mergeshil;

        return $this;
    }

    /**
     * Get mergeshil
     *
     * @return string
     */
    public function getMergeshil()
    {
        return $this->mergeshil;
    }

    /**
     * Set timeTable
     *
     * @param string $timeTable
     *
     * @return Doctors
     */
    public function setTimeTable($timeTable)
    {
        $this->timeTable = $timeTable;

        return $this;
    }

    /**
     * Get timeTable
     *
     * @return string
     */
    public function getTimeTable()
    {
        return $this->timeTable;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Doctors
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
     * @return Doctors
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
     * @return Doctors
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
     * @return Doctors
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
     * Set isDoctor
     *
     * @param boolean $isDoctor
     *
     * @return Doctors
     */
    public function setIsDoctor($isDoctor)
    {
        $this->isDoctor = $isDoctor;

        return $this;
    }

    /**
     * Get isDoctor
     *
     * @return boolean
     */
    public function getIsDoctor()
    {
        return $this->isDoctor;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Doctors
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set isShow
     *
     * @param boolean $isShow
     *
     * @return Doctors
     */
    public function setIsShow($isShow)
    {
        $this->isShow = $isShow;

        return $this;
    }

    /**
     * Get isShow
     *
     * @return boolean
     */
    public function getIsShow()
    {
        return $this->isShow;
    }

    /**
     * Set isRequire
     *
     * @param boolean $isRequire
     *
     * @return Doctors
     */
    public function setIsRequire($isRequire)
    {
        $this->isRequire = $isRequire;

        return $this;
    }

    /**
     * Get isRequire
     *
     * @return boolean
     */
    public function getIsRequire()
    {
        return $this->isRequire;
    }

    /**
     * Set star
     *
     * @param integer $star
     *
     * @return Doctors
     */
    public function setStar($star)
    {
        $this->star = $star;

        return $this;
    }

    /**
     * Get star
     *
     * @return integer
     */
    public function getStar()
    {
        return $this->star;
    }
}
