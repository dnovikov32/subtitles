<template>

    <div>

        <div class="loader" v-if="isLoading">
            <b-spinner variant="primary" label="Spinning"></b-spinner>
        </div>

        <div v-if="subtitle">

            <h4 v-if="film" class="float-left">{{ film.title }}</h4>

            <div v-if="film" class="float-right sticky-top buttons">
                <b-button variant="success"
                      size="sm"
                      @click="save"
                      :disabled="isLoading"
                >
                    <i class="fa fa-save"></i> Save
                </b-button>

                <b-button variant="danger"
                      size="sm"
                      :disabled="isLoading"
                      @click="destroy"
                      title="Delete subtitle"
                >
                    <i class="fa fa-trash"></i>
                </b-button>
            </div>

            <table v-if="rows" class="table table-striped table-hover">

                <tr v-for="(row, index) in maxLength">

                    <td class="col--index">
                        {{ index }}
                    </td>

                    <td class="col">
                        <div v-if="en[index]">
                            <div class="time">{{ en[index].startTimeFormatted }}</div>
                            <div class="text" v-text="en[index].text" @blur="changeText($event, 'en', index)" contenteditable></div>

                            <div class="ctrl">
                                <div class="ctrl__btn" @click="up('en', index)"><i class="fa fa-angle-up"></i></div>
                                <div class="ctrl__btn" @click="down('en', index)"><i class="fa fa-angle-down"></i></div>
                            </div>
                        </div>
                    </td>

                    <td class="col">
                        <div v-if="ru[index]">

                            <div class="time">{{ ru[index].startTimeFormatted }}</div>

                            <div class="text" v-text="ru[index].text" @blur="changeText($event, 'ru', index)" contenteditable></div>

                            <div class="ctrl">
                                <div class="ctrl__btn" @click="up('ru', index)"><i class="fa fa-angle-up"></i></div>
                                <div class="ctrl__btn" @click="down('ru', index)"><i class="fa fa-angle-down"></i></div>
                            </div>

                        </div>
                    </td>

                </tr>
            </table>

        </div>

    </div>

</template>

<script>
    export default {

        computed: {
            isLoading: function () {
                return this.$store.getters['isLoading'];
            },
            hasError: function () {
                return this.$store.getters['hasError'];
            },
            subtitle () {
                return this.$store.getters['subtitles/subtitle'];
            },
            film () {
                return this.subtitle ? this.subtitle.film : [];
            },
            rows () {
                return this.$store.getters['subtitles/rows'];
            },
            ru () {
                return this.rows ? this.rows.ru : [];
            },
            en () {
                return this.rows ? this.rows.en : [];
            },
            maxLength () {
                if (! (this.en && this.ru)) {
                    return 0;
                }

                if (this.en.length > this.ru.length) {
                    return this.en.length;
                }

                return this.ru.length;
            }
        },

        created () {
            this.$store.dispatch('subtitles/find', this.$route.params.id);
        },

        methods: {
            up (lang, index) {
                let result = true;
                let element = this.rows[lang][index - 1];

                if (element && element.text) {
                    result = confirm('Вы уверены, что хотите удалить верхнюю строчку?');
                }

                if (result) {
                    this.rows[lang].splice(index - 1, 1);
                }

                return false;
            },

            down (lang, index) {
                this.rows[lang].splice(index, 0, {
                    text: '',
                    startTime: this.rows[lang][index].startTime,
                    endTime: this.rows[lang][index].endTime,
                });

                return false;
            },

            changeText (event, lang, index) {
                let newText = event.target.innerText;
                let oldText = this.rows[lang][index].text;

                if (oldText != newText) {
                    this.rows[lang][index].text = newText;
                }
            },

            save () {
                let data = {
                    id: this.subtitle.id,
                    rows: JSON.stringify(this.rows)
                };

                this.$store.dispatch('subtitles/update', data).then( () => {
                    if (this.hasError) {
                        return false;
                    }

                    this.$notify({type: 'success', text: 'Subtitle saved'});
                });
            },

            destroy () {
                if (! confirm('Are you sure??')) {
                    return false;
                }

                let filmId = this.film.id;

                this.$store.dispatch('subtitles/destroy', this.subtitle.id).then(() => {
                    this.$notify({type: 'success', text: 'Subtitle deleted'});
                    this.$router.push({ name: 'films.edit', params: { id: filmId } });
                });
            }

        }

    }
</script>

<style scoped>
    .buttons {
        top: 5px;
    }

    .col {
        position: relative;
        width: 50%;
    }

    .col--index {
        position: relative;
        width: 1%;
        color: #ccc;
    }

    .time {
        margin-bottom: 5px;
        font-size: 9px;
        color: #9a9a9a;
    }

    .text {
        white-space: pre-wrap;
    }

    .ctrl {
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
    }

        .ctrl__btn {
            display: inline-block;
            width: 17px;
            height: 17px;
            margin-left: 2px;
            text-align: center;
            line-height: 1.5em;
            cursor: pointer;
        }

</style>