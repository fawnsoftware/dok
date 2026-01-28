
<script>
    export default {
        name: 'SyncRemoteButton',

        props: {
            resource: {
                type: String,
                required: true,
            },
            source: {
                type: String,
                required: true,
            },
            action: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                loading: false,
            }
        },

        computed: {
            disabled() {
                return this.loading || this.source === ''
            }
        },

        methods: {
            sync() {
                this.loading = true;

                this.$axios.post(this.action, {
                    resource: this.resource,
                    source: this.source
                })
                .then(({ data }) => {
                    if (data.status === 'success') {
                        this.$toast.success(data.message || 'Sync complete')
                    } else {
                        this.$toast.error(data.message || 'Sync failed')
                    }
                })
                .catch(() => {
                    this.$toast.error('Request failed')
                })
                .finally(() => {
                    this.loading = false
                })
            },
        },
    }
</script>

<template>
    <ui-button
        text="Sync"
        :loading="loading"
        :disabled="disabled"
        @click="sync"
    />
</template>
