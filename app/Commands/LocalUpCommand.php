<?php

namespace App\Commands;

use App\Helpers\VentonHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use LaravelZero\Framework\Commands\Command;

class LocalUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'local:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start local docker containers.';

    /**
     * The console command docker path.
     */
    private string $dockerPath;

    /**
     * The console command resource path.
     */
    private string $resourcesPath;

    /**
     * Create the console command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->dockerPath = VentonHelper::getLibraryPath() . '/Docker';
        $this->resourcesPath = resource_path('local');
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $filesystem = new Filesystem;

        $filesystem->copyDirectory($this->resourcesPath, $this->dockerPath);

        $process = Process::path($this->dockerPath);

        if (! $this->createDockerNetwork($process)) {
            return;
        }

        if (! $this->createDatabaseDirectory($filesystem)) {
            return;
        }

        $this->createDockerContainers($process);
    }

    private function createDockerNetwork(PendingProcess $process): bool
    {
        $result = $process->run('docker network inspect venton');

        if ($result->failed()) {
            $this->line('Network venton does not exist. Creating it now...');
            $result = $process->run('docker network create --driver=bridge --subnet=10.1.0.0/24 --ip-range=10.1.0.0/25 -o "com.docker.network.bridge.name=venton" venton');

            if ($result->failed()) {
                VentonHelper::getExitDetails($this, $result);

                return false;
            } else {
                $this->info('Network venton created successfully.');
            }
        } else {
            $this->info('Network venton already exists.');
        }

        return true;
    }

    private function createDatabaseDirectory(Filesystem $filesystem): bool
    {
        $databasePath = $this->dockerPath . '/database';

        if (! $filesystem->exists($databasePath)) {
            if ($filesystem->makeDirectory($databasePath, 0755, true)) {
                $this->info('Database directory created successfully.');
            } else {
                $this->error('Database directory could not be created.');

                return false;
            }
        } else {
            $this->info('Database directory already exists.');
        }

        return true;
    }

    private function createDockerContainers(PendingProcess $process): void
    {
        $result = $process->run('docker compose --project-name venton_local up -d --force-recreate');

        if ($result->failed()) {
            VentonHelper::getExitDetails($this, $result);
        } else {
            $this->info('Docker containers started successfully.');
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
