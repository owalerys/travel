import Vue from 'vue'

export default {
    namespaced: true,
    state () {
        return {
            "title": "",
            "description": "",
            "url": "",
            "schema_version": "",
            "category_slug": "",
            "fields": {}
        }
    },
    getters: {
        content (state) {
            return {
                "title": state.title,
                "description": state.description,
                "url": state.url,
                "schema_version": state.schema_version,
                "category_slug": state.category_slug,
                "fields": state.fields
            }
        },
        item: (state) => (key, slug) => {
            if (state.fields[slug]) {
                if (state.fields[slug].items[key]) {
                    return state.fields[slug].items[key]
                }
            }

            return {}
        },
        field: (state) => (slug) => {
            if (state.fields[slug]) {
                return state.fields[slug]
            }

            return {}
        }
    },
    actions: {
        load ({ commit, state, rootGetters }, { slug, version, content }) {
            return new Promise((resolve, reject) => {
                commit('UNSET_FIELDS')
                commit('SET_SCHEMA', { slug, version })
                commit('UPDATE_TITLE', { title: content.title || '' })
                commit('UPDATE_DESCRIPTION', { description: content.description || '' })
                commit('UPDATE_URL', { url: content.url || '' })

                let schema = rootGetters['content/categoryBySlugAndVersion'](version, slug)

                for (let prop in schema.fields) {
                    if (schema.fields.hasOwnProperty(prop)) {
                        if (content.fields) {
                            commit('INITIALIZE_FIELD', { slug: prop, type: schema.fields[prop].filter, title: content.fields[prop].custom_title || '' })
                            commit('SET_FIELD_ITEMS', { slug: prop, items: content.fields[prop].items || [] })
                        } else {
                            commit('INITIALIZE_FIELD', { slug: prop, type: schema.fields[prop].filter, title: '' })
                            commit('SET_FIELD_ITEMS', { slug: prop, items: [] })
                            commit('ADD_FIELD_ITEM', { slug: prop })
                        }
                    }
                }

                resolve()
            })
        }
    },
    mutations: {
        UPDATE_TITLE (state, { title }) {
            state.title = title
        },
        UPDATE_DESCRIPTION (state, { description }) {
            state.description = description
        },
        UPDATE_URL (state, { url }) {
            state.url = url
        },
        SET_SCHEMA (state, { version, slug }) {
            state.schema_version = version
            state.category_slug = slug
        },
        INITIALIZE_FIELD (state, { slug, type, title }) {
            Vue.set(state.fields, slug, {
                "custom_title": title || "",
                "slug": slug,
                "type": type,
                "items": []
            })
        },
        SET_FIELD_TITLE (state, { slug, title }) {
            state.fields[slug].custom_title = title
        },
        UNSET_FIELDS (state) {
            for (let prop in state.fields) {
                if (state.fields.hasOwnProperty(prop)) {
                    Vue.delete(state.fields, prop)
                }
            }
        },
        SET_FIELD_ITEMS (state, { slug, items }) {
            if (typeof items === Array && items.length > 0) {
                state.fields[slug].items.splice(0)
                state.fields[slug].items.push(...items)
            } else {
                state.fields[slug].items.splice(0)
            }
        },
        ADD_FIELD_ITEM (state, { slug }) {
            let type = state.fields[slug].type

            let item = {
                "custom_heading": "",
                "attributes": {},
                "value": "",
                "additional_info": ""
            }

            if (type === "phone") {
                item.attributes.country_code = ""
            } else if (type === "url") {
                item.attributes.display = ""
            }

            state.fields[slug].items.push(item)
        },
        DELETE_LAST_FIELD (state, { slug }) {
            if (state.fields[slug].items.length > 1) {
                state.fields[slug].items.splice(state.fields[slug].items.length - 1, 1)
            }
        },
        UPDATE_FIELD_ITEM (state, { slug, key, item }) {
            state.fields[slug].items.splice(key, 1, item)
        },
        UPDATE_SUB_HEADING (state, { slug, key, sub_heading }) {
            state.fields[slug].items[key].custom_heading = sub_heading
        },
        UPDATE_ADDITIONAL_INFO (state, { slug, key, additional_info }) {
            state.fields[slug].items[key].additional_info = additional_info
        },
        UPDATE_VALUE (state, { slug, key, value }) {
            state.fields[slug].items[key].value = value
        },
        UPDATE_ATTRIBUTE (state, { slug, key, attribute, value }) {
            state.fields[slug].items[key].attributes[attribute] = value
        }
    }
}