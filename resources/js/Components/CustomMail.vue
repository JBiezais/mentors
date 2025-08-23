<template>
    <Modal :show="show" @close="close" max-width="2xl">
        <div class="p-6 space-y-5">
            <h1 class="text-xl font-semibold">Epasta ziņa 1</h1>
            <editor
                api-key="kg9ubz7kwfz57eu4x20dvd1sfnd3o2h2pa7bc2hwkxbuv9x9"
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
    </Modal>
</template>

<script>
import Editor from '@tinymce/tinymce-vue'
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
export default {
    name: "CustomMail",
    components:{Modal, SecondaryButton, PrimaryButton, Editor},
    props: {
        show: {
            type: Boolean,
            required: true
        }
    },
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
