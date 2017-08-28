<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Medical
 *
 * @ORM\Table(name="Medicals")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Medicals
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
     * @ORM\Column(name="headline", type="text", nullable=false)
     */
    private $headline;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text",  nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string",length=100,  nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string",length=50,  nullable=true)
     */
    private $email;


    /**
     * @var string
     *
     * @ORM\Column(name="fb_address", type="text", nullable=true)
     */
    private $fbAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string",length=100,  nullable=true)
     */
    private $website;

    /**
     * @var string
     *
     * @ORM\Column(name="bus_station", type="string",length=100,  nullable=true)
     */
    private $busStation;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_parking", type="boolean",  nullable=true)
     */
    private $isParking;

    /**
     * @var int
     * @ORM\Column(name="like_count", type="integer", nullable=true)
     */
    private $likeCount;

    /**
     * @var string
     *
     * @ORM\Column(name="parking_price", type="string",length=100,  nullable=true)
     */
    private $parkingPrice;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_card", type="boolean",  nullable=true)
     */
    private $isCard;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_wifi", type="boolean",  nullable=true)
     */
    private $isWifi;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_tasag", type="boolean",  nullable=true)
     */
    private $isTasag;

    /**
     * @var string
     *
     * @ORM\Column(name="tasag_info", type="text",  nullable=true)
     */
    private $tasagInfo;


    /**
     * @var string
     *
     * @ORM\Column(name="hool_total", type="text",  nullable=true)
     */
    private $hoolTotal;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_saturday_hool", type="boolean",  nullable=true)
     */
    private $isSaturdayHool;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sunday_hool", type="boolean",  nullable=true)
     */
    private $isSundayHool;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_daatgal", type="boolean",  nullable=true)
     */
    private $isDaatgal;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_laboratory", type="boolean",  nullable=true)
     */
    private $isLaboratory;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_ontsloh", type="boolean",  nullable=true)
     */
    private $isOntsloh;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_remove", type="boolean",  nullable=true)
     */
    private $isRemove;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255,  nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="time_table", type="text",  nullable=true)
     */
    private $timeTable;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_doctor", type="boolean",  nullable=true)
     */
    private $isDoctor;

    /**
     * @ORM\OneToMany(targetEntity="MedicalPhoto", mappedBy="medical")
     */
    private $photos;

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
     * @var \DateTime
     *
     */
    public $ehlehDate;

    /**
     * @var \DateTime
     */
    public $duusahDate;

    /**
     * @var string
     *
     * @ORM\Column(name="long_lat", type="string", length=100, nullable=false)
     */
    private $longLat;

    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $imagefile;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedDate(new \DateTime("now"));
