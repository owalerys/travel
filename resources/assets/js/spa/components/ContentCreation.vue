<template>
    <v-container fluid>
        <v-btn @click="loadSchema">Load</v-btn>
        <v-layout row wrap>
            <v-flex xs6>
                <v-container fluid grid-list-md v-if="schema">
                    <h2>Category: {{ schema.title || '' }}</h2>
                    <br/>
                    <v-expansion-panel popout>
                        <v-expansion-panel-content>
                            <div slot="header">Title, Description, URL</div>
                            <v-card>
                                <v-card-text>
                                    <v-text-field label="Title" v-model="title"></v-text-field>
                                    <v-text-field label="Description" v-model="description" multi-line></v-text-field>
                                    <v-text-field label="Link" v-model="url"></v-text-field>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content>
                            <div slot="header">
                                File Uploads
                            </div>
                            <v-card>
                                <v-card-text>
                                    Upload component goes here
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content v-for="(field, slug) in content.fields" :key="slug" v-if="content.fields">
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
                                    <v-btn flat>Visit External Link</v-btn>
                                    <v-btn flat>View Article Content</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content>
                            <div slot="header">Article Preview</div>
                            <v-card>
                                <v-card-text>
                                    <travel-article
                                            v-if="loaded === true"
                                        :schema="schema"
                                        :content="content"
                                        :airline="airlines[0]"
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

export default {
    data () {
        return {
            selectedSchema: {
                version: '1',
                category_slug: 'schedule_changes'
            },
            loaded: false
        }
    },
    computed: {
        schema () {
            return this.fetchSchema(this.selectedSchema.version, this.selectedSchema.category_slug)
        },
        ...mapGetters({
            fetchSchema: 'content/categoryBySlugAndVersion',
            content: 'content/editor/content',
            airlines: 'content/airlines'
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
            updateTitle: 'content/editor/UPDATE_TITLE',
            updateDescription: 'content/editor/UPDATE_DESCRIPTION',
            updateUrl: 'content/editor/UPDATE_URL'
        }),
        ...mapActions({
            load: 'content/editor/load'
        }),
        loadSchema () {
            this.load({ version: this.selectedSchema.version, slug: this.selectedSchema.category_slug, content: {} })
            this.loaded = true
        }
    },
    components: {
        'travel-items': Items,
        'travel-article': Article
    }
}
</script>
