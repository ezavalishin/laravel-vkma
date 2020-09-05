<?php

namespace ezavalishin\Guards;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VKMAGuard
{
    protected ?Model $user = null;
    protected Request $request;
    protected string $vkmaSecret;
    protected Model $model;
    protected string $vkIdKey;

    public function __construct(
        Request $request,
        string $vkmaSecret,
        string $model,
        string $vkIdKey
    ) {
        $this->request = $request;
        $this->vkmaSecret = $vkmaSecret;
        $this->model = resolve($model);
        $this->vkIdKey = $vkIdKey;
    }

    public function user(): ?Model
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $vkParamsHeader = $this->request->header('Vk-Params');

        if (! $vkParamsHeader) {
            return null;
        }

        parse_str(base64_decode($vkParamsHeader), $paramsUrl);

        if (! $this->validate($paramsUrl)) {
            return null;
        }

        return $this->resolveUser($paramsUrl['vk_user_id']);
    }

    public function validate(array $credentials = []): bool
    {
        if (Validator::make($credentials, [
            'vk_user_id' => 'required|integer',
            'sign' => 'required|string',
        ])->fails()) {
            return false;
        }

        if (! $this->signIsValid($credentials)) {
            return false;
        }

        return true;
    }

    private function collectUsefulParams($params): Collection
    {
        return collect($params)->map(static function ($param) {
            return $param ?? '';
        })->filter(static function ($param, $key) {
            return Str::startsWith($key, 'vk_');
        })->sortKeys();
    }

    private function signIsValid($params): bool
    {
        $usefulParams = $this->collectUsefulParams($params);

        /* Формируем строку вида "param_name1=value&param_name2=value"*/
        $signParamsQuery = $usefulParams->map(static function ($value, $key) {
            $value = urlencode($value);

            return "{$key}=$value";
        })->join('&');

        /* Получаем хеш-код от строки, используя защищенный ключ приложения. Генерация на основе метода HMAC. */
        $sign = rtrim(strtr(base64_encode(hash_hmac(
            'sha256', $signParamsQuery, $this->vkmaSecret, true
        )), '+/', '-_'), '=');

        return $sign === $params['sign'];
    }

    private function resolveUser(int $vkUserId): Model
    {
        return $this->model->newQuery()->firstOrCreate([
            $this->vkIdKey => $vkUserId,
        ]);
    }
}
