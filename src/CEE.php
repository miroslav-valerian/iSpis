<?php

namespace Valerian\Ispis;

use \DateTime;
use Valerian\Ispis\Exception\CEEException;
use Valerian\Ispis\Exception\IspisException;

/**
 * Centrální evidence exekucí
 */
class CEE extends Base
{

    const REGISTER = 'CEE';

    const QF_ALL = 1;
    const QF_NIN = 2;
    const QF_NSB = 3;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $surname;

    /**
     * @var DateTime|null
     */
    private $dob;

    /**
     * @var string|null
     */
    private $nin;

    /**
     * @var bool
     */
    private $separateQuery = false;

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param null|string $surname
     * @return $this
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param DateTime|null $dob
     * @return $this
     */
    public function setDob(DateTime $dob = null)
    {
        $this->dob = $dob;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNin()
    {
        return $this->nin;
    }

    /**
     * @param null|string $nin
     * @return $this
     */
    public function setNin($nin)
    {
        $this->nin = $nin;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSeparateQuery()
    {
        return $this->separateQuery;
    }

    /**
     * @param bool $separateQuery
     * @return $this
     */
    public function setSeparateQuery($separateQuery)
    {
        $this->separateQuery = $separateQuery;
        return $this;
    }

    /**
     * @return bool
     * @throws CEEException
     * @throws IspisException
     */
    public function isDebtor()
    {
        if ($this->separateQuery) {
            $query = $this->getQuery(self::QF_NSB);
            $response = $this->query($query);

            if (!isset($response->body->detail->CEE_Pocet)) {
                throw new CEEException('Unknown state');

            } elseif ((int) $response->body->detail->CEE_Pocet !== 0) {
                return true;
            }

            $query = $this->getQuery(self::QF_NIN);
            $response = $this->query($query);

            if (!isset($response->body->detail->CEE_Pocet)) {
                throw new CEEException('Unknown state');

            } elseif ((int) $response->body->detail->CEE_Pocet !== 0) {
                return true;
            }

        } else {
            $query = $this->getQuery(self::QF_ALL);
            $response = $this->query($query);

            if (!isset($response->body->detail->CEE_Pocet)) {
                throw new CEEException('Unknown state');

            } elseif ((int) $response->body->detail->CEE_Pocet !== 0) {
                return true;
            }
        }

        return false;
    }

    private function getQuery($part = self::QF_ALL)
    {
        $params = array(
            'name' => '',
            'surname' => '',
            'dob' => '',
            'nin' => '',
        );

        if (isset($this->name)) {
            $params['name'] .= "&Jmeno={$this->name}";
        }

        if (isset($this->surname)) {
            $params['surname'] .= "&Prijmeni={$this->surname}";
        }

        if (isset($this->dob)) {
            $params['dob'] .= "&Narozen={$this->dob->format('j.n.Y')}";
        }

        if (isset($this->nin)) {
            $params['nin'] .= "&RC={$this->nin}";
        }

        if ($part == self::QF_ALL) {
            return $params['name'] . $params['surname'] . $params['dob'] . $params['nin'];

        } elseif ($part == self::QF_NSB) {
            return $params['name'] . $params['surname'] . $params['dob'];

        } elseif ($part == self::QF_NIN) {
            return $params['nin'];
        }

        return '';
    }

}
