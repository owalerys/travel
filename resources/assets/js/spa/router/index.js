import Vue from 'vue'
import Router from 'vue-router'

import ExampleComponent from '../components/ExampleComponent'
import ContentCreation from '../components/ContentCreation'
import Search from '../components/Search'
import ContentManager from 'Travel/components/content/manager/Landing'
import ArticleOverview from 'Travel/components/content/manager/ArticleOverview'
import VersionEditor from 'Travel/components/content/manager/VersionEditor'

Vue.use(Router)

const routes = [
    { path: '/', redirect: { name: 'manage-content' }, name: 'landing' },
    { path: '/kb', component: ExampleComponent, name: 'kb' },
    { path: '/content/manage', component: ContentManager, name: 'manage-content' },
    { path: '/content/manage/article/:article_id', component: ArticleOverview, name: 'article-overview' },
    { path: '/content/manage/article/:article_id/version/:version/edit', component: VersionEditor, name: 'version-editor' },
    { path: '/search', component: Search, name: 'search' }
]

export default new Router({ mode: 'history', routes, base: '/app/' })
