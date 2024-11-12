<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GitPreCommit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git:pre-commit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Git pre-commit hook, with Laravel Docs and Laravel Pint.';

    private int $exitCode = 0;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->checkLaravelPint();

        return $this->exitCode;
    }

    private function checkLaravelPint()
    {
        $this->warn('Checking Laravel Pint ...');

        $process = $this->process('./vendor/bin/pint --test', false);

        $exitCode = $process->getExitCode();

        if ($exitCode !== 0) {
            $this->error('Laravel Pint was not run before commit. (Hint: Run ./vendor/bin/pint before commit.)');
        } else {
            $this->info('Laravel Pint check successful.');
            $this->newLine();
        }

        $this->exitCode = $exitCode;
    }

    private function process(string $command, $shouldWriteOutput = true): Process
    {
        $process = new Process(explode(' ', $command));

        $process->run(function ($type, $line) use ($shouldWriteOutput) {
            if ($shouldWriteOutput) {
                $this->output->write($line);
            }
        });

        return $process;
    }
}
