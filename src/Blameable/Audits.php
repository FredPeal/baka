<?php

namespace Baka\Blameable;

use Baka\Database\Model;

class Audits extends Model
{
    public int $users_id;
    public string $entity_id;
    public string $model_name;
    public string $ip;
    public string $type;

    /**
     * add the relationships.
     *
     * @return void
     */
    public function initialize()
    {
        $this->setSource('audits');
        $this->hasMany('id', '\Baka\Blameable\AuditsDetails', 'audits_id', ['alias' => 'details']);
        $this->belongsTo('users_id', '\Baka\Auth\Users', 'id', ['alias' => 'user']);
    }

    /**
     * Get custom fields.
     *
     * @param mixed $records
     *
     * @return void
     */
    public function getCustomFields($records)
    {
        return $records;
    }
}
