<script setup>
import { useI18n } from 'vue-i18n'
import CKeditor from '../components/CKeditor.vue';
import { computed, ref, watch, onMounted } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useQuery } from '@tanstack/vue-query'
import ModalAlert from '../components/UI/ModalAlert.vue'
import ru from 'element-plus/dist/locale/ru.mjs'
import MetaTagsBlock from "../components/MetaTagsBlock.vue";
import translit from "../utils/translit";
const { t, locale } = useI18n()


const props = defineProps({ article: Object })


const linkPreview = ref(null)

function flattenTree(tree) {
  let result = [];

  function flatten(node) {
    if (!node) return
    result.push(node); // Add the current node to the result array

    if (node.parents) {
      flatten(node.parents); // Recursively flatten the parent
    }
  }

  flatten(tree); // Start flattening from the current node

  return result.reverse(); // Reverse the array to get the correct order
}

const breadcrumbs = computed(() => {
  return flattenTree(props.article?.category_parents_tree);
})

const localeCkeditor = ref(ru)

const { data: settings } = useQuery({
  queryKey: ['settings'],
  queryFn: async () => {
    const res = await fetch('/admin/settings-info', { method: 'GET' });
    return res.json();
  },
})

const { data: categories, } = useQuery({
  queryKey: ['categories'],
  queryFn: async () => {
    const res = await fetch('/api/categories', { method: 'POST' });
    return res.json();
  },
})

const { data: tags } = useQuery({
  queryKey: ['tags'],
  queryFn: async () => {
    const res = await fetch('/api/tags', { method: 'POST' });
    return res.json();
  },
})

const { data: authors } = useQuery({
  queryKey: ['authors'],
  queryFn: async () => {
    const res = await fetch('/api/authors', { method: 'POST' });
    return res.json();
  },
})

const showSuccessModal = ref(false)

const endpointForm = useForm({
  name: props.article?.name,
  category_id: props.article?.category_id,
  tags: props.article?.tags_ids,
  content_markdown: props.article?.content_markdown,
  author_id: props.article?.author_id,
  publish_date: props.article?.publish_date,
  published: props.article?.published ?? true,
  title: props.article?.title,
  description: props.article?.description,
  keywords: props.article?.keywords,
  slug: translit(props.article?.name),
  mainPic: null,
})

watch(() => endpointForm.name, () => {
  endpointForm.slug = translit(endpointForm.name)
})



function sendForm(preview = false) {
  let saveUrl = '/admin/article/store'
  if (preview) {
    saveUrl = '/admin/article/preview'
    // set cookie
    document.cookie = `${usePage().props?.tokenForArticlePreview?.cookieName}=${usePage().props?.tokenForArticlePreview?.cookieValue}; expires=Fri, 31 Dec 9999 23:59:59 GMT"; path=/`;
  }
  if (props.article) {
    saveUrl = `/admin/article/${props.article?.id}/update`
    if (preview) {
      saveUrl = `/admin/article/${props.article?.id}/update-preview`
    }
  }

  endpointForm.post(saveUrl, {
    preserveScroll: true,
    onSuccess: (data) => {
      if (!preview) {
        showSuccessModal.value = true
      }

      if (preview) {
        linkPreview.value = data.props?.flash?.message?.previewUrl
        showSuccessModal.value = true
      }
    }
  })
}

function getContent(content) {
  endpointForm.content_markdown = content
}

function modalClose() {
  window.location.href = '/admin/article'
}

watch(settings, () => {
  locale.value = settings.value.lang
})

const createUrl = (blob) => {
  const res = window.URL.createObjectURL(blob)
  console.log("res", res)
  return res
}

const copyToClipboard = (val) => {
  window.navigator.clipboard.writeText(val)
}

</script>

