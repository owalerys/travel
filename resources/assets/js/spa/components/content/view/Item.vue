<template>
    <div>
        <div v-if="isNested">
            <h2 v-if="showCategoryTitle === true">{{ schema.field_categories[fieldSchema.category].title }}</h2>
            <v-divider v-if="showCategoryTitle === true"></v-divider>
            <br v-if="showCategoryTitle">
            <h3 v-if="index === 0">{{ (field.custom_title) ? field.custom_title : fieldSchema.title }}</h3>
            <br v-if="index === 0 && fieldSchema.multiple">
            <h4 v-if="item.custom_heading && fieldSchema.multiple">{{ item.custom_heading }}</h4>
        </div>
        <div v-else>
            <h2 v-if="index === 0">{{ (field.custom_title) ? field.custom_title : fieldSchema.title }}</h2>
            <v-divider v-if="index === 0"></v-divider>
            <h3 v-if="item.custom_heading && fieldSchema.multiple">{{ item.custom_heading }}</h3>
        </div>

        <p v-if="fieldSchema.filter === 'email'">{{ item.value }}</p>
        <p v-else-if="fieldSchema.filter === 'url'">
            <a :href="item.value" target="_blank">{{ (item.attributes.display) ? item.attributes.display : item.value }}</a>
        </p>
        <p v-else-if="fieldSchema.filter === 'phone'">
            {{ phone }}
        </p>
        <p v-else-if="fieldSchema.filter === 'paragraph'" v-html="sanitizedValue"></p>
        <p v-else v-html="sanitizedValue"></p>

        <p v-if="item.additional_info"><i v-html="sanitizedAdditionalInfo"></i></p>
    </div>
</template>

<script>
    import { format } from 'libphonenumber-js'
    import { mapGetters } from 'vuex'

    export default {
        props: ['schema', 'fieldSchema', 'item', 'slug', 'index', 'field'],
        computed: {
            isNested () {
                if (!this.schema.field_categories) {
                    return false
                }

                if (!this.fieldSchema.category) {
                    return false
                }

                return true
            },
            showCategoryTitle () {
                if (this.isNested === false || this.index > 0) {
                    return false
                }

                // find if first in category
                for (let prop in this.schema.fields) {
                    if (this.schema.fields.hasOwnProperty(prop)) {
                        if (this.schema.fields[prop].category === this.fieldSchema.category) {

                            let schemaSlug = this.schema.fields[prop].slug

                            // see if field has any content
                            for (let i = 0; i < this.content.fields[this.schema.fields[schemaSlug].slug].items.length; i++) {
                                if (this.content.fields[schemaSlug].items[i].value) {
                                    return schemaSlug === this.slug
                                }
                            }
                        }
                    }
                }

                return false
            },
            phone () {
                if (this.fieldSchema.filter === 'phone' && this.item.attributes.country_code && this.item.value) {
                    return format({ country: this.item.attributes.country_code, phone: this.item.value }, 'International')
                }

                return ''
            },
            ...mapGetters({
                content: 'content/manage/overview/editor/content'
            }),
            sanitizedValue () {
                return this.prep(this.item.value)
            },
            sanitizedAdditionalInfo () {
                return this.prep(this.item.additional_info)
            }
        },
        methods: {
            prep (value) {
                value = value || ''

                value = value.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')

                value = value.replace(/\n/g, '<br/>')

                return value
            }
        }
    }
</script>
