<template>
    <div class="fixed z-10 top-0 h-screen">
        <div class="w-screen h-screen flex bg-gray-900 opacity-60 relative">
        </div>
        <div class="absolute inset-x-10 md:inset-x-1/5 xl:inset-1/4 h-4/6 md:h-3/5 md:w-1/2 xl:h-1/2 rounded-xl bg-gray-200 py-10 flex overflow-scroll">
            <div class="mx-auto space-y-5">
                <h1 class="text-xl font-semibold">Epasta ziņa</h1>
                <editor
                    api-key="asjef8xxim81si331ci5a80ypn35vkqxrjynu0xl5merr1yl"
                    v-model="form.content"
                    :init="{
                     height: 350,
                     menubar: false,
                     plugins: [
                       'advlist autolink lists link image charmap print preview anchor',
                       'searchreplace visualblocks code fullscreen',
                       'insertdatetime media table paste code help wordcount'
                     ],
                     toolbar:
                       'undo redo | formatselect | bold italic backcolor | \
                       alignleft aligncenter alignright alignjustify | \
                       bullist numlist outdent indent | removeformat | help'
                }"
                ></editor>
                <div class="ml-auto w-fit">
                    <PrimaryButton>Nosūtīt</PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import PrimaryButton from "@/Components/PrimaryButton.vue";
export default {
    name: "CustomMail",
    components:{PrimaryButton, Editor},
    data(){
        return {
            form:{
                content: ''
            }
        }
    },
    methods:{
        sendMessageData(){
            this.$emit
        }
    }
}
</script>

<style scoped>

</style>
