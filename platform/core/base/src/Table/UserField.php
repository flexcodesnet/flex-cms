<?php

namespace FXC\Base\Table;

use FXC\Base\Models\Role;
use FXC\Base\Models\User;
use FXC\Base\Supports\BaseFields;

class UserField extends BaseFields
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct(User::class);

        $this->addField('name')
            ->setType('text')
            ->setRequired()
            ->get();

        $this->addField('username')
            ->setType('text')
            ->setDisabled()
            ->get();

        $this->addField('email')
            ->setType('email')
            ->setRequired()
            ->get();

        $this->addField('password')
            ->setType('password')
            ->ignoreInTable()
            ->get();

        $this->addField('password_confirmation')
            ->setType('password')
            ->ignoreInTable()
            ->get();

        $this->addField('role_id')
            ->setNested('role', Role::query())
            ->setRequired()
            ->get();

    }
}