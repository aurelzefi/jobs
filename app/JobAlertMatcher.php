<?php

declare(strict_types=1);

namespace App;

use App\Models\Alert;
use App\Models\Job;
use App\Models\Keyword;
use Illuminate\Database\Eloquent\Collection;

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
        if (! $this->countryMatches()) {
            return false;
        }

        if (! $this->cityMatches()) {
            return false;
        }

        if (! $this->typeMatches()) {
            return false;
        }

        if (! $this->styleMatches()) {
            return false;
        }

        if ($this->allKeywordsShouldMatch() && ! $this->allKeywordsMatch()) {
            return false;
        }

        if (! $this->hasMatchingKeywords()) {
            return false;
        }

        return true;
    }

    protected function countryMatches(): bool
    {
        return $this->alert->country_id === $this->job->country_id;
    }

    protected function cityMatches(): bool
    {
        return (bool) mb_stripos($this->job->city, $this->alert->city);
    }

    protected function typeMatches(): bool
    {
        return in_array($this->job->type, $this->alert->job_types);
    }

    protected function styleMatches(): bool
    {
        return in_array($this->job->style, $this->alert->job_styles);
    }

    protected function allKeywordsShouldMatch(): bool
    {
        return $this->alert->has_all_keywords;
    }

    protected function allKeywordsMatch(): bool
    {
        return $this->matchingKeywords()->count() === $this->alert->keywords->count();
    }

    protected function hasMatchingKeywords(): bool
    {
        return $this->matchingKeywords()->count() > 0;
    }

    protected function matchingKeywords(): Collection
    {
        return $this->alert->keywords->filter(function (Keyword $keyword) {
            return $this->keywordMatches($keyword);
        });
    }

    protected function keywordMatches(Keyword $keyword): bool
    {
        return mb_stripos($this->job->title, $keyword->word) || mb_stripos($this->job->description, $keyword->word);
    }
}
