<?php

namespace happy\CmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Role
 * @ORM\Table(name="UserRole")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Role implements RoleInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    private $disname;


    /**
     * Role constructor.
     */
    public function __construct($role)
    {
        $this->name = $role;
    }

    /**
     * Get id
     *
     * @return int
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
     * @return Role
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

    public function getRole()
    {
        return $this->name;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Set disname
     *
     * @param string $disname
     *
     * @return Role
     */
    public function setDisname($disname)
    {
        $this->disname = $disname;

        return $this;
    }

    /**
     * Get disname
     *
     * @return string
     */
    public function getDisname()
    {
        return $this->disname;
    }

    /**
     * Set parent
     *
     * @param \happy\CmsBundle\Entity\Role $parent
     *
     * @return Role
     */
    public function setParent(\happy\CmsBundle\Entity\Role $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \happy\CmsBundle\Entity\Role
     */
    public function getParent()
    {
        return $this->parent;
    }
}
