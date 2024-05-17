<?php

namespace App\Rules;

use App\Models\WhitelistedKeyword;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckWhitelistedKeywords implements ValidationRule
{
    private int $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!WhitelistedKeyword::where('user_project_id', $this->projectId)->exists()){
            $fail('Please create or insert your project Whitelisted Keywords');
        }
    }
}