//        $this->setIsRemove(0);
//        $this->setLikeCount(0);
//        $this->setIsParking(0);
//        $this->setIsCard(0);
//        $this->setIsTasag(0);
//        $this->setIsSaturdayHool(0);
//        $this->setIsSundayHool(0);
//        $this->setIsLaboratory(0);
//        $this->setIsOntsloh(0);
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
     * @return Medicals
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
     * Set address
     *
     * @param string $address
     *
     * @return Medicals
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
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

        $dir = 'medical/img';
        $filename = $this->getImageFile()->getFilename() . '.' . $this->getImageFile()->guessExtension();
        $this->getImageFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->photo = $path;
        $this->imagefile = null;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Medicals
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
     * Set email
     *
     * @param string $email
     *
     * @return Medicals
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fbAddress
     *
     * @param string $fbAddress
     *
     * @return Medicals
     */
    public function setFbAddress($fbAddress)
    {
        $this->fbAddress = $fbAddress;

        return $this;
    }

    /**
     * Get fbAddress
     *
     * @return string
     */
    public function getFbAddress()
    {
        return $this->fbAddress;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Medicals
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set busStation
     *
     * @param string $busStation
     *
     * @return Medicals
     */
    public function setBusStation($busStation)
    {
        $this->busStation = $busStation;

        return $this;
    }

    /**
     * Get busStation
     *
     * @return string
     */
    public function getBusStation()
    {
        return $this->busStation;
    }

    /**
     * Set isParking
     *
     * @param boolean $isParking
     *
     * @return Medicals
     */
    public function setIsParking($isParking)
    {
        $this->isParking = $isParking;

        return $this;
    }

    /**
     * Get isParking
     *
     * @return boolean
     */
    public function getIsParking()
    {
        return $this->isParking;
    }

    /**
     * Set likeCount
     *
     * @param integer $likeCount
     *
     * @return Medicals
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    /**
     * Get likeCount
     *
     * @return integer
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * Set parkingPrice
     *
     * @param string $parkingPrice
     *
     * @return Medicals
     */
    public function setParkingPrice($parkingPrice)
    {
        $this->parkingPrice = $parkingPrice;

        return $this;
    }

    /**
     * Get parkingPrice
     *
     * @return string
     */
    public function getParkingPrice()
    {
        return $this->parkingPrice;
    }

    /**
     * Set isCard
     *
     * @param boolean $isCard
     *
     * @return Medicals
     */
    public function setIsCard($isCard)
    {
        $this->isCard = $isCard;

        return $this;
    }

    /**
     * Get isCard
     *
     * @return boolean
     */
    public function getIsCard()
    {
        return $this->isCard;
    }

    /**
     * Set isTasag
     *
     * @param boolean $isTasag
     *
     * @return Medicals
     */
    public function setIsTasag($isTasag)
    {
        $this->isTasag = $isTasag;

        return $this;
    }

    /**
     * Get isTasag
     *
     * @return boolean
     */
    public function getIsTasag()
    {
        return $this->isTasag;
    }

    /**
     * Set tasagInfo
     *
     * @param string $tasagInfo
     *
     * @return Medicals
     */
    public function setTasagInfo($tasagInfo)
    {
        $this->tasagInfo = $tasagInfo;

        return $this;
    }

    /**
     * Get tasagInfo
     *
     * @return string
     */
    public function getTasagInfo()
    {
        return $this->tasagInfo;
    }

    /**
     * Set hoolTotal
     *
     * @param string $hoolTotal
     *
     * @return Medicals
     */
    public function setHoolTotal($hoolTotal)
    {
        $this->hoolTotal = $hoolTotal;

        return $this;
    }

    /**
     * Get hoolTotal
     *
     * @return string
     */
    public function getHoolTotal()
    {
        return $this->hoolTotal;
    }

    /**
     * Set isSaturdayHool
     *
     * @param boolean $isSaturdayHool
     *
     * @return Medicals
     */
    public function setIsSaturdayHool($isSaturdayHool)
    {
        $this->isSaturdayHool = $isSaturdayHool;

        return $this;
    }

    /**
     * Get isSaturdayHool
     *
     * @return boolean
     */
    public function getIsSaturdayHool()
    {
        return $this->isSaturdayHool;
    }

    /**
     * Set isSundayHool
     *
     * @param boolean $isSundayHool
     *
     * @return Medicals
     */
    public function setIsSundayHool($isSundayHool)
    {
        $this->isSundayHool = $isSundayHool;

        return $this;
    }

    /**
     * Get isSundayHool
     *
     * @return boolean
     */
    public function getIsSundayHool()
    {
        return $this->isSundayHool;
    }

    /**
     * Set isLaboratory
     *
     * @param boolean $isLaboratory
     *
     * @return Medicals
     */
    public function setIsLaboratory($isLaboratory)
    {
        $this->isLaboratory = $isLaboratory;

        return $this;
    }

    /**
     * Get isLaboratory
     *
     * @return boolean
     */
    public function getIsLaboratory()
    {
        return $this->isLaboratory;
    }

    /**
     * Set isOntsloh
     *
     * @param boolean $isOntsloh
     *
     * @return Medicals
     */
    public function setIsOntsloh($isOntsloh)
    {
        $this->isOntsloh = $isOntsloh;

        return $this;
    }

    /**
     * Get isOntsloh
     *
     * @return boolean
     */
    public function getIsOntsloh()
    {
        return $this->isOntsloh;
    }

    /**
     * Set isRemove
     *
     * @param boolean $isRemove
     *
     * @return Medicals
     */
    public function setIsRemove($isRemove)
    {
        $this->isRemove = $isRemove;

        return $this;
    }

    /**
     * Get isRemove
     *
     * @return boolean
     */
    public function getIsRemove()
    {
        return $this->isRemove;
    }

    /**
     * Set timeTable
     *
     * @param string $timeTable
     *
     * @return Medicals
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return Medicals
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
     * @return Medicals
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
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add photo
     *
     * @param \happy\CmsBundle\Entity\MedicalPhoto $photo
     *
     * @return Medicals
     */
    public function addPhoto(\happy\CmsBundle\Entity\MedicalPhoto $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \happy\CmsBundle\Entity\MedicalPhoto $photo
     */
    public function removePhoto(\happy\CmsBundle\Entity\MedicalPhoto $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set isWifi
     *
     * @param boolean $isWifi
     *
     * @return Medicals
     */
    public function setIsWifi($isWifi)
    {
        $this->isWifi = $isWifi;

        return $this;
    }

    /**
     * Get isWifi
     *
     * @return boolean
     */
    public function getIsWifi()
    {
        return $this->isWifi;
    }

    /**
     * Set isDaatgal
     *
     * @param boolean $isDaatgal
     *
     * @return Medicals
     */
    public function setIsDaatgal($isDaatgal)
    {
        $this->isDaatgal = $isDaatgal;

        return $this;
    }

    /**
     * Get isDaatgal
     *
     * @return boolean
     */
    public function getIsDaatgal()
    {
        return $this->isDaatgal;
    }

    /**
     * Set isDoctor
     *
     * @param boolean $isDoctor
     *
     * @return Medicals
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
     * Set longLat
     *
     * @param string $longLat
     *
     * @return Medicals
     */
    public function setLongLat($longLat)
    {
        $this->longLat = $longLat;

        return $this;
    }

    /**
     * Get longLat
     *
     * @return string
     */
    public function getLongLat()
    {
        return $this->longLat;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Medicals
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
     * Set headline
     *
     * @param string $headline
     *
     * @return Medicals
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * Get headline
     *
     * @return string
     */
    public function getHeadline()
    {
        return $this->headline;
    }
}
