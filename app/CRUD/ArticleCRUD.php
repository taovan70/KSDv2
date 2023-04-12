<?php

namespace App\CRUD;

use App\Models\SubSection;
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
            'label' => __('table.sub_section'),
            'type' => 'select',
            'name' => 'sub_section_id',
            'attribute' => 'name',
            'entity' => 'subSection',
            'wrapper' => [
                'href' => fn($crud, $column, $article, $sub_section_id) => backpack_url("sub-section/{$sub_section_id}/show")
            ]
        ]);
        CRUD::column('created_at')->label(__('table.created'));
        CRUD::addColumn([
            'name' => 'published',
            'label' => __('table.article_fields.published'),
            'type' => 'boolean'
        ]);
        CRUD::addColumn([
            'name' => 'publish_date',
            'label' => __('table.article_fields.publish_date'),
            'type' => 'date'
        ]);
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

    public static function subSectionFilter(CrudPanel $crud): void
    {
        CRUD::addFilter([
            'type'  => 'select2_multiple',
            'label' => __('table.sub_sections'),
            'name' => 'sub_section_id'
        ], function () {
            return SubSection::all()->pluck('name', 'id')->toArray();
        }, function ($values) use ($crud) {
            $crud->addClause(function (Builder $query) use ($values) {
                return $query->whereHas('subSection', function (Builder $query) use ($values) {
                    return $query->whereIn('sub_section_id', json_decode($values));
                });
            });
        });
    }

    public static function formFields(bool $isCreate = true): void
    {
        CRUD::field('name')->label(__('table.name'));
        CRUD::addField([
            'name' => 'sub_section_id',
            'label' => __('table.sub_section'),
            'type' => 'select2_grouped',
            'entity' => 'subSection',
            'attribute' => 'name',
            'group_by'  => 'section',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'subSections'
        ]);
        CRUD::addField([
            'name' => 'author_id',
            'label' => __('table.author'),
            'type' => 'select2_from_ajax',
            'entity' => 'author',
            'attribute' => 'fullName',
            'data_source' => url('api/article_authors'),
            'minimum_input_length' => 0,
            'dependencies' => ['sub_section_id'],
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
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format'   => 'dd-mm-yyyy',
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