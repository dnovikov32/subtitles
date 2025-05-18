<template>

    <div>

        <div v-if="isLoading">
            <b-spinner variant="primary" label="Spinning"></b-spinner>
        </div>

        <div v-if="serial">

            <h4>
                {{ serial.title }}

                <div class="float-right">
                    <b-button :to="`/films/edit/${serial.id}`" variant="primary" size="sm">
                        <i class="fa fa-edit"></i> Edit
                    </b-button>
                </div>
            </h4>

            <ul>
                <li v-for="(subtitle, index) in subtitles" :key="index">

                    <router-link :to="{ name: 'subtitles.show', params: {id: subtitle.id } }">
                        s{{ subtitle.season }}e{{ subtitle.episode }} -
                        {{ subtitle.title ? subtitle.title : 'noname' }}
                    </router-link>

                </li>
            </ul>

        </div>

    </div>

</template>

<script>
    export default {

        computed: {
            isLoading: function () {
                return this.$store.getters['isLoading'];
            },
            serial () {
                return this.$store.getters['serials/serial'];
            },
            subtitles () {
                return this.serial ? this.serial.subtitles : [];
            }
        },

        created () {
            this.$store.dispatch('serials/show', this.$route.params.id);
        },

        methods: {

        }

    }
</script>