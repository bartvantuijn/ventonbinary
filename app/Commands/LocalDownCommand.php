<?php

namespace App\Commands;

use App\Helpers\VentonHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use LaravelZero\Framework\Commands\Command;

class LocalDownCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local:down';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop local docker containers.';

    /**
     * The console command docker path.
     *
     * @var string
     */
    private string $dockerPath;

    /**
     * Create the console command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->dockerPath = VentonHelper::getLibraryPath() . '/Docker';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $process = Process::path($this->dockerPath);

        $this->stopDockerContainers($process);
    }

    private function stopDockerContainers(PendingProcess $process): void
    {
        $result = $process->run('docker compose --project-name venton_local down');

        if ($result->failed()) {
            VentonHelper::getExitDetails($this, $result);
        } else {
            $this->info('Docker containers stopped successfully.');
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
