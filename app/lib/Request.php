<?php
namespace app\lib;


/**
 * a simple class to get a request or the raw data
 *
 * Class Request
 */
class Request
{
    /**
     * comparison strings for REQUEST_METHOD
     */
    const POSTMETHOD = 'post';
    const GETMETHOD = "get";
    const PUTMETHDO = "put";
    const DELETEMETHOD = "delete";
    const HEADMETHOD = "head";
    const OPTIONSMETHOD = "options";

    /**
     * @var array
     */
    private $requestData = [];


    /**
     * @var array
     */
    private $postData = [];

    /**
     * @var string
     */
    private $rawData = '';

    /**
     * @var bool
     */
    private $isPost = false;

    /**
     * @var bool
     */
    private $isGet = false;

    /**
     * @var bool
     */
    private $isPut = false;

    /**
     * @var bool
     */
    private $isDelete = false;

    /**
     * @var bool
     */
    private $isHead = false;

    /**
     * @var bool
     */
    private $isOptions = false;

    /**
     *
     */
    public function __construct()
    {
        $this->requestData = [];

        $data = file_get_contents('php://input');

        if ($data && ($jsonData = json_decode($data, true))) {
            $this->requestData = array_merge((array) $_GET,(array)  $_POST, (array) $jsonData);
        }

        $this->rawData = $data;

        if (!isset($_SERVER['REQUEST_METHOD'])) {
            return;
        }

        $this->postData = $_POST;

        // reset the superglobal
        $_POST = [];


        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);

        $this->isPost = $requestMethod == self::POSTMETHOD;
        $this->isGet = $requestMethod == self::GETMETHOD;
        $this->isPut = $requestMethod == self::PUTMETHDO;
        $this->isDelete = $requestMethod == self::DELETEMETHOD;
        $this->isHead = $requestMethod == self::HEADMETHOD;
        $this->isOptions = $requestMethod == self::OPTIONSMETHOD;
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return (bool) $this->isPost;
    }

    /**
     * @return bool
     */
    public function isGet()
    {
        return (bool) $this->isGet;
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        return (bool) $this->isPut;
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return (bool) $this->isDelete;
    }

    /**
     * @return bool
     */
    public function isHead()
    {
        return (bool) $this->isHead;
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        return (bool) $this->isOptions();
    }

    /**
     * @param bool|true $asArray
     */
    public function getJsonData($asArray = false)
    {
        return json_decode($this->rawData, $asArray);
    }


    /**
     * @param string $key
     * @return null
     */
    public function get($key = '')
    {
        if (isset($this->requestData[$key])) {
            return $this->requestData[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @return null
     */
    public function getPost($key = '')
    {
        if (!$key || empty($this->postData[$key])) {
            return null;
        }

        return $this->postData[$key];
    }

    /**
     * @return string
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @return array
     */
    public function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * @param array $requestData
     */
    public function setRequestData($requestData)
    {
        $this->requestData = $requestData;
    }
}