<template>
    <div v-show="hasAnyContent">
        <!-- Title -->
        <!--<h2>{{ (field.title) ? field.title : fieldSchema.title }}</h2>-->
        <!-- Items -->
        <travel-item
                v-for="(item, index) in field.items"
                :slug="slug"
                :key="index"
                :index="index"
                :field-schema="fieldSchema"
                :schema="schema"
                :item="item"
                :field="field"
        ></travel-item>
    </div>
</template>

<script>
    import Item from './Item'

    export default {
        data () {
            return {

            }
        },
        props: ['field', 'slug', 'schema'],
        components: {
            'travel-item': Item
        },
        computed: {
            hasAnyContent () {
                for (let i = 0; i < this.field.items.length; i++) {
                    if (this.field.items[i].value) {
                        return true
                    }
                }

                return false
            },
            fieldSchema () {
                return this.schema.fields[this.slug]
            }
        }
    }
</script>
