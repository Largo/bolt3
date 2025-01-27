<?php

namespace Bolt\Tests\AccessControl;

use Bolt\AccessControl\AccessChecker;
use Bolt\AccessControl\Token\Token;
use Bolt\Logger\FlashLogger;
use Bolt\Storage\Entity;
use Bolt\Tests\BoltUnitTest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test for AccessControl\AccessChecker.
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class AccessCheckerTest extends BoltUnitTest
{
    public function tearDown(): void
    {
        $this->resetDb();
    }

    public function testLoadAccessControl()
    {
        $accessControl = $this->getAccessControl();
        $this->assertInstanceOf(AccessChecker::class, $accessControl);
    }

    /**
     * @expectedException        \Bolt\Exception\AccessControlException
     * @expectedExceptionMessage Can not validate session with an empty token.
     */
    public function testIsValidSessionNoCookie()
    {
        $accessControl = $this->getAccessControl();
        $this->assertInstanceOf(AccessChecker::class, $accessControl);

        $accessControl->isValidSession(null);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage getAuthToken required a name and salt to be provided.
     */
    public function testIsValidSessionInvalidUsername()
    {
        $app = $this->getApp();

        $userEntity = new Entity\Users();
        $tokenEntity = new Entity\Authtoken();

        $userEntity->setUsername(null);
        $tokenEntity->setSalt('vinagre');

        $token = new Token($userEntity, $tokenEntity);

        $app['session']->start();
        $app['session']->set('authentication', $token);

        $accessControl = $this->getAccessControl();
        $this->assertInstanceOf(AccessChecker::class, $accessControl);

        $response = $accessControl->isValidSession($token);
        $this->assertFalse($response);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage getAuthToken required a name and salt to be provided.
     */
    public function testIsValidSessionInvalidSalt()
    {
        $app = $this->getApp();

        $userEntity = new Entity\Users();
        $tokenEntity = new Entity\Authtoken();

        $userEntity->setUsername('koala');
        $tokenEntity->setSalt(null);

        $token = new Token($userEntity, $tokenEntity);

        $app['session']->start();
        $app['session']->set('authentication', $token);

        $accessControl = $this->getAccessControl();
        $this->assertInstanceOf(AccessChecker::class, $accessControl);

        $response = $accessControl->isValidSession($token);
        $this->assertFalse($response);
    }

    public function testIsValidSessionGenerateToken()
    {
        $app = $this->getApp(false);
        $this->addDefaultUser($app);

        $ipAddress = '8.8.8.8';
        $userAgent = 'Bolt PHPUnit tests';

        $logger = $this->getMockBuilder(FlashLogger::class)
            ->setMethods(['info', 'has'])
            ->getMock()
        ;
        $logger
            ->expects($this->atLeastOnce())
            ->method('info')
            ->with($this->equalTo('You have been logged out.'))
        ;
        $this->setService('logger.flash', $logger);

        $userEntity = new Entity\Users();
        $tokenEntity = new Entity\Authtoken();

        $userEntity->setId(42);
        $userEntity->setUsername('koala');
        $tokenEntity->setToken('gum-leaves');
        $tokenEntity->setSalt('vinagre');
        $tokenEntity->setUseragent('Bolt PHPUnit tests');

        $token = new Token($userEntity, $tokenEntity);
        $request = Request::createFromGlobals();
        $request->server->set('REMOTE_ADDR', $ipAddress);
        $request->server->set('HTTP_USER_AGENT', $userAgent);
        $request->cookies->set($app['token.authentication.name'], $token);
        $app['request_stack']->push($request);

        $app['session']->start();
        $app['session']->set('authentication', $token);

        $accessControl = $this->getAccessControl();
        $this->assertInstanceOf(AccessChecker::class, $accessControl);

        $response = $accessControl->isValidSession($token);
        $this->assertFalse($response);
    }

    public function testIsValidSessionValidWithDbTokenNoDbUser()
    {
        $accessControl = $this->getAccessControl();
        $this->assertInstanceOf(AccessChecker::class, $accessControl);

        $app = $this->getApp();
        $userName = 'koala';
        $ipAddress = '8.8.8.8';
        $userAgent = 'Bolt PHPUnit tests';

        $userEntity = new Entity\Users();
        $userEntity->setUsername($userName);

        $tokenEntity = new Entity\Authtoken();
        $tokenEntity->setUserId(42);
        $tokenEntity->setToken('gum-leaves');
        $tokenEntity->setSalt('vinagre');
        $tokenEntity->setIp('8.8.8.8');
        $tokenEntity->setUseragent('Bolt PHPUnit tests');
        $repo = $app['storage']->getRepository(Entity\Authtoken::class);
        $repo->save($tokenEntity);

        $token = new Token($userEntity, $tokenEntity);

        $request = Request::createFromGlobals();
        $request->server->set('REMOTE_ADDR', $ipAddress);
        $request->server->set('HTTP_USER_AGENT', $userAgent);
        $request->cookies->set($app['token.authentication.name'], $token);
        $app['request_stack']->push($request);

        $response = $accessControl->isValidSession($token);
        $this->assertFalse($response);
    }

    /**
     * @return \Bolt\AccessControl\AccessChecker
     */
    protected function getAccessControl()
    {
        $request = Request::createFromGlobals();
        $request->server->set('HTTP_USER_AGENT', 'Bolt PHPUnit tests');

        $app = $this->getApp();

        return $app['access_control'];
    }
}
