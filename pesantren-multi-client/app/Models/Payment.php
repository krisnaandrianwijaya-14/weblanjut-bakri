<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Concerns\BelongsToClient;
class Payment extends Model
{
use HasFactory, BelongsToClient;
}
