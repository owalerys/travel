import Vue from 'vue'
import http from 'Travel/http'
import uuid from 'uuid'

export default {
    namespaced: true,
    state () {
        return {
            title: "",
            description: "",
            url: "",
            schema_version: "",
            category_slug: "",
            fields: {},
            versionId: null,
            modified: false,
            articleId: null,
            saving: false,
            busUuid: uuid.v4()
        }
    },
    getters: {
        content (state) {
            return {
                title: state.title,
                description: state.description,
                url: state.url,
                schema_version: state.schema_version,
                category_slug: state.category_slug,
                fields: state.fields
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
        load ({ commit, state, rootGetters }, { article, versionNumber }) {
            return new Promise((resolve, reject) => {
                commit('UNSET_FIELDS')

                let version = article.versions.find((item) => {
                    return item.version === versionNumber
                })

                let content = JSON.parse(version.content)
                if (content instanceof Object === false) {
                    content = {}
                }

                commit('SET_SCHEMA', {
                    slug: version.category_slug,
                    version: version.schema_version,
                    versionId: version.id,
                    articleId: article.id
                })

                commit('UPDATE_TYPE', { type: version.type })
                commit('UPDATE_TITLE', { title: version.title || '' })
                commit('UPDATE_DESCRIPTION', { description: version.description || '' })
                commit('UPDATE_URL', { url: version.url || '' })

                let schema = rootGetters['content/categoryBySlugAndVersion'](version.schema_version, version.category_slug)

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
        },
        save ({ commit, state, dispatch }) {
            commit('SAVING_STATE', { status: true })
            http.patch('/manage/article/' + state.articleId + '/' + state.versionId,
                {
                    content: {
                        title: state.title,
                        description: state.description,
                        url: state.url,
                        type: state.type,
                        fields: state.fields,
                        schema_version: state.schema_version,
                        category_slug: state.category_slug
                    }
                }).then((resolve) => {
                dispatch('messages/write', { type: 'success', busUuid: state.busUuid, message: 'Saved!', timeout: 5000 }, { root: true })
                commit('SAVING_STATE', { status: false })
            }).catch((error) => {
                dispatch('messages/write', { type: 'error', busUuid: state.busUuid, message: 'An error occurred...', timeout: 10000 }, { root: true })
                commit('SAVING_STATE', { status: false })
            })
        }
    },
    mutations: {
        SAVING_STATE (state, { status }) {
            state.saving = status
        },
        UPDATE_TYPE (state, { type }) {
            state.type = type
        },
        UPDATE_TITLE (state, { title }) {
            state.title = title

            state.modified = true
        },
        UPDATE_DESCRIPTION (state, { description }) {
            state.description = description

            state.modified = true
        },
        UPDATE_URL (state, { url }) {
            state.url = url

            state.modified = true
        },
        SET_SCHEMA (state, { versionId, version, slug, articleId }) {
            state.versionId = versionId
            state.schema_version = version
            state.category_slug = slug
            state.articleId = articleId

            state.modified = false
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

            state.modified = true
        },
        UNSET_FIELDS (state) {
            for (let prop in state.fields) {
                if (state.fields.hasOwnProperty(prop)) {
                    Vue.delete(state.fields, prop)
                }
            }
        },
        SET_FIELD_ITEMS (state, { slug, items }) {
            if (items instanceof Array && items.length > 0) {
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

            state.modified = true
        },
        DELETE_LAST_FIELD (state, { slug }) {
            if (state.fields[slug].items.length > 1) {
                state.fields[slug].items.splice(state.fields[slug].items.length - 1, 1)
            }

            state.modified = true
        },
        UPDATE_FIELD_ITEM (state, { slug, key, item }) {
            state.fields[slug].items.splice(key, 1, item)

            state.modified = true
        },
        UPDATE_SUB_HEADING (state, { slug, key, sub_heading }) {
            state.fields[slug].items[key].custom_heading = sub_heading

            state.modified = true
        },
        UPDATE_ADDITIONAL_INFO (state, { slug, key, additional_info }) {
            state.fields[slug].items[key].additional_info = additional_info

            state.modified = true
        },
        UPDATE_VALUE (state, { slug, key, value }) {
            state.fields[slug].items[key].value = value

            state.modified = true
        },
        UPDATE_ATTRIBUTE (state, { slug, key, attribute, value }) {
            state.fields[slug].items[key].attributes[attribute] = value

            state.modified = true
        }
    }
}