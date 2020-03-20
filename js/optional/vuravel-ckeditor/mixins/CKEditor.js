import CKEditor from '@ckeditor/ckeditor5-vue'
Vue.use( CKEditor );

require('ckeditor5-build-from-source') //locally... but doesn't work for the public package
//import ClassicEditor from 'ckeditor5-build-from-source'

export default {
    data(){
        return {
            editor: window.ClassicEditor, //locally... but doesn't work for the public package
            //editor: ClassicEditor,
            editorConfig: null
        }
    },
    methods: {
        focus(){
            //focusing CKeditor not working when click on label :/
            this.$_focusAction()
            this.$refs.content.instance.editing.view.focus()
        },
        $_inputAction(){
            this.$_changeAction()
            this.$emit('change', this.$_value)
        }
    },
    created(){
        //$_data toolbar was undefined if declared in data()
        this.editorConfig = {
            alignment: { options: [ 'left', 'right', 'center', 'justify' ] },
            toolbar: this.$_data('toolbar')
        }
    }
}