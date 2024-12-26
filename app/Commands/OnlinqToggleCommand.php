<?php

namespace App\Commands;

use App\Helpers\VentonHelper;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Filesystem\Filesystem;
use LaravelZero\Framework\Commands\Command;

class OnlinqToggleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'onlinq:toggle {state?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle Onlinq NPM and Composer configuration.';

    /**
     * The console command npm file.
     *
     * @var string
     */
    private string $npmFile;

    /**
     * The console command composer file.
     *
     * @var string
     */
    private string $composerFile;

    /**
     * Create the console command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->npmFile = VentonHelper::getHomePath() . '/.npmrc';
        $this->composerFile = VentonHelper::getHomePath() . '/.composer/config';;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $filesystem = new Filesystem();

        if (!VentonHelper::getHomePath() == '') {
            $this->toggleBkExtension($filesystem, $this->npmFile);
            $this->toggleBkExtension($filesystem, $this->composerFile);
        }
    }

    private function toggleBkExtension(Filesystem $filesystem, string $file): void
    {
        $original = $file;
        $backup = $file . '.bk';

        if ($filesystem->exists($original) && $this->argument('state') !== 'up') {
            $filesystem->move($original, $backup);
            $this->info('Renamed original to ' . $backup);
        } else if ($filesystem->exists($backup) && $this->argument('state') !== 'down') {
            $filesystem->move($backup, $original);
            $this->info('Renamed backup to ' . $original);
        } else {
            $this->error($original . ' does not exist.');
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
