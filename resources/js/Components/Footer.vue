<template>
    <div class="w-full bg-blue-850 p-8 md:flex space-y-5 md:space-y-0">
        <div class="w-full md:w-1/3 lg:w-1/4 space-y-5 my-auto text-gray-200">
            <h1 class="text-lg font-semibold">Koordinē</h1>
            <div>
                <a href="https://sp.rsu.lv/" target="_blank"><img class="mr-auto w-full max-w-sm" src="/img/white-footer-logo.png" alt="RSUsp"></a>
            </div>
            <div class="flex space-x-5">
                <a href="tel:+37127750555">+371 27 750 555</a>
                <a href="mailto:sp@rsu.lv">sp@rsu.lv</a>
            </div>
        </div>
        <div class="md:text-right ml-auto text-gray-200 flex flex-col my-auto space-y-2">
            <a href="/files/RSU%20SP_Privātuma%20politika.pdf" target="_blank">Datu izmantošana</a>
            <h1 class="font-semibold">Jautājumu gadījumā zvani vai raksti:</h1>
            <div class="flex flex-col font-semibold">
                <a :href="'tel:'+contacts?.phone">{{contacts?.phone}}</a>
                <a :href="'mailto:'+contacts?.email">{{contacts?.email}}</a>
            </div>
            <div class="flex md:space-x-5">
                <div class="md:flex-grow hidden md:block"></div>
                <div class="p-2 bg-gray-200 rounded-full flex w-fit mr-5 md:mr-0">
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
    components: {Link},
    props: {
        contacts: {
            email: String,
            phone: String
        }
    }
}
</script>

<style scoped>

</style>
