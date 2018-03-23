<template>
    <v-layout column>
        <v-dialog v-model="newDialog" persistent max-width="500px">
            <v-card>
                <v-card-title>
                    Create a new article
                </v-card-title>
                <v-card-text>
                    <message-bus :bus-uuid="storeBusUuid"></message-bus>
                    <v-select
                            :items="types"
                            label="Select a content type"
                            item-value="value"
                            item-text="display"
                            v-model="type"
                    ></v-select>
                    <v-text-field label="Custom Title" v-model="title"></v-text-field>
                    <v-text-field label="Custom Description" multi-line v-model="description"></v-text-field>
                    <v-text-field label="Link" required v-if="type && type === 'link'" v-model="url"></v-text-field>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" flat @click.stop="newDialog=false" :disabled="storeCreating">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="green" flat :loading="storeCreating" @click="doCreate">Create</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-container fluid>
            <v-layout row wrap>
                <v-flex xs12>
                    <message-bus :bus-uuid="storeBusUuidMain"></message-bus>
                    <v-card light color="white">
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-select
                                            :items="airlines"
                                            :required="true"
                                            :autocomplete="true"
                                            item-text="search_name"
                                            item-value="id"
                                            v-model="airline"
                                            label="Select"
                                            persistent-hint
                                            hint="Airlines"
                                            single-line
                                            clearable
                                            bottom
                                            prepend-icon="flight_takeoff"
                                            :loading="storeSearching"
                                    ></v-select>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn flat color="blue" @click="runSearch" :loading="storeSearching" :disabled="!airline">Search</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-container fluid grid-list-xl>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card>
                            <v-card-text>
                                <v-select
                                        :items="search"
                                        :required="true"
                                        :autocomplete="true"
                                        v-model="topic"
                                        label="Select"
                                        persistent-hint
                                        hint="Topics to create"
                                        item-text="display"
                                        item-value="reference"
                                        single-line
                                        bottom
                                        clearable
                                        prepend-icon="library_books"
                                        :disabled="!airline"
                                >
                                </v-select>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn flat color="green" :disabled="!topic || !airline" @click="newDialog = !newDialog">Create New Article</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                    <v-flex xs12>
                        <v-data-table
                            :headers="headers"
                            :results="results"
                            class="elevation-1"
                            :items="storeResults"
                            :loading="storeSearching"
                            >
                            <template slot="items" slot-scope="props">
                                <td>{{ fetchSchema(props.item.schema_version, props.item.category_slug).title }}</td>
                                <td>{{ props.item.display_title ? props.item.display_title : '-' }}</td>
                                <td>{{ props.item.type.charAt(0).toUpperCase() + props.item.type.slice(1) }}</td>
                                <td>{{ (props.item.live) ? 'Live' : ((props.item.active) ? 'Draft' : 'Archived') }}</td>
                                <td>
                                    <v-btn flat color="primary"
                                        :to="{ name: 'article-overview', params: { article_id: props.item.id } }"
                                    >
                                        View</v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-container>
    </v-layout>
</template>

<script>
    import { mapGetters, mapState, mapActions, mapMutations } from 'vuex'
    import MessageBus from 'Travel/components/MessageBus'

    export default {
        data () {
            return {
                newDialog: false,
                results: [],
                headers: [
                    {
                        text: 'Topic',
                        value: 'topic',
                        sortable: false
                    },
                    {
                        text: 'Title',
                        value: 'title',
                        sortable: false
                    },
                    {
                        text: 'Type',
                        value: 'type',
                        sortable: false
                    },
                    {
                        text: 'Status',
                        value: 'status',
                        sortable: false
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    }
                ],
                selectedContentType: null
            }
        },
        computed: {
            schema () {
                return this.fetchSchema(this.topic.version, this.topic.slug)
            },
            types () {

                if (!this.schema.type) {
                    return []
                }

                if (typeof this.schema.type === 'string') {
                    let type = this.schema.type

                    return [
                        {
                            value: this.schema.type,
                            display: type.charAt(0).toUpperCase() + type.slice(1)
                        }
                    ]
                }

                if (this.schema.type instanceof Array) {
                    let types = []

                    for (let i = 0; i < this.schema.type.length; i++) {
                        let type = this.schema.type[i]

                        types.push({
                            value: this.schema.type[i],
                            display: type.charAt(0).toUpperCase() + type.slice(1)
                        })
                    }

                    return types
                }

                return []
            },
            ...mapGetters({
                fetchSchema: 'content/categoryBySlugAndVersion'
            }),
            ...mapGetters({
                airlines: 'content/airlines',
                search: 'content/creationSearch'
            }),
            ...mapState('content/manage', {
                storeAirline: (state) => state.airline,
                storeSearching: (state) => state.searching,
                storeTopic: (state) => state.topic,
                storeCreating: (state) => state.creating,
                storeBusUuid: (state) => state.creation.busUuid,
                storeBusUuidMain: (state) => state.busUuid,
                storeType: (state) => state.creation.type,
                storeTitle: (state) => state.creation.title,
                storeDescription: (state) => state.creation.description,
                storeUrl: (state) => state.creation.url,
                storeResults: (state) => state.results
            }),
            loadingTable () {
                return this.airline !== null
            },
            airline: {
                get () {
                    return this.storeAirline
                },
                set (value) {
                    this.setAirline({ airline_id: value })
                }
            },
            topic: {
                get () {
                    return this.storeTopic
                },
                set (value) {
                    this.setTopic({ topic: value })
                }
            },
            type: {
                get () {
                    return this.storeType
                },
                set (value) {
                    this.setType({ type: value })
                }
            },
            title: {
                get () {
                    return this.storeTitle
                },
                set (value) {
                    this.setTitle({ title: value })
                }
            },
            description: {
                get () {
                    return this.storeDescription
                },
                set (value) {
                    this.setDescription({ description: value })
                }
            },
            url: {
                get () {
                    return this.storeUrl
                },
                set (value) {
                    this.setUrl({ url: value })
                }
            }
        },
        methods: {
            ...mapMutations('content/manage', {
                setAirline: 'UPDATE_AIRLINE',
                setTopic: 'UPDATE_TOPIC',
                setTitle: 'UPDATE_TITLE',
                setUrl: 'UPDATE_URL',
                setDescription: 'UPDATE_DESCRIPTION',
                setType: 'UPDATE_TYPE'
            }),
            ...mapActions('content/manage', {
                runSearch: 'search',
                create: 'create'
            }),
            doCreate () {
                this.create().then(() => {
                    this.newDialog = false
                    this.runSearch()
                })
            }
        },
        components: {
            'message-bus': MessageBus
        }
    }
</script>
