<template>
    <div class="w-full bg-gray-550 p-8 flex">
        <div class="w-1/2 md:w-1/3 lg:w-1/4 space-y-5 my-auto">
            <h1 class="text-gray-200 text-lg font-semibold">Koordinē</h1>
            <div>
                <a href="https://sp.rsu.lv/" target="_blank"><img class="mx-auto" src="https://mentors.rsu.lv/images/rsusp-logo.svg" alt="RSUsp"></a>
            </div>
        </div>
        <div class="text-right ml-auto text-gray-200 flex flex-col my-auto space-y-3">
            <a href="/files/RSU%20SP_Privātuma%20politika.pdf" target="_blank">Datu izmantošana</a>
            <div class="flex space-x-5">
                <a href="tel:+37167409159">+371 67 409 159</a>
                <a href="mailto:sp@rsu.lv">sp@rsu.lv</a>
            </div>
            <div class="flex space-x-5">
                <div class="flex-grow"></div>
                <div class="p-2 bg-gray-200 rounded-full flex w-fit">
                    <a href="https://www.instagram.com/rsusp/" target="_blank" class="h-5 w-5"><img src="/img/instagram.svg" alt="instagram"></a>
                </div>
                <div class="p-2 bg-gray-200 rounded-full flex w-fit">
                    <a href="https://www.facebook.com/RSUSP" target="_blank"><img src="/img/facebook.svg" class="h-5 w-5" alt="instagram"></a>
                </div>
            </div>
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
