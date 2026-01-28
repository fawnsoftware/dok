@extends('statamic::layout')
@section('title', 'Github Sync')

@section('content')
<div
>
    <ui-header icon="sync" title="Remote Sync" />

    @if (empty(config('dok.resources')))
        <ui-alert variant="default">
            <h2>No resources</h2>
            <p>You currently have no resources set. Set resources in the dok configuration file.</p>
        </ui-alert>
    @endif

    @foreach (config('dok.resources') as $name => $resource)
        <ui-panel>
            <ui-card>
                <ui-heading :level="2" size="xl">
                    {{ $name }}
                </ui-heading>
                <ui-table>
                    <ui-table-rows>
                        <ui-table-row>
                            <ui-table-cell>
                                Source
                                <ui-subheading text="The resource location." />
                            </ui-table-cell>
                            <ui-table-cell class="text-right">
                                @if (isset($resource['source']))
                                    <ui-badge color="default" text="{{ $resource['source'] }}" />
                                @else
                                    <ui-badge icon="warning-diamond" color="red" text="No source set" />
                                @endif
                            </ui-table-cell>
                        </ui-table-row>
                        <ui-table-row>
                            <ui-table-cell>
                                Repository
                                <ui-subheading text="The repository to sync." />
                            </ui-table-cell>
                            <ui-table-cell class="text-right">
                                @if (isset($resource['repo']))
                                    <ui-badge color="default" text="{{ $resource['repo'] }}" />
                                @else
                                    <ui-badge icon="warning-diamond" color="red" text="No repo set" />
                                @endif
                            </ui-table-cell>
                        </ui-table-row>
                        <ui-table-row>
                            <ui-table-cell>
                                Branch
                                <ui-subheading text="The branch to sync." />
                            </ui-table-cell>
                            <ui-table-cell class="text-right">
                                @if (isset($resource['branch']))
                                    <ui-badge color="default" text="{{ $resource['branch'] }}" />
                                @else
                                    <ui-badge icon="warning-diamond" color="red" text="No branch set" />
                                @endif
                            </ui-table-cell>
                        </ui-table-row>
                        <ui-table-row>
                            <ui-table-cell>
                                Content
                                <ui-subheading text="Only syncs these folders on the branch." />
                            </ui-table-cell>
                            <ui-table-cell class="text-right">
                                <div style="display: flex; justify-content: end; flex-wrap: wrap; gap: 10px;">
                                @if (isset($resource['content']))
                                    @foreach ($resource['content'] as $content)
                                        <ui-badge color="default" text="/{{ $content }}" />
                                    @endforeach
                                @else
                                    <ui-badge icon="fieldtype-checkboxes" color="emerald" text="Importing everything" />
                                @endif
                                </div>
                            </ui-table-cell>
                        </ui-table-row>
                        <ui-table-row>
                            <ui-table-cell>
                                Destination
                                <ui-subheading text="Synced content will live here." />
                            </ui-table-cell>
                            <ui-table-cell class="text-right">
                                <ui-badge color="default" text="{{ Str::finish(str_replace(base_path(), '', config('dok.resource_location')), '/') }}{{ $name }}" />
                            </ui-table-cell>
                        </ui-table-row>
                    </ui-table-rows>
                </ui-table>
            </ui-card>
            <ui-panel-footer>
                <div class="flex items-center justify-end w-full mt-10">
                    <sync-remote-button
                        resource="{{ $name }}"
                        source="{{ $resource['source'] ?? '' }}"
                        action="{{ cp_route('utilities.remote-sync.sync') }}"
                    />
                </div>
            </ui-panel-footer>
        </ui-panel>
    @endforeach
</div>
@endsection