<template>
  <el-config-provider :locale="localeCkeditor">
    <nav class="article-breadcrumb">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><a href="/">{{ $t("makeArticle.fields.main_page") }}</a></el-breadcrumb-item>
        <el-breadcrumb-item v-for="breadcrumb in breadcrumbs"><a :href="`/admin/category/${breadcrumb.id}/show`">{{
          breadcrumb.name }}</a></el-breadcrumb-item>
        <el-breadcrumb-item v-if="article?.category.id"><a :href="`/admin/category/${article?.category.id}/show`">{{
          article?.category.name }}</a>
        </el-breadcrumb-item>
        <el-breadcrumb-item>{{ endpointForm.name }}</el-breadcrumb-item>
      </el-breadcrumb>
    </nav>

    <MetaTagsBlock v-model:title="endpointForm.title" v-model:description="endpointForm.description"
      v-model:keywords="endpointForm.keywords" :endpointForm="endpointForm" :slug="endpointForm.name"
      :settings="settings" />
    <ModalAlert :visible="showSuccessModal" title="Успешно" @close="modalClose">
      <span v-if="linkPreview">
        Ссылка для превью: <a :href="linkPreview" target="_blank">Просмотреть превью</a>
      </span>
      <span v-else>Запись успешно сохранена </span> 
    </ModalAlert>
    <form>
      <div class="article_name">
        <label for="article_name">{{ $t("makeArticle.fields.title") }}</label>
        <el-input v-model="endpointForm.name" size="large" placeholder="Название статьи" id="article_name" />
        <div v-if="endpointForm.errors.name" class="form_error_text">{{ endpointForm.errors.name }}</div>
      </div>
      <div class="form_small_fields_block">
        <div class="article_category">
          <label for="article_category">{{ $t("makeArticle.fields.category") }}</label>
          <div>
            <el-select v-model="endpointForm.category_id" filterable placeholder="Select" size="large"
              id="article_category">
              <el-option v-for="category in categories" :key="category.id" :label="category.name" :value="category.id" />
            </el-select>
          </div>
          <div v-if="endpointForm.errors.category_id" class="form_error_text">{{
            endpointForm.errors.category_id
          }}
          </div>
        </div>
        <div class="article_tags">
          <label for="article_tags">{{ $t("makeArticle.fields.tags") }}</label>
          <div>
            <el-select v-model="endpointForm.tags" filterable multiple placeholder="Select" size="large"
              id="article_tags">
              <el-option v-for="tag in tags" :key="tag.id" :label="tag.name" :value="tag.id" />
            </el-select>
          </div>
          <div v-if="endpointForm.errors.tags" class="form_error_text">{{ endpointForm.errors.tags }}</div>
        </div>
        <div class="article_author">
          <label for="article_author">{{ $t("makeArticle.fields.author") }}</label>
          <div>
            <el-select v-model="endpointForm.author_id" filterable placeholder="Select" size="large" id="article_author">
              <el-option v-for="author in authors" :key="author.id" :label="author.name" :value="author.id" />
            </el-select>
          </div>
          <div v-if="endpointForm.errors.author_id" class="form_error_text">{{ endpointForm.errors.author_id }}</div>
        </div>
        <div class="article_publish_date">
          <label for="article_publish_date">{{ $t("makeArticle.fields.publish_date") }}</label>
          <div>
            <el-date-picker size="large" v-model="endpointForm.publish_date" type="datetime" placeholder="Выберите дату"
              format="DD/MM/YYYY HH:mm:ss" value-format="YYYY/MM/DD HH:mm:ss" id="article_publish_date" />
          </div>
          <div v-if="endpointForm.errors.publish_date" class="form_error_text">{{
            endpointForm.errors.publish_date
          }}
          </div>
        </div>
      </div>

      <div class="article_published">
        <el-checkbox v-model="endpointForm.published" size="large">{{ $t("makeArticle.fields.published") }}</el-checkbox>
      </div>
      <div class="article_content">
        <CKeditor @content="getContent" :content="endpointForm.content_markdown" />
        <div v-if="endpointForm.errors.content_markdown" class="form_error_text">{{
          endpointForm.errors.content_markdown
        }}
        </div>
      </div>
      <div class="mb-3">
        <label v-if="props.article?.mainPic[0]?.original_url" for="article_publish_date">{{ $t("makeArticle.fields.preview") }}</label>
        <div class="mb-3">
          <img v-if="props.article?.mainPic[0]?.original_url"
            :src="endpointForm.mainPic ? createUrl(endpointForm.mainPic) : props.article?.mainPic[0]?.original_url"
            alt="Главное изображение статьи" class="post_mainPic_thumb">
        </div>
        <div>
          <input type="file" @input="endpointForm.mainPic = $event.target.files[0]" />
          <div v-if="endpointForm.errors.mainPic" class="form_error_text">{{ endpointForm.errors.mainPic }}</div>
        </div>
      </div>
      <el-button @click="sendForm(false)" color="#626aef">{{ $t("makeArticle.fields.save") }}</el-button>
      <el-button @click="sendForm(true)" color="#626aef">{{ $t("makeArticle.fields.preview") }}</el-button>
    </form>

    <section class="format_instruction mt-5">
      <h5>Инструкция</h5>
      <table>
        <tr class="instruction_table_row">
          <td>
            Содержание (h2, h3)
          </td>
          <td>
            <code>
                    +TOC+
                    +TOC+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+TOC+
