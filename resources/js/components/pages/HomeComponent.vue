<template>
    <v-app id="app">
        <Calendar />
    </v-app>
</template>

<script>
import Calendar from '../pageParts/Calendar';

export default {
    components: {
        Calendar,
    },
    mounted() {
        console.log('HomeComponent mounted.');
    },
};
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
}
</style>
