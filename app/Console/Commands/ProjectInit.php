<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ProjectInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project-init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $user = User::where([['username','sadmin']])->first();
        if(!$user){
            $user = new  User();
            $user->username='sadmin';
            $user->password='$2y$10$1VUMhaqxkQjBG91VFCKBAONSNJLFxoLD0volHNq7j2xjHlw9126ea';
            $user->status = 0;
            $user->save();
        }



    }
}
