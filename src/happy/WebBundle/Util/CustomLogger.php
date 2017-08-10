<?php
/**
 * Created by PhpStorm.
 * User: tsepu
 * Date: 4/9/16
 * Time: 6:03 PM
 */

namespace happy\WebBundle\Util;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomLogger
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct()
    {
//        $this->container = $container;
    }

//    public function saveToBase($trans, $type, $owner, $req, $resp) {
//        $entity = new RechargeLog();
//        $entity = $entity->writeLog($trans, $type, $owner, $req, $resp);
//
//        $em = $this->container->get('doctrine')->getManager();
//        $em->persist($entity);
//        $em->flush();
//    }

    public function saveToFile($name, $info, $result, $error, $url, $params) {
        $date = new \DateTime();
        $file = $name . '-' . $date->format('Y-m-d') . '.log';
        $logger = new Logger($name);
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../../../app/logs/' . $file, Logger::INFO));

        if ($error) {
            $logger->addError('', array(
                'INFO' => $info,
                'ERROR' => $error,
                'RESPONSE' => $result,
                'URL' => $url,
                'PARAMS' => $params,

            ));
        } else {
            $logger->addInfo('', array(
                'INFO' => $info,
                'RESPONSE' => $result,
                'URL' => $url,
                'PARAMS' => $params,

            ));
        }
    }
}