<?php

namespace FXC\Blog\Models;

use FXC\Base\Models\BaseModel;

class CategoryTranslation extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'lang_code',
        'categories_id',
        'name',
        'description',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
