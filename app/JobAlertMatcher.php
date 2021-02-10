<?php

declare(strict_types=1);

namespace App;

use App\Models\Alert;
use App\Models\Job;

class JobAlertMatcher
{
    protected $job;

    protected $alert;

    public function __construct(Job $job, Alert $alert)
    {
        $this->job = $job;
        $this->alert = $alert;
    }

    public function match(): bool
    {
        if (! $this->countriesMatch()) {
            return false;
        }

        if (! $this->citiesMatch()) {
            return false;
        }

        if (! $this->typesMatch()) {
            return false;
        }

        if (! $this->stylesMatch()) {
            return false;
        }

        if ($this->allKeywordsShouldMatch() && ! $this->allKeywordsMatch()) {
            return false;
        }

        if (! $this->atLeastOneKeywordMatches()) {
            return false;
        }

        return true;
    }

    protected function countriesMatch(): bool
    {
        return $this->alert->country_id === $this->job->country_id;
    }

    protected function citiesMatch(): bool
    {
        return mb_stripos($this->job->city, $this->alert->city) !== false;
    }

    protected function typesMatch(): bool
    {
        return in_array($this->job->type, $this->alert->job_types);
    }

    protected function stylesMatch(): bool
    {
        return in_array($this->job->style, $this->alert->job_styles);
    }

    protected function allKeywordsShouldMatch(): bool
    {
        return $this->alert->has_all_keywords;
    }

    protected function allKeywordsMatch(): bool
    {
        foreach ($this->alert->keywords as $keyword) {
            if (! mb_stripos($this->job->title, $keyword->word) && ! mb_stripos($this->job->description, $keyword->word)) {
                return false;
            }
        }

        return true;
    }

    protected function atLeastOneKeywordMatches(): bool
    {
        $matched = 0;

        foreach ($this->alert->keywords as $keyword) {
            if (mb_stripos($this->job->title, $keyword->word) || mb_stripos($this->job->description, $keyword->word)) {
                $matched++;
            }
        }

        return $matched > 0;
    }
}
