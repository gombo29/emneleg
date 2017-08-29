<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * MedicalPhoto
 *
 * @ORM\Table(name="MedicalPhoto")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class MedicalPhoto
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
     * @ORM\ManyToOne(targetEntity="Medicals", inversedBy="photos")
     * @ORM\JoinColumn(name="medical_id", referencedColumnName="id", nullable=true)
     */
    private $medical;

    /**
     * @var boolean
     * @ORM\Column(name="is_main", type="boolean" ,nullable=true)
     */
    private $main;

    /**
     * @var string
     *
     * @ORM\Column(name="tailbar", type="text", nullable=true)
     */
    private $tailbar;

    /**
     * @var int
     * @ORM\Column(name="sort_id", type="integer")
     */
    private $sortId;

    /**
     * @var string
     * @ORM\Column(name="path", type="string" ,nullable=true)
     */
    private $path;


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
     *        mimeTypesMessage = "Зурган файл биш байна!",
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
        $this->setSortId(0);
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
     * Set main
     *
     * @param boolean $main
     *
     * @return MedicalPhoto
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set sortId
     *
     * @param integer $sortId
     *
     * @return MedicalPhoto
     */
    public function setSortId($sortId)
    {
        $this->sortId = $sortId;

        return $this;
    }

    /**
     * Get sortId
     *
     * @return integer
     */
    public function getSortId()
    {
        return $this->sortId;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return MedicalPhoto
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return MedicalPhoto
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
     * @return MedicalPhoto
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
     * @return MedicalPhoto
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
        $this->path = $path;

//        $imageGod = $container->get('imagegod');
//        $imageGod->resizeImageToMaxOnlyWidth($resources . $path, $resources . $path, 300);


        $this->imagefile = null;
    }


    /**
     * Set tailbar
     *
     * @param string $tailbar
     *
     * @return MedicalPhoto
     */
    public function setTailbar($tailbar)
    {
        $this->tailbar = $tailbar;

        return $this;
    }

    /**
     * Get tailbar
     *
     * @return string
     */
    public function getTailbar()
    {
        return $this->tailbar;
    }
}
