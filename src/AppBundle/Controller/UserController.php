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

use AppBundle\Entity\User;

/**
 * User controller
 *
 * @Route("/user")
 */
class UserController extends FOSRestController
{
    /**
     * Get single user
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a User for a given id, current user if no ID specified",
     *   output = "AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     *
     * @param int $id the user id
     *
     * @return array
     *
     * @throws AccessDeniedException when no user found
     *
     * @Route("/{id}", name="get", requirements={ "id" = "\d+" }, defaults={ "id" = null } )
     * @Method("GET")
     */
    public function getAction($id)
    {
        $userInfo = $this->container->get('app.user.manager')->getUserInfo($id);

        if (!$userInfo) {
            throw $this->createNotFoundException('User not found');
        }

        $view = $this->view($userInfo, 200)->setFormat('json');

        return $view;
    }

    /**
     * Generate an auth token
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Generate an auth token and returns it for further use",
     *   output = "AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     *
     * @param Request $request
     *
     * @return string
     *
     * @throws AccessDeniedException when no user found
     *
     * @Route("/generateToken", name="generateToken")
     * @Method("POST")
     */
    public function generateTokenAction(Request $request)
    {
        $universalID = $request->request->get('universalID');
        if (!$universalID) {
            throw $this->createNotFoundException('Required universalID missing');
        }

        $credentials = $this->getCustomerCredentials($universalID);
        if (!$credentials) {
            throw $this->createNotFoundException('Customer not found');
        }

        $token = $this->generateToken($credentials['login'], $credentials['password']);
        $view = $this->view($token, 200)->setFormat('json');

        return $view;
    }

    /**
     * Generate token
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Validate the couple user/password and if it exists, returns an auth token",
     *   output = "string",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     *
     * @param string $username the user login
     * @param string $password the user password
     *
     * @return string
     *
     * @throws AccessDeniedException when no user found
     *
     * @Route("/login/username/{username}/password/{password}", name="login")
     * @Method("GET")
     */
    public function loginAction($username, $password)
    {
        if (!$username || !$password) {
            throw $this->createNotFoundException('Required parameter(s) missing');
        }
        $password = md5($password);

        if (!$this->canLogin($username, $password)) {
            throw $this->createNotFoundException('User not found');
        } else {
            $em = $this->getDoctrine()->getManager();

            $token = $this->generateToken($username, $password);
            $user = $em->getRepository('AppBundle:User')->findOneByToken($token);

            if (!$user) {
                $user = new User();
                $user->setToken($token['token'])
                     ->setIp($this->container->get('request')->getClientIp())
                     ->setExpiryDate($this->getExpiryDate())
                ;

                $em->persist($user);
                $em->flush();
            }

            $view = $this->view($token, 200)->setFormat('json');
        }

        return $view;
    }

    /**
     * Checks whether a user can login
     *
     * @param  string $username
     * @param  string $password
     * @return boolean
     */
    private function canLogin($username, $password)
    {
        $serviceUrl = "http://prod-load-balancer-8090-754838643.ap-southeast-1.elb.amazonaws.com/customer-service/"
                    . "customer-legacy/log-in?serverLocation=CN"
                    . "&login=" . $username
                    . "&password=" . $password
        ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serviceUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$this->isJSONValid($response)) {
            return false;
        }

        return true;
    }

    private function getCustomerCredentials($universalID)
    {
        $serviceUrl = "http://prod-load-balancer-8090-754838643.ap-southeast-1.elb.amazonaws.com/customer-service/"
                    . "customer-legacy/" . $universalID . "/is-client-factory/false"
        ;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $serviceUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$this->isJSONValid($response)) {
            return false;
        }
        $customer = json_decode($response);

        $credentials = array(
            'login'    => $customer->clientInformation->login,
            'password' => md5($customer->clientInformation->password)
        );
        return $credentials;
    }

    /**
     * Generate auth token
     *
     * @param  string $username
     * @param  string $password
     * @return string
     */
    private function generateToken($username, $password)
    {
        return array('token' => md5($username . $password));
    }

    private function getExpiryDate()
    {
        $expiryDate = new \DateTime('now');
        $expiryDate->modify('+7 days');

        return $expiryDate;
    }

    private function isJSONValid($string)
    {
        if (is_string($string)) {
            @json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }
}
