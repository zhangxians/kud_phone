<?php

namespace App\Jobs;

use App\Facades\LogsRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Request;

class LogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    protected $log;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        try{
            $user_id=request()->user()->id;
        }catch (\Exception $e){
            $user_id=0;
        }
        $this->log = [
            'user_id' =>$user_id,
            'user_ip'=>request::ip(),
            'url'=>request::getUri(),
            'msg' => $params['msg'],
            'data' => $params['data'],
            'type' => $params['type'],
            'created_at'=>$params['created_at']
        ];
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        LogsRepository::store($this->log);
    }
}
