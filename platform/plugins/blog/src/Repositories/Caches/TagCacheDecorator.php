<?php

namespace FXC\Blog\Repositories\Caches;

use FXC\Blog\Repositories\Interfaces\TagInterface;
use FXC\Support\Repositories\Caches\CacheAbstractDecorator;

class TagCacheDecorator extends CacheAbstractDecorator implements TagInterface
{
    /**
     * {@inheritDoc}
     */
    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getPopularTags($limit, array $with = ['slugable'], array $withCount = ['posts'])
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    /**
     * {@inheritDoc}
     */
    public function getAllTags($active = true)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
