<?php

namespace App\CRUD;

use App\Models\Category;
use App\Models\Tag;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

class ArticleCRUD extends CrudPanelFacade
{
    public static function listColumns(): void
    {
        CRUD::column('name')->label(__('table.name'));
        CRUD::addColumn([
            'label' => __('table.author'),
            'type' => 'select',
            'name' => 'author_id',
            'attribute' => 'name',
            'entity' => 'author',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $author_id) => backpack_url("author/{$author_id}/show")
            ]
        ]);
        CRUD::addColumn([
            'label' => __('table.category'),
            'type' => 'select',
            'name' => 'category_id',
            'attribute' => 'name',
            'entity' => 'category',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $category_id) => backpack_url("category/{$category_id}/show")
            ]
        ]);
        CRUD::column('created_at')->label(__('table.created'));
        CRUD::addColumn([
            'name' => 'published',
            'label' => __('table.article_fields.published'),
            'type' => 'boolean'
        ]);
        CRUD::column('slug')->label(__('table.article_fields.slug'));
        CRUD::column('publish_date')->label(__('table.article_fields.publish_date'));
    }

    public static function showColumns(): void
    {
        CRUD::addColumn([
            'name' => 'tags',
            'label' => __('table.tags'),
            'type' => 'relationship',
            'attribute' => 'name',
            'wrapper' => [
                'href' => function ($crud, $column, $article, $tag_id) {
                    return backpack_url("tag/{$tag_id}/show");
                }
            ]
        ]);

        CRUD::addColumn([
            'name' => 'structure',
            'label' => __('table.article_fields.structure'),
            'type' => 'markdown'
        ]);

        CRUD::addColumn([
            'name' => 'content',
            'label' => __('table.article_fields.content'),
            'type' => 'markdown'
        ]);
    }

    public static function tagFilter(CrudPanel $crud): void
    {
        CRUD::addFilter([
            'type'  => 'select2_multiple',
            'label' => __('table.tags'),
            'name' => 'tag_id'
        ], function () {
            return Tag::all()->pluck('name', 'id')->toArray();
        }, function ($values) use ($crud) {
            $crud->addClause(function (Builder $query) use ($values) {
                return $query->whereHas('tags', function (Builder $query) use ($values) {
                    return $query->whereIn('tag_id', json_decode($values));
                });
            });
        });
    }

    public static function categoryFilter(CrudPanel $crud): void
    {
        CRUD::addFilter([
            'type'  => 'select2_multiple',
            'label' => __('table.category'),
            'name' => 'category_id'
        ], function () {
            return Category::all()->pluck('name', 'id')->toArray();
        }, function ($values) use ($crud) {
            $crud->addClause(function (Builder $query) use ($values) {
                return $query->whereHas('category', function (Builder $query) use ($values) {
                    return $query->whereIn('category_id', json_decode($values));
                });
            });
        });
    }

    public static function formFields(bool $isCreate = true): void
    {
        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'name' => 'category_id',
            'label' => __('table.category'),
            'type' => 'select2_from_ajax',
            'entity' => 'category',
            'attribute' => 'name',
            'data_source' => url('api/categories'),
            'minimum_input_length' => 0,
            'method' => 'POST',
            'include_all_form_fields' => true
        ]);
        CRUD::addField([
            'name' => 'author_id',
            'label' => __('table.author'),
            'type' => 'select2_from_ajax',
            'entity' => 'author',
            'attribute' => 'fullName',
            'data_source' => url('api/article_authors'),
            'minimum_input_length' => 0,
            'dependencies' => ['category_id'],
            'method' => 'POST',
            'include_all_form_fields' => true
        ]);
        CRUD::addField([
            'name' => 'tags',
            'label' => __('table.tags'),
            'type' => 'relationship',
            'ajax' => true,
            'attribute' => 'name',
            'data_source' => url('api/tags'),
            'method' => 'POST',
            'include_all_form_fields' => false,
            'inline_create' => [ 'entity' => 'tag' ]
        ]);
        CRUD::addField([
            'name' => 'publish_date',
            'label' => __('table.article_fields.publish_date'),
            'type' => 'datetime_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy HH:mm',
                'language' => 'ru'
            ],
        ]);

        if ($isCreate) {
            // Text editor to paste full article and then parse it on parts.
            CRUD::addField([
                'name' => 'article_text',
                'label' => __('table.article'),
                'type' => 'ckeditor',
                'options'       => [
                    'autoGrow_minHeight'   => 200,
                    'autoGrow_bottomSpace' => 50
                ]
            ]);
        } else {
            // Article divided on parts
            CRUD::addField([
                'name' => 'elements',
                'label' => __('table.article_fields.elements'),
                'type' => 'repeatable',
                'subfields' => [
                    [
                        'name' => 'content',
                        'label' => __('table.article_fields.content'),
                        'type' => 'ckeditor',
                    ]
                ]
            ]);
        }
    }
}
