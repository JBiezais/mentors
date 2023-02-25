<template>
    <div class="fixed z-10 top-0 h-screen">
        <div @click="close()" class="w-screen h-screen flex bg-gray-900 opacity-60 relative">
        </div>
        <div class="absolute inset-x-10 md:inset-x-1/5 xl:inset-1/4 h-4/6 md:h-3/5 md:w-1/2 xl:h-1/2 rounded-xl bg-gray-200 py-10 flex overflow-scroll">
            <div class="mx-auto space-y-5">
                <h1 class="text-xl font-semibold">Epasta ziņa</h1>
                <editor
                    api-key="asjef8xxim81si331ci5a80ypn35vkqxrjynu0xl5merr1yl"
                    v-model="form.content"
                    :init="{
                    height: 350,
                    menubar: true,
                    link_context_toolbar: true,
                    menu: {
                        file: { title: 'File', items: 'newdocument restoredraft | preview | export print | deleteallconversations' },
                        edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
                        view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments' },
                        insert: { title: 'Insert', items: 'image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime' },
                        format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat' },
                        tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount' },
                        table: { title: 'Table', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' },
                        help: { title: 'Help', items: 'help' }
                    },
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'print', 'preview', 'anchor',
                        'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'paste', 'code', 'help', 'wordcount'
                    ],
                    toolbar:[
                        'undo redo | formatselect | bold italic backcolor | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent link | removeformat | help'
                    ]
                    }"

                ></editor>
                <div class="ml-auto w-fit space-x-3">
                    <SecondaryButton @click="close">Aizvērt</SecondaryButton>
                    <PrimaryButton @click="sendMessageData">Nosūtīt</PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
export default {
    name: "CustomMail",
    components:{SecondaryButton, PrimaryButton, Editor},
    data(){
        return {
            form:{
                content: ''
            }
        }
    },
    methods:{
        sendMessageData(){
            this.$emit('custom', this.form.content)
        },
        close(){
            this.$emit('close')
        }
    }
}
</script>

<style scoped>

</style>
