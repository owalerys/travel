<template>
    <v-app>
        <v-navigation-drawer
                fixed
                :clipped="true"
                v-model="drawer"
                app
        >
            <v-list>
                <v-list-tile
                        value="true"
                        v-for="(item, i) in items"
                        :key="i"
                        :to="item.path"
                        :replace="true"
                >
                    <v-list-tile-action>
                        <v-icon v-html="item.icon"></v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title v-text="item.title"></v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar fixed app :clipped-left="true" color="light-blue">
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title v-text="title"></v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon href="/logout">
                <v-icon>lock</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <router-view></router-view>
        </v-content>
        <v-footer :fixed="true" app>
            <span>&copy; 2018</span>
        </v-footer>
    </v-app>
</template>

<script>
    export default {
        data () {
            return {
                clipped: true,
                drawer: false,
                fixed: true,
                items: [
                    {
                        icon: 'bubble_chart',
                        title: 'Knowledge Base',
                        path: '/kb'
                    },
                    {
                        icon: 'bubble_chart',
                        title: 'Content Management',
                        path: '/content'
                    }
                ],
                right: true,
                rightDrawer: false,
                title: 'AgentRef'
            }
        }
    }
</script>