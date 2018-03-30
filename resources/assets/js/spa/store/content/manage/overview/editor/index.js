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
            busUuid: uuid.v4(),
            formUuid: uuid.v4(),
            media: {
                busUuid: uuid.v4(),
                uploads: [],
                uploading: false,
                dialog: {
                    id: null,
                    title: null,
                    description: null,
                    internal: false,
                    busUuid: uuid.v4(),
                    submitting: false
                }
            }
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
        },
        upload: (state) => (id) => {
            if (state.media.uploads.length) {
                return state.media.uploads.find((item) => {
                    return item.id === id
                })
            }

            return {}
        },
        dialogUpload (state, getters) {
            return getters.upload(state.media.dialog.id) || {}
        }
    },
    actions: {
        load ({ commit, state, rootGetters, dispatch }, { article, versionNumber }) {
            return new Promise((resolve, reject) => {
                dispatch('validation/flush', { formUuid: state.formUuid }, { root: true })
                commit('UNSET_FIELDS')
                commit('CLEAR_MEDIA')

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

                commit('PUSH_MEDIA', { media: version.media })
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
            return new Promise((resolve, reject) => {
                commit('SAVING_STATE', { status: true })
                dispatch('validation/flush', { formUuid: state.formUuid }, { root: true })
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
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { type: 'error', busUuid: state.busUuid, message: error.response.data.message || 'An unknown error occurred...', timeout: 10000 }, { root: true })
                    dispatch('validation/load', { formUuid: state.formUuid, errors: error.response.data.errors || [] }, { root: true })
                    commit('SAVING_STATE', { status: false })
                    reject(error)
                })
            })
        },
        reorder ({ commit }, { slug, oldIndex, newIndex }) {
            return new Promise((resolve, reject) => {
                commit('REORDER_FIELD', { slug, oldIndex, newIndex })
                resolve()
            })
        },
        async multiUpload ({ commit, dispatch, state }, { files }) {
            commit('UPLOADING_STATUS', { status: true })
            let toResolve = files.map(item => dispatch('upload', { file: item }))
            return new Promise((resolve, reject) => {
                Promise.all(toResolve).then((result) => {
                    commit('UPLOADING_STATUS', { status: false })
                    resolve()
                }).catch((error) => {
                    commit('UPLOADING_STATUS', { status: false })
                    reject()
                })
            })
        },
        async upload ({ commit, dispatch, state }, { file }) {
            return new Promise((resolve, reject) => {
                let id = uuid.v4()
                commit('ADD_MEDIA', { file, id: id })
                dispatch('doUpload', { id }).then((result) => {
                    resolve()
                }).catch((error) => {
                    reject()
                })
            })
        },
        async doUpload ({ commit, state, getters, dispatch }, { id }) {
            commit('START_UPLOAD', { id })
            return new Promise((resolve, reject) => {
                let formData = new FormData()
                formData.append('file', getters.upload(id).file)

                http.post('/manage/article/' + state.articleId + '/' + state.versionId + '/upload',
                    formData,
                    {
                        onUploadProgress: (event) => {
                            commit('UPLOAD_PROGRESS', { progress: Math.floor(event.loaded / event.total) * 100, id })
                        },
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then((result) => {
                    commit('SUCCESS_UPLOAD', { id, file: result.data })
                    resolve()
                }).catch((error) => {
                    commit('FAIL_UPLOAD', { id })
                    dispatch('messages/write', { type: 'error', message: error.response.data.message || 'An unknown error occurred...', timeout: 10000, busUuid: state.media.busUuid }, { root: true })
                    dispatch('messages/writeFromErrorBag', { bag: error.response.data.errors || {}, timeout: 10000, busUuid: state.media.busUuid }, { root: true })
                    reject(error)
                })
            })
        },
        async doDelete ({ commit, state, getters }, { id }) {
            commit('START_DELETE', { id })
            return new Promise((resolve, reject) => {
                http.delete('/manage/article/' + state.articleId + '/' + state.versionId + '/upload/' + id).then((result) => {
                    commit('SUCCESS_DELETE', { id })
                    resolve()
                }).catch((error) => {
                    commit('FAIL_DELETE', { id })
                    reject()
                })
            })
        },
        async localDelete ({ commit }, { id }) {
            commit('START_DELETE', { id })
            return new Promise((resolve, reject) => {
                commit('SUCCESS_DELETE', { id })
                resolve()
            })
        },
        async doDownload ({ commit, state }, { id }) {
            commit('START_DOWNLOAD', { id })
            return new Promise((resolve, reject) => {
                http.get('/manage/article/' + state.articleId + '/' + state.versionId + '/upload/' + id).then((result) => {
                    window.open(result.data.url, '_blank')
                    commit('SUCCESS_DOWNLOAD', { id })
                    resolve()
                }).catch((error) => {
                    commit('FAIL_DOWNLOAD', { id })
                    reject()
                })
            })
        },
        async editProperties ({ commit, getters }, { id }) {
            return new Promise((resolve, reject) => {
                let upload = getters.upload(id)

                commit('SET_PROPERTIES_DIALOG', { upload })

                resolve()
            })
        },
        async doPropertiesSubmit ({ commit, state, dispatch, getters }) {
            return new Promise((resolve, reject) => {
                commit('START_PROPERTIES')
                http.patch('/manage/article/' + state.articleId + '/' + state.versionId + '/upload/' + state.media.dialog.id, {
                    title: state.media.dialog.title,
                    description: state.media.dialog.description,
                    internal: state.media.dialog.internal
                }).then((result) => {
                    dispatch('messages/write', { type: 'success', message: 'Successfully updated!', busUuid: state.media.dialog.busUuid, timeout: 5000 }, { root: true })

                    commit('UPDATE_CUSTOM_PROPERTIES', { upload: getters.dialogUpload, custom_properties: result.data.custom_properties })

                    commit('SUCCESS_PROPERTIES')
                    resolve()
                }).catch((error) => {
                    dispatch('messages/write', { type: 'error', message: error.result.data.message || 'An unexpected error occurred...', busUuid: state.media.dialog.busUuid, timeout: 5000 }, { root: true })
                    dispatch('validation/load', { formUuid: state.media.dialog.busUuid, errors: error.result.data.errors || {} }, { root: true })

                    commit('FAIL_PROPERTIES')
                    reject()
                })
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
        },
        REORDER_FIELD (state, { items, slug }) {
            state.fields[slug].items.splice(0, items.length, ...items)
        },
        CLEAR_MEDIA (state) {
            state.media.busUuid = uuid.v4()
            state.media.uploads.splice(0)
            state.media.uploading = false
        },
        PUSH_MEDIA (state, { media }) {
            for (let i = 0; i < media.length; i++) {

                let file = media[i]

                let item = {
                    id: file.id,
                    model_type: file.model_type,
                    model_id: file.model_id,
                    collection_name: file.collection_name,
                    name: file.name,
                    file_name: file.file_name,
                    mime_type: file.mime_type,
                    disk: file.disk,
                    size: file.size,
                    manipulations: file.manipulations,
                    custom_properties: file.custom_properties,
                    order_column: file.order_column,
                    created_at: file.created_at,
                    updated_at: file.updated_at,
                    status: 'uploaded',
                    message: 'Uploaded',
                    progress: 100
                }

                state.media.uploads.push(item)
            }
        },
        UPLOADING_STATUS (state, { status }) {
            state.media.uploading = status
        },
        ADD_MEDIA (state, { file, id }) {
            let newFile = {
                id: id,
                name: null,
                file_name: file.name,
                mime_type: file.type,
                size: file.size,
                file: file,
                status: 'new',
                message: 'Not Yet Uploaded',
                progress: 0
            }

            state.media.uploads.push(newFile)
        },
        START_UPLOAD (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'uploading'
            item.message = 'Uploading...'
            item.progress = 0
        },
        SUCCESS_UPLOAD (state, { id, file }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            Vue.set(item, 'id', file.id)
            Vue.set(item, 'model_type', file.model_type)
            Vue.set(item, 'model_id', file.model_id)
            Vue.set(item, 'collection_name', file.collection_name)
            Vue.set(item, 'name', file.name)
            Vue.set(item, 'file_name', file.file_name)
            Vue.set(item, 'mime_type', file.mime_type)
            Vue.set(item, 'disk', file.disk)
            Vue.set(item, 'size', file.size)
            Vue.set(item, 'manipulations', file.manipulations)
            Vue.set(item, 'custom_properties', file.custom_properties)
            Vue.set(item, 'order_column', file.order_column)
            Vue.set(item, 'created_at', file.created_at)
            Vue.set(item, 'updated_at', file.updated_at)

            item.status = 'uploaded'
            item.message = 'Uploaded!'
            item.progress = 100
        },
        FAIL_UPLOAD (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'upload-failed'
            item.message = 'Upload failed!'
            item.progress = 0
        },
        UPLOAD_PROGRESS (state, { id, progress }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.progress = progress
        },
        START_DELETE (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'deleting'
            item.message = 'Deleting...'
        },
        SUCCESS_DELETE (state, { id }) {
            let itemIndex = state.media.uploads.findIndex((item) => {
                return item.id === id
            })

            state.media.uploads.splice(itemIndex, 1)
        },
        FAIL_DELETE (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'delete-failed'
            item.message = 'Deletion failed!'
        },
        START_DOWNLOAD (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'downloading'
            item.message = 'Downloading...'
        },
        SUCCESS_DOWNLOAD (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'uploaded'
            item.message = 'Downloaded!'
        },
        FAIL_DOWNLOAD (state, { id }) {
            let item = state.media.uploads.find((item) => {
                return item.id === id
            })

            item.status = 'uploaded'
            item.message = 'Download failed!'
        },
        SET_PROPERTIES_DIALOG (state, { upload }) {
            upload = upload || {}

            state.media.dialog.id = upload.id || null

            let custom_properties = upload.custom_properties || {}

            state.media.dialog.description = custom_properties.description || null
            state.media.dialog.title = custom_properties.title || null
            state.media.dialog.internal = custom_properties.internal || false
        },
        UPDATE_TITLE_PROPERTY (state, { title }) {
            state.media.dialog.title = title
        },
        UPDATE_DESCRIPTION_PROPERTY (state, { description }) {
            state.media.dialog.description = description
        },
        UPDATE_INTERNAL_PROPERTY (state, { internal }) {
            state.media.dialog.internal = internal
        },
        START_PROPERTIES (state) {
            state.media.dialog.submitting = true
        },
        SUCCESS_PROPERTIES (state) {
            state.media.dialog.submitting = false
        },
        FAIL_PROPERTIES (state) {
            state.media.dialog.submitting = false
        },
        UPDATE_CUSTOM_PROPERTIES (state, { upload, custom_properties }) {
            Vue.set(upload.custom_properties, 'title', custom_properties.title || null)
            Vue.set(upload.custom_properties, 'description', custom_properties.description || null)
            Vue.set(upload.custom_properties, 'internal', custom_properties.internal || null)
        }
    }
}