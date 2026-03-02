<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class directInject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:direct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::first()->update([
            'name' => 'digital',
            'password' => bcrypt('ayo022025'),
        ]);

        return Command::SUCCESS;
    }
}
