<?php

namespace Tests\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Hive;

class MeasureControllerTest extends AbstractControllerTest
{
    const PREFIX_URL = '/measures';

    private static $apiKey;
    private static $slug;
    private static $user = ['username' => 'dummy user2', 'password' => 'coucou'];

    // === SETUP ===
    public static function setUpBeforeClass()
    {
        $container = static::createClient()->getContainer();

        /** @var \Doctrine\Common\Persistence\ObjectManager $em */
        $em = $container->get('doctrine')->getManager();

        /** @var \FOS\UserBundle\Doctrine\UserManager $userManager */
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setEmail('tmp-user@urbanpotager.com')
                ->setUsername(self::$user['username'])
                ->setPlainPassword(self::$user['password'])
                ->setEnabled(true);

        $userManager->updateUser($user, true);

        $hive = new Hive();
        $hive->setName('tmp hive')
               ->setDescription('description')
               ->setOwner($user);

        $em->persist($hive);

        $em->flush();

        self::$apiKey = $hive->getApiKey();
        self::$slug = $hive->getSlug();
    }

    public static function tearDownAfterClass()
    {
        $container = static::createClient()->getContainer();

        /** @var \Doctrine\Common\Persistence\ObjectManager $em */
        $em = $container->get('doctrine')->getManager();
        $hiveRepository = $em->getRepository('CoreBundle:Hive');

        $hive = $hiveRepository->findOneBy(['apiKey' => self::$apiKey]);

        $em->remove($hive);
        $em->flush();

        /** @var \FOS\UserBundle\Doctrine\UserManager $userManager */
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail('tmp-user@urbanpotager.com');

        $userManager->deleteUser($user);
    }

    // === POST ===
    public function testPostSuccessful()
    {
        $url = self::PREFIX_URL . '?api_key=' . self::$apiKey;

        $params = [
            'type' => 'humidity-inside',
            'value' => 15.45,
        ];

        $this->isSuccessful(Request::METHOD_POST, $url, $params);

        $params['type'] = 'humidity-inside';

        $this->isSuccessful(Request::METHOD_POST, $url, $params);
    }

    public function testPostBadRequest()
    {
        $url = self::PREFIX_URL . '?api_key=' . self::$apiKey;

        $params = [
            'type' => 'humidity-inside',
        ];

        $this->isBadRequest(Request::METHOD_POST, $url, $params);
    }

    public function testPostNotFound()
    {
        $url = self::PREFIX_URL;

        $params = [
            'type' => 'humidity-inside',
            'value' => 15.45,
        ];

        $this->isNotFound(Request::METHOD_POST, $url, $params);
    }

    // === GET ===
    public function testGetSuccessful()
    {
        $baseUrl = '/hives/' . self::$slug . '/measures/';

        $url = $baseUrl . 'humidity-inside';
        $header = $this->getHeaderConnect(self::$user['username'], self::$user['password']);

        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetUnauthorized()
    {
        $baseUrl = '/hives/' . self::$slug . '/measures/';
        $url = $baseUrl . 'humidity-inside';

        $this->isUnauthorized(Request::METHOD_GET, $url);
    }

    public function testGetForbidden()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);
        $baseUrl = '/hives/' . self::$slug . '/measures/';

        $url = $baseUrl . 'humidity-inside';
        $this->isForbidden(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetNotFound()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $fakeHiveUrl = '/hives/' . $this->fakeSlug() . '/measures/humidity-inside';
        $this->isNotFound(Request::METHOD_GET, $fakeHiveUrl, [], $header);

        $fakeMeasureUrl = '/hives/' . self::$slug . '/measures/' . $this->fakeSlug();
        $this->isNotFound(Request::METHOD_GET, $fakeMeasureUrl, [], $header);
    }
}
