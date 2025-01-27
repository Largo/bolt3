<?php

namespace Bolt\Tests\Nut;

use Bolt\Filesystem\Adapter\Local;
use Bolt\Filesystem\Filesystem;
use Bolt\Nut\ConfigSet;
use Bolt\Tests\BoltUnitTest;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class to test src/Nut/ConfigSet.
 *
 * @author Ross Riley <riley.ross@gmail.com>
 */
class ConfigSetTest extends BoltUnitTest
{
    public function testSet()
    {
        $app = $this->getApp();
        $filesystem = new Filesystem(new Local(PHPUNIT_ROOT . '/resources/'));
        $app['filesystem']->mountFilesystem('config', $filesystem);

        $command = new ConfigSet($app);
        $tester = new CommandTester($command);

        // Test successful update
        $tester->execute(['key' => 'sitename', 'value' => 'my test', '--file' => 'config.yml']);
        $this->assertRegExp('/\[OK\] Setting updated to/', $tester->getDisplay());
        $this->assertRegExp('/sitename: my test/', $tester->getDisplay());

        // Test non-existent fails
        $tester->execute(['key' => 'nonexistent', 'value' => 'test', '--file' => 'config.yml']);
        $this->assertRegExp("/The key 'nonexistent' was not found in config:\/\/config.yml/", $tester->getDisplay());
    }

    public function testDefaultFile()
    {
        $app = $this->getApp();
        $filesystem = new Filesystem(new Local(PHPUNIT_ROOT . '/resources/'));
        $app['filesystem']->mountFilesystem('config', $filesystem);

        $command = new ConfigSet($app);
        $tester = new CommandTester($command);
        $tester->execute(['key' => 'nonexistent', 'value' => 'test']);
        $this->assertRegExp("/The key 'nonexistent' was not found in config:\/\/config.yml/", $tester->getDisplay());
    }

    public static function setUpBeforeClass(): void
    {
        @mkdir(PHPUNIT_ROOT . '/resources/', 0777, true);
        @mkdir(TEST_ROOT . '/app/cache/', 0777, true);
        $distname = realpath(TEST_ROOT . '/app/config/config.yml.dist');
        @copy($distname, PHPUNIT_ROOT . '/resources/config.yml');
    }

    public static function tearDownAfterClass(): void
    {
        @unlink(PHPUNIT_ROOT . '/resources/config.yml');
        @unlink(TEST_ROOT . '/app/cache/');
    }
}
