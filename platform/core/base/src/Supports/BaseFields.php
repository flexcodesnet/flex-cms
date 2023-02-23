<?php

namespace FXC\Base\Supports;

use Doctrine\DBAL\Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class BaseFields
{

    public const TRANSLATION_SECTION = 'translations';
    public const GENERAL_SECTION = 'general';
    public const SEO_META_SECTION = 'seo_meta';
    public const EDITOR_SECTION = 'editor';

    /**
     * @var
     */
    private $className;

    /**
     * @var
     */
    private $tableName;

    /**
     * @var
     */
    private $fields;

    /**
     * @var
     */
    private $hidden_fields;

    /**
     * @var Repository|Application|mixed
     */
    private $properties = [];

    /**
     * @var string
     */
    private $name;

    private $thName;
    /**
     * @var string
     */
    private $slug;
    /**
     * @var string
     */
    private $type;

    /**
     * @var string[]
     */
    private $types = [];

    private $phoneColumns = [];
    private $imageColumns = [];
    private $integerColumns = [];
    private $columnsType;
    private $schemaTables;

    /**
     * @var
     */
    private $subTitleFields;
    /**
     * @var
     */
    private $treeview;
    /**
     * @var
     */
    private $nested;

    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $min;
    /**
     * @var string
     */
    private $max;

    /**
     * @var
     */
    private $validations;

    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $table;
    /**
     * @var string
     */
    private $class;
    /**
     * @var bool
     */
    private $required;
    /**
     * @var bool
     */
    private $translatable;
    /**
     * @var bool
     */
    private $disabled;
    /**
     * @var bool
     */
    private $searchable;
    /**
     * @var bool
     */
    private $showInTable;

    /**
     * @var
     */
    private $section;

    /**
     * @var bool
     */
    private $showInForm;

    /**
     * @var bool
     */
    private $sortable;

    public function __construct($className)
    {
        $this->className = $className;
        $this->tableName = app($className)->getTable();
        $this->phoneColumns = array_merge($this->phoneColumns, config('base_fields.phone_columns', []));
        $this->imageColumns = array_merge($this->imageColumns, config('base_fields.image_columns', []));
        $this->integerColumns = array_merge($this->integerColumns, config('base_fields.integer_columns', []));

        $this->types = config('base_fields.types', []);
        $this->hidden_fields = config('base_fields.hidden', []);
        $this->properties = config('base_fields.properties', []);

        $this->schemaTables = $this->getSchemaCachedTables();
        $this->columnsType = $this->getSchemaCachedColumnTypes();
    }

    /**
     * @param $name
     * @return \App\Support\BaseFields
     */
    public function addField($name): BaseFields
    {
        $this->setClass($this->className);
        $this->setTable($this->tableName);
        $this->setName($name);
        $this->setSlug($name);
        $this->setThName($name);
        $this->setType($this->columnsType[$this->slug] ?? 'custom');
        $this->setShowInTable();
        $this->setShowInForm();
        $this->setValidations();
        $this->setSection(self::GENERAL_SECTION);
        $translatable = array_merge(app($this->className)->translatable ?? [], app($this->className)->transFields ?? []);
        if (in_array($this->slug, $translatable)) {
            $this->setTranslatable();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function get(): BaseFields
    {
        $this->setValidations();
        $field = [];
        foreach ($this->properties as $property) {
            $field[$property] = $this->{$property};
        }
        $field = (object) $field;
        $this->fields = $this->getFields()->add($field);
        $this->clear();
        return $this;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getFields(): \Illuminate\Support\Collection
    {
        return collect($this->fields);
    }

    /**
     * @return array
     */
    public function getAvailableFields(): array
    {
        return $this->getFields()
            ->where('showInTable', true)
            ->toArray();
    }

    /**
     * @return array
     */
    public function getTranslatableFields(): array
    {
        return collect($this->getFormFields())
            ->where('translatable', true)
            ->toArray();
    }

    /**
     * @return array
     */
    public function getNotTranslatableFields(): array
    {
        return collect($this->getFormFields())
            ->where('translatable', false)
            ->toArray();
    }

    /**
     * @return array
     */
    public function getFormFields(): array
    {
        return $this->getFields()
            ->whereNotIn('name', $this->hidden_fields)
            ->where('showInForm', true)
            ->toArray();
    }

    /**
     * @return $this
     */
    public function clear(): BaseFields
    {
        foreach ($this->properties as $property) {
            if (empty($model->{$property})) {
                $this->{$property} = null;
            }
        }

        return $this;
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function setName(string $name): BaseFields
    {
        $this->name = \Illuminate\Support\Str::snake("{$this->table}.{$name}");
        return $this;
    }

    /**
     * @param  string  $slug
     * @return $this
     */
    public function setSlug(string $slug): BaseFields
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param $relation
     * @param  null  $data
     * @param  null  $query
     * @param  null  $className
     * @return $this
     */
    public function setNested($relation, $query = null, $className = null, $data = null): BaseFields
    {
        $this->type = 'nested';
        $this->setNestedObj([
            'relation'     => $relation,
            'relation_key' => "{$relation}_id",
            'data'         => $data,
            'query'        => $query ?? (isset($className) ? $className::query() : null),
        ]);
        return $this;
    }

    /**
     * @return $this
     */
    public function setLocaleList(): BaseFields
    {
        $this->type = 'locale';
        $this->setNestedObj([
            'data' => get_list_option('locales'),
        ]);
        return $this;
    }

    /**
     * @param $relation
     * @param $listOptionSlug
     * @param  null  $query
     * @param  null  $className
     * @return $this
     */
    public function setNestedList($relation, $listOptionSlug, $query = null, $className = null): BaseFields
    {
        $this->setNested($relation, null, null, get_list_option($listOptionSlug));

        return $this;
    }

    /**
     * @param $relation
     * @param  null  $query
     * @param  null  $className
     * @return $this
     */
    public function setMultiNested($relation, $query = null, $className = null): BaseFields
    {
        $this->type = 'multi_select';
        $this->setNestedObj([
            'relation'     => $relation,
            'relation_key' => "{$relation}_id",
            'query'        => $query ?? $className::query(),
        ]);
        return $this;
    }

    /**
     * @param  array  $nested
     * @return void
     */
    public function setNestedObj(array $nested)
    {
        $this->nested = (object) $nested;
    }

    /**
     * @param  string  $name
     * @return $this
     */
    public function setThName(string $name): BaseFields
    {
        $this->thName = "messages.fields.{$name}";
        return $this;
    }

    /**
     * @param  bool  $show
     * @return $this
     */
    public function setShowInTable(bool $show = true): BaseFields
    {
        $this->showInTable = $show;
        return $this;
    }

    /**
     * @param  bool  $show
     * @return $this
     */
    public function ignoreInTable(bool $show = false): BaseFields
    {
        $this->showInTable = $show;
        return $this;
    }

    /**
     * @param $section
     * @return $this
     */
    public function setSection($section = null): BaseFields
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @param  string  $type
     * @return $this
     */
    public function setType(string $type): BaseFields
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param  string  $title
     * @return $this
     */
    public function setTitle(string $title): BaseFields
    {
        $this->title = $title;
        $this->setThName($title);
        return $this;
    }

    /**
     * @param  bool  $showInForm
     * @return $this
     */
    public function setShowInForm(bool $showInForm = true): BaseFields
    {
        $this->showInForm = $showInForm;
        return $this;
    }

    /**
     * @param $validations
     * @return $this|string[]
     */
    function setValidations($validations = null)
    {
        if ($validations) {
            $this->validations = $validations;

            return $this;
        }

        $validationArray = ['nullable'];
        $max = $max ?? $this->getValidationMax();

        if ($max) {
            $validationArray[] = "max:{$max}";
        }

        if (in_array($this->type, $this->phoneColumns)) {
            $validationArray[] = "max:20";
            $validationArray[] = "regex:/^([0-9\s\+\(\)]*)$/";
        }

        if (in_array($this->type, $this->integerColumns)) {
            $validationArray[] = "integer";
        }

        if (in_array($this->type, ['email', 'integer', 'numeric', 'image'])) {
            $validationArray[] = $this->type;
        }
        $this->validations = $validationArray;

    }

    /**
     * @return int|null
     */
    private function getValidationMax(): ?int
    {
        switch ($this->type) {
            case 'string':
            case 'text':
                $maxByType = 255;
                break;
            case 'textarea':
                $maxByType = 40000;
                break;
            case 'mediumtext':
                $maxByType = 80000;
                break;
            case 'longtext':
                $maxByType = 100000;
                break;
            case 'image':
                $maxByType = 2048;
                break;
            default:
                $maxByType = null;
                break;
        }

        return $maxByType;
    }

    /**
     * @param  bool  $showInForm
     * @return $this
     */
    public function ignoreInForm(bool $showInForm = false): BaseFields
    {
        $this->showInForm = $showInForm;
        return $this;
    }

    /**
     * @param  string  $class
     * @return $this
     */
    public function setClass(string $class): BaseFields
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @param  string  $table
     * @return $this
     */
    public function setTable(string $table): BaseFields
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @param  bool  $required
     * @return $this
     */
    public function setRequired(bool $required = true): BaseFields
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param  bool  $translatable
     * @return $this
     */
    public function setTranslatable(bool $translatable = true): BaseFields
    {
        $this->translatable = $translatable;
        $this->setSection(self::TRANSLATION_SECTION);

        return $this;
    }

    /**
     * @param  bool  $disabled
     * @return $this
     */
    public function setDisabled(bool $disabled = true): BaseFields
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param  bool  $searchable
     * @return $this
     */
    public function setSearchable(bool $searchable): BaseFields
    {
        $this->searchable = $searchable;
        return $this;
    }

    /**
     * @param  bool  $sortable
     * @return $this
     */
    public function setSortable(bool $sortable): BaseFields
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * @param $fields
     * @return $this
     */
    public function setSubTitleFields($fields): BaseFields
    {
        $this->type = 'children';
        $this->subTitleFields = $fields;
        return $this;
    }

    /**
     * @param $model
     * @return $this
     */
    public function setTreeView($model): BaseFields
    {
        $this->type = 'treeview';
        $this->treeview['model'] = $model;
        $this->ignoreInTable();
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param  null  $id
     * @param  null  $field_prefix
     * @param  null  $available_columns
     *
     * @return array
     */
    public function getValidationRules($id = null, $field_prefix = null, $available_columns = null)
    {
        $available_columns = $available_columns ?? $this->getFormFields();

        $validation = [];

        foreach ($available_columns as $column) {
            $attr = $column->slug;

            if ($id or $field_prefix) {
                $this->ignoreRowId($column->validations, $id);
            }

            // Fix rules for fields which with prefix like (deal_title, account_owner_id)
            $field_attr = $field_prefix ? "{$field_prefix}_{$attr}" : $attr;

            // if field is multi file or file multiple
            if (in_array($column->type, ['attachment', 'file'])) {
                // set file is required when is multiple
                if (in_array('required', $column->validations)) {
                    $validation[$field_attr][] = 'required';
                }

                // Max count of multiple files
                foreach ($column->validations ?? [] as $key => $rule) {
                    if (Str::contains($rule, 'max_file_count')) {
                        unset($column['validations'][$key]);
                        break;
                    }
                }
                $validation["{$field_attr}.*"] = $column->validations;
            } else {
                // if field not in section type and not is from file type
                $validation[$field_attr] = $column->validations;
            }
        }

        return $validation;
    }

    /**
     * @param $validations
     * @param $id
     *
     * @return mixed
     */
    function ignoreRowId($validations, $id)
    {
        // id not null (this function used to update any item)
        // Check if column validations has unique rule
        foreach ($validations as $key => $item_validation) {
            // get type of validations item // if object try to get his class name
            if (gettype($item_validation) == 'object') {
                if ("Illuminate\Validation\Rules\Unique" == get_class($item_validation)) {
                    $validations[$key] = $item_validation->ignore($id);
                }
            }
        }

        return $validations;
    }

    /**
     * @param  null  $available_columns
     * @return array
     */
    public function getValidationMessages($available_columns = null)
    {
        $available_columns = $available_columns ?? $this->getFormFields();
        $messages = [];
        $options = [];
        foreach ($available_columns as $column) {
            $name = $column->slug;
            foreach ($options as $option) {
                $msg = __("validation.{$option}", ['attribute' => __($name),]);
                if (!in_array($msg, $messages)) {
                    $messages["{$name}.{$option}"] = $msg;
                }
            }
        }
        return $messages;
    }


    /**
     * @return bool
     */
    public function getIsRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function getSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * @return bool
     */
    public function getSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @return string
     */
    public function getMax(): string
    {
        return $this->max;
    }

    /**
     * @param  string  $max
     */
    public function setMax(string $max): void
    {
        $this->max = $max;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param  string  $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getMin(): string
    {
        return $this->min;
    }

    /**
     * @param  string  $min
     */
    public function setMin(string $min): void
    {
        $this->min = $min;
    }

    /**
     * @return mixed
     */
    private function getSchemaCachedTables()
    {
        // Cache for one day
        $cache_key = CacheKey::schema_tables();

        return get_cached_key_data($cache_key, function () {
            return DB::getDoctrineSchemaManager()->listTableNames();
        }, 3600 * 24);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    private function getSchemaCachedColumnTypes()
    {
        // Cache for one day
        $cache_key = CacheKey::schema_table_columns_types($this->tableName);

        return get_cached_key_data($cache_key, function () {
            return collect(Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableColumns($this->tableName))
                ->map(function ($cols) {
                    return $cols->getType()->getName();
                })->toArray();
        }, 3600 * 24);
    }

    /**
     * @return mixed
     */
    public function getHiddenFields()
    {
        return $this->hidden_fields;
    }

    /**
     * @param $field
     * @return false
     */
    public function isMultipleField($field): bool
    {
        return $field->file_details->multiple ?? false;

    }


    /**
     * @return BaseFields
     */
    public function addSeoFields(): BaseFields
    {
        $this->addField('slug')
            ->setSection(self::SEO_META_SECTION)
            ->setType('text')
            ->ignoreInTable()
            ->get();

        $this->addField('title')
            ->setSection(self::SEO_META_SECTION)
            ->setType('text')
            ->ignoreInTable()
            ->get();

        $this->addField('description')
            ->setSection(self::SEO_META_SECTION)
            ->setType('textarea')
            ->ignoreInTable()
            ->get();

        $this->addField('keywords')
            ->setSection(self::SEO_META_SECTION)
            ->setType('tags')
            ->ignoreInTable()
            ->get();

        return $this;
    }

    /**
     * @return BaseFields
     */
    public function addEditorFields(): BaseFields
    {
        $this->addField('created_by')
            ->setSection(self::EDITOR_SECTION)
            ->setType('text')
            ->ignoreInTable()
            ->ignoreInForm()
            ->get();

        $this->addField('updated_by')
            ->setSection(self::EDITOR_SECTION)
            ->setType('text')
            ->ignoreInTable()
            ->ignoreInForm()
            ->get();

        $this->addField('deleted_by')
            ->setSection(self::EDITOR_SECTION)
            ->setType('text')
            ->ignoreInTable()
            ->ignoreInForm()
            ->get();
        return $this;
    }


}