<?php

namespace FXC\Base\Http\Controllers;

use App\Models\SeoMeta;
use App\Table\SeoMetaField;
use FXC\Base\Supports\BaseFields;
use FXC\Base\Supports\Str;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $data;
    protected $className;

    public function __construct()
    {
        $this->data = (object) [];
    }

    public static function instance()
    {
        return new static();
    }

    /**
     * @param $className
     * @return void
     */
    public function setDataFields($className)
    {
        $this->data->class = $className;
        $this->data->moduleName = $className::getTableName();
        $this->data->fieldsClass = $className::getFieldClassName();
        $this->data->fieldsObj = $this->fieldsObj();
        $this->data->fields = $this->fieldsObj()->getFields();
        $this->data->availableFields = $this->fieldsObj()->getAvailableFields();
        $this->data->formFields = $this->fieldsObj()->getFormFields();

        $sections = collect($this->data->formFields)->groupBy("section");
        $this->data->sections = [
            BaseFields::TRANSLATION_SECTION => $sections[BaseFields::TRANSLATION_SECTION] ?? [],
//            BaseFields::SEO_META_SECTION    => $sections[BaseFields::SEO_META_SECTION],
            BaseFields::GENERAL_SECTION     => $sections[BaseFields::GENERAL_SECTION] ?? [],
        ];

//        $this->data->translatableFields = $this->data->sections['translations'] ?? [];
        $this->data->translatableFields = $this->fieldsObj()->getTranslatableFields();
        $this->data->notTranslatableFields = $this->fieldsObj()->getNotTranslatableFields();

//        $this->data->parentFieldLocal = app()->getLocale();
    }

    /**
     * @return Application|mixed
     */
    private function fieldsObj()
    {
        return app($this->data->fieldsClass);
    }

    /**
     * @param  Request  $request
     * @param $view
     * @param $pageCount
     * @param $perPage
     * @return Application|Factory|View
     */
    public function paginate(Request $request, $view, $pageCount, $perPage)
    {
        if (isset($this->data->paginator)) {
            $pageCount = $pageCount - 1;
            if (($request->page < 1 || $request->page > $this->data->paginator->lastPage())) {
                abort(404);
            }

            $this->data->paginator->appends(request()->except('page'));

            if ($this->data->paginator->currentPage() + $pageCount <= $this->data->paginator->lastPage()) {
                if ($this->data->paginator->currentPage() > 2) {
                    $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->currentPage() - ((int) ($pageCount / 2)),
                        $this->data->paginator->currentPage() + $pageCount - ((int) ($pageCount / 2)));
                } else {
                    if ($this->data->paginator->currentPage() == 2) {
                        $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->currentPage() - 1,
                            $this->data->paginator->currentPage() + $pageCount - 1);
                    } else {
                        if ($this->data->paginator->currentPage() == 1) {
                            $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->currentPage(), $this->data->paginator->currentPage() + $pageCount);
                        }
                    }
                }
            } else {
                $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->lastPage() - $pageCount, $this->data->paginator->lastPage());
            }

            $this->data->previous_page = $this->data->paginator->currentPage() - 1;
            $this->data->next_page = $this->data->paginator->currentPage() + 1;

            if ($this->data->previous_page < 1) {
                $this->data->previous_page = 1;
            }
            if ($this->data->next_page > $this->data->paginator->lastPage()) {
                $this->data->next_page = $this->data->paginator->lastPage();
            }

            $this->data->previous_page = $this->data->paginator->previousPageUrl();
            $this->data->next_page = $this->data->paginator->nextPageUrl();
            $this->data->first_page = $this->data->paginator->url(1);
            $this->data->last_page = $this->data->paginator->url($this->data->paginator->lastPage());

            $this->data->per_page = $perPage;
        }

        return view($view, (array) $this->data);
    }

    /**
     * @param  Request  $request
     * @param  null  $id
     * @return void
     */
    protected function validateRequest(Request $request, $id = null)
    {
        $rules = $this->fieldsObj()->getValidationRules($id);
        $messages = $this->fieldsObj()->getValidationMessages();

        $request->validate($rules, $messages);
    }

    /**
     * @param  Request  $request
     * @return void
     */
    protected function createNewRecord(Request $request)
    {
        $this->validateRequest($request);

        $this->data->model = new $this->className;

        $this->setRecordAttributeValues($request);
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return void
     */
    protected function updateExistsRecord(Request $request, $id)
    {
        $this->validateRequest($request, $id);

        $this->data->model = $this->className::query()->findOrFail($id);

        $this->setRecordAttributeValues($request, $id);
    }

    /**
     * @param  Request  $request
     * @param $id
     * @return void
     */
    protected function updateSeoMetaRecord(Request $request, $id)
    {
        $seoFieldObj = new SeoMetaField();
        $rules = $seoFieldObj->getValidationRules();
        $messages = $seoFieldObj->getValidationMessages();
        $request->validate($rules, $messages);

        foreach (get_locales() as $local) {
            $data = [
                'locale'      => $local,
                'module_id'   => $id,
                'module_type' => $this->data->class,
            ];
            $localeData = $request->{$local};
            app()->setLocale($local);
            $updateData = array_merge($data, $localeData ?? []);

            SeoMeta::query()->updateOrCreate($data, $updateData);
        }

//        create_seo_meta($request->title, $request->description, $id, $this->data->class, $request->slug);
    }

    /**
     * @param $field_type
     * @param $dateValue
     * @return array|Carbon|mixed
     */
    private function dateFormatValue($field_type, $dateValue)
    {
        $date_string_value = $value = $date_strings = $dateValue;
        if (!is_countable($date_string_value)) {
            $date_strings = [$dateValue];
        }
        $values = [];
        foreach ($date_strings as $dateValue) {
            switch ($field_type) {
                case 'date':
                    if ($dateValue) {
                        $value = Carbon::createFromFormat(config("panel.date.show_format.date", 'd/m/Y'), $dateValue)->toDateString();
                        $value = Carbon::parse($value);
                        $values[] = $value->toDateString();
                    }
                    break;
                case 'datetime':
                    if ($dateValue) {
                        $value = Carbon::createFromFormat(config("panel.date.show_format.datetime", 'd/m/Y H:i A'), $dateValue)->toDateTimeLocalString();
                        $value = Carbon::parse($value);
                        $values[] = $value->toDateTimeString();
                    }
                    break;
                default:
                    $value = $dateValue;
                    $values[] = $value;
                    break;
            }
        }

        if (is_countable($date_string_value)) {
            return $values;
        }

        return $value;
    }

    /**
     * @param $phone
     * @return array|string|string[]|null
     */
    private function phoneNumberFormat($phone)
    {
        if (is_array($phone)) {
            $phones = $phone;
            foreach ($phones as $key => $phone) {
                $phones[$key] = $this->fixPhoneNumber($phone);
            }

            return $phones;
        }

        return $this->fixPhoneNumber($phone);
    }

    /**
     * @param $phone
     * @return array|string|string[]|null
     */
    private function fixPhoneNumber($phone)
    {
        if (is_null($phone)) {
            return null;
        }

        $phone = clean_special_chars($phone);   // fix special characters

        if (!Str::startsWith($phone, '00')) {
            $phone = "00{$phone}"; // add 00 to phone if it does not start with it
        }

        return $phone;
    }

    /**
     * @param $request
     * @param  null  $id
     * @return void|null
     */
    private function setRecordAttributeValues($request, $id = null)
    {
        $record = $this->data->model;
        if (!$record) {
            return null;
        }
        $translatableFields = $this->data->translatableFields;
        $notTranslatableFields = $this->data->notTranslatableFields;

        foreach ($translatableFields as $field) {
            $value = null;
            $attr = $field->slug;
            $field_type = $field->type;

            if ($field_type == 'morphMany' or in_array($attr, $this->fieldsObj()->getHiddenFields() ?? [])) {
                continue;
            }
            $transValue = [];
            foreach (get_locales() as $local) {
                switch ($field_type) {
                    case 'boolean':
                    case 'checkbox':
                        $value = $request->has($attr) ? 1 : 0;
                        break;
                    default:
                        $value = $request->{$local}[$attr];
                        break;
                }

                if (in_array($attr, app($this->className)->transFields ?? [])) {
                    if (!$record->id) {
                        $record->save();
                    }
                    $transValue[$local] = $value;
                    $record->{$attr} = $transValue;
                } else {
                    $record->setTranslation($attr, $local, $value);
                }
            }
        }

        foreach ($notTranslatableFields as $field) {
            $value = null;
            $attr = $field->slug;
            $field_type = $field->type;
            $isJsonField = $field->is_json_field ?? false;

            if ($field_type == 'morphMany' or in_array($attr, $this->fieldsObj()->getHiddenFields() ?? [])) {
                continue;
            }
            $toEscapeColumns = [];
            switch ($field_type) {
                case 'boolean':
                case 'checkbox':
                    $value = $request->has($attr) ? 1 : 0;
                    break;
                case 'date':
                case 'datetime':
                case 'time':
                    $value = $this->dateFormatValue($field_type, $request->{$attr});
                    break;
                case 'phone':
                case 'mobile':
                    $value = $this->phoneNumberFormat($request->{$attr});
                    break;
                case 'nested_multiclass':
                    $value = empty($request->{$attr}) ? null : $request->{$attr};
                    break;
                case 'password':
                    if ($request->{$attr}) {
                        $value = bcrypt($request->{$attr});
                    }
                    break;
                case 'file':
                case 'video':
                case 'image':
                case 'photo':
                case 'avatar':
                    $toEscapeColumns[] = $attr;
                    // if multiple file
                    if ($this->fieldsObj()->isMultipleField($field)) {
                        $mFile = [];
                        if ($request->hasFile($attr)) {
                            if ($id) {
                                $mFile[] = upload_image($request, $attr, $record->folderPath, $record->{$attr});
                            } else {
                                $mFile[] = upload_image($request, $attr, $record->folderPath);
                            }
                        }
                        $record->{$attr} = implode(',', $mFile);
                    } else {
                        if ($request->hasFile($attr)) {
                            if ($id) {
                                $record->{$attr} = upload_image($request, $attr, $record->folderPath, $record->{$attr});
                            } else {
                                $record->{$attr} = upload_image($request, $attr, $record->folderPath);
                            }
                        }
                    }
                    break;
                case'multi_select':
                case'lookup_with_multi_select':
                case'children':
                case'treeview':
                    $value = $request->{$attr};
                    if (is_array($value)) // is_array
                    {
                        $firstItem = collect($value)->first();
                        if (!is_array($firstItem)) {
                            $value = collect($value)->unique()->toArray();
                            $value = implode(',', $value);
                            $value = trim($value, ',');
                        } else {
                            $value = collect($value)->map(function ($val) {
                                $val = collect($val)->unique()->toArray();
                                $val = implode(',', $val);
                                return trim($val, ',');
                            });
                        }
                    }
                    $toEscapeColumns[] = $attr;
                    if ($id) {
                        $record->{$attr}()->detach();
                    }
                    if (isset($value)) {
                        $value = explode(',', $value);
                        $record->{$attr}()->attach($value);
                    }
                    break;
                default:
                    if ($isJsonField) {
                        $jsonAttrs = $column['json_attrs'] ?? [];
                        $jsonObj = [];
                        foreach ($jsonAttrs as $jsonAttr) {
                            $jsonFieldName = $attr.'_'.$jsonAttr;
                            $toEscapeColumns[] = $jsonFieldName;
                            $jsonObj[$jsonAttr] = $request->{$jsonFieldName} ?? null;
                        }
                        $value = json_encode($jsonObj);
                    } else {
                        $value = $request->{$attr};
                    }
                    break;
            }
            if (!in_array($attr, $toEscapeColumns)) {
                $record->{$attr} = $value;
            }
        }

        $record->save();
        $this->data->model = $record;

    }

    /**
     * @param $id
     * @return void
     */
    public function setDataModelAttribute($id = null)
    {
        $id = $id ?? request()->query('id');
        if (!isset($this->data->model) and !is_null($id)) {
            $this->data->model = call_user_func(sprintf('%s::query', $this->data->class))->findOrFail($id);
        }
    }

}
