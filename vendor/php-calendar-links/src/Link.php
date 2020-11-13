<?php

namespace Spatie\CalendarLinks;

use DateTime;
use Spatie\CalendarLinks\Exceptions\InvalidLink;
use Spatie\CalendarLinks\Generators\Google;
use Spatie\CalendarLinks\Generators\Ics;
use Spatie\CalendarLinks\Generators\WebOutlook;
use Spatie\CalendarLinks\Generators\Yahoo;

/**
 * @property-read string $title
 * @property-read \DateTime $from
 * @property-read \DateTime $to
 * @property-read string $description
 * @property-read string $address
 * @property-read bool $allDay
 */
class Link
{
    /** @var string */
    protected $title;

    /** @var \DateTime */
    protected $from;

    /** @var \DateTime */
    protected $to;

    /** @var string */
    protected $description;

    /** @var bool */
    protected $allDay;

    /** @var string */
    protected $address;

    public function __construct($title, DateTime $from, DateTime $to, $allDay = false)
    {
        $this->title = $title;
        $this->allDay = $allDay;

        if ($to < $from) {
            throw InvalidLink::invalidDateRange($from, $to);
        }

        $this->from = clone $from;
        $this->to = clone $to;
    }

    /**
     * @param string $title
     * @param \DateTime $from
     * @param \DateTime $to
     * @param bool $allDay
     *
     * @return static
     * @throws InvalidLink
     */
    public static function create($title, DateTime $from, DateTime $to, $allDay = false)
    {
        return new static($title, $from, $to, $allDay);
    }

    /**
     * @param string   $title
     * @param DateTime $fromDate
     * @param int      $numberOfDays
     *
     * @return Link
     * @throws InvalidLink
     */
    public static function createAllDay($title, DateTime $fromDate, $numberOfDays = 1)
    {
    	$from = clone $fromDate;
        $from = $from->modify('midnight');

        $to = clone $from;
        $to = $to->modify("+$numberOfDays days");

        return new self($title, $from, $to, true);
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function address($address)
    {
        $this->address = $address;

        return $this;
    }

    public function formatWith(Generator $generator)
    {
        return $generator->generate($this);
    }

    public function google()
    {
        return $this->formatWith(new Google());
    }

    public function ics()
    {
        return $this->formatWith(new Ics());
    }

    public function yahoo()
    {
        return $this->formatWith(new Yahoo());
    }

    public function webOutlook()
    {
        return $this->formatWith(new WebOutlook());
    }

    public function __get($property)
    {
        return $this->$property;
    }
}
