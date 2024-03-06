<?php

return [
    'common' => [
        'required' => 'This field is required.',
        'max' => 'Maximum allowed number of characters:',
        'min' => 'Minimum allowed number of characters:',
        'unique' => 'This value must be unique.',
        'email' => 'Please provide a valid email address.',
        'image' => 'The field must be an image.',
        'invalid' => 'Invalid value.',
    ],
    'articles' => [
        'image' => 'Please provide a link to the image, you cannot embed an image in the editor.'
    ],
    'category' => [
        'not_empty' => 'You cannot delete a category if it or its subcategories contain entries.'
    ],
    'author' => [
        'not_empty' => 'You cannot delete an author if it has articles.'
    ],
];
