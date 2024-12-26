<?php

namespace App\Commands;

use App\Helpers\VentonHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use LaravelZero\Framework\Commands\Command;

class ProjectGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:generate {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate project configuration files';

    /**
     * The console command project path.
     *
     * @var string
     */
    private string $projectPath;

    /**
     * The console command resource path.
     *
     * @var string
     */
    private string $resourcesPath;

    /**
     * Create the console command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->projectPath = getcwd();
        $this->resourcesPath = resource_path('project');
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filesystem = new Filesystem();

        $projectName = $this->argument('name') ?? $this->ask('What is the project name?');

        while (empty($projectName)) {
            $this->error('Project name cannot be empty.');
            $projectName = $this->ask('What is the project name?');
        }

        if ($this->confirm('Project configuration files will be stored in ' . $this->projectPath, true)) {
            $filesystem->copyDirectory($this->resourcesPath, $this->projectPath);
            $this->configureFiles($filesystem, $projectName);
        }
    }

    private function configureFiles(Filesystem $filesystem, string $projectName): void
    {
        $composeFile = $this->projectPath . '/docker-compose.yaml';
        $dockerFile = $this->projectPath . '/Dockerfile';

        try {
            $file = $filesystem->get($composeFile);
            $configuredFile = str_replace('{PROJECT_NAME}', $projectName, $file);

            $filesystem->put($composeFile, $configuredFile);
            $this->info($composeFile . ' configuration file written.');
        } catch (\Exception $e) {
            $this->error('Failed to configure ' . $composeFile . ': ' . $e->getMessage());
        }

        try {
            $file = $filesystem->get($dockerFile);
            $configuredFile = str_replace('{PROJECT_NAME}', $projectName, $file);

            $filesystem->put($dockerFile, $configuredFile);
            $this->info($dockerFile . ' configuration file written.');
        } catch (\Exception $e) {
            $this->error('Failed to configure ' . $dockerFile . ': ' . $e->getMessage());
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
