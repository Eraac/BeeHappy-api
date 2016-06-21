<?php

namespace Tests\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class AdminControllerTest extends AbstractControllerTest
{
    const PREFIX_URL = '/admin/';

    // === GET ===
    public function testCGetHiveSuccessful()
    {
        $admin = self::ADMIN;
        $header = $this->getHeaderConnect($admin['username'], $admin['password']);

        $url = self::PREFIX_URL . 'hives';
        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetHiveUnauthorized()
    {
        $url = self::PREFIX_URL . 'hives';
        $this->isUnauthorized(Request::METHOD_GET, $url);
    }

    public function testGetHiveForbidden()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . 'hives';
        $this->isForbidden(Request::METHOD_GET, $url, [], $header);
    }

    public function testCGetUserSuccessful()
    {
        $admin = self::ADMIN;
        $header = $this->getHeaderConnect($admin['username'], $admin['password']);

        $url = self::PREFIX_URL . 'users';
        $this->isSuccessful(Request::METHOD_GET, $url, [], $header);
    }

    public function testGetUserUnauthorized()
    {
        $url = self::PREFIX_URL . 'users';
        $this->isUnauthorized(Request::METHOD_GET, $url);
    }

    public function testGetUserForbidden()
    {
        $user = self::USER1;
        $header = $this->getHeaderConnect($user['username'], $user['password']);

        $url = self::PREFIX_URL . 'users';
        $this->isForbidden(Request::METHOD_GET, $url, [], $header);
    }
}
