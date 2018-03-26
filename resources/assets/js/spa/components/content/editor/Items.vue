<template>
    <div>
        <v-text-field label="Custom Title (optional)" v-if="fieldSchema.custom_title" v-model="title" :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.custom_title')"></v-text-field>

        <draggable v-model="dragItems" :move="checkMove" :options="{ handle: '.draggable' }">
            <editor-item v-for="(item, index) in dragItems" :slug="slug" :key="index" :index="index" :field-schema="fieldSchema" :form-uuid="formUuid"></editor-item>
        </draggable>

        <div v-if="fieldSchema.multiple">
            <v-btn color="success" @click="addItem({ slug })">Add Field Item</v-btn>
            <v-btn color="error" @click="deleteItem({ slug })">Remove Last Item</v-btn>
        </div>
    </div>
</template>

<script>
    import { mapMutations, mapGetters, mapActions } from 'vuex'
    import Item from './Item'
    import Draggable from 'vuedraggable'

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
            dragItems: {
                get () {
                    return this.field.items
                },
                set (value) {
                    this.reorderField({ items: value, slug: this.slug })
                }
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
                deleteItem: 'content/manage/overview/editor/DELETE_LAST_FIELD',
                reorderField: 'content/manage/overview/editor/REORDER_FIELD'
            }),
            checkMove (event) {
                console.log(event)
            }
        },
        components: {
            'editor-item': Item,
            'draggable': Draggable
        }
    }
</script>
