<?php
namespace SocialNetwork\app\lib;

class WebSocketUser
{
    public $socket;
    public $id;
    public $headers = array();
    public $handshake = false;
    public $handlingPartialPacket = false;
    public $partialBuffer = "";
    public $sendingContinuous = false;
    public $partialMessage = "";

    public $uid = false;
    public $hasSentClose = false;

    function __construct($id, $socket)
    {
        $this->id = $id;
        $this->socket = $socket;
    }
}