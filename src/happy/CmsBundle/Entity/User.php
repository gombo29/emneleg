<?php

namespace happy\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity
 *
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function setLocked($locked)
    {
        $this->locked = $locked;
    }
}
