<?php

namespace Tests\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tests\UserBundle\Component\HttpFoundation\File\CustomUploadedFile;
use Tests\CoreBundle\Controller\AbstractControllerTest;

class UserControllerTest extends AbstractControllerTest
{
    const PREFIX_URL = '/users';
    const EMAIL = 'tmp-user@beehappy.com';

    private static $token;

    // === SETUP ===
    public static function tearDownAfterClass()
    {
        $container = static::createClient()->getContainer();

        /** @var \FOS\UserBundle\Doctrine\UserManager $userManager */
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail(self::EMAIL);

        $userManager->deleteUser($user);
    }

    // === HELPER ===
    private function getResetToken($email)
    {
        $container = static::createClient()->getContainer();

        /** @var \FOS\UserBundle\Doctrine\UserManager $userManager */
        $userManager = $container->get('fos_user.user_manager');
        $user = $userManager->findUserByEmail($email);

        return $user->getConfirmationToken();
    }

    // === POST ===
    public function testPostTokenSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password'], true);

        $this->assertTrue(!is_null($header));
    }

    public function testPostTokenUnauthorized()
    {
        $header = $this->getHeaderConnect('nope', 'nope', false);

        $this->assertTrue(is_null($header));
    }

    public function testPostCreateSuccessful()
    {
        $image = new CustomUploadedFile(
            dirname(__FILE__) . '/../assets/images/white-square.jpg',
            'white-square.jpg',
            'image/jpg',
            322,
            null,
            true
        );

        $params = [
            'username' => 'super username',
            'email' => self::EMAIL,
            'plainPassword' => 'coucou',
            'image' => ['file' => $image],
        ];

        $this->isSuccessful(Request::METHOD_POST, self::PREFIX_URL, $params);
    }

    public function testPostCreateBadRequest()
    {
        $image = new CustomUploadedFile(
            dirname(__FILE__) . '/../assets/images/bad-image.txt',
            'bad-image.txt',
            'text/plain',
            20
        );

        $params = [
            'username' => 'super username',
            'email' => self::EMAIL,
            'plainPassword' => 'coucou',
            'image' => ['file' => $image],
        ];

        $this->isBadRequest(Request::METHOD_POST, self::PREFIX_URL, $params);

        $params = [
            'username' => 'super username',
            'email' => 'not-an-email',
            'plainPassword' => 'coucou',
        ];

        $this->isBadRequest(Request::METHOD_POST, self::PREFIX_URL, $params);

        $params = [
            'username' => 'super username',
            'email' => 'email@google.com',
        ];

        $this->isBadRequest(Request::METHOD_POST, self::PREFIX_URL, $params);
    }

    public function testPostForgetPasswordSuccessful()
    {
        $params = [
            'username' => self::EMAIL,
        ];

        $this->isSuccessful(Request::METHOD_POST, '/forget-password', $params);

        self::$token = $this->getResetToken($params['username']);
    }

    public function testPostForgetPasswordBadRequest()
    {
        $this->isBadRequest(Request::METHOD_POST, '/forget-password');
    }

    public function testPostForgetPasswordNotFound()
    {
        $params = [
            'username' => 'not-exists@urbanpotager.com',
        ];

        $this->isNotFound(Request::METHOD_POST, '/forget-password', $params);
    }

    public function testPostForgetPasswordConflict()
    {
        $params = [
            'username' => self::EMAIL,
        ];

        $this->isConflict(Request::METHOD_POST, '/forget-password', $params);
    }

    public function testPostResetPasswordBadRequest()
    {
        $url = '/reset-password/' . self::$token;

        $this->isBadRequest(Request::METHOD_POST, $url);
    }

    public function testPostResetPasswordSuccessful()
    {
        $url = '/reset-password/' . self::$token;

        $params = [
            'plainPassword' => 'newpassword',
        ];

        $this->isSuccessful(Request::METHOD_POST, $url, $params);

        $header = $this->getHeaderConnect(self::EMAIL, $params['plainPassword'], true);

        $this->assertTrue(!is_null($header));
    }

    public function testPostResetPasswordNotFound()
    {
        $url = '/reset-password/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_POST, $url);
    }
}
