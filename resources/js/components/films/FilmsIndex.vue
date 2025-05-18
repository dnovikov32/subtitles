<template>

    <div class="row">

        <div class="col-6">

            <h4>Films list</h4>

            <ul v-if="films" class="list-unstyled">
                <li v-for="(film, index) in films" :key="index">
                    <router-link v-if="film.subtitle" :to="{ name: 'subtitles.show', params: { id: film.subtitle.id } }">
                        {{ film.title }}
                    </router-link>
                </li>
            </ul>

        </div>

        <div class="col-6">

            <h4>Serials list</h4>

            <ul v-if="serials" class="list-unstyled">
                <li v-for="(serial, index) in serials" :key="index">
                    <router-link :to="{ name: 'serials.show', params: { id: serial.id } }">
                        {{ serial.title }}
                    </router-link>
                </li>
            </ul>

        </div>

    </div>

</template>

<script>
    export default {

        computed: {
            films () {
                return this.$store.getters['films/films'];
            },
            serials () {
                return this.$store.getters['films/serials'];
            }
        },

        created () {
            this.$store.dispatch('films/all');
        }

    }
</script>

<style scoped>
    .list {
        padding-left: 5px;
        list-style: none;
    }
</style>