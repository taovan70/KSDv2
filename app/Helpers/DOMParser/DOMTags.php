<?php

namespace App\Helpers\DOMParser;

class DOMTags
{
    const HTML = 'html';
    const BODY = 'body';
    const HEADER_H1 = 'h1';
    const HEADER_H2 = 'h2';
    const HEADER_H3 = 'h3';
    const TEXT_P = 'p';
    const LIST_UL = 'ul';
    const LIST_OL = 'ol';
    const LIST_LI = 'li';
    const TABLE = 'table';
    const LINK = 'a';
    const IMG = 'img';

    const PRESERVED_TAGS = [
        self::HEADER_H1 => self::HEADER_H1,
        self::HEADER_H2 => self::HEADER_H2,
        self::HEADER_H3 => self::HEADER_H3,
        self::TEXT_P => self::TEXT_P,
        self::LIST_UL => self::LIST_UL,
        self::LIST_OL => self::LIST_OL,
        self::TABLE => self::TABLE,
        self::LINK => self::LINK,
        self::IMG => self::IMG
    ];

    const HEADERS = [
        self::HEADER_H1,
        self::HEADER_H2,
        self::HEADER_H3,
    ];
}