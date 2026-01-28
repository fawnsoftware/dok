import GitHubSyncButton from './SyncRemoteButton.vue';

Statamic.booting(() => {
    Statamic.$components.register('sync-remote-button', GitHubSyncButton);
});
