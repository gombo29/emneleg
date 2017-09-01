<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * MedicalType
 *
 * @ORM\Table(name="MedicalType")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class MedicalType
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="img_mobile", type="string", length=100, nullable=true)
     */
    private $imgMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=100, nullable=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="img_active", type="string", length=100, nullable=true)
     */
    private $imgActive;


    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $imagefile;


    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public$imageMobilefile;


    /**
     *
     * @Assert\Image(
     *        mimeTypesMessage = "Зурган файл биш байна!"
     * )
     *
     */
    public $imageActivefile;


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
     * Get Image
     *
     * @return UploadedFile
     */
    public function getImageActiveFile()
    {
        return $this->imageActivefile;
    }


    /**
     * Set Image
     *
     * @param UploadedFile $file
     */
    public function setImageActiveFile(UploadedFile $file = null)
    {
        $this->imageActivefile = $file;
    }


    /**
     * Get Image
     *
     * @return UploadedFile
     */
    public function getImageMobileFile()
    {
        return $this->imageMobilefile;
    }


    /**
     * Set Image
     *
     * @param UploadedFile $file
     */
    public function setImageMobileFile(UploadedFile $file = null)
    {
        $this->imageMobilefile = $file;
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
     * @return MedicalType
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
     * @return MedicalType
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
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

        $dir = 'medical_type/img/full';
        $filename = $this->getImageFile()->getFilename() . '.' . $this->getImageFile()->guessExtension();
        $this->getImageFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->setImg($path);
        $this->imagefile = null;
    }


    /**
     *
     * @param $container
     */
    public function uploadImageActive(Container $container)
    {

        if (null === $this->getImageActiveFile()) {
            return;
        }

        $resources = $container->getParameter('localstatfolder');

        $dir = 'medical_type/img/active';
        $filename = $this->getImageActiveFile()->getFilename() . '.' . $this->getImageActiveFile()->guessExtension();
        $this->getImageActiveFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->setImgActive($path);
        $this->imageActivefile = null;
    }

    /**
     *
     * @param $container
     */
    public function uploadImageMobile(Container $container)
    {

        if (null === $this->getImageMobileFile()) {
            return;
        }

        $resources = $container->getParameter('localstatfolder');

        $dir = 'medical_type/img/active';
        $filename = $this->getImageMobileFile()->getFilename() . '.' . $this->getImageMobileFile()->guessExtension();
        $this->getImageMobileFile()->move(
            $resources . '/' . $dir, $filename
        );
        $path = $dir . "/" . $filename;
        $this->setImgMobile($path);
        $this->imageMobilefile = null;
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
     * @return MedicalType
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
     * Set imgMobile
     *
     * @param string $imgMobile
     *
     * @return MedicalType
     */
    public function setImgMobile($imgMobile)
    {
        $this->imgMobile = $imgMobile;

        return $this;
    }

    /**
     * Get imgMobile
     *
     * @return string
     */
    public function getImgMobile()
    {
        return $this->imgMobile;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return MedicalType
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set imgActive
     *
     * @param string $imgActive
     *
     * @return MedicalType
     */
    public function setImgActive($imgActive)
    {
        $this->imgActive = $imgActive;

        return $this;
    }

    /**
     * Get imgActive
     *
     * @return string
     */
    public function getImgActive()
    {
        return $this->imgActive;
    }
}
