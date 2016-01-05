<?php

/**
 * Class BaseController
 */
class BaseController
{

    /**
     * @var array
     */
    private $_templateVars = false;

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
        header("Location: $location");
    }
}
