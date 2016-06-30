<?php

namespace Tests\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Hive;

class NotificationControllerTest extends AbstractControllerTest
{
    const PREFIX_URL = '/notifications';
    static $slugHive;

    // === SETUP ===
    public static function setUpBeforeClass()
    {
        $container = static::createClient()->getContainer();

        /** @var \Doctrine\Common\Persistence\ObjectManager $em */
        $em = $container->get('doctrine')->getManager();

        /** @var \FOS\UserBundle\Doctrine\UserManager $userManager */
        $userManager = $container->get('fos_user.user_manager');
        $user = self::USER1;
        $user = $userManager->findUserByUsername($user['username']);

        $hive = new Hive();
        $hive->setName('tmp hive')
            ->setDescription('description')
            ->setOwner($user);

        $em->persist($hive);

        $em->flush();

        self::$slugHive = $hive->getSlug();
    }

    public static function tearDownAfterClass()
    {
        $container = static::createClient()->getContainer();

        /** @var \Doctrine\Common\Persistence\ObjectManager $em */
        $em = $container->get('doctrine')->getManager();
        $hiveRepository = $em->getRepository('CoreBundle:Hive');

        $hive = $hiveRepository->findOneBy(['slug' => self::$slugHive]);

        $em->remove($hive);
        $em->flush();
    }

    // === GET ===
    public function testGetNotificationSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $this->isSuccessful(Request::METHOD_GET, self::PREFIX_URL, [], $header);
    }

    public function testGetNotificationUnauthorized()
    {
        $this->isUnauthorized(Request::METHOD_GET, self::PREFIX_URL);
    }

    public function testGetNotificationHiveSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetNotificationHiveUnauthorized()
    {
        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isUnauthorized(Request::METHOD_GET, $url);
    }

    public function testGetNotificationHiveForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isForbidden(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetNotificationHiveNotFound()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_GET, $url, [], $header);
    }
}
