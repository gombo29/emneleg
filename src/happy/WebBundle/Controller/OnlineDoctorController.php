<?php

namespace happy\WebBundle\Controller;

use happy\CmsBundle\Entity\Content;
use happy\CmsBundle\Entity\LaboratoryType;
use happy\CmsBundle\Entity\Medicals;
use happy\CmsBundle\Entity\MedicalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class OnlineDoctorController extends Controller
{



    /**
     * @Route("/doctor", name="online_doctor")
     */
    public function homeAction()
    {
        return $this->render('@happyWeb/OnlineDoctor/index.html.twig');
    }
}
