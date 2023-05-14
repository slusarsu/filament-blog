<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class StartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adm:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->call('migrate');
        $this->info("-- migrations done");
        $this->call('optimize:clear');

        $user = User::query()->where('email', 'admin@admin.com')->first();

        if(!$user) {
//            $this->call('cache:forget spatie.permission.cache');
            $this->call('cache:clear');
            $this->call('db:seed');
            $this->info("-- data added to db");
        }

        $this->call('optimize:clear');
    }
}
