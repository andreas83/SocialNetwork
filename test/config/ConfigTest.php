<?php

class Config_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    const TEST_DATA_FOLDER = 'testdata';
    const CONFIG_FILE_NAME = 'test.cfg';
    const HTTP_HOST_NAME = 'test.example.com';
    const HTTP_HOST_NAME2 = 'test2.example.com';

    private $testDataPath;

    /**
     * @before
     */
    public function checkDummyConfig()
    {
        if (!$this->testDataPath) {
            $this->testDataPath = __DIR__ . DIRECTORY_SEPARATOR . self::TEST_DATA_FOLDER . DIRECTORY_SEPARATOR ;
        }

        if (!is_dir($this->testDataPath)) {
            throw new \Exception('please create testdata folder!');
        }
    }

    /**
     * @test
     */
    public function checkIfConfigLoadsFileInTargetPath()
    {
        Config::setConfigPath($this->testDataPath);
        Config::loadConfig();

        $this->assertEquals('1', Config::get('testValue'));
    }


    /**
     * @test
     */
    public function checkIfConfigLoadsSubConfigBasedOnHttpHost()
    {
        Config::setSubConfigHostName(self::HTTP_HOST_NAME);
        Config::loadConfig();

        $this->assertEquals('2', Config::get('testValue'));
    }

    /**
     * @test
     */
    public function checkIfConfigNotReloadingIfNotUpdated() {
        Config::setSubConfigHostName(self::HTTP_HOST_NAME);
        Config::loadConfig();

        $this->assertEquals(false, Config::isUpdateConfig());
    }

    /**
     * @test
     */
    public function checkIfConfigUpdateFlagIsSetIfHostNameChanges() {
        Config::setSubConfigHostName(self::HTTP_HOST_NAME);
        Config::setSubConfigHostName(self::HTTP_HOST_NAME2);

        $this->assertEquals(true, Config::isUpdateConfig());
    }

    /**
     * @test
     */
    public function checkIfConfigIsUpdatedIfHostNameChanges() {
        Config::setSubConfigHostName(self::HTTP_HOST_NAME);
        Config::setSubConfigHostName(self::HTTP_HOST_NAME2);

        $this->assertEquals('3', Config::get('testValue'));
    }

}