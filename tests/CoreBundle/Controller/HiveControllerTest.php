<?php

namespace Tests\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Tests\UserBundle\Component\HttpFoundation\File\CustomUploadedFile;

class HiveControllerTest extends AbstractControllerTest
{
    const PREFIX_URL = '/hives';
    static $slugHive;

    // === HELPER ===
    private function paramHive($name)
    {
        return [
            'name' => $name,
            'description' => 'description hive',
        ];
    }

    // === POST ===
    public function testPostSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $params = $this->paramHive('super hive');

        $this->isSuccessful(Request::METHOD_POST, self::PREFIX_URL, $params, $header);
        $hive = $this->getResponseContent('hive');

        $url = self::PREFIX_URL . '/' . $hive['slug'];

        self::$slugHive = $hive['slug'];

        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);
        $hive = $this->getResponseContent('hive');

        $this->assertEquals(
            $hive['name'], $params['name'],
            sprintf('[hive] %s != [params] %s', $hive['name'], $params['name'])
        );
    }

    public function testPostPictureSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive . '/pictures';

        $image = new CustomUploadedFile(
            dirname(__FILE__) . '/../../UserBundle/assets/images/white-square.jpg',
            'white-square.jpg',
            'image/jpg',
            322,
            null,
            true
        );

        $params = [
            'image' => ['file' => $image],
        ];

        $this->isSuccessful(Request::METHOD_POST, $url, $params, $header);
        $image = $this->getResponseContent('image_link');

        $this->assertTrue(!is_null($image));
    }

    public function testPostBadRequest()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $params = [
            'description' => 'super hive',
        ];

        $this->isBadRequest(Request::METHOD_POST, self::PREFIX_URL, $params, $header);
    }

    public function testPostUnauthorized()
    {
        $this->isUnauthorized(Request::METHOD_POST, self::PREFIX_URL);
    }

    // === GET ===
    public function testCGetSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $this->isSuccessful(Request::METHOD_GET, self::PREFIX_URL, [], $header);
    }

    public function testCGetUnauthorized()
    {
        $this->isUnauthorized(Request::METHOD_GET, self::PREFIX_URL);
    }

    public function testGetOwnerSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetUnauthorized()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $params = $this->paramHive('hive');

        $this->isSuccessful(Request::METHOD_POST, self::PREFIX_URL, $params, $header);
        $hive = $this->getResponseContent('hive');

        $url = self::PREFIX_URL . '/' . $hive['slug'];

        $this->isUnauthorized(Request::METHOD_GET, $url);
    }

    public function testGetForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isForbidden(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetNotFound()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_GET, $url, [], $header);
    }

    // === PATCH ===
    public function testPatchSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $params = [
            'name' => 'updated name',
        ];

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isSuccessful(Request::METHOD_PATCH, $url, $params, $header);

        $url = self::PREFIX_URL . '/updated-name';

        self::$slugHive = 'updated-name';

        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);

        $newHive = $this->getResponseContent('hive');

        $this->assertTrue(
            $newHive['name'] == $params['name'],
            sprintf('[inserted] %s != [new] %s', $newHive['name'], $params['name'])
        );
    }

    public function testPatchBadRequest()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $params = [
            'description' => '',
        ];

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isBadRequest(Request::METHOD_PATCH, $url, $params, $header);
    }

    public function testPatchUnauthorized()
    {
        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isUnauthorized(Request::METHOD_PATCH, $url);
    }

    public function testPatchForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isForbidden(Request::METHOD_PATCH, $url, [], $header);
    }

    public function testPatchNotFound()
    {
        $admin = self::ADMIN;
        $header = $this->getHeaderConnect($admin['username'], $admin['password']);

        $url = self::PREFIX_URL . '/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_PATCH, $url, [], $header);
    }

    // === DELETE ===
    public function testDeleteUnauthorized()
    {
        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isUnauthorized(Request::METHOD_DELETE, $url);
    }

    public function testDeleteForbidden()
    {
        $user = self::USER2;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . self::$slugHive;

        $this->isForbidden(Request::METHOD_DELETE, $url, [], $header);
    }

    public function testDeleteNotFound()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . '/' . $this->fakeSlug();

        $this->isNotFound(Request::METHOD_DELETE, $url, [], $header);
    }

    public function testDeleteSuccessful()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $slugs = [self::$slugHive, 'hive'];

        foreach ($slugs as $slug) {
            $url = self::PREFIX_URL . '/' . $slug;

            $this->isSuccessful(Request::METHOD_DELETE, $url, [], $header);
            $this->isNotFound(Request::METHOD_GET, $url, [], $header);
        }
    }
}
