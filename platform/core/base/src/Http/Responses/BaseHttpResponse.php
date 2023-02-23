<?php

namespace FXC\Base\Http\Responses;

use FXC\Base\Helpers\BaseHelper;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class BaseHttpResponse implements Responsable
{
    /**
     * @var bool
     */
    protected $error = false;

    /**
     * @var array|string|JsonResource
     */
    protected $data;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $previousUrl = '';

    /**
     * @var string
     */
    protected $nextUrl = '';

    /**
     * @var bool
     */
    protected $withInput = false;

    /**
     * @var array
     */
    protected $additional = [];

    /**
     * @var int
     */
    protected $code = 200;

    /**
     * @param array|string|JsonResource $data
     * @return $this
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $previousUrl
     * @return BaseHttpResponse
     */
    public function setPreviousUrl(string $previousUrl): self
    {
        $this->previousUrl = $previousUrl;

        return $this;
    }

    /**
     * @param string $nextUrl
     * @return BaseHttpResponse
     */
    public function setNextUrl(string $nextUrl): self
    {
        $this->nextUrl = $nextUrl;

        return $this;
    }

    /**
     * @param bool $withInput
     * @return BaseHttpResponse
     */
    public function withInput(bool $withInput = true): self
    {
        $this->withInput = $withInput;

        return $this;
    }

    /**
     * @param int $code
     * @return BaseHttpResponse
     */
    public function setCode(int $code): self
    {
        if ($code < 100 || $code >= 600) {
            return $this;
        }

        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return BaseHttpResponse
     */
    public function setMessage(?string $message): self
    {
        $this->message = BaseHelper::clean($message);

        return $this;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->error;
    }

    /**
     * @param bool $error
     * @return BaseHttpResponse
     */
    public function setError(bool $error = true): self
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param array $additional
     * @return BaseHttpResponse
     */
    public function setAdditional(array $additional): self
    {
        $this->additional = $additional;

        return $this;
    }

    /**
     * @return BaseHttpResponse|RedirectResponse|JsonResource
     */
    public function toApiResponse()
    {
        if ($this->data instanceof JsonResource) {
            return $this->data->additional(array_merge([
                'error' => $this->error,
                'message' => $this->message,
            ], $this->additional));
        }

        return $this->toResponse(request());
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function toResponse($request)
    {
        if ($request->expectsJson()) {
            $data = [
                'error' => $this->error,
                'data' => $this->data,
                'message' => $this->message,
            ];

            if ($this->additional) {
                $data = array_merge($data, ['additional' => $this->additional]);
            }

            return response()
                ->json($data, $this->code);
        }

        if ($request->input('submit') === 'save' && !empty($this->previousUrl)) {
            return $this->responseRedirect($this->previousUrl);
        } elseif (!empty($this->nextUrl)) {
            return $this->responseRedirect($this->nextUrl);
        }

        return $this->responseRedirect(URL::previous());
    }

    /**
     * @param string $url
     * @return RedirectResponse
     */
    protected function responseRedirect(string $url): RedirectResponse
    {
        if ($this->withInput) {
            return redirect()
                ->to($url)
                ->with($this->error ? 'error_msg' : 'success_msg', $this->message)
                ->withInput();
        }

        return redirect()
            ->to($url)
            ->with($this->error ? 'error_msg' : 'success_msg', $this->message);
    }
}
