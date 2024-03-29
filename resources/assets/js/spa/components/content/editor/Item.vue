<template>
    <div>
        <v-card :class="{ 'elevation-2': fieldSchema.multiple == true }">
            <v-card-text>
                <v-icon class="draggable" color="grey" v-if="fieldSchema.multiple">drag_handle</v-icon>

                <v-text-field label="Custom Heading (optional)" v-if="fieldSchema.multiple && fieldSchema.custom_sub_heading" v-model="subHeading" :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.custom_sub_heading')"></v-text-field>

                <!-- Phone -->
                <v-select
                        label="Select"
                        :items="countries"
                        item-text="name"
                        item-value="code"
                        v-if="fieldSchema.filter === 'phone'"
                        :value="attributes.country_code"
                        @input="inputAttribute({ attribute: 'country_code', value: $event })"
                        autocomplete
                        hint="Countries"
                        persistent-hint
                        :required="value instanceof String && value.length"
                        :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.attributes.country_code')"
                ></v-select>
                <v-text-field
                        label="Phone (excluding country code)"
                        v-if="fieldSchema.filter === 'phone'"
                        v-model="value"
                        :required="attributes.country_code == true"
                        :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.value')"
                ></v-text-field>
                <p v-if="fieldSchema.filter === 'phone'">{{ phone }}</p>

                <!-- URL -->
                <v-text-field
                        label="Link"
                        v-if="fieldSchema.filter === 'url'"
                        v-model="value"
                        :required="attributes.display == true"
                        :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.value')"
                ></v-text-field>
                <v-text-field
                        label="Text to Display"
                        v-if="fieldSchema.filter === 'url'"
                        :value="attributes.display"
                        @input="inputAttribute({ attribute: 'display', value: $event })"
                        :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.attributes.display')"
                ></v-text-field>

                <!-- Paragraph -->
                <v-text-field label="Paragraph Text" v-if="fieldSchema.filter === 'paragraph'" v-model="value" multi-line
                              :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.value')"
                ></v-text-field>

                <!-- Email -->
                <v-text-field label="Email" v-if="fieldSchema.filter === 'email'" v-model="value"
                      :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.value')"
                ></v-text-field>

                <v-text-field label="Additional Info" v-if="fieldSchema.additional_info" v-model="additionalInfo" multi-line
                      :error-messages="fieldErrors(formUuid, 'content.fields.' + slug + '.items.' + index + '.additional_info')"
                ></v-text-field>
            </v-card-text>
        </v-card>
        <br v-if="fieldSchema.multiple"/>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapState } from 'vuex'
    import { format } from 'libphonenumber-js'

    export default {
        data () {
            return {

            }
        },
        props: [
            'index',
            'slug',
            'fieldSchema',
            'formUuid'
        ],
        computed: {
            value: {
                get () {
                    return this.getItem(this.index, this.slug).value
                },
                set (value) {
                    this.updateValue({ slug: this.slug, key: this.index, value: value })
                }
            },
            subHeading: {
                get () {
                    return this.getItem(this.index, this.slug).custom_heading
                },
                set (value) {
                    return this.updateSubHeading({ slug: this.slug, key: this.index, sub_heading: value })
                }
            },
            additionalInfo: {
                get () {
                    return this.getItem(this.index, this.slug).additional_info
                },
                set (value) {
                    return this.updateAdditionalInfo({ slug: this.slug, key: this.index, additional_info: value })
                }
            },
            attributes () {
                return this.getItem(this.index, this.slug).attributes
            },
            field () {
                return this.getField(this.slug)
            },
            ...mapGetters({
                getItem: 'content/manage/overview/editor/item',
                getField: 'content/manage/overview/editor/field'
            }),
            ...mapState({
                countries: state => state.content.countries
            }),
            phone () {
                if (this.fieldSchema.filter === 'phone' && this.attributes.country_code && this.value) {
                    return format({ country: this.attributes.country_code, phone: this.value }, 'International')
                }

                return ''
            },
            ...mapGetters({
                fieldErrors: 'validation/errors',
                fieldHasErrors: 'validation/hasErrors',
                categoryHasErrors: 'validation/categoryErrors'
            }),
            test () {
                return this.fieldErrors(this.formUuid, 'content.fields.' + this.slug + '.items.' + this.index + '.value')
            }
        },
        methods: {
            ...mapMutations({
                updateSubHeading: 'content/manage/overview/editor/UPDATE_SUB_HEADING',
                updateAdditionalInfo: 'content/manage/overview/editor/UPDATE_ADDITIONAL_INFO',
                updateValue: 'content/manage/overview/editor/UPDATE_VALUE',
                updateAttribute: 'content/manage/overview/editor/UPDATE_ATTRIBUTE'
            }),
            inputAttribute ({ attribute, value }) {
                this.updateAttribute({ slug: this.slug, key: this.index, attribute, value })
            }
        }
    }
</script>

<style scoped>
    .draggable {
        cursor: move;
        cursor: -webkit-grabbing;
    }
</style>
