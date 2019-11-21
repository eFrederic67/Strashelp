<?php


namespace App\Model;

class BlogManager extends AbstractManager
{
    const TABLE = 'article';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
}
