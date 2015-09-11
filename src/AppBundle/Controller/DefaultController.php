<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default controller
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/uploadReport", name="uploadReport")
     */
    public function uploadReportAction()
    {
        return $this->render('views/default/uploadReport.html.twig');
    }

    /**
     * @Route("/supplierConfirmation", name="supplierConfirmation")
     */
    public function supplierConfirmationAction()
    {
        return $this->render('views/default/supplierConfirmation.html.twig');
    }

    /**
     * @Route("/barometer", name="barometer")
     */
    public function barometerAction()
    {
        return $this->render('views/default/barometer.html.twig');
    }
}
