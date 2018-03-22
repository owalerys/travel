<template>
    <v-container fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-btn flat color="primary" dark :to="{ name: 'article-overview', params: { article_id: $route.params.article_id } }">
                    <v-icon left dark>chevron_left</v-icon> Back
                </v-btn>
                <v-btn flat color="green" @click="save" v-if="modified === true" :loading="saving === true">Save</v-btn>
            </v-flex>
            <v-flex xs12>
                <message-bus :busUuid="busUuid"></message-bus>
            </v-flex>
            <v-flex xs6>
                <v-container fluid grid-list-md v-if="schema">
                    <h2>Category: {{ schema.title || '' }}</h2>
                    <br/>
                    <v-expansion-panel popout>
                        <v-expansion-panel-content v-model="titleExpand">
                            <div slot="header">Title, Description, URL</div>
                            <v-card>
                                <v-card-text>
                                    <v-text-field label="Title" v-model="title"></v-text-field>
                                    <v-text-field label="Description" v-model="description" multi-line></v-text-field>
                                    <v-text-field v-if="content.type === 'link'" label="Link" v-model="url"></v-text-field>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content v-if="content.type === 'content' && false">
                            <div slot="header">
                                File Uploads
                            </div>
                            <v-card>
                                <v-card-text>
                                    Upload component goes here
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content v-for="(field, slug) in content.fields" :key="slug" v-if="content.fields && content.type === 'content'">
                            <div slot="header" v-if="schema.fields[slug].category">{{ schema.fields[slug].title }} - <strong>{{ schema.field_categories[schema.fields[slug].category].title }}</strong></div>
                            <div slot="header" v-else>{{ schema.fields[slug].title }}</div>
                            <v-card>
                                <v-card-text>
                                    <travel-items :slug="slug" :schema="schema"></travel-items>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-container>
            </v-flex>
            <v-flex xs6>
                <v-container fluid grid-list-md>
                    <h2>Preview</h2>
                    <br/>
                    <v-expansion-panel popout>
                        <v-expansion-panel-content>
                            <div slot="header">Search Results Preview</div>
                            <v-card>
                                <v-card-title>
                                    <div class="headline">{{ (title) ? title : schema.title }}</div>
                                </v-card-title>
                                <v-card-text v-if="description">
                                    {{ description }}
                                </v-card-text>
                                <v-card-text v-else>
                                    This description will say "Click to read more" if you don't write one
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn flat v-if="content.type === 'link'">Visit External Link</v-btn>
                                    <v-btn flat v-if="content.type === 'content'">View Article</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content v-model="previewExpand" v-if="content.type === 'content'">
                            <div slot="header">Article Preview</div>
                            <v-card>
                                <v-card-text>
                                    <travel-article
                                            v-if="loaded === true && content && schema"
                                            :schema="schema"
                                            :content="content"
                                            :airline="airline"
                                    ></travel-article>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import { mapActions, mapGetters, mapMutations, mapState } from 'vuex'
    import Items from 'Travel/components/content/editor/Items'
    import Article from 'Travel/components/content/view/Article'
    import MessageBus from 'Travel/components/MessageBus'

    export default {
        data () {
            return {
                loaded: false,
                previewExpand: true,
                titleExpand: true
            }
        },
        beforeRouteEnter (to, from, next) {
            next((vm) => {
                vm.previewExpand = true
                vm.titleExpand = true
                if (vm.article) {
                    vm.loadVersion({ article_id: to.params.article_id, version: to.params.version })
                } else {
                    vm.$router.push({
                        name: 'article-overview',
                        article_id: to.params.article_id
                    })
                }
            })
        },
        computed: {
            airline () {
                if (this.article && this.airlines) {
                    return this.airlines.find((item) => {
                        return item.id === this.article.topic_id
                    })
                } else {
                    return {}
                }
            },
            version () {
                if (this.article) {
                    return this.article.versions.find((item) => {
                        return item.version === this.$route.params.version
                    })
                } else {
                    return {}
                }
            },
            schema () {
                return this.fetchSchema(
                    this.version.schema_version,
                    this.version.category_slug
                )
            },
            ...mapGetters({
                fetchSchema: 'content/categoryBySlugAndVersion',
                airlines: 'content/airlines'
            }),
            ...mapState('content/manage/overview', {
                article: state => state.article,
                content: state => state.editor,
                busUuid: state => state.editor.busUuid,
                saving: state => state.editor.saving,
                modified: state => state.editor.modified
            }),
            title: {
                get () {
                    return this.content.title
                },
                set (value) {
                    this.updateTitle({ title: value })
                }
            },
            description: {
                get () {
                    return this.content.description
                },
                set (value) {
                    this.updateDescription({ description: value })
                }
            },
            url: {
                get () {
                    return this.content.url
                },
                set (value) {
                    this.updateUrl({ url: value })
                }
            }
        },
        methods: {
            ...mapMutations({
                updateTitle: 'content/manage/overview/editor/UPDATE_TITLE',
                updateDescription: 'content/manage/overview/editor/UPDATE_DESCRIPTION',
                updateUrl: 'content/manage/overview/editor/UPDATE_URL'
            }),
            ...mapActions('content/manage/overview/editor', {
                load: 'load',
                save: 'save'
            }),
            loadVersion () {
                this.load({ article: this.article , versionNumber: this.$route.params.version })
                this.loaded = true
            }
        },
        components: {
            'travel-items': Items,
            'travel-article': Article,
            'message-bus': MessageBus
        }
    }
</script>
