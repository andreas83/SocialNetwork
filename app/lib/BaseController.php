<?php

/**
 * Class BaseController
 */
class BaseController
{
    private $response;

    /**
     * @var array
     */
    protected $_templateVars = false;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function assign($name, $value)
    {
        $this->_templateVars[$name] = $value;
    }

    /**
     * @param $data
     */
    public function addHeader($data)
    {
        $this->_templateVars['header'][] = $data;
    }

    /**
     * @param $data
     */
    public function addFooter($data)
    {
        $this->_templateVars['footer'][] = $data;
    }


    /**
     * @param $file
     * @param bool $return
     * @return string
     */
    public function render($file, $return = false)
    {
        $this->_templateVars['footer'][] = "";
        $this->_templateVars['header'][] = "";


        if ($this->_templateVars) {
            foreach ($this->_templateVars as $__key => $__val) {
                $$__key = $__val;
            }
        }

        if ($return) {
            ob_start();
        }

        include_once("app/template/" . $file);

        if ($return) {
            return ob_get_clean();
        }

        return '';
    }


    /**
     * @param $location
     */
    public function redirect($location)
    {
        $this->getResponse()
            ->setHeaders(["Location: $location"])
            ->executeHeaders();
    }


    /**
     * @param $data
     */
    public function asJson($data)
    {
        $this->getResponse()
            ->addHeader('Content-Type: application/json')
            ->setContent($data)
            ->executeHeaders();

        die(json_encode($this->getResponse()));
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!$this->response) {
            $this->response = new Response();
        }

        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    // execute the http headers
    public function __destruct()
    {
        if ($this->response) {
            $this->getResponse()->executeHeaders();
        }


    }
}
