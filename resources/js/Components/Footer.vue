<template>
    <div class="w-full bg-gray-900 p-8 flex">
        <div class="w-1/2 md:w-1/3 lg:w-1/4 space-y-5 my-auto">
            <h1 class="text-gray-200 text-lg font-semibold">Koordinē</h1>
            <img class="mx-auto" src="https://mentors.rsu.lv/images/rsusp-logo.svg" alt="RSUsp">
        </div>
        <div class="text-right ml-auto text-gray-200 flex flex-col my-auto space-y-3">
            <a>Kontakti</a>
            <a>Datu izmantošana</a>
            <Link :href="route('login')" v-if="$page.props.auth.user === null">Autorizēties</Link>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    name: "Footer",
    components: {Link}
}
</script>

<style scoped>

</style>
