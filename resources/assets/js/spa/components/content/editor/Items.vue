<template>
    <div>
        <v-text-field label="Custom Title (optional)" v-if="fieldSchema.custom_title" v-model="title" :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.custom_title')"></v-text-field>

        <editor-item v-for="(item, index) in field.items" :slug="slug" :key="index" :index="index" :field-schema="fieldSchema" :form-uuid="formUuid"></editor-item>

        <div v-if="fieldSchema.multiple">
            <v-btn color="success" @click="addItem({ slug })">Add Field Item</v-btn>
            <v-btn color="error" @click="deleteItem({ slug })">Remove Last Item</v-btn>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapGetters } from 'vuex'
    import Item from './Item'

    export default {
        data () {
            return {

            }
        },
        props: [
            'slug',
            'schema',
            'formUuid'
        ],
        computed: {
            title: {
                get () {
                    return this.field.title
                },
                set (value) {
                    this.setTitle({ slug: this.slug, title: value })
                }
            },
            field () {
                return this.getField(this.slug)
            },
            fieldSchema () {
                return this.schema.fields[this.slug]
            },
            ...mapGetters({
                getField: 'content/manage/overview/editor/field'
            }),
            ...mapGetters({
                fieldErrors: 'validation/errors',
                fieldHasErrors: 'validation/hasErrors',
                categoryHasErrors: 'validation/categoryErrors'
            }),
        },
        methods: {
            ...mapMutations({
                setTitle: 'content/manage/overview/editor/SET_FIELD_TITLE',
                addItem: 'content/manage/overview/editor/ADD_FIELD_ITEM',
                deleteItem: 'content/manage/overview/editor/DELETE_LAST_FIELD'
            })
        },
        components: {
            'editor-item': Item
        }
    }
</script>
