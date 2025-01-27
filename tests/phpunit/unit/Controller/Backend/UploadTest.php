<?php

namespace Bolt\Tests\Controller\Backend;

use Bolt\Common\Json;
use Bolt\Tests\Controller\ControllerUnitTest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class to test correct operation of Upload Controller.
 *
 * @author Ross Riley <riley.ross@gmail.com>
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 **/
class UploadTest extends ControllerUnitTest
{
    public function setUp(): void
    {
        @mkdir(PHPUNIT_ROOT . '/resources/files', 0777, true);
        chmod(PHPUNIT_ROOT . '/resources/files', 0777);
    }

    public function tearDown(): void
    {
        @unlink(TEST_ROOT . '/app/cache/config-cache.json');
        $this->getService('filesystem')->getDir('files://')->setVisibility('public');
    }

    public function testResponses()
    {
        $this->getApp()->flush();
        $this->setRequest(Request::create(
            '/upload/files',
            'POST',
            [],
            [],
            [],
            []
        ));

        $response = $this->controller()->uploadNamespace($this->getRequest(), 'files');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // We haven't posted a file so an empty resultset should be returned
        $content = Json::parse($response->getContent());
        $this->assertEquals(0, count($content));
    }

    public function testUpload()
    {
        $this->getApp()->flush();
        $request = $this->getFileRequest();
        $response = $this->controller()->uploadNamespace($request, 'files');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = Json::parse($response->getContent());
        $this->assertEquals(1, count($content));
    }

    public function testInvalidFiletype()
    {
        $this->getApp()->flush();
        $this->setRequest(Request::create(
            '/upload/files',
            'POST',
            [],
            [],
            [
                'files' => [
                    [
                        'tmp_name' => PHPUNIT_ROOT . '/resources/generic-logo-evil.exe',
                        'name'     => 'logo.exe',
                    ],
                ],
            ],
            []
        ));

        $response = $this->controller()->uploadNamespace($this->getRequest(), 'files');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $content = Json::parse($response->getContent());
        $file = $content[0];
        $this->assertArrayHasKey('error', $file);
        $this->assertRegExp('/extension/i', $file['error']);
    }

    public function testHandlerParsing()
    {
        $this->getApp()->flush();
        $this->setRequest(Request::create(
            '/upload/files',
            'POST',
            ['handler' => 'files://'],
            [],
            [
                'files' => [
                    [
                        'tmp_name' => PHPUNIT_ROOT . '/resources/generic-logo.png',
                        'name'     => 'logo.png',
                    ],
                ],
            ],
            []
        ));

        $response = $this->controller()->uploadNamespace($this->getRequest(), 'files');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    /**
     * @expectedException \Bolt\Filesystem\Exception\FileNotFoundException
     * @expectedExceptionMessage File not found at path: logo.png
     */
    public function testMultipleHandlerParsing()
    {
        $this->getApp()->flush();
        $this->setRequest(Request::create(
            '/upload/files',
            'POST',
            ['handler' => ['files://', 'ftp://']],
            [],
            [
                'files' => [
                    [
                        'tmp_name' => __DIR__ . '/resources/generic-logo.png',
                        'name'     => 'logo.png',
                    ],
                ],
            ],
            []
        ));

        // Not properly implemented as yet, this will need to be revisited on implementation
        $this->controller()->uploadNamespace($this->getRequest(), 'files');
    }

    public function testFileObjectUploads()
    {
        $this->getApp()->flush();
        $this->setRequest(Request::create(
            '/upload/files',
            'POST',
            [],
            [],
            [
                'files' => [new UploadedFile(PHPUNIT_ROOT . '/resources/generic-logo.png', 'logo.png')],
            ],
            []
        ));
        $response = $this->controller()->uploadNamespace($this->getRequest(), 'files');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    protected function getFileRequest($namespace = 'files')
    {
        $this->setRequest(Request::create(
            '/upload/' . $namespace,
            'POST',
            [],
            [],
            [
                'files' => [
                    [
                        'tmp_name' => PHPUNIT_ROOT . '/resources/generic-logo.png',
                        'name'     => 'logo.png',
                    ],
                ],
            ],
            []
        ));

        return $this->getRequest();
    }

    /**
     * @return \Bolt\Controller\Backend\Upload
     */
    protected function controller()
    {
        return $this->getService('controller.backend.upload');
    }
}
