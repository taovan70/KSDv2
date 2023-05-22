<script setup>
import { useI18n } from 'vue-i18n'
import CKeditor from '../components/CKeditor.vue';
import {computed, ref, watch} from 'vue'
import {useForm} from '@inertiajs/vue3'
import {useQuery} from '@tanstack/vue-query'
import ModalAlert from '../components/UI/ModalAlert.vue'
import ru from 'element-plus/dist/locale/ru.mjs'
import MetaTagsBlock from "../components/MetaTagsBlock.vue";
import translit from "../utils/translit";
const { t, locale } = useI18n()


const props = defineProps({article: Object})


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

const {data: settings} = useQuery({
  queryKey: ['settings'],
  queryFn: async () => {
    const res = await fetch('/admin/settings-info', {method: 'GET'});
    return res.json();
  },
})

const {data: categories,} = useQuery({
  queryKey: ['categories'],
  queryFn: async () => {
    const res = await fetch('/api/categories', {method: 'POST'});
    return res.json();
  },
})

const {data: tags} = useQuery({
  queryKey: ['tags'],
  queryFn: async () => {
    const res = await fetch('/api/tags', {method: 'POST'});
    return res.json();
  },
})

const {data: authors} = useQuery({
  queryKey: ['authors'],
  queryFn: async () => {
    const res = await fetch('/api/authors', {method: 'POST'});
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
  slug: translit(props.article?.name)
})

watch(() => endpointForm.name, () => {
  endpointForm.slug = translit(endpointForm.name)
})



function sendForm() {
  let saveUrl = '/admin/article/store'
  if (props.article) {
    saveUrl = `/admin/article/${props.article?.id}/update`
  }

  endpointForm.post(saveUrl, {
    //preserveScroll: true,
    onSuccess: () => {
      showSuccessModal.value = true
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

</script>

<template>
  <el-config-provider :locale="localeCkeditor">
    <nav class="article-breadcrumb">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item><a href="/">{{ $t("makeArticle.fields.main_page") }}</a></el-breadcrumb-item>
        <el-breadcrumb-item v-for="breadcrumb in breadcrumbs"><a
            :href="`/admin/category/${breadcrumb.id}/show`">{{ breadcrumb.name }}</a></el-breadcrumb-item>
        <el-breadcrumb-item v-if="article?.category.id"><a
            :href="`/admin/category/${article?.category.id}/show`">{{ article?.category.name }}</a>
        </el-breadcrumb-item>
        <el-breadcrumb-item>{{ endpointForm.name }}</el-breadcrumb-item>
      </el-breadcrumb>
    </nav>

    <MetaTagsBlock
        v-model:title="endpointForm.title"
        v-model:description="endpointForm.description"
        v-model:keywords="endpointForm.keywords"
        :endpointForm="endpointForm"
        :slug="endpointForm.name"
        :settings="settings"
    />
    <ModalAlert :visible="showSuccessModal" title="Успешно" @close="modalClose">
      Запись успешно сохранена
    </ModalAlert>
    <form>
      <div class="article_name">
        <label for="article_name">{{ $t("makeArticle.fields.title") }}</label>
        <el-input v-model="endpointForm.name" size="large" placeholder="Название статьи" id="article_name"/>
        <div v-if="endpointForm.errors.name" class="form_error_text">{{ endpointForm.errors.name }}</div>
      </div>
      <div class="form_small_fields_block">
        <div class="article_category">
          <label for="article_category">{{ $t("makeArticle.fields.category") }}</label>
          <div>
            <el-select v-model="endpointForm.category_id" filterable placeholder="Select" size="large"
                       id="article_category">
              <el-option
                  v-for="category in categories"
                  :key="category.id"
                  :label="category.name"
                  :value="category.id"
              />
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
              <el-option
                  v-for="tag in tags"
                  :key="tag.id"
                  :label="tag.name"
                  :value="tag.id"
              />
            </el-select>
          </div>
          <div v-if="endpointForm.errors.tags" class="form_error_text">{{ endpointForm.errors.tags }}</div>
        </div>
        <div class="article_author">
          <label for="article_author">{{ $t("makeArticle.fields.author") }}</label>
          <div>
            <el-select v-model="endpointForm.author_id" filterable placeholder="Select" size="large"
                       id="article_author">
              <el-option
                  v-for="author in authors"
                  :key="author.id"
                  :label="author.name"
                  :value="author.id"
              />
            </el-select>
          </div>
          <div v-if="endpointForm.errors.author_id" class="form_error_text">{{ endpointForm.errors.author_id }}</div>
        </div>
        <div class="article_publish_date">
          <label for="article_publish_date">{{ $t("makeArticle.fields.publish_date") }}</label>
          <div>
            <el-date-picker
                size="large"
                v-model="endpointForm.publish_date"
                type="datetime"
                placeholder="Выберите дату"
                format="DD/MM/YYYY HH:mm:ss"
                value-format="YYYY/MM/DD HH:mm:ss"
                id="article_publish_date"
            />
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
        <CKeditor @content="getContent" :content="endpointForm.content_markdown"/>
        <div v-if="endpointForm.errors.content_markdown" class="form_error_text">{{
            endpointForm.errors.content_markdown
          }}
        </div>
      </div>
      <el-button @click="sendForm" color="#626aef">{{ $t("makeArticle.fields.save") }}</el-button>
    </form>
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
  grid-template-columns: 1fr 1fr;
  grid-gap: 3%;
}

:deep .el-select--large {
  width: 100%;
}

:deep .el-date-editor--datetime, :deep .el-input__wrapper {
  width: 100%;
}

@media screen and (max-width: 768px) {
  .form_small_fields_block {
    display: grid;
    grid-template-columns: 1fr;
  }
}

</style>
