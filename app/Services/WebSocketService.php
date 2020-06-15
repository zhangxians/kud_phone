<?php


namespace App\Services;

use App\Models\User;
use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Cache;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * @see https://www.swoole.co.uk/docs/modules/swoole-websocket-server
 */
class WebSocketService implements WebSocketHandlerInterface
{
    // Declare constructor without parameters
    public function __construct()
    {
    }

    public function onOpen(Server $server, Request $request)
    {
        // Before the onOpen event is triggered, the HTTP request to establish the WebSocket has passed the Laravel route,
        // so Laravel's Request, Auth information is readable, and Session is readable and writable, but only in the onOpen event.
        // \Log::info('New WebSocket connection', [$request->fd, request()->all(), session()->getId(), session('xxx'), session(['yyy' => time()])]);

        $all   = request()->all();
        $token = $all['token'] ?? false;

        if ($token) {
            $user = User::where('token', $token)->first();
            // 有user时 保存数据
            if ($user) {
                $socketUser            = Cache::get('socketUser') ?? [];
                $socketUser[$user->id] = ['user_id' => $user->id, 'socket_id' => $request->fd, 'token' => $token];
                Cache::forever('socketUser', $socketUser);
                $this->setMessageToAdmin(0,$user->id,$server);
            }

        }

        //$server->push($request->fd, $token);


        // throw new \Exception('an exception');// all exceptions will be ignored, then record them into Swoole log, you need to try/catch them
    }

    public function onMessage(Server $server, Frame $frame)
    {
        // \Log::info('Received message', [$frame->fd, $frame->data, $frame->opcode, $frame->finish]);
        $server->push($frame->fd, date('Y-m-d H:i:s'));
        // throw new \Exception('an exception');// all exceptions will be ignored, then record them into Swoole log, you need to try/catch them
    }

    public function onClose(Server $server, $fd, $reactorId)
    {
        $socketUser = Cache::get('socketUser');
        if ($socketUser && count($socketUser) > 0) {
            foreach ($socketUser as $k => $s) {
                if ($s['socket_id'] == $fd) {
                    $this->setMessageToAdmin(1,$s['user_id'],$server);

                    unset($socketUser[$k]);
                }
            }
        }
        Cache::forever('socketUser', $socketUser);

        // throw new \Exception('an exception');// all exceptions will be ignored, then record them into Swoole log, you need to try/catch them
    }


    protected function setMessageToAdmin($type,$id,$server)
    {
        $socketUser = Cache::get('socketUser') ?? [];
        $socket_id  = false;
        foreach ($socketUser as $s) {
            if ($s['user_id'] == 1) {
                $socket_id = $s['socket_id'];
            }
            continue;
        }
        $msg = $type?'退出登录':'登录';
        if ($socket_id&&$id!=1) {
            $server->push($socket_id, json_encode(['user_id'=>$id,'type'=>$type,'msg'=>"用户 {$id} 已经{$msg}了",'status'=>1]));
        }
    }
}