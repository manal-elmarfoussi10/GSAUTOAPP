<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class InterventionPhoto extends Model
{
    protected \$fillable = [
        'intervention_id', 'uploaded_by', 'file_path', 'commentaire'
    ];

    public function intervention()
    {
        return \$this->belongsTo(Intervention::class);
    }

    public function uploader()
    {
        return \$this->belongsTo(User::class, 'uploaded_by');
    }
}