+TOC+
`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Информационный блок "Полезно" (Зелёный)</td>
          <td>
            <code>
                      +InfoGreen+
                      Заменить здесь
                      +InfoGreen+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+InfoGreen+

          Заменить здесь

+InfoGreen+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Информационный блок "Важно" (Красный)</td>
          <td>
            <code>
                      +InfoRed+
                      Заменить здесь
                      +InfoRed+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+InfoRed+

          Заменить здесь

+InfoRed+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Информационный блок "Интересно" (Синий)</td>
          <td>
            <code>
                      +InfoBlue+
                      Заменить здесьtd
                      +InfoBlue+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+InfoBlue+

          Заменить здесь

+InfoBlue+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Преимущества-недостатки (Голубая галочка)</td>
          <td>
            <code>
                    +ProsConsBlueMark+

                      (+) преимущество 1

                      (+) преимущество 2

                      ||

                      (-) недостаток 1

                      (-) недостаток 2

                    +ProsConsBlueMark+
                  </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+ProsConsBlueMark+

(+) преимущество 1

(+) преимущество 2

||

(-) недостаток 1

(-) недостаток 2

+ProsConsBlueMark+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Преимущества-недостатки (Зелёный плюс на белом фоне)</td>
          <td>
            <code>
                      +ProsConsEmptyPlus+
                        (+) преимущество 1

                        (+) преимущество 2

                        ||

                        (-) недостаток 1

                        (-) недостаток 2
                      +ProsConsEmptyPlus+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+ProsConsEmptyPlus+

(+) преимущество 1

(+) преимущество 2

||

(-) недостаток 1

(-) недостаток 2

+ProsConsEmptyPlus+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Преимущества-недостатки (Белый плюс на зелёном фоне)</td>
          <td>
            <code>
                      +ProsConsGreenPlus+
                      (+) преимущество 1

                      (+) преимущество 2

                      ||

                      (-) недостаток 1

                      (-) недостаток 2
                      +ProsConsGreenPlus+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+ProsConsGreenPlus+

(+) преимущество 1

(+) преимущество 2

||

(-) недостаток 1

(-) недостаток 2

+ProsConsGreenPlus+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Преимущества-недостатки (Зелёная галочка)</td>
          <td>
            <code>
                      +ProsConsGreenMark+
                      (+) преимущество 1

                      (+) преимущество 2

                      ||

                      (-) недостаток 1

                      (-) недостаток 2
                      +ProsConsGreenMark+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+ProsConsGreenMark+

(+) преимущество 1

(+) преимущество 2

||

(-) недостаток 1

(-) недостаток 2

+ProsConsGreenMark+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Преимущества-недостатки (Зелёная галочка-прерывистый)</td>
          <td>
            <code>
                      +ProsConsGreenMarkDashed+
                      (+) преимущество 1

                      (+) преимущество 2

                      ||

                      (-) недостаток 1

                      (-) недостаток 2
                      +ProsConsGreenMarkDashed+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+ProsConsGreenMarkDashed+

(+) преимущество 1

(+) преимущество 2

||

(-) недостаток 1

(-) недостаток 2

+ProsConsGreenMarkDashed+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Совет эксперта</td>
          <td>
            <code>
                      +Advice+
                      Заменить здесь
                      +Advice+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+Advice+

          Заменить здесь

+Advice+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Текстовый блок с рекламным предложением</td>
          <td>
            <code>
                      +TextBlockFirst+
                        --Заголовок заменить--
                         Tекст здесь  заменить
                      +TextBlockFirst+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+TextBlockFirst+

          --Заголовок заменить--
          Tекст здесь  заменить

+TextBlockFirst+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>А вы знали?</td>
          <td>
            <code>
                      +DidYouKnowInArticle+
            
                      +DidYouKnowInArticle+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+DidYouKnowInArticle+

          
+DidYouKnowInArticle+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Цитата 1 (сплошная)</td>
          <td>
            <code>
                      +QuoteSolid+
                          Текст заменить
                          -- Автор заменить--
                      +QuoteSolid+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+QuoteSolid+
              Текст заменить

              -- Автор заменить--
          
+QuoteSolid+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Цитата 1 (прерывистая)</td>
          <td>
            <code>
                      +QuoteDashed+
                          Текст заменить
                          -- Автор заменить--
                      +QuoteDashed+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+QuoteDashed+
              Текст заменить

              -- Автор заменить--
          
+QuoteDashed+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Доп ссылки блок (сплошной)</td>
          <td>
            <code>
                    +LinksAlsoSolid+
                      Здесь текст заменить

                      ||

                      Здесь ссылки как всегда через редактор

                    +LinksAlsoSolid+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+LinksAlsoSolid+
              Здесь текст заменить

              ||

              Здесь ссылки как всегда через редактор

+LinksAlsoSolid+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Доп ссылки блок (прерывистая рамка)</td>
          <td>
            <code>
                    +LinksAlsoDashed+
                      Здесь текст заменить

                      ||

                      Здесь ссылки как всегда через редактор

                    +LinksAlsoDashed+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+LinksAlsoDashed+
              Здесь текст заменить

              ||

              Здесь ссылки как всегда через редактор

+LinksAlsoDashed+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Видео YouTube</td>
          <td>
            <code>
                    +YouTubeComponent+
                      Здесь ссылка из embed заменить
                    +YouTubeComponent+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+YouTubeComponent+
              Здесь ссылка из embed заменить
+YouTubeComponent+`)" />
          </td>
        </tr>
        <tr class="instruction_table_row">
          <td>Галерея картинок</td>
          <td>
            <code>
                    +ArticleGalleryWrapper+
                      Здесь сами картинки заменить
                    +ArticleGalleryWrapper+
                    </code>
          </td>
          <td>
            <font-awesome-icon icon="fa-solid fa-copy" class="icon copy_to_clipboard_icon" @click="copyToClipboard(`+ArticleGalleryWrapper+
              Здесь ссылка из embed заменить
+ArticleGalleryWrapper+`)" />
          </td>
        </tr>
      </table>
    </section>

  </el-config-provider>
</template>

<style scoped>
.article_name,
.article_category,
.article_tags,
.article_content,
.article_author,
.article_publish_date {
  margin-bottom: 20px;
}

.form_error_text {
  color: red;
  font-size: 12px;
}

label {
  margin-bottom: 5px;
}

.article-breadcrumb {
  margin-bottom: 15px;
}

:deep .el-breadcrumb__inner a {
  font-weight: 400;
}

.form_small_fields_block {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr;
  grid-gap: 3%;
}

:deep .el-select--large {
  width: 100%;
}

:deep .el-date-editor--datetime,
:deep .el-input__wrapper {
  width: 100%;
}

.post_mainPic_thumb {
  width: 200px;
}

.copy_to_clipboard_icon {
  cursor: pointer;
}

.instruction_table_row {
  margin-bottom: 10px;
}

.instruction_table_row div:last-child {
  text-align: right;
}

@media screen and (max-width: 1200px) {
  .form_small_fields_block {
    display: grid;
    grid-template-columns: 1fr 1fr;
  }
}

@media screen and (max-width: 768px) {
  .form_small_fields_block {
    display: grid;
    grid-template-columns: 1fr;
  }
}
</style>
