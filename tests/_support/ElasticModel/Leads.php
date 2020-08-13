<?php

namespace Baka\Test\Support\ElasticModel;

use Baka\Contracts\Elasticsearch\ElasticIndexModelTrait;
use Baka\Database\Model;

class Leads extends Model
{
    use ElasticIndexModelTrait;

    public function initialize()
    {
        $this->setSource('leads');
        $this->belongsTo(
            'users_id',
            'Baka\Test\Support\Models\Users',
            'id',
            [
                'alias' => 'user',
                'elasticIndex' => true,
            ]
        );
    }
}
