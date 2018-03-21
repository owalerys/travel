<template>
    <div>
        <!-- Article Title -->
        <h1>{{ airline.name + ' - ' + ((content.title) ? content.title : schema.title) }}</h1>
        <br v-if="content.description">
        <p v-if="content.description">{{ content.description }}</p>
        <br/>
        <!-- Fields -->
        <travel-field
                v-for="(field, slug) in content.fields"
                :key="slug"
                :field="field"
                :slug="slug"
                :schema="schema"
        ></travel-field>
    </div>
</template>

<script>
    import Field from './Field'

    export default {
        data () {
            return {

            }
        },
        props: [ 'schema', 'content', 'airline' ],
        components: {
            'travel-field': Field
        }
    }
</script>
