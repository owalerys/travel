<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <v-flex xs12>
                <!-- Higher level navigation and controls -->
                <v-btn flat :to="{ name: 'manage-content' }">Back</v-btn>
                <v-btn flat @click="refreshArticle">Refresh</v-btn>
            </v-flex>
            <v-flex xs12 v-if="loading">
                <v-progress-linear indeterminate></v-progress-linear>
            </v-flex>
            <v-flex xs12 v-if="!loading">
                <message-bus :bus-uuid="busUuid"></message-bus>
            </v-flex>
            <v-flex xs12 md4 v-if="!loading && article">
                <v-card>
                    <v-card-title><div class="headline">Overview</div></v-card-title>
                    <v-card-text>
                        <v-data-table
                                :items="summary"
                                hide-actions
                                hide-headers
                        >
                            <template slot="items" slot-scope="props">
                                <td><strong>{{ props.item.title }}</strong></td>
                                <td class="text-xs-right">{{ props.item.detail }}</td>
                            </template>
                        </v-data-table>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn flat color="green" v-if="canActivate" @click.stop="activate" :loading="modifyingStatus">Unarchive</v-btn>
                        <v-btn flat color="red" v-if="canArchive" @click.stop="archive" :loading="modifyingStatus">Archive</v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
            <v-flex xs12 md8 v-if="!loading && article">
                <v-card>
                    <v-card-title><div class="headline">Versions</div></v-card-title>
                    <v-card-text>
                        <v-data-table
                                :items="versions"
                                :headers="versionHeaders"
                                hide-actions
                        >
                            <template slot="items" slot-scope="props">
                                <!--
                                    Version Number
                                    Title
                                    Status
                                    Type
                                    Author
                                    Actions
                                -->
                                <td>{{ props.item.version }}</td>
                                <td>{{ props.item.title ? props.item.title : schema.title }}</td>
                                <td>{{ props.item.status.charAt(0).toUpperCase() + props.item.status.slice(1) }}</td>
                                <td>{{ props.item.type.charAt(0).toUpperCase() + props.item.type.slice(1) }}</td>
                                <td>{{ props.item.author.name }}</td>
                                <td v-if="!props.item.loading">
                                    <!-- Edit -->
                                    <v-btn flat icon color="grey"
                                           :to="{ name: 'version-editor', params: { article_id: props.item.article_id, version: props.item.version, action: 'edit' } }"
                                           v-if="user.id === props.item.author.id && props.item.status === 'editing'"
                                    ><v-icon>mode_edit</v-icon></v-btn>
                                    <!-- Done -->
                                    <!--<v-btn flat icon color="grey"><v-icon>done</v-icon></v-btn>-->
                                    <!-- Review -->
                                    <!--<v-btn flat icon color="grey"><v-icon>mode_comment</v-icon></v-btn>-->
                                    <!-- Retire / Abandon -->
                                    <v-btn flat icon color="grey" @click="archiveVersion(props.item.version)" v-if="user.id === props.item.author.id && props.item.status === 'editing'"><v-icon>archive</v-icon></v-btn>
                                    <!-- Publish -->
                                    <!--<v-btn flat icon color="grey"><v-icon>cloud_upload</v-icon></v-btn>-->
                                    <!-- View -->
                                    <v-btn flat icon color="grey"
                                        :to="{ name: 'version-editor', params: { article_id: props.item.article_id, version: props.item.version, action: 'view' } }"
                                    ><v-icon>remove_red_eye</v-icon></v-btn>
                                    <!-- Copy -->
                                    <v-btn flat icon color="grey" @click="copyVersion(props.item.version)"><v-icon>content_copy</v-icon></v-btn>
                                </td>
                                <td v-else>
                                    <v-progress-linear indeterminate></v-progress-linear>
                                </td>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 v-if="!loading && article && false">
                <v-card>
                    <v-card-title><div class="headline">Log</div></v-card-title>
                    <v-card-text>
                        <v-data-table
                                :items="log"
                                hide-actions
                                hide-headers
                        >
                            <template slot="items" slot-scope="props">
                                <td><strong>{{ props.item.title }}</strong></td>
                                <td class="text-xs-right">{{ props.item.detail }}</td>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapActions, mapState, mapGetters } from 'vuex'
    import MessageBus from 'Travel/components/MessageBus'

    export default {
        data () {
            return {
                versionHeaders: [
                    {
                        text: 'Version',
                        value: 'version',
                        sortable: false
                    },
                    {
                        text: 'Title',
                        value: 'title',
                        sortable: false
                    },
                    {
                        text: 'Status',
                        value: 'status',
                        sortable: false
                    },
                    {
                        text: 'Type',
                        value: 'type',
                        sortable: false
                    },
                    {
                        text: 'Author',
                        value: 'author',
                        sortable: false
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    }
                ]
            }
        },
        beforeRouteEnter (to, from, next) {
            next((vm) => {
                vm.fetchArticle({ article_id: to.params.article_id })
            })
        },
        computed: {
            ...mapState('content/manage/overview', {
                article: state => state.article,
                busUuid: state => state.busUuid,
                loading: state => state.loading,
                modifyingStatus: state => state.modifyingStatus
            }),
            summary () {
                let summary = []

                if (!this.articleAvailable) {
                    return summary
                }

                summary.push({
                    title: 'Airline',
                    detail: this.article.topic.name
                })

                summary.push({
                    title: 'Article Type',
                    detail: this.schema.title
                })

                summary.push({
                    title: 'Display Title',
                    detail: this.article.display_title || ''
                })

                summary.push({
                    title: 'Status',
                    detail: (this.article.live) ? 'Live' : ((this.article.active) ? 'Draft' : 'Archived')
                })

                return summary
            },
            ...mapGetters({
                fetchSchema: 'content/categoryBySlugAndVersion',
                user: 'auth/user'
            }),
            schema () {
                let version = this.article.schema_version || ''
                let slug = this.article.category_slug || ''

                return this.fetchSchema(version, slug)
            },
            versions () {
                if (!this.articleAvailable) {
                    return []
                }

                if (this.article.versions && this.article.versions.length > 0) {
                    return this.article.versions
                } else {
                    return []
                }
            },
            log () {
                return []
            },
            articleAvailable () {
                return !!this.article
            },
            canArchive () {
                let anyActive = false
                for (let i = 0; i < this.versions.length; i++) {
                    if (this.versions[i].status === 'live') {
                        anyActive = true
                    }
                }

                return this.article.active && anyActive === false && this.versions.length
            },
            canActivate () {
                return !this.article.active
            }
        },
        methods: {
            ...mapActions({
                fetchArticle: 'content/manage/overview/fetch',
                refreshArticle: 'content/manage/overview/refresh',
                activateArticle: 'content/manage/overview/activate',
                archiveArticle: 'content/manage/overview/archive',
                forkVersion: 'content/manage/overview/fork',
                archiveVersionAction: 'content/manage/overview/versionArchive'
            }),
            copyVersion (version) {
                this.forkVersion({ version }).then((result) => {
                    this.refreshArticle()
                }).catch((error) => {

                })
            },
            archiveVersion (version) {
                this.archiveVersionAction({ version }).then((result) => {
                    this.refreshArticle()
                }).catch((error) => {

                })
            },
            archive () {
                this.archiveArticle().then((result) => {
                    this.refreshArticle()
                }).catch((error) => {

                })
            },
            activate () {
                this.activateArticle().then((result) => {
                    this.refreshArticle()
                }).catch((error) => {

                })
            }
        },
        components: {
            'message-bus': MessageBus
        }
    }
</script>
