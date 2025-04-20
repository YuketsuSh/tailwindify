<?php

namespace Azuriom\Plugin\Tailwindify\Models;

use Azuriom\Models\Traits\HasTablePrefix;
use Illuminate\Database\Eloquent\Model;

class TailwindifyReplacement extends Model
{
    use HasTablePrefix;

    protected $table = 'tailwindify_replacements';

    protected $fillable = ['bootstrap_class', 'tailwind_class', 'status'];

    public $timestamps = false;
}
