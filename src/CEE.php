<?php

namespace Valerian\Ispis;

use Valerian\Ispis\Exception\CEEException;

/**
 * Centrální evidence exekucí
 */
class CEE extends Base
{

    const REGISTER = 'CEE';

    /**
     * @return bool
     * @throws CEEException
     */
    public function isDebtor($nin)
    {
        $response = $this->query("&RC={$nin}");

        if (!isset($response->body->detail->CEE_Pocet)) {
            throw new CEEException('Unknown state');

        } elseif ((int) $response->body->detail->CEE_Pocet === 0) {
            return false;
        }

        return true;
    }

}
