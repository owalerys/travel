<template>
    <v-layout column>
        <v-container fluid grid-list-md>
            <v-layout row>
                <v-flex xs9>
                    <v-card light color="white">
                        <v-toolbar flat color="white">
                            <v-btn flat color="primary" dark>
                                <v-icon left dark>chevron_left</v-icon> Back
                            </v-btn>
                        </v-toolbar>
                        <v-card-title>
                            <h1>Air Canada - Schedule Changes</h1>
                        </v-card-title>
                        <v-card-text>
                            <h2>Section</h2>
                            <p>Lorem ipsum dolor sit amet</p>
                            <h2>Section</h2>
                            <p>Lorem ipsum dolor sit amet</p>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs3>
                    <v-card>
                        <v-card-title primary-title>
                            <h2>Supporting PDFs</h2>
                        </v-card-title>
                        <v-list two-line>
                            <v-list-tile @click="">
                                <v-list-tile-action>
                                    <v-icon>picture_as_pdf</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>Domestic Doc</v-list-tile-title>
                                    <v-list-tile-sub-title>PDF Description...</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-divider></v-divider>
                            <v-list-tile @click="">
                                <v-list-tile-action>
                                    <v-icon>picture_as_pdf</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>International Doc</v-list-tile-title>
                                    <v-list-tile-sub-title>PDF Description...</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-divider></v-divider>
                            <v-list-tile @click="">
                                <v-list-tile-action>
                                    <v-icon>picture_as_pdf</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>Transatlantic Doc</v-list-tile-title>
                                    <v-list-tile-sub-title>PDF Description...</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-divider></v-divider>
                            <v-list-tile @click="">
                                <v-list-tile-action>
                                    <v-icon>picture_as_pdf</v-icon>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>Transpacific Doc</v-list-tile-title>
                                    <v-list-tile-sub-title>PDF Description...</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-layout>
    <!--<v-container fluid>
      <v-layout row>
        <v-flex xs9>
          <v-card light color="white">
            <v-toolbar>
              <v-btn icon>
                <v-icon>chevron_left</v-icon>
              </v-btn>
              <v-toolbar-title v-text="'Air Canada - Schedule Changes'"></v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <h2>Section</h2>
              <p>Lorem ipsum dolor sit amet</p>
              <h2>Section</h2>
              <p>Lorem ipsum dolor sit amet</p>
            </v-card-text>
          </v-card>
        </v-flex>
        <v-flex xs3>
          <v-card light color="white">
            <v-toolbar>
              <v-btn icon>
                <v-icon>clear</v-icon>
              </v-btn>
              <v-toolbar-title v-text="'PDFs'"></v-toolbar-title>
            </v-toolbar>
            <v-card-text></v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>-->
    <!--<v-container fluid>
      <v-slide-y-transition mode="out-in">
        <v-layout column align-center>
          <img src="/static/v.png" alt="Vuetify.js" class="mb-5" />
          <blockquote>
            &#8220;First, solve the problem. Then, write the code.&#8221;
            <footer>
              <small>
                <em>&mdash;John Johnson</em>
              </small>
            </footer>
          </blockquote>
        </v-layout>
      </v-slide-y-transition>
    </v-container>-->
</template>

<script>
    export default {
        data () {
            return {}
        }
    }
</script>
