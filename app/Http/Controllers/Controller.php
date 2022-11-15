<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $data;

    public function __construct()
    {
        $this->data = (object)[];
    }

    public static function instance()
    {
        return new static();
    }

    public function paginate(Request $request, $view, $pageCount, $perPage)
    {
        if (isset($this->data->paginator)) {
            $pageCount = $pageCount - 1;
            if (($request->page < 1 || $request->page > $this->data->paginator->lastPage()))
                abort(404);

            $this->data->paginator->appends(request()->except('page'));

            if ($this->data->paginator->currentPage() + $pageCount <= $this->data->paginator->lastPage()) {
                if ($this->data->paginator->currentPage() > 2)
                    $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->currentPage() - ((int)($pageCount / 2)), $this->data->paginator->currentPage() + $pageCount - ((int)($pageCount / 2)));
                else if ($this->data->paginator->currentPage() == 2)
                    $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->currentPage() - 1, $this->data->paginator->currentPage() + $pageCount - 1);
                else if ($this->data->paginator->currentPage() == 1)
                    $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->currentPage(), $this->data->paginator->currentPage() + $pageCount);
            } else {
                $this->data->url_range = $this->data->paginator->getUrlRange($this->data->paginator->lastPage() - $pageCount, $this->data->paginator->lastPage());
            }

            $this->data->previous_page = $this->data->paginator->currentPage() - 1;
            $this->data->next_page = $this->data->paginator->currentPage() + 1;

            if ($this->data->previous_page < 1)
                $this->data->previous_page = 1;
            if ($this->data->next_page > $this->data->paginator->lastPage())
                $this->data->next_page = $this->data->paginator->lastPage();

            $this->data->previous_page = $this->data->paginator->previousPageUrl();
            $this->data->next_page = $this->data->paginator->nextPageUrl();
            $this->data->first_page = $this->data->paginator->url(1);
            $this->data->last_page = $this->data->paginator->url($this->data->paginator->lastPage());

            $this->data->per_page = $perPage;
        }

        return view($view, (array)$this->data);
    }

}
