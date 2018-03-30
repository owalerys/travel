<template>
    <div>
        <v-dialog v-model="propertiesDialog" persistent max-width="500px">
            <v-card>
                <v-card-title>
                    File Properties
                </v-card-title>
                <v-card-text>
                    <message-bus :bus-uuid="dialog.busUuid"></message-bus>
                    <v-text-field
                            label="Title"
                            v-model="title"
                            type="text"
                            :error-messages="fieldErrors(dialog.busUuid, 'title')"
                            :disabled="dialog.submitting"
                    ></v-text-field>
                    <v-text-field
                            label="Description"
                            v-model="description"
                            type="text"
                            :error-messages="fieldErrors(dialog.busUuid, 'description')"
                            :disabled="dialog.submitting"
                    ></v-text-field>
                    <v-checkbox v-model="internal"
                                label="For Internal Viewing Only?"
                                :error-messages="fieldErrors(dialog.busUuid, 'internal')"
                                :disabled="dialog.submitting"
                    ></v-checkbox>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" flat @click.stop="closeDialog" :disabled="dialog.submitting">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="green" flat :loading="dialog.submitting" @click="submitProperties">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <message-bus :bus-uuid="busUuid"></message-bus>
        <v-data-table :items="uploads" :headers="headers" hide-actions>
            <template slot="items" slot-scope="props">
                <td>{{ props.item.file_name }}</td>
                <td>{{ props.item.size | niceSize }}</td>
                <td>{{ props.item.message }}</td>
                <td v-if="props.item.status === 'uploading'">
                    <v-progress-linear :value="props.item.progress"></v-progress-linear>
                </td>
                <td v-else>
                    <v-btn flat icon color="grey"
                        v-if="props.item.status === 'uploaded' || props.item.status === 'downloading'"
                           :loading="props.item.status === 'downloading'"
                           @click="doDownload({ id: props.item.id })"
                    ><v-icon>file_download</v-icon></v-btn>
                    <v-btn flat icon color="red" v-if="props.item.status === 'upload-failed'" @click="doUpload({ id: props.item.id })"><v-icon>refresh</v-icon></v-btn>
                    <v-btn flat icon color="grey" v-if="props.item.status === 'uploaded'" @click.stop="openDialog(props.item.id)"><v-icon>settings</v-icon></v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                            flat icon color="grey"
                            v-if="props.item.status === 'uploaded' || props.item.status === 'delete-failed' || props.item.status === 'deleting'"
                            :loading="props.item.status === 'deleting'"
                            @click="doDelete({ id: props.item.id })"
                    ><v-icon>delete_forever</v-icon></v-btn>
                    <v-btn
                            flat icon color="grey"
                            v-if="props.item.status === 'upload-failed' || props.item.status === 'new'"
                            :loading="props.item.status === 'deleting'"
                            @click="localDelete({ id: props.item.id })"
                    ><v-icon>delete_forever</v-icon></v-btn>
                </td>
            </template>
        </v-data-table>
        <v-layout row>
            <v-spacer></v-spacer>
            <v-btn flat icon color="green" @click.stop="$refs.upload.click()" :loading="massUploading"><v-icon>add_circle</v-icon></v-btn>
        </v-layout>
        <input type="file" v-show="false" ref="upload" @change="uploadFiles" accept="application/pdf">
    </div>
</template>

<script>
    import { mapGetters, mapActions, mapState, mapMutations } from 'vuex'
    import MessageBus from 'Travel/components/MessageBus'

    export default {
        data () {
            return {
                headers: [
                    {
                        text: 'Filename',
                        value: null,
                        sortable: false,
                        width: '15%'
                    },
                    {
                        text: 'Size',
                        value: null,
                        sortable: false,
                        width: '15%'
                    },
                    {
                        text: 'Status',
                        value: null,
                        sortable: false,
                        width: '15%'
                    },
                    {
                        text: 'Actions',
                        value: null,
                        sortable: false,
                        width: '55%'
                    }
                ],
                propertiesDialog: false
            }
        },
        computed: {
            ...mapState('content/manage/overview/editor', {
                uploads: state => state.media.uploads,
                massUploading: state => state.media.uploading,
                busUuid: state => state.media.busUuid,
                dialog: state => state.media.dialog
            }),
            ...mapGetters({
                fieldErrors: 'validation/errors'
            }),
            title: {
                get () {
                    return this.dialog.title
                },
                set (value) {
                    this.updateTitle({ title: value })
                }
            },
            description: {
                get () {
                    return this.dialog.description
                },
                set (value) {
                    this.updateDescription({ description: value })
                }
            },
            internal: {
                get () {
                    return this.dialog.internal
                },
                set (value) {
                    this.updateInternal({ internal: value })
                }
            }
        },
        methods: {
            ...mapActions('content/manage/overview/editor', {
                multiUpload: 'multiUpload',
                doUpload: 'doUpload',
                doDelete: 'doDelete',
                localDelete: 'localDelete',
                doDownload: 'doDownload',
                initializeDialog: 'editProperties',
                doPropertiesSubmit: 'doPropertiesSubmit'
            }),
            ...mapMutations('content/manage/overview/editor', {
                updateTitle: 'UPDATE_TITLE_PROPERTY',
                updateDescription: 'UPDATE_DESCRIPTION_PROPERTY',
                updateInternal: 'UPDATE_INTERNAL_PROPERTY'
            }),
            uploadFiles () {
                let upload = this.$refs.upload

                let toUpload = []
                for (let i = 0; i < upload.files.length; i++) {
                    toUpload.push(upload.files[i])
                }

                if (toUpload.length) {
                    this.multiUpload({ files: toUpload })
                }
            },
            openDialog (id) {
                this.initializeDialog({ id })

                this.propertiesDialog = true
            },
            closeDialog () {
                this.initializeDialog({ id: null })

                this.propertiesDialog = false
            },
            submitProperties () {
                this.doPropertiesSubmit()
            }
        },
        filters: {
            niceSize: function (value) {
                let size = value

                let humanSize = (size, multiple, decimal) => {
                    return Math.floor(size / multiple) + '.' + Math.floor(((size / multiple) - Math.floor(size / multiple)) * decimal);
                }

                if (size < 1024) {
                    return size + ' B';
                }
                else if (size < 1024 * 1024) {
                    return humanSize(size, 1024, 10) + ' kB';
                }
                else if (size < 1024 * 1024 * 1024) {
                    return humanSize(size, Math.pow(1024, 2), 10) + ' MB';
                }
                else {
                    return humanSize(size, Math.pow(1024, 3), 10) + ' GB';
                }
            }
        },
        components: {
            'message-bus': MessageBus
        }
    }
</script>
