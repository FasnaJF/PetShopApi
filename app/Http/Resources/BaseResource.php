<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{

    public function withSuccess($flag): static
    {
        $this->with = $this->with + ['success' => $flag];
        return $this;
    }

    public function withError($errorMessage): static
    {
        $this->with = $this->with + ['error' => $errorMessage];
        return $this;
    }

    public function withErrors($errorMessages): static
    {
        $this->with = $this->with + ['errors' => $errorMessages];
        return $this;
    }

    public function withTrace($trace): static
    {
        $this->with = $this->with + ['trace' => $trace];
        return $this;
    }

    public function withExtra($extra): static
    {
        $this->with = $this->with + ['extra' => $extra];
        return $this;
    }
}
