<?php

namespace FXC\Base\Table;

use FXC\Base\Models\Permission;
use FXC\Base\Models\Role;
use FXC\Base\Supports\BaseFields;

class RoleField extends BaseFields
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(Role::class);

        $this->addField('title')
            ->setType('text')
            ->setRequired()
            ->get();

        $this->addField('permissions')
            ->setTreeView(Permission::query()->first())
            ->get();

    }
}