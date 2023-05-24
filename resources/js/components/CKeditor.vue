<script lang="jsx">
import {ref, watch} from "vue";
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor'
import Markdown from '@ckeditor/ckeditor5-markdown-gfm/src/markdown';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
//import UploadAdapter from '@ckeditor/ckeditor5-adapter-ckfinder/src/uploadadapter';
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote';
//import EasyImage from '@ckeditor/ckeditor5-easy-image/src/easyimage';
import Heading from '@ckeditor/ckeditor5-heading/src/heading';
import Image from '@ckeditor/ckeditor5-image/src/image';
import ImageCaption from '@ckeditor/ckeditor5-image/src/imagecaption';
import ImageStyle from '@ckeditor/ckeditor5-image/src/imagestyle';
import ImageToolbar from '@ckeditor/ckeditor5-image/src/imagetoolbar';
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload';
import Link from '@ckeditor/ckeditor5-link/src/link';
import List from '@ckeditor/ckeditor5-list/src/list';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting';
import SimpleUploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/simpleuploadadapter';
///import {MediaEmbed} from '@ckeditor/ckeditor5-media-embed';
//import { GeneralHtmlSupport } from '@ckeditor/ckeditor5-html-support';
import {Table, TableToolbar} from '@ckeditor/ckeditor5-table';

export default {
  components: {
    ckeditor: CKEditor.component
  },
  props: {
    content: {
      type: String,
      default: ''
    }
  },
  emits: ['content'],
  setup(props, {emit}) {
    const editor = ClassicEditor
    const editorData = ref(props.content)
    const editorConfig = ref({
      plugins: [
        //UploadAdapter,
        //GeneralHtmlSupport,
        Paragraph, Markdown, Bold, Italic, Essentials, Link,
        Autoformat,
        Bold,
        Italic,
        BlockQuote,
        Heading,
        Image,
        ImageCaption,
        ImageStyle,
        ImageToolbar,
        ImageUpload,
        List,
        Alignment,
        SourceEditing,
        SimpleUploadAdapter,
        //MediaEmbed,
        Table,
        TableToolbar,
      ],
      toolbar: {
        items: [
          'heading',
          'alignment',
          'bold',
          'italic',
          'link',
          'bulletedList',
          'numberedList',
          'uploadImage',
          'blockQuote',
          'undo',
          'redo',
          'sourceEditing',
          //'mediaEmbed',
          'insertTable',
        ]
      },
      image: {
        toolbar: [
          // 'imageStyle:inline',
          // 'imageStyle:block',
          // 'imageStyle:side',
          // '|',
          'toggleImageCaption',
          'imageTextAlternative'
        ]
      },
      table: {
        contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
      },
      simpleUpload: {
        uploadUrl: '/admin/image/store',
        withCredentials: true,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      },
      // htmlSupport: {
      //   allow: [
      //     {
      //       name: 'div'
      //     },
      //   ],
      //   disallow: [ /* HTML features to disallow */ ]
      // }
    })

    watch(editorData, (newValue) => {
      emit('content', newValue)
    }, {immediate: true})

    return {
      editor,
      editorData,
      editorConfig
    }
  },
}

</script>

<template>
  <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>
