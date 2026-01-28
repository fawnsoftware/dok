<?php

namespace App\Console\Commands\Dok;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;
use Statamic\Facades\Nav;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\progress;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dok:create:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new documentation release for an existing project.';

    /**
     * Messages to show after progress completion.
     */
    protected array $afterProgressMessages = [];

    protected $progress = null;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $projectId = $this->askForProjectId();

        $projectEntry = Entry::find($projectId);

        $projectName = $projectEntry->name;

        $releaseVersion = $this->askForReleaseVersion();

        $releaseHandle = $this->askForReleaseHandle($projectName, $releaseVersion);

        $route = $this->askForRoute($projectName, $releaseVersion);

        $releaseIsStable = $this->askForIsReleaseStable();

        $this->startProgress('Starting scaffolding', 10);

        $this->advance('Creating collection');
        $docsCollection = Collection::make($releaseHandle)
            ->title("{$projectName} {$releaseVersion}")
            ->dated(false)
            ->icon('programming-code-block')
            ->template('docs/show')
            ->layout('docs/layout')
            ->routes($route)
            ->structureContents([
                'root' => true,
            ])
            ->requiresSlugs(true);

        $this->advance('Creating navigation');
        $navigation = Nav::make($releaseHandle)
            ->title("{$projectName} {$releaseVersion}")
            ->collections([$releaseHandle]);

        $this->advance('Creating release entry');
        $releaseEntry = Entry::make()
            ->collection('releases')
            ->blueprint('release')
            ->slug(Str::slug("{$projectName}-{$releaseVersion}"))
            ->data([
                'title' => $releaseVersion,
                'version_collection' => $releaseHandle,
                'version_navigation' => $releaseHandle,
                'version' => $releaseVersion,
            ]);

        $this->advance('Saving docs collection');
        $docsCollection->save();

        $this->advance('Saving release entry');
        $releaseEntry->save();

        $this->advance('Saving navigation');
        $navigation->save();

        $this->advance('Adding release to project structure');
        $releaseEntry->collection()
            ->structure()
            ->in($releaseEntry->locale())
            ->appendTo($projectEntry->id(), $releaseEntry->id())
            ->save();

        $this->advance('Setting stable release');
        if ($releaseIsStable) {
            $projectEntry->set('latest_stable_release', $releaseEntry->id());

            $projectEntry->save();
        }

        $this->advance('Setting up default blueprint');
        if (File::exists(base_path().'/resources/blueprints/default-docs.yaml')) {
            $defaultBlueprint = \Statamic\Facades\YAML::file(base_path().'/resources/blueprints/default-docs.yaml')->parse();

            $defaultBlueprint['title'] = $docsCollection->title();

            $docsCollection->entryBlueprint()->setContents($defaultBlueprint)->save();
        } else {
            $this->afterProgressMessages[] = fn () => $this->components->warn('Tried to assign blueprint [resources/blueprints/default-docs.yaml] to documentation collection, but the file is missing. Falling back to default blueprint.');
        }

        $this->advance('Finishing up');

        $this->endProgress();

        foreach ($this->afterProgressMessages as $callback) {
            $callback();
        }

        $this->components->info('Docs version created successfully.');

        return self::SUCCESS;
    }

    private function askForProjectId()
    {
        return select(
            label: 'The project this release belongs to',
            options: $this->getExistingProjects(),
            required: true,
        );
    }

    private function askForReleaseVersion()
    {
        return text(
            label: 'Release version',
            placeholder: '1.x | 12.x | etc.',
            required: true,
        );
    }

    private function askForReleaseHandle(string $projectName, string $releaseVersion)
    {
        return text(
            label: 'Release handle',
            default: Str::slug($projectName.'_'.$releaseVersion, '_'),
            placeholder: 'plugin_v2',
            required: true,
            hint: 'Collection and navigation handle.',
            validate: fn ($value) => $this->handleExists($value)
                        ? 'This handle already exists. Maybe you already have a release with this version?'
                        : null,
        );
    }

    private function askForRoute(string $projectName, string $releaseVersion)
    {
        $routes = config('dok.create_routes');

        $replacements = [
            '<VERSION>' => $this->versionUrlSlug($releaseVersion),
            '<PROJECT_NAME>' => Str::slug($projectName),
        ];

        // Replace the placeholders with the data
        $routes = collect($routes)
            ->mapWithKeys(function ($value, $key) use ($replacements) {
                $newKey = str_replace(array_keys($replacements), array_values($replacements), $key);
                $newValue = str_replace(array_keys($replacements), array_values($replacements), $value);

                return [$newKey => $newValue];
            })
            ->put('skip', 'Skip this step')
            ->all();

        return select(
            label: 'What route would you like to use?',
            options: $routes,
            required: true,
            hint: 'You can change this later in collection settings.'
        );
    }

    private function askForIsReleaseStable()
    {
        return confirm(
            label: 'Is this the stable release?',
            default: true,
            hint: 'You can change this later in collection settings.'
        );
    }

    private function handleExists(string $handle)
    {
        return Collection::findByHandle($handle) || Nav::findByHandle($handle);
    }

    private function endProgress()
    {
        if ($this->progress) {
            $this->progress->finish();
            $this->progress = null;
        }
    }

    private function startProgress(string $label, int $steps)
    {
        $this->progress = progress(label: $label, steps: $steps);
        $this->progress->start();
    }

    private function advance(string $label)
    {
        if ($this->progress) {
            $this->progress->label($label);
            Sleep::for(500)->milliseconds();
            $this->progress->advance();
        }
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

    private function versionUrlSlug(string $value): string
    {
        // Let's keep the dots in the slugified version because they look nice
        $dotPlaceholder = strtolower(Str::random(12));

        $value = str_replace('.', $dotPlaceholder, $value);

        $value = Str::slug($value);

        $value = str_replace($dotPlaceholder, '.', $value);

        return $value;
    }
}
