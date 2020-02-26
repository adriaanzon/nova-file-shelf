<template>
    <card>
        <div class="flex flex-col justify-between p-6 h-full">
            <h3 class="mb-3 text-90 uppercase tracking-wide font-bold text-sm">{{ card.title }}</h3>
            <div v-if="file === null">
                This shelf is empty
            </div>
            <div v-else>
                <div class="flex justify-between items-center">
                    <div>
                        <div>{{ file.name }}</div>
                        <div class="text-xs text-80 mt-1" :title="momentTimestamp">Uploaded {{ momentTimestamp.fromNow() }}</div>
                    </div>

                    <a :href="file.downloadUrl" class="inline-flex cursor-pointer text-70 hover:text-primary mr-3">
                        <icon type="download" style="overflow: visible"></icon>
                    </a>
                </div>
            </div>

            <!-- TODO: tooltip with {{ card.helpText }} -->

            <div class="mt-3">
                <form @submit.prevent>
                    <input type="file" @change="upload" style="font-size: revert; line-height: revert;" />
                </form>
                <div class="text-danger text-sm font-bold mt-1" v-if="uploadError">
                    {{ uploadError }}
                </div>
            </div>
        </div>
    </card>
</template>

<script>
export default {
    data: () => ({
        file: null,
        uploadError: null
    }),

    props: [
        'card'

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],

    mounted() {
        //
        axios.get(this.url).then(this.setFile)
    },

    methods: {
        setFile({ data }) {
            this.file = data
        },

        async upload(event) {
            let data = new FormData()
            data.append('file', event.target.files[0])

            try {
                this.setFile(
                    await axios.post(this.url, data, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                )
            } catch (error) {
                if (error.response.status === 413) {
                    this.uploadError = 'File too large'
                } else if (error.response.status === 422) {
                    this.uploadError = error.response.data.errors.file[0]
                }
            } finally {
                event.target.value = ''
            }
        }
    },

    computed: {
        url() {
            return '/nova-vendor/nova-file-shelf/shelves/' + this.card.key
        },
        momentTimestamp() {
            return moment.unix(this.file.timestamp)
        }
    }
}
</script>
