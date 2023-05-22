<script setup>
import {watch, ref, computed} from "vue";
import translit from "../utils/translit";
import { useI18n } from 'vue-i18n'

const props = defineProps({
  title: String,
  description: String,
  keywords: String,
  endpointForm: Object,
  slug: String,
  settings: Object
})

const titleLength = ref(70)
const descriptionLength = ref(160)
const {t} = useI18n({})


function truncate(source, size) {
  if(!source || !size) return ''
  return source.length > size ? source.slice(0, size - 1) + "…" : source;
}

const truncateDescription = computed(() => truncate(props.description, descriptionLength.value))
const truncateTitle = computed(() => truncate(props.title, titleLength.value))


const slugTranslit = computed(() => translit(props.slug))

const titleRemainSymbols = computed(() => {
  if (!props.title) return titleLength
  return titleLength.value - title.value.length
})

const descriptionRemainSymbols = computed(() => {
  if (!props.description) return descriptionLength
  return descriptionLength.value - description.value.length
})

const emit = defineEmits(['update:title', 'update:description', 'update:keywords'])

const title = ref(props.title)
const description = ref(props.description)
const keywords = ref(props.keywords)

const metaViewDevice = ref('desktop')
const metaViewSearchEngine = ref('google')


watch(title, () => {
  emit('update:title', title.value)
})

watch(description, () => {
  emit('update:description', description.value)
})

watch(keywords, () => {
  emit('update:keywords', keywords.value)
})

const setMetaViewDevice = (device) => {
  metaViewDevice.value = device
}

const setMetaViewSearchEngine = (searchEngine) => {
  metaViewSearchEngine.value = searchEngine
}

</script>

<template>
  <section class="meta-tags-block">
    <div class="meta-tags-block__left-column">
      <div class="keywords_row">
        <label for="meta_keywords">Keywords</label>
        <el-input v-model="keywords" id="meta_keywords"/>
        <div v-if="endpointForm?.errors.keywords" class="form_error_text">{{ endpointForm.errors.keywords }}</div>
      </div>
      <div class="title_row">
        <label for="meta_title">Title</label>
        <el-input v-model="title" id="meta_title"/>
        <div class="field-remain-symbols">{{ t("makeArticle.fields.symbols_remains") }}: <span
            class="field-remain-symbols__number"
            :class="{'field-remain-symbols__number_error': titleRemainSymbols < 0}">{{ titleRemainSymbols }}</span>
        </div>
        <div v-if="endpointForm?.errors.title" class="form_error_text">{{ endpointForm.errors.title }}</div>
      </div>
      <div class="description_row">
        <label for="meta_description">Description</label>
        <el-input v-model="description" type="textarea" :rows="4" id="meta_description"/>
        <div class="field-remain-symbols">{{ t("makeArticle.fields.symbols_remains") }}: <span
            class="field-remain-symbols__number"
            :class="{'field-remain-symbols__number_error': descriptionRemainSymbols < 0}">{{
            descriptionRemainSymbols
          }}</span></div>
        <div v-if="endpointForm?.errors.description" class="form_error_text">{{ endpointForm.errors.description }}</div>
      </div>
    </div>
    <div class="meta-tags-block__right-column">
      <div class="meta-result-buttons">
        <div class="buttons__device"
        >
          <div class="buttons__device__phone" @click="setMetaViewDevice('phone')"
               :class="{'device-active-button': metaViewDevice === 'phone' }">
            <i class="la la-mobile"></i>
          </div>
          <div class="buttons__device__desktop" @click="setMetaViewDevice('desktop')"
               :class="{'device-active-button': metaViewDevice === 'desktop' }">
            <i class="la la-desktop"></i>
          </div>
        </div>
        <div>
          <el-button plain :type="metaViewSearchEngine === 'google'? 'primary' : ''"
                     @click="setMetaViewSearchEngine('google')">Google
          </el-button>
          <el-button plain :type="metaViewSearchEngine === 'yandex'? 'primary' : ''"
                     @click="setMetaViewSearchEngine('yandex')">Yandex
          </el-button>
        </div>
      </div>
      <div class="meta-result-field">
        <div class="meta-result-field__icon" v-if="metaViewDevice === 'phone' && metaViewSearchEngine === 'yandex'">
          <i class="la la-globe"></i>
        </div>

        <div class="search_item_yandex" v-if="metaViewDevice === 'phone' && metaViewSearchEngine === 'yandex'">
          <div class="search_item__title">{{ truncateTitle }}</div>
          <div class="search_item__url">{{ settings?.site_url }} > {{ slugTranslit }}</div>
          <div class="search_item__description"> {{ truncateDescription }}</div>
        </div>

        <div class="search_item_yandex" v-if="metaViewDevice === 'desktop' && metaViewSearchEngine === 'yandex'">
          <div class="search_item__title">{{ truncateTitle }}</div>
          <div class="search_item__url">{{ settings?.site_url }} > {{ slugTranslit }}</div>
          <div class="search_item__description"> {{ truncateDescription }}</div>
        </div>

        <div class="search_item_google" v-if="metaViewDevice === 'phone' && metaViewSearchEngine === 'google'">
          <div class="search_item__url">
            <i class="la la-globe"></i><span class="search_item__url_link">{{ settings?.site_url }} > {{ slugTranslit }}</span>
          </div>
          <div class="search_item__title">{{ truncateTitle }}</div>
          <div class="search_item__description"> {{ truncateDescription }}</div>
        </div>

        <div class="search_item_google" v-if="metaViewDevice === 'desktop' && metaViewSearchEngine === 'google'">
          <div class="search_item__url">
            <i class="la la-globe"></i> <span class="search_item__url_link">{{ settings?.site_url }} > {{ slugTranslit }}</span>
          </div>
          <div class="search_item__title">{{ truncateTitle }}</div>
          <div class="search_item__description"> {{ truncateDescription }}</div>
        </div>

      </div>
    </div>

  </section>
