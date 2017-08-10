<?php

namespace happy\WebBundle\Util;

use Doctrine\ORM\QueryBuilder;
use happy\WebBundle\Util\CustomLogger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailBox
{
    private $container;
    private $em;
    private $from;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
        $this->from = $container->getParameter('mailer_user');
    }

    public function sendEmail($subject, $to, $body)
    {

        try {
            $txt = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->from)
                ->setTo($to)
                ->setBody($body, 'text/html');

            $result = $this->container->get('mailer')->send($txt);
        } catch (\Swift_TransportException $e) {
            $result = $e->getMessage();
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        $log = $this->container->get('c_log');
        $log->saveToFile('mail', 'mail', $result, $this->container->get('request')->getUri(), $to, $subject);

        return $result;
    }

    

}