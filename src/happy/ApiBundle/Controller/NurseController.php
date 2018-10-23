<?php

namespace happy\ApiBundle\Controller;

use happy\CmsBundle\Entity\DoctorFeedback;
use happy\CmsBundle\Entity\DoctorLog;
use happy\CmsBundle\Entity\DoctorPosition;
use happy\CmsBundle\Entity\Doctors;
use happy\CmsBundle\Entity\DoctorType;
use happy\CmsBundle\Entity\QpayInvoice;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Nurse controller.
 *
 * @Route("/nurse")
 */
class NurseController extends Controller
{

    /* ======= APP HOME ======= */

    /**
     *  Lists all project entities.
     *
     * @Route("/service-type", name="api_nurse_service_type")
     * @Method("GET")
     *
     */
    public function servicetypeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorType')->createQueryBuilder('n');

        /**@var DoctorType[] $nurseType */
        $nurseType = $qb
            ->select('n.id', 'n.name', 'n.price')
            ->where('n.isShow = 1')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse(
            array(
                "data" => $nurseType,
            )
        );
    }


    /**
     *  Lists all project entities.
     *
     * @Route("/position", name="api_nurse_position")
     * @Method("GET")
     *
     */
    public function positionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('happyCmsBundle:DoctorPosition')->createQueryBuilder('n');

        /**@var DoctorPosition[] $doctorPosition */
        $doctorPosition = $qb
            ->select('n.id', 'n.name')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse(
            array(
                "data" => $doctorPosition,
            )
        );
    }

    /**
     *  Lists all project entities.
     *
     * @Route("/", name="api_nurse_list")
     * @Method({"GET", "POST"})
     *
     */
    public function nurseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $positionId = $request->get('position');
        $serviceId = $request->get('services');

        if ($positionId == null && $serviceId == null) {
            $positionId = 1;
            $serviceId = 1;
        }

        $qb = $em->getRepository('happyCmsBundle:DoctorQpay')->createQueryBuilder('n');
        /**@var Doctors[] $nurse */
        $nurse = $qb
            ->select('d.id as nurseId', 'd.name as nurseName', 'd.like', 'd.dislike', 'd.namtar as nurseNamtar', 'd.photo as nursePhoto', 'd.turshlaga as nurseTurshlaga', 'd.surguuli as NurseSurguuli', 'd.mergeshil as NurseMergeshil', 'dt.price as ServicePrice', 'dt.id as ServiceId', 'dp.id as PositionId')
            ->leftJoin('n.doctor', 'd')
            ->leftJoin('n.doctorType', 'dt')
            ->leftJoin('n.doctorPosition', 'dp')
            ->andWhere('dp.id = :posid')
            ->setParameter('posid', $positionId)
            ->andWhere('dt.id = :serid')
            ->setParameter('serid', $serviceId)
            ->andWhere('d.isDoctor = 0')
            ->andWhere('d.isShow = 1')
            ->orderBy('d.createdDate', 'asc')
            ->groupBy('d.id')
            ->getQuery()
            ->getArrayResult();

        foreach ($nurse as $key => $n) {
            if ($n['nursePhoto'] == null) {
                $nurse[$key]['nursePhoto'] = $this->container->getParameter('localstatfolder') . 'nurse-list.jpg';
            } else {
                $nurse[$key]['nursePhoto'] = $this->container->getParameter('localstatfolder') . $n['nursePhoto'];
            }
        }

        return new JsonResponse(
            array(
                "data" => $nurse,
                "Header" => 'Таны дуудлага бүртгэгдлээ.',
                "Body" => 'Та дуудлагаа баталгаажуулахын тулд манай үйлчилгээний данслуу урьдчилгаа төлбөр шилжүүлэх хэрэгтэй. Та доорх данслуу урьдчилгаа төлбөр шилжүүлнэ үү! ',
                "Info" => 'Хүлээн авах банк: Худалдаа хөгжлийн банк \n
                            Дансны дугаар: 472020237 \n
                            Данс эзэмшигч: Emneleg.mn \n
                            Дүн: 20000₮',
                "Phone" => 'Асуух зүйл байвал: 99031675',
                "Footer" => 'Та дуудлагаа баталгаажуулсан тохиолдолд бид 5 минутын дотор тантай эргэн холбогдох болно.'
            ),
            200, array(
                'Access-Control-Allow-Origin' => '*'
            )
        );
    }

    /**
     *  Lists all project entities.
     *
     * @Route("/log", name="api_nurse_log")
     * @Method({"GET", "POST"})
     *
     */
    public function nurselogAction(Request $request)
    {

        $nurseid = $request->request->get('nurseid');
        $nursename = $request->request->get('nursename');
        $nurseprice = $request->request->get('price');
        $service = $request->request->get('service');
        $position = $request->request->get('position');
        if ($nurseid == null) {
            return new JsonResponse(array(
                'status' => 'nurse id null bn',
            ));
        } else {
            $em = $this->container->get('doctrine')->getManager();
            $doctorlog = new DoctorLog();
            $doctorlog->setType($service);
            $doctorlog->setDoctorId($nurseid);
            $doctorlog->setDoctorName($nursename);
            $doctorlog->setPrice($nurseprice);
            $doctorlog->setPosition($position);
            $em->persist($doctorlog);
            $em->flush();
            return new JsonResponse(array(
                'status' => 'ok',
            ));
        }
    }

    /**
     *  Lists all project entities.
     *
     * @Route("/feedback", name="api_nurse_feedback")
     * @Method({"GET", "POST"})
     *
     */
    public function nurseFeedbackAction(Request $request)
    {
        $nurseid = $request->request->get('id');
        $phonenumber = $request->request->get('phonenumber');
        $content = $request->request->get('content');

        if ($nurseid == null) {
            return new JsonResponse(array(
                'status' => 'nurse id null bn',
            ));
        } else {
            $em = $this->container->get('doctrine')->getManager();
            $doctorFeedback = new DoctorFeedback();
            $doctorFeedback->setDoctorId($nurseid);
            $doctorFeedback->setPhonenumber($phonenumber);
            $doctorFeedback->setContent($content);
            $em->persist($doctorFeedback);
            $em->flush();
            return new JsonResponse(array(
                'status' => 'ok',
            ));
        }
    }


    /**
     *
     * @Route("/payment", name="payment_nurse")
     * @Method("POST")
     *
     */
    public function paymentAction(Request $request)
    {
        $typeId = $request->request->get('typeId');
        $positionId = $request->request->get('positionId');
        $phone = $request->request->get('phone');

        if (!$typeId or !$phone) {
            return new JsonResponse(array(
                'status' => 'error',
                'message' => 'Some parameters are missing.',
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $dt = $em->getRepository('happyCmsBundle:DoctorType')->find($typeId);


        $invoice = new QpayInvoice();
        $invoice->setAmount($dt->getPrice());
        $invoice->setCreatedDate(new \DateTime('now'));
        $invoice->setStatus('pending');
        $invoice->setInvoiceTypeId(1);
        $invoice->setDoctorType($dt);
        if ($positionId) {
            $dp = $em->getRepository('happyCmsBundle:DoctorPosition')->find($positionId);
            $invoice->setDoctorPosition($dp);
        }

        $invoice->setPhoneNumber($phone);
        $em->persist($invoice);
        $em->flush();

        $text = $phone . ' дугаараас ' . $dt->getName() . ' үйлчилгээний ' . $dt->getPrice() . ' төлбөр төлөх хүсэлт явууллаа.';

//99686817
        $url = 'http://43.231.112.201:8080/WebServiceQPayMerchant.asmx/qPay_genInvoiceSimple';
//        $url = 'http://43.231.112.201:8080/WebServiceQPayMerchant.asmx/qPay_genInvoiceWithAccount';
        $body = '{
                    "type": "4",
                    "merchant_code": "INTRFRON_ALPHA",
                    "merchant_verification_code": "Scst9P2RhFzSVqn9",
                    "merchant_customer_code": "85032613",
                    "json_data": {
                        "operator_code": null,
                        "invoice_code":"INTRFRON_ALPHA_INVOICE",
                        "merchant_branch_code": "1",
                         "merchant_invoice_number": "' . $invoice->getId() . '",
                        "invoice_date": "2016-09-12 10:25:56.258",
                        "invoice_description": "' . $text . '",    
                        "invoice_total_amount": "' . $dt->getPrice() . '",
                        "item_btuk_code": "5654454",
                        "vat_flag": "1"
                    }
                }';


        $result = $this->restCall($url, $body);


        $resEncode = json_encode($result);
        $resDecode = json_decode($resEncode);
        $data = array();

        var_dump($result);
        exit();

        if ($resDecode->result_code == 0 && $resDecode->result_msg == 'SUCCESS') {
            foreach ($resDecode->json_data->qPay_deeplink as $bankData) {
                $data[] = array(
                    'name' => $bankData->name,
                    'redirectUrl' => $bankData->link
                );
            }

            return new JsonResponse(array(
                'status' => 'success',
                'message' => 'амжилттай',
                'amount' => $dt->getPrice(),
                'amontUI' => $dt->getPrice() . '.00₮',
                'result' => $data
            ));
        } else {
            return new JsonResponse(array(
                'status' => 'error',
                'message' => 'алдаа гарлаа',
            ));
        }

    }


    public function restCall($url, $request)
    {
        $curl = curl_init($url);
        $headers = array(
            'Content-Type:application/json',
            'Authorization: Basic ' . base64_encode("qpay_intrfron_alpha:xVTn3nEd")
        );

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $curl_response = curl_exec($curl);

        $info = curl_getinfo($curl);
        if ($curl_response === false) {
            curl_close($curl);
            return "error " . $info;
        }
        curl_close($curl);

        $curl_response = substr($curl_response, $info['header_size']);
        $decoded = json_decode($curl_response, true);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            return $decoded->response->errormessage;
        }
        return $decoded;
    }

    /**
     *
     * @Route("/qpay/callback", name="payment_callback")
     *
     *
     */
    public function callbackAction(Request $request)
    {
        $invoiceId = $request->get('invoiceId');
        if (!$invoiceId) {
            return new JsonResponse(array(
                'status' => 'error',
                'message' => 'invoiceId not found'
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository(QpayInvoice::class)->createQueryBuilder('p');
        $invoice = $qb
            ->where('p.id = :invoiceId')
            ->andWhere('p.status = :stat')
            ->setParameter('invoiceId', $invoiceId)
            ->setParameter('stat', 'pending')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (is_null($invoice)) {
            return new JsonResponse(array(
                'status' => 'error',
                'message' => 'invoice not found'
            ));
        }

        $invoice->setStatus('done');
        $em->persist($invoice);
        $em->flush();

        return new JsonResponse(array(
            'status' => 'success',
            'message' => 'амжилттай',
        ));
    }


}