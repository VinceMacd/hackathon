<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author      Xavier Besnault <xavier.besnault@gmail.com>
 * @creation    23 November 2014 (02:54)
 * @description Manager for the entity User
 *
 */

namespace AppBundle\Manager;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class UserManager
 */
class UserManager
{
    private $repository;

    public function __construct($em)
    {
        $this->repository = $em->getRepository('AppBundle:User');
    }

    /**
     * Get profile info.
     *
     * @param mixed $id
     *
     * @return User
     */
    public function getUserInfo($id)
    {
        return $this->repository->find($id);
    }
}
