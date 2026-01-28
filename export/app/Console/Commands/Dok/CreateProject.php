<?php

namespace App\Console\Commands\Dok;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Statamic\Facades\Entry;

use function Laravel\Prompts\text;

class CreateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dok:create:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new documentation project.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $projectName = text(
            label: 'Project name',
            placeholder: '  Eg, Laravel, Statamic, React',
            required: true,
            hint: 'You can change this later in collection settings.'
        );

        $projectSlug = text(
            label: 'Project slug',
            placeholder: 'project-my-plugin',
            default: Str::slug('project-'.$projectName),
            required: true,
        );

        $projectEntry = Entry::make()
            ->collection('releases')
            ->blueprint('project')
            ->slug($projectSlug)
            ->data([
                'title' => 'Project: '.$projectName,
                'name' => $projectName,
                'logo_text' => $projectName,
            ]);

        $projectEntry->save();

        $projectEntry->collection()
            ->structure()
            ->in($projectEntry->locale())
            ->append($projectEntry)
            ->save();

        $this->components->info("Project [{$projectName}] created successfully!");

        return self::SUCCESS;
    }

    private function getExistingProjects(): array
    {
        return Entry::query()
            ->where('collection', 'releases')
            ->where('blueprint', 'project')
            ->get()
            ->mapWithKeys(fn ($entry) => [
                $entry->id => $entry->title,
            ])
            ->all();
    }
}
