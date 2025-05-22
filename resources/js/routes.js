import Home from './components/main/Home.vue';
import FilmsIndex from './components/films/FilmsIndex.vue';
import FilmsEdit from './components/films/FilmsEdit.vue';
import SerialsShow from './components/serial/SerialsShow.vue';
import SubtitlesShow from './components/subtitles/SubtitlesShow.vue';
import SubtitlesEdit from './components/subtitles/SubtitlesEdit.vue';

export default [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/films/index',
        name: 'films.index',
        component: FilmsIndex
    },
    {
        path: '/films/create',
        name: 'films.create',
        component: FilmsEdit
    },
    {
        path: '/films/update/:id',
        name: 'films.update',
        component: FilmsEdit
    },
    {
        path: '/films/edit/:id',
        name: 'films.edit',
        component: FilmsEdit
    },
    {
        path: '/serials/show/:id',
        name: 'serials.show',
        component: SerialsShow
    },
    {
        path: '/subtitles/show/:id',
        name: 'subtitles.show',
        component: SubtitlesShow
    },
    {
        path: '/subtitles/edit/:id',
        name: 'subtitles.edit',
        component: SubtitlesEdit
    },
]