</template>

<style scoped>
.meta-tags-block {
  display: flex;
  gap: 15px;
}

.meta-tags-block__left-column {
  flex-basis: 100%;
}

.meta-tags-block__right-column {
  flex-basis: 100%;
}

.description_row, .keywords_row, .title_row {
  margin-bottom: 20px;
}

.meta-tags-block {
  padding: 20px;
  box-shadow: 0 0 0 1px var(--el-input-border-color, var(--el-border-color)) inset;
  border-radius: var(--el-input-border-radius, var(--el-border-radius-base));
  border: 1px solid #dcdfe6;
  height: 80%;
  margin-top: 10px;
  margin-bottom: 30px;
}

.meta-result-field {
  display: flex;
  box-shadow: 0 0 0 1px var(--el-input-border-color, var(--el-border-color)) inset;
  border-radius: var(--el-input-border-radius, var(--el-border-radius-base));
  border: 1px solid rgb(96, 98, 102);
  height: 80%;
  margin-top: 10px;
  background-color: #fff;
  padding: 10px;
}

.form_error_text {
  color: red;
  font-size: 12px;
}

label {
  margin-bottom: 5px;
}

.meta-result-buttons {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.buttons__device {
  display: flex;
  gap: 10px;
}

.buttons__device__phone,
.buttons__device__desktop {
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  border: 1px solid #dcdfe6;
  border-radius: var(--el-input-border-radius, var(--el-border-radius-base));
}

.device-active-button {
  border-color: #0B90C4;
}

.field-remain-symbols {
  font-size: 14px;
}

.field-remain-symbols__number {
  font-weight: bold;
  color: #00a65a;
}

.field-remain-symbols__number_error {
  color: red;
}

.search_item__url {
  display: flex;
  align-items: center;
}

.search_item__url_link {
  padding-left: 5px;
}

.search_item_yandex,
.search_item_google {
  overflow: hidden;
  margin: 8px 0 0;
  padding: 16px 15px;
  border-radius: 16px;
  border: 1px solid #dcdfe6;
  background: #fff;
  font-size: 15px;
  line-height: 20px;
  width: 100%;
  height: fit-content;
}

.search_item_yandex .search_item__title {
  color: navy;
  outline: 0;
  font-size: 20px;
  line-height: 20px;
}

.search_item_yandex .search_item__url {
  display: inline-block;
  overflow: hidden;
  max-width: 95%;
  vertical-align: top;
  text-overflow: ellipsis;
  white-space: nowrap;
  color: #006000;
}

.search_item_yandex .search_item__description {
  font-size: 16px;
  line-height: 21px;
  word-break: break-word;
  color: #3c4148;
}

.meta-result-field__icon {
  margin-top: 10px;
  margin-right: 10px;
}

.search_item_google .search_item__title {
  color: #1a0dab;
  font-size: 20px;
  font-weight: 400;
  display: inline-block;
  line-height: 1.3;
  margin-bottom: 3px;
}

.search_item_google .search_item__description {
  color: #4d5156;
  line-height: 1.58;
  text-align: left;
  font-size: 14px;
}


@media screen and (max-width: 768px) {
  .meta-tags-block {
    flex-direction: column;
  }

  .meta-result-field {
    min-height: 300px;
  }

}
</style>



