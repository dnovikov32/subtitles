<template>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="loader" v-if="isLoading">
                <b-spinner variant="primary" label="Spinning"></b-spinner>
            </div>
            <h4>{{ film.id ? 'Update film' : 'Create film' }}</h4>
        </div>

        <div class="col-8">
            <b-form id="form" @submit="save">
                <h5>Common</h5>
                <b-card class="mb-3">
                    <b-form-group
                        label="Title"
                        label-for="title"
                        label-cols-sm="2">
                        <b-form-input
                            id="title"
                            type="text"
                            v-model="film.title"
                            required
                            trim />
                    </b-form-group>
                </b-card>

                <div class="mb-3">
                    <h5>Add subtitles</h5>
                    <b-card class="mb-3">

                        <b-form-group label="Title" label-cols-sm="3">
                            <b-form-input
                                type="text"
                                v-model="subtitle.title"
                                trim />
                        </b-form-group>

                        <div class="row">
                             <div class="col">
                                <b-form-group label="Season" label-cols-sm="6">
                                    <b-form-input
                                        type="number"
                                        v-model="subtitle.season" />
                                </b-form-group>
                            </div>
                            <div class="col">
                                <b-form-group label="Episode" label-cols-sm="6">
                                    <b-form-input
                                        type="number"
                                        v-model="subtitle.episode" />
                                </b-form-group>
                            </div>
                        </div>

                        <b-form-group v-for="(file, fileIndex) in subtitle.files" :key="fileIndex">
                            <b-form-file
                                v-model="file.file"
                                accept=".srt"
                                :placeholder="file.text">
                            </b-form-file>
                        </b-form-group>

                    </b-card>
                </div>

                <b-button type="submit" variant="primary" :disabled="isLoading">
                    <i class="fa fa-save"></i> Save
                </b-button>

                <b-button v-if="film.id" variant="danger"
                      class="ml-2"
                      :disabled="isLoading"
                      @click="destroy"
                      title="Delete film">
                    <i class="fa fa-trash"></i> Delete
                </b-button>

            </b-form>

        </div>

        <div class="col-4" v-if="film.subtitles">
            <h5>Subtitles</h5>

            <ul class="list-unstyled">
               <li v-for="(subtitle, index) in film.subtitles" :key="index">
                    <router-link :to="{ name: 'subtitles.edit', params: {id: subtitle.id } }">
                       <span v-if="subtitle.title">{{ subtitle.title }} - </span>

                       <span v-if="! (subtitle.season && subtitle.episode)">Default</span>
                       <span v-else>s{{ subtitle.season }}e{{ subtitle.episode }}</span>
                   </router-link>
               </li>
            </ul>

        </div>

    </div>

</template>

<script>
    import each from 'lodash/each';

    export default {

        data () {
            return {
                languages: [
                    {value: 1, text: 'English', name: 'en'},
                    {value: 2, text: 'Russian', name: 'ru'}
                ],
                subtitle: {}
            }
        },

        computed: {
            isLoading: function () {
                return this.$store.getters['isLoading'];
            },
            hasError: function () {
                return this.$store.getters['hasError'];
            },
            film: function () {
                return this.$store.getters['films/film'];
            }
        },

        created () {
            this.subtitle = this.newSubtitle();

            if (this.$route.params.id) {
                this.$store.dispatch('films/find', this.$route.params.id);
            }
        },

        watch: {
            '$route': function () {
                this.subtitle = this.newSubtitle();

                if (this.$route.params.id) {
                    this.$store.dispatch('films/find', this.$route.params.id);
                }
            }
        },

        methods: {
            newSubtitle () {
                let files = [];

                let subtitle = {
                    title: '',
                    season: 0,
                    episode: 0,
                    files: files,
                };

                each(this.languages, function (lang) {
                    files.push({
                        file: null,
                        lang: lang.name,
                        text: lang.text
                    });
                });

                 return subtitle;
            },

            createForm () {
                let form = new FormData();

                form.append('film[id]', this.film.id || null);
                form.append('film[title]', this.film.title);

                form.append('subtitle[title]', this.subtitle.title);
                form.append('subtitle[season]', this.subtitle.season);
                form.append('subtitle[episode]', this.subtitle.episode);

                each(this.subtitle.files, function (file) {
                    form.append(`${file.lang}`, file.file);
                });

                return form;
            },

            save (event) {
                event.preventDefault();

                this.$store.dispatch('films/edit', this.createForm()).then(() => {
                    if (this.hasError) {
                        return false;
                    }

                    this.$notify({type: 'success', text: 'Film saved'});

                    this.$router.push({ name: 'films.update', params: { id: this.film.id } });
                    this.subtitle = this.newSubtitle();
                });

            },

            destroy () {
                if (! confirm('Are you sure??')) {
                    return false;
                }

                this.$store.dispatch('films/destroy', this.film.id).then(() => {
                    this.$notify({type: 'success', text: 'Film was delete'});
                    this.$router.push({ name: 'films.index'});
                });
            }
        }

    }
</script>
