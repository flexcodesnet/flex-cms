<?php

namespace FXC\Base\Table;

use FXC\Base\Supports\BaseFields;

class SettingField extends BaseFields
{
    public function __construct()
    {
       parent::__construct(\App\Models\Setting::class);

       $this->addField('title')
            ->setType('text')
            ->setRequired()
            ->get();
    }
}
