<?php

namespace Tests\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Hive;
use CoreBundle\Entity\Alert;

class HiveAlertControllerTest extends AbstractControllerTest
{
    private static $prefixUrl;
    private static $slugHive;
    private static $slugAlert;

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

        $typeRepository = $em->getRepository('CoreBundle:Type');
        $type = $typeRepository->findOneBy(['slug' => 'humidity-inside']);

        $alert = new Alert();
        $alert->setName('coucou')
              ->setType($type)
              ->setComparison(Alert::$OPERATOR['equal'])
              ->setDescription('description')
              ->setMessage('message')
              ->setOwner($user)
              ->setThreshold(4.54);

        $em->persist($hive);
        $em->persist($alert);

        $em->flush();

        self::$slugHive = $hive->getSlug();
        self::$slugAlert = $alert->getSlug();
        self::$prefixUrl = '/hives/' . self::$slugHive . '/alerts';
    }

    public static function tearDownAfterClass()
    {
        $container = static::createClient()->getContainer();

        /** @var \Doctrine\Common\Persistence\ObjectManager $em */
        $em = $container->get('doctrine')->getManager();
        $hiveRepository = $em->getRepository('CoreBundle:Hive');
        $alertRepository = $em->getRepository('CoreBundle:Alert');

        $hive = $hiveRepository->findOneBy(['slug' => self::$slugHive]);
        $alert = $alertRepository->findOneBy(['slug' => self::$slugAlert]);

        $em->remove($hive);
        $em->remove($alert);
        $em->flush();
    }

    // === GET ===
    public function testCGetSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $this->isSuccessful(Request::METHOD_GET, self::$prefixUrl, [], $header);
    }

    public function testCGetUnauthorized()
    {
        $this->isUnauthorized(Request::METHOD_GET, self::$prefixUrl);
    }

    public function testCGetForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $this->isForbidden(Request::METHOD_GET, self::$prefixUrl, [], $header);
    }

    public function testCGetNotFound()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = '/hives/' . $this->fakeSlug() . '/alerts';

        $this->isNotFound(Request::METHOD_GET, $url, [], $header);
    }

    // === POST ===
    public function testPostSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::$prefixUrl . '/' . self::$slugAlert;

        $this->isSuccessful(Request::METHOD_POST, $url, [], $header);
    }

    public function testPostUnauthorized()
    {
        $url = self::$prefixUrl . '/' . self::$slugAlert;

        $this->isUnauthorized(Request::METHOD_POST, $url);
    }

    public function testPostForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::$prefixUrl . '/' . self::$slugAlert;

        $this->isForbidden(Request::METHOD_POST, $url, [], $header);
    }

    public function testPostNotFound()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::$prefixUrl . '/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_POST, $url, [], $header);

        $url = '/hives/' . $this->fakeSlug() . '/alerts/' . self::$slugAlert;

        $this->isNotFound(Request::METHOD_POST, $url, [], $header);
    }

    // === DELETE ===
    public function testDeleteUnauthorized()
    {
        $url = self::$prefixUrl . '/' . self::$slugAlert;

        $this->isUnauthorized(Request::METHOD_DELETE, $url);
    }

    public function testDeleteForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::$prefixUrl . '/' . self::$slugAlert;

        $this->isForbidden(Request::METHOD_DELETE, $url, [], $header);
    }

    public function testDeleteSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::$prefixUrl . '/' . self::$slugAlert;

        $this->isSuccessful(Request::METHOD_DELETE, $url, [], $header);
    }

    public function testDeleteNotFound()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::$prefixUrl . '/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_DELETE, $url, [], $header);

        $url = '/hives/' . $this->fakeSlug() . '/' . self::$slugAlert;

        $this->isNotFound(Request::METHOD_DELETE, $url, [], $header);
    }
}
