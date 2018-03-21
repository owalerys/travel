<template>
    <v-layout column>
        <v-container fluid>
            <v-layout row wrap>
                <v-flex xs12>
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
                                    ></v-select>
                                </v-flex>
                                <v-flex xs12 v-show="airlineSelected">
                                    <v-select
                                        :items="search"
                                        :required="true"
                                        :autocomplete="true"
                                        v-model="topic"
                                        label="Select"
                                        persistent-hint
                                        hint="Topics"
                                        item-text="display"
                                        item-value="reference"
                                        single-line
                                        bottom
                                        clearable
                                        @change="runSearch"
                                        prepend-icon="library_books"
                                    >
                                    </v-select>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-container fluid grid-list-xl>
                <v-layout row wrap>
                    <v-flex xs12 sm12 md6 lg4 v-show="topicSelected && airlineSelected" v-for="result in results" :key="result.id">
                        <v-card light color="white">
                            <v-card-title primary-title>
                                <div class="headline">{{ result.title }}</div>
                            </v-card-title>
                            <v-card-text v-if="result.short_description">
                                {{ result.short_description }}
                            </v-card-text>
                            <v-card-actions>
                                <v-btn flat>{{ (result.type === 'url') ? 'Visit External Link' : 'View Article' }}</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-container>
    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex'

    export default {
        data () {
            return {
                airline: null,
                topic: null,
                results: [
                    {
                        id: 1,
                        title: 'Domestic Schedule Change Information',
                        type: 'article'
                    },
                    {
                        id: 2,
                        title: 'Live Update Schedule Change Restrictions',
                        type: 'url',
                        url: 'https://walerys.com'
                    },
                    {
                        id: 3,
                        title: 'International Schedule Change Information',
                        type: 'article'
                    },
                    {
                        id: 4,
                        title: 'Important Schedule Change Exceptions',
                        short_description: 'Hello world heres an article description to get you started',
                        type: 'article'
                    },
                    {
                        id: 5,
                        title: 'A Message from Air Canada',
                        type: 'url',
                        url: 'https://walerys.com'
                    },
                    {
                        id: 6,
                        title: 'Lorem Ipsum Dolor Sit Amet',
                        type: 'article'
                    },
                ]
            }
        },
        computed: {
            ...mapGetters({
                airlines: 'content/airlines',
                search: 'content/search'
            }),
            airlineSelected () {
                return this.airline > 0
            },
            topicSelected () {
                return this.topic !== null && this.topic !== undefined && this.topic !== ''
            }
        },
        methods: {
            runSearch () {

            }
        }
    }
</script>

<style scoped>

</style>