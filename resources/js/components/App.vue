<template>

    <div>

        <notifications />

        <div class="row">
            <div class="col-12 mt-1 mb-5">
                <Navbar></Navbar>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <router-view></router-view>
            </div>
        </div>

    </div>

</template>

<script>
    import Navbar from './Navbar';

    export default {
        components: {
            Navbar
        }
    }
</script>

<style scoped>

</style>