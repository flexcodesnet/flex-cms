<?php

namespace FXC\Base\Traits;

use App\Support\CacheKey;
use Exception;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

trait MigrationTrait
{
    protected $table = null;
    protected $columns = null;
    protected $indexes = null;

    /**
     * @param  Blueprint  $table
     * @param $columns
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function addTableIndexes(Blueprint $table, $columns)
    {
        $table_name = $table->getTable();
        $doctrineSchemaManager = Schema::getConnection()->getDoctrineSchemaManager();
        $listTableIndexes = $doctrineSchemaManager->listTableIndexes($table_name);
        $listTableIndexKeys = array_keys($listTableIndexes);

        foreach ($columns ?? [] as $column_name) {

            // get column type
            $column_type = $table_column_types[$column_name] ?? null;

            // get index name
            $column_index_name = $this->getColumnIndexName($table_name, $column_name);

            // check index exists or not and check if index type is not text
            if ((!in_array($column_index_name, $listTableIndexKeys) and !in_array($column_type, ['text', 'mediumtext', 'longtext']))) {
                try {
                    // create index
                    $table->index($column_name, $column_index_name);
                } catch (Exception $exp) {
                    Log::alert($exp);
                }
            }
        }
    }

    /**
     * @param  Blueprint  $table
     * @param $columns
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function deleteTableIndexes(Blueprint $table, $columns)
    {
        $table_name = $table->getTable();

        $doctrineSchemaManager = Schema::getConnection()->getDoctrineSchemaManager();
        $listTableIndexes = $doctrineSchemaManager->listTableIndexes($table_name);
        $listTableIndexKeys = array_keys($listTableIndexes);


        foreach ($columns ?? [] as $column_name) {
            // get index name
            $column_index_name = $this->getColumnIndexName($table_name, $column_name);

            //check if index exists
            if (in_array($column_index_name, $listTableIndexKeys)) {
                $table->dropIndex($column_index_name);
            }
        }
    }

    /**
     * @param $table_name
     * @param $column_name
     * @return string
     */
    public function getColumnIndexName($table_name, $column_name): string
    {
        return "{$table_name}_{$column_name}_index";
    }

    /**
     * @return mixed
     * @throws \Doctrine\DBAL\Exception
     */
    private function get_schema_cached_column_types($table = null)
    {
        $this->table = $table ?? $this->table;

        // Cache for one day
        $cache_key = CacheKey::schema_table_columns_types($this->table);

        return get_cached_key_data($cache_key, function () {
            return collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableColumns($this->table))
                ->map(function ($cols) {
                    return $cols->getType()->getName();
                })->toArray();
        }, 3600 * 24);
    }

    /**
     * @return mixed
     */
    private function get_schema_cached_tables()
    {
        // Cache for one day
        $cache_key = CacheKey::schema_tables();

        return get_cached_key_data($cache_key, function () {
            return DB::getDoctrineSchemaManager()->listTableNames();
        }, 3600 * 24);
    }
}