<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Role
 * @ORM\Table(name="UserLog")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class UserLogs
{
    /**
     * @var int
     *
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="admin_name", type="string", nullable=true)
     */
    private $adminname;

    /**
     * @var string
     * @ORM\Column(name="med_id", type="string", nullable=true)
     */
    private $medId;


    /**
     * @var string
     * @ORM\Column(name="value", type="text", nullable=true)
     */
    private $value;

    /**
     * @var string
     * @ORM\Column(name="action", type="text", nullable=true)
     */
    private $action;

    /**
     * @var string
     * @ORM\Column(name="ipaddress", type="string", nullable=true)
     */
    private $ipaddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetime", nullable=true)
     */
    private $createdDate;



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
     * Set adminname
     *
     * @param string $adminname
     *
     * @return UserLogs
     */
    public function setAdminname($adminname)
    {
        $this->adminname = $adminname;

        return $this;
    }

    /**
     * Get adminname
     *
     * @return string
     */
    public function getAdminname()
    {
        return $this->adminname;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return UserLogs
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set ipaddress
     *
     * @param string $ipaddress
     *
     * @return UserLogs
     */
    public function setIpaddress($ipaddress)
    {
        $this->ipaddress = $ipaddress;

        return $this;
    }

    /**
     * Get ipaddress
     *
     * @return string
     */
    public function getIpaddress()
    {
        return $this->ipaddress;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return UserLogs
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
     * Set value
     *
     * @param string $value
     *
     * @return UserLogs
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set medId
     *
     * @param string $medId
     *
     * @return UserLogs
     */
    public function setMedId($medId)
    {
        $this->medId = $medId;

        return $this;
    }

    /**
     * Get medId
     *
     * @return string
     */
    public function getMedId()
    {
        return $this->medId;
    }
}
