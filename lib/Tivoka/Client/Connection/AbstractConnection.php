<?php
/**
 * Tivoka - JSON-RPC done right!
 * Copyright (c) 2011-2012 by Marcel Klehr <mklehr@gmx.net>
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package  Tivoka
 * @author Marcel Klehr <mklehr@gmx.net>
 * @author Rafał Wrzeszcz <rafal.wrzeszcz@wrzasq.pl>
 * @copyright (c) 2011-2012, Marcel Klehr
 */

namespace Tivoka\Client\Connection;
use Tivoka\Exception;
use Tivoka\NativeInterface;
use Tivoka\Notification;
use Tivoka\Request;
use Tivoka\Tivoka;

/**
 * JSON-RPC connection
 * @package Tivoka
 */
class AbstractConnection implements ConnectionInterface {
    
    public $spec = Tivoka::SPEC_2_0;
    
    /**
     * Sets the spec version to use for this connection
     * @param string $spec The spec version (e.g.: "2.0")
     */
    public function useSpec($spec) {
        $this->spec = Tivoka::validateSpecVersion($spec);
        return $this;
    }

    /**
     * Sends a JSON-RPC request
     * @param Request $request A Tivoka request
     * @return Request if sent as a batch request the BatchRequest object will be returned
     */
    abstract public function send(Request $request);
    
    /**
     * Send a request directly
     * @param string $method
     * @param array $params
     */
    public function sendRequest($method, $params=null) {
        $request = new Request($method, $params);
        $this->send($request);
        return $request;
    }
    
    /**
     * Send a notification directly
     * @param string $method
     * @param array $params
     */
    public function sendNotification($method, $params=null) {
        $this->send(new Notification($method, $params));
    }
    
    /**
     * Creates a native remote interface for the target server
     * @return Tivoka\Client\NativeInterface
     */
    public function getNativeInterface()
    {
        return new NativeInterface($this);
    }

    /**
     * Constructs connection handler.
     * @param mixed $target Server connection configuration.
     * @return ConnectionInterface
     */
    public static function factory($target)
    {
        // TCP conneciton is defined as ['host' => $host, 'port' => $port] definition
        if (is_array($target) && isset($target['host'], $target['port'])) {
            return new Tcp($target['host'], $target['port']);
        } else {
            // HTTP end-point should be defined just as string
            return new Http($target);
        }
    }
}
