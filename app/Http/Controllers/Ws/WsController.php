<?php
namespace App\Http\Controllers\Ws;
use App\Http\Controllers\Controller;

class WsController extends Controller
{
    public function push()
    {
        $fd = 1; // Find fd by userId from a map [userId=>fd].
        /**@var \Swoole\WebSocket\Server $swoole */
        $swoole = app('swoole');
        $success = $swoole->push($fd, 'Push data to fd#1 in Controller');
        var_dump($success);
    }
}