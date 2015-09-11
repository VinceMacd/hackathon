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

use AppBundle\Entity\Notification;
use AppBundle\Entity\Notification_User;

/**
 * User controller
 *
 * @Route("/notification")
 */
class NotificationController extends FOSRestController
{
    /**
     * Create a notification
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Create a notification and pushes it to the server",
     *   output = "boolean",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     500 = "Returned when something went wrong"
     *   }
     * )
     *
     *
     * @param Request $request
     *
     * @return void
     *
     * @throws AccessDeniedException when something went wrong
     *
     * @Route("/create", name="create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $token = $request->request->get('token');
        $title = $request->request->get('title');
        $content = $request->request->get('content');
        $actionUrl = $request->request->get('actionUrl');
        $actionCaption = $request->request->get('actionCaption') ?: 'More Details';

        if (!$token || !$title) {
            throw $this->createNotFoundException('Required parameter(s) missing');
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneByToken($token);

        if (!$user) {
            throw $this->createNotFoundException('Unknown token');
        }

        $n = $this->saveNotification($token, $title, $actionCaption, $content, $actionUrl);
        $this->sendNotification($n, $actionUrl);

        $view = $this->view(true, 200)->setFormat('json');

        return $view;
    }

    /**
     * Save notification in DB
     *
     * @param  string $token
     * @param  string $title
     * @param  string $actionCaption
     * @param  string $content
     * @param  string $actionUrl
     * @return Notification
     */
    private function saveNotification($token, $title, $actionCaption, $content = null, $actionUrl = null)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->findOneByToken($token);
        $notification = new Notification();
        $notification->setTitle($title)
                     ->setContent($content)
                     ->setType(0)
                     ->setActionCaption($actionCaption)
        ;
        $em->persist($notification);
        $em->flush();

        $notificationUser = new Notification_User();
        $notificationUser->setNotification($notification)
                         ->setUser($user)
                         ->setCreatedTime(new \DateTime())
                         ->setActionUrl($actionUrl)
                         ->setIsClicked(false)
        ;

        $em->persist($notificationUser);
        $em->flush();

        return $notification;
    }

    /**
     * Sends a notification through socket.io
     *
     * @param  Notification $notification
     * @param  string       $actionUrl
     * @return void
     */
    private function sendNotification($notification, $actionUrl = null)
    {
        $data = array(
            'id'            => (string)$notification->getId(),
            'title'         => $notification->getTitle(),
            'content'       => $notification->getContent(),
            'actionCaption' => $notification->getActionCaption(),
            'actionUrl'     => $actionUrl
        );

        $socketClient = $this->get('elephantio_client.ainotifier');
        $socketClient->send('new', ['notification' => json_encode($data)]);
    }
}
