<?php

namespace ezavalishin\VKMA\Jobs;

use ezavalishin\VKMA\Contracts\VKMAUserInterface;
use ezavalishin\VKMA\Facades\VKMA;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class FillUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var VKMAUserInterface|Model
     */
    protected VKMAUserInterface $model;

    /**
     * Create a new job instance.
     *
     * @param VKMAUserInterface $model
     */
    public function __construct(VKMAUserInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $map = $this->model->vkFieldsMap();
        $vkFieldsToFetch = array_values($map);

        $response = VKMA::getClient()->getUser($this->model->getVkUserId(), $vkFieldsToFetch);

        foreach ($map as $attributeName => $vkFieldName) {
            $this->model->setAttribute($attributeName, $this->parseField($vkFieldName, $response[$vkFieldName]));
        }

        $this->model->save();
    }

    private function parseField($fieldName, $value)
    {
        if (! isset($value) || empty($value)) {
            return null;
        }

        $methodName = Str::camel('parse_'.$fieldName);

        if (method_exists($this->model, $methodName)) {
            return $this->model->{$methodName}($value);
        }

        if (method_exists($this, $methodName)) {
            return $this->{$methodName}($value);
        }

        return $value;
    }

    private function parseBdate($value)
    {
        if (preg_match('/\d{1,2}.\d{1,2}.\d{4}/', $value)) {
            return Carbon::parse($value);
        }

        if (preg_match('/\d{1,2}.\d{1,2}/', $value)) {
            $date = explode('.', $value);

            $bdate = new Carbon();

            $bdate->setYear(0);
            $bdate->setMonth($date[1]);
            $bdate->setDay($date[0]);

            return $bdate;
        }

        return null;
    }

    private function parseCountry($value)
    {
        return $value['id'];
    }

    private function parseCity($value)
    {
        return $value['id'];
    }
}
