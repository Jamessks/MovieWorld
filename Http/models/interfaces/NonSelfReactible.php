<?php

namespace Http\models\interfaces;

interface NonSelfReactible
{
    public function isUserOwnerOfInstance(int $id, int $instanceId);
}
