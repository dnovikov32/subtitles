<template>

    <b-navbar toggleable="lg" variant="light" class="px-0">

        <b-navbar-brand to="/">Subtitles</b-navbar-brand>

        <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

        <b-collapse id="nav-collapse" is-nav>

            <b-navbar-nav>
                <b-nav-item to="/films/index">Films</b-nav-item>
                <b-nav-item to="/films/create">Create</b-nav-item>
            </b-navbar-nav>

            <b-navbar-nav class="ml-auto">
                <b-nav-item to="/user/login">Login</b-nav-item>
            </b-navbar-nav>

        </b-collapse>

    </b-navbar>

</template>

<script>

    export default {
        name: "Navbar"
    }

</script>

<style scoped>

</style>