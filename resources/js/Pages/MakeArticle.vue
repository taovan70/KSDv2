<script setup>
import CKeditor from '../components/CKeditor.vue';
import {reactive, ref} from 'vue'
import {router, useForm} from '@inertiajs/vue3'
import {useQuery} from '@tanstack/vue-query'
import ModalAlert from '../components/UI/ModalAlert.vue'
import ru from 'element-plus/dist/locale/ru.mjs'

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
  name: null,
  category: null,
  tag: null,
  content: null,
  author: null,
  publish_date: null,
})

function sendForm() {
  endpointForm.post('/admin/article/store', {
    preserveScroll: true,
    onSuccess: () => {
      console.log(345)
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
      <el-input v-model="endpointForm.name" placeholder="Название статьи"/>
      <div v-if="endpointForm.errors.name" class="form_error_text">{{ endpointForm.errors.name }}</div>
    </div>
    <div class="article_category">
      <span>Категория</span>
      <div>
        <el-select v-model="endpointForm.category" filterable placeholder="Select">
          <el-option
              v-for="category in categories"
              :key="category.id"
              :label="category.name"
              :value="category.id"
          />
        </el-select>
      </div>
      <div v-if="endpointForm.errors.category" class="form_error_text">{{ endpointForm.errors.category }}</div>
    </div>
    <div class="article_tags">
      <span>Тэги</span>
      <div>
        <el-select v-model="endpointForm.tag" filterable multiple placeholder="Select">
          <el-option
              v-for="tag in tags"
              :key="tag.id"
              :label="tag.name"
              :value="tag.id"
          />
        </el-select>
      </div>
      <div v-if="endpointForm.errors.tag" class="form_error_text">{{ endpointForm.errors.tag }}</div>
    </div>
    <div class="article_author">
      <span>Автор</span>
      <div>
        <el-select v-model="endpointForm.author" filterable placeholder="Select">
          <el-option
              v-for="author in authors"
              :key="author.id"
              :label="author.name"
              :value="author.id"
          />
        </el-select>
      </div>
      <div v-if="endpointForm.errors.author" class="form_error_text">{{ endpointForm.errors.author }}</div>
    </div>
    <div class="article_publish_date">
      <span>Дата публикации</span>
      <div>
        <el-date-picker
            v-model="endpointForm.publish_date"
            type="datetime"
            placeholder="Выберите дату"
            format="DD/MM/YYYY HH:mm:ss"
            value-format="YYYY/MM/DD HH:mm:ss"
        />
      </div>
      <div v-if="endpointForm.errors.publish_date" class="form_error_text">{{ endpointForm.errors.publish_date }}</div>
    </div>
    <div class="article_content">
      <CKeditor @content="getContent" content="12345"/>
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
