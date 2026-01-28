<?php

namespace App\Console\Commands\Dok;

use App\Services\SyncGitHubService;
use Illuminate\Console\Command;

use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;

class SyncGithub extends Command
{
    protected $signature = 'dok:sync:github {--resource=}';

    protected $description = 'Sync documentation from a GitHub repository to the local folder';

    public function handle()
    {
        try {
            $resource = $this->option('resource') ?? select(
                label: 'Resource to sync',
                options: collect(config('dok.resources'))->keys()->all(),
                required: true
            );

            $stats = spin(
                callback: fn () => (new SyncGitHubService)->sync($resource),
                message: 'Syncing...'
            );

            $this->components->info('Sync finished successfully!');

            table(
                headers: ['Files Synced', 'Directories created'],
                rows: [
                    [
                        $stats['files'],
                        $stats['directories'],
                    ],
                ]
            );

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->components->error('Sync failed!');
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

    }
}
