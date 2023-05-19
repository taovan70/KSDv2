<script setup>
import CKeditor from '../components/CKeditor.vue';
import {ref} from 'vue'
import {useForm} from '@inertiajs/vue3'
import {useQuery} from '@tanstack/vue-query'
import ModalAlert from '../components/UI/ModalAlert.vue'
import ru from 'element-plus/dist/locale/ru.mjs'

const props = defineProps({ article: Object })

const locale = ref(ru)

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
  content: props.article?.content,
  author_id: props.article?.author_id,
  publish_date: props.article?.publish_date,
  published: props.article?.published ?? true,
})

function sendForm() {
  let saveUrl = '/admin/article/store'
  if(props.article) {
    saveUrl = `/admin/article/${props.article?.id}/update`
  }

  endpointForm.post(saveUrl, {
    preserveScroll: true,
    onSuccess: () => {
      showSuccessModal.value = true
    }
  })
}

function getContent(content) {
  endpointForm.content = content
}

function modalClose() {
  window.location.href = '/admin/article'
}

</script>

<template>
  <el-config-provider :locale="locale">
  <ModalAlert :visible="showSuccessModal" title="Успешно" @close="modalClose">
    Запись успешно сохранена
  </ModalAlert>

  <form>
    <div class="article_name">
      <span>Название</span>
      <el-input v-model="endpointForm.name" size="large" placeholder="Название статьи"/>
      <div v-if="endpointForm.errors.name" class="form_error_text">{{ endpointForm.errors.name }}</div>
    </div>
    <div class="article_category">
      <span>Категория</span>
      <div>
        <el-select v-model="endpointForm.category_id" filterable placeholder="Select" size="large">
          <el-option
              v-for="category in categories"
              :key="category.id"
              :label="category.name"
              :value="category.id"
          />
        </el-select>
      </div>
      <div v-if="endpointForm.errors.category_id" class="form_error_text">{{ endpointForm.errors.category_id }}</div>
    </div>
    <div class="article_tags">
      <span>Тэги</span>
      <div>
        <el-select v-model="endpointForm.tags" filterable multiple placeholder="Select" size="large">
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
      <span>Автор</span>
      <div>
        <el-select v-model="endpointForm.author_id" filterable placeholder="Select" size="large">
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
      <span>Дата публикации</span>
      <div>
        <el-date-picker
            size="large"
            v-model="endpointForm.publish_date"
            type="datetime"
            placeholder="Выберите дату"
            format="DD/MM/YYYY HH:mm:ss"
            value-format="YYYY/MM/DD HH:mm:ss"
        />
      </div>
      <div v-if="endpointForm.errors.publish_date" class="form_error_text">{{ endpointForm.errors.publish_date }}</div>
    </div>
    <div class="article_published">
      <el-checkbox v-model="endpointForm.published" size="large">Опубликована</el-checkbox>
    </div>
    <div class="article_content">
      <CKeditor @content="getContent" :content="endpointForm.content"/>
      <div v-if="endpointForm.errors.content" class="form_error_text">{{ endpointForm.errors.content }}</div>
    </div>
    <el-button @click="sendForm" color="#626aef">Отправить</el-button>
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
</style>
