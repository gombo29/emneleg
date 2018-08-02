<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="qpay_invoice")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class QpayInvoice
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
     * @ORM\Column(name="phone_number", type="string", length=100, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=100, nullable=true)
     */
    private $amount;


    /**
     * @ORM\ManyToOne(targetEntity="DoctorType")
     * @ORM\JoinColumn(name="doctor_type", referencedColumnName="id", nullable=true)
     */
    private $doctorType;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=100, nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="invoice_type_id", type="integer")
     */
    private $invoiceTypeId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
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
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return QpayInvoice
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return QpayInvoice
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return QpayInvoice
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set invoiceTypeId
     *
     * @param integer $invoiceTypeId
     *
     * @return QpayInvoice
     */
    public function setInvoiceTypeId($invoiceTypeId)
    {
        $this->invoiceTypeId = $invoiceTypeId;

        return $this;
    }

    /**
     * Get invoiceTypeId
     *
     * @return integer
     */
    public function getInvoiceTypeId()
    {
        return $this->invoiceTypeId;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return QpayInvoice
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
     * @return QpayInvoice
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
     * @return QpayInvoice
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
}
