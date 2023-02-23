<?php

namespace FXC\Base\Table;

use FXC\Base\Models\Permission;
use FXC\Base\Supports\BaseFields;

class PermissionField extends BaseFields
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(Permission::class);
        $this->addField('title')
            ->setType('text')
            ->setRequired()
            ->get();

//        $this->addField('slug')
//            ->setType('text')
//            ->setRequired(false)
//            ->setDisabled(true)
//            ->get();

        $this->addField('children')
            ->setSubTitleFields(['title'])
            ->setShowInTable(false)
            ->get();
    }
}