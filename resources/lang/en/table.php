<?php

return [
    'name' => 'Name',
    'created' => 'Created',
    'add' => 'Add',
    'yes' => 'Yes',
    'no' => 'No',
    'category' => 'Category',
    'parent_category' => 'Parent category',
    'categories' => 'Categories',
    'subject' => 'Subject',
    'subjects' => 'Subjects',
    'section' => 'Section',
    'sections' => 'Sections',
    'sub_section' => 'Subsection',
    'sub_sections' => 'Subsections',
    'author' => 'Author',
    'authors' => 'Authors',
    'article' => 'Article',
    'user' => 'User',
    'users' => 'Users',
    'articles' => 'Articles',
    'articles_accusative' => 'Articles',
    'tags' => 'Tags',
    "user_logging" => "Logging",
    'author_fields' => [
        'full_name' => 'Full name',
        'age' => 'Age',
        'gender' => 'Gender',
        'name' => 'Name',
        'surname' => 'Surname',
        'middle_name' => 'Middle name',
        \App\Models\Author::MALE => 'Male',
        \App\Models\Author::FEMALE => 'Female',
        'biography' => 'Biography',
        'address' => 'Address',
        'personal_site' => 'Personal site',
        'social_networks' => 'Social networks',
        'social_network' => 'Social network',
        'account' => 'Account',
        'photo' => 'Photo',
        'description' => 'Description',
    ],

    'user_fields' =>[
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Password confirmation',
        'role' => 'Role',
        'change_lang' => 'Change language',
        'lang' => 'Language',
        'information_changed' => 'Information changed',
    ],

    'article_fields' => [
        'content' => 'Content',
        'structure' => 'Structure',
        'elements' => 'Elements',
        'html_tag' => 'Html tag',
        'publish_date' => 'Publish date',
        'published' => 'Published'
    ],

    'adv_block_fields' => [
        'content' => 'Content',
        'active' => 'Active',
        'device_type' => 'Device type',
        'color_type' => 'Color type',
        'description' => 'Description',
        'page' => 'Page',
        'created' => 'Created',
        'comment' => 'Comment',
        'add_page' => 'Add page',
        'add_block' => 'Add block',
    ]
];
