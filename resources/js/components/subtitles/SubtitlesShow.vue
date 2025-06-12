<template>

    <div>

        <div class="loader" v-if="isLoading">
            <b-spinner variant="primary" label="Spinning"></b-spinner>
        </div>

        <div v-if="subtitle">

            <h4 v-if="film" class="float-left">{{ film.title }}</h4>

            <div v-if="film" class="float-right sticky-top buttons">

                <b-button variant="info"
                          size="sm"
                          class="text-white"
                          @click="toggleAll"
                          title="Show all"
                          :disabled="isLoading"
                >
                    <i v-if="isAllVisible" class="fa fa-eye-slash"></i>
                    <i v-else class="fa fa-eye"></i>
                </b-button>

                <b-button variant="info"
                          size="sm"
                          class="text-white"
                          @click="changeViewMode"
                          title="Change view mode"
                          :disabled="isLoading"
                >
                    <i v-if="isRowsView" class="fa fa-table"></i>
                    <i v-else class="fa fa-list"></i>
                </b-button>

                <b-button :to="`/films/edit/${film.id}`"
                          variant="primary"
                          size="sm"
                          :disabled="isLoading"
                >
                    <i class="fa fa-edit"></i> Edit
                </b-button>

            </div>

            <table v-if="rows" class="table table-striped table-hover">

                <tr v-for="(row, index) in rows" class="tr">

                    <td>
                        <div v-text="row.position" class="position"></div>
                        <div v-text="row.startTime" class="time"></div>
                    </td>

                    <td class="col">
                        <div class="text text--eng" @click="toggleRow(row)">{{ row.text1 }}</div>

                        <div class="text text--rus" v-show="isRowsView && row.status">{{ row.text2 }}</div>
                    </td>

                    <td class="col" v-show="isTableView && row.status">
                        <div class="text text--rus">{{ row.text2 }}</div>
                    </td>

                </tr>

            </table>

        </div>

    </div>

</template>

<script>
    export default {

        data () {
            return {
                viewMode: 'rows',
                isAllVisible: false
            }
        },

        computed: {
            isLoading: function () {
                return this.$store.getters['isLoading'];
            },
            subtitle () {
                return this.$store.getters['subtitles/subtitle'];
            },
            film () {
                return this.subtitle ? this.subtitle.film : [];
            },
            rows () {
                return this.subtitle ? this.subtitle.rows : [];
            },
            isRowsView () {
                return this.viewMode === 'rows';
            },
            isTableView () {
                return this.viewMode === 'table';
            }
        },

        created () {
            this.$store.dispatch('subtitles/show', this.$route.params.id);
        },

        methods: {
            changeViewMode () {
                if (this.isRowsView) {
                    this.viewMode = 'table';
                } else {
                    this.viewMode = 'rows';
                }
            },

            toggleRow (row) {
                row.status = Number(! row.status);
            },

            toggleAll () {
                if (this.isAllVisible) {
                    this.rows.map( (el) => el.status = 0 );
                    this.isAllVisible = false;
                } else {
                    this.rows.map( (el) => el.status = 1 );
                    this.isAllVisible = true;
                }
            },

        }

    }
</script>

<style scoped>
    .buttons {
        top: 5px;
    }

    .tr {
        position: relative;
        display: flex;
        flex-wrap: wrap;
    }

    .position {
        font-size: 10px;
        color: #676767;
    }

    .time {
        font-size: 10px;
        color: #676767;
    }

    .text {
        white-space: pre-wrap;
    }

    .text--eng {
        cursor: pointer;
    }

    .text--rus {
        color: #676767;
    }

</style>
