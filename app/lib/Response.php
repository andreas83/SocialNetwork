<?php
namespace app\lib;

class Response implements \JsonSerializable
{

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $content;


    /**
     * @param array $content
     * @param array $headers
     */
    public function __construct($content = [], $headers = [])
    {
        if (!$content) {
            $this->content = [];
        } else {
            $this->content = $content;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addContent($key, $value) {
        $this->content[$key] = $value;

        return $this;
    }

    /**
     * @param string $header
     */
    public function addHeader($header) {
        $this->headers[] = $header;

        return $this;
    }

    /**
     * @param string $header
     */
    public function removeHeader($header) {
        if (in_array($header, $this->headers)) {
            unset($this->headers[array_search($header, $this->headers)]);
        }

        return $this;
    }

    /**
     * executes so all the http-headers to be sent
     *
     * @return $this
     */
    public function executeHeaders() {
        $this->headers = array_unique($this->headers);
        foreach ($this->headers as $header) {
            header($header);
        }

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $this->executeHeaders();

        return $this->content;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     */
    public function setContent(array $content)
    {
        $this->content = $content;
        return $this;
    }
}