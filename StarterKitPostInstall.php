<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;

class StarterKitPostInstall
{
    protected string $env = '';

    protected string $config = '';

    public function handle($console)
    {

        $this->exportAndDeleteFile('config/dok.export.php', 'config/dok.php');
        info('[✓] Added config/dok.php');

        $this->config = app('files')->get(base_path('config/dok.php'));
        $this->env = app('files')->get(base_path('.env'));

        $this->chooseCodeHighligher();
        $this->writeFiles();
        $this->finish();
    }

    protected function chooseCodeHighligher(): void
    {
        $selected = select(
            required: true,
            label: 'Which code highligher do you want to use?',
            options: [
                'torchlight-engine' => 'Torchlight Engine/Phiki (Recommended)',
                'none' => 'None',
            ]
        );

        if ($selected === 'torchlight-engine') {
            $this->runCommand(
                command: 'composer require torchlight/engine',
                message: 'Installing Torchlight Engine...'
            );

            return;
        }
    }

    protected function runCommand(string $command, bool $spin = true, string $message = ''): void
    {
        $result = null;

        if ($spin) {
            spin(
                callback: function () use (&$result, $command) {
                    $result = Process::timeout(120)->run($command);
                },
                message: $message
            );
        } else {
            $result = Process::timeout(120)->run($command);
        }

        if ($result->failed()) {
            throw new \RuntimeException("Command failed: {$command}\n".$result->errorOutput());
        }

        echo $result->output();
    }

    protected function writeFiles(): void
    {
        app('files')->put(base_path('config/dok.php'), $this->config);
        app('files')->put(base_path('.env'), $this->env);
    }

    protected function exportAndDeleteFile(string $source, string $destination): void
    {
        $sourcePath = base_path($source);
        $destinationPath = base_path($destination);

        File::ensureDirectoryExists(dirname($destinationPath));

        File::put($destinationPath, File::get($sourcePath));

        File::delete($sourcePath);
    }

    protected function finish(): void
    {
        info('I hope this starterkit helps you! If you have any questions or run into any issues, please create an issue on GitHub. Have a nice day!');
    }
}
