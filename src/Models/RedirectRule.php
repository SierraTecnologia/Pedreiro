<?php

namespace Pedreiro\Models;

use Muleta\Library\Utils;
use Config;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Request;

class RedirectRule extends Base
{
    /**
     * Don't allow cloning because the "from" is unique
     *
     * @var bool
     */
    public $cloneable = false;

    /**
     * Admins should not be localized
     *
     * @var bool
     */
    public static $localizable = false;

    /**
     * Validation rules
     *
     * @var array
     */
    public $rules = [
        'from' => 'required|unique:redirect_rules,from',
        'to' => 'required',
    ];

    /**
     * Redirection codes
     *
     * @return array
     */
    public static function getCodes()
    {
        return [
            '301' => __('pedreiro::redirect_rules.model.301'),
            '302' => __('pedreiro::redirect_rules.model.302'),
        ];
    }

    /**
     * Generate the admin title
     *
     * @return string
     */
    public function getAdminTitleAttribute()
    {
        // Use the label, if defined
        if ($this->label) {
            return $this->label;
        }

        // Else make from the `from` and `to`
        // http://character-code.com/arrows-html-codes.php
        return $this->from .' &#8594; '.$this->to;
    }

    /**
     * Pre-validation rules
     *
     * @param \Illuminate\Validation\Validator $validation
     *
     * @return void
     */
    public function onValidating($validation)
    {
        // Clean up "from" route, stripping host and leading slash
        $this->from = preg_replace('#^([^/]*//[^/]+)?/?#', '', $this->from);

        // Make an absolute path if the current domain is entered
        $this->to = Utils\URL::urlToAbsolutePath($this->to);

        // Add row exception for unique
        if ($this->exists) {
            $rules = $validation->getRules();
            $rules['from'][1] .= ','.$this->getKey();
            $validation->setRules($rules);
        }
    }

    /**
     * Orders instances of this model in the admin as well as default ordering
     * to be used by public site implementation.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOrdered(Builder $query, string $direction = 'asc'): self
    {
        return $query->orderBy('from', $direction);
    }

    /**
     * See if the current request matches the "FROM" using progressively more
     * expensive ways to match the from column.
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @return void
     */
    public function scopeMatchUsingRequest($query)
    {
        return $query->where(
            function ($query) {
                $from = $this->pathAndQuery();
                $escaped_from = DB::connection()->getPdo()->quote($from);
                $from_col = DB::getDriverName() == 'sqlsrv' ? '[from]' : '`from`';
                $query->where('from', $from)->orWhereRaw("{$escaped_from} LIKE {$from_col}");
                if (Config::get('support::core.allow_regex_in_redirects')) {
                    $query->orWhereRaw("{$escaped_from} REGEXP {$from_col}");
                }
            }
        );
    }

    /**
     * Get the path and query from the request
     *
     * @return string
     */
    public function pathAndQuery()
    {
        $query = Request::getQueryString();
        $path = ltrim(Request::path(), '/'); // ltrim fixes homepage

        return $query ? $path.'?'.$query : $path;
    }
}
