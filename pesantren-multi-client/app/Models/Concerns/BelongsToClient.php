<?php

namespace App\Models\Concerns;

use App\Support\ClientContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait untuk model yang data-nya milik satu client.
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait BelongsToClient
{
    public static function bootBelongsToClient(): void
    {
        // Auto-filter saat BACA — hanya tampil data client aktif
        static::addGlobalScope('client', function (Builder $builder): void {
            $context = app(ClientContext::class);

            if ($context->has()) {
                $builder->where(
                    $builder->qualifyColumn('client_id'),
                    $context->id()
                );
            }
        });

        // Auto-fill client_id saat TULIS — tidak perlu isi manual
        static::creating(function (Model $model): void {
            $context = app(ClientContext::class);

            if ($context->has() && empty($model->getAttribute('client_id'))) {
                $model->setAttribute('client_id', $context->id());
            }
        });
    }
}
