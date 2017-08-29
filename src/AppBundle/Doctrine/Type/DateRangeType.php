<?php

namespace AppBundle\Doctrine\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use League\Period\Period;

/**
 * Class DateRangeType.
 */
final class DateRangeType extends Type
{
    const DATE_RANGE_TYPE = 'daterange';
    const DATABASE_DATE_FORMAT = 'Y-m-d';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return sprintf('[%s, %s)', // Postgres representation of daterange
            $value->getStartDate()->format(self::DATABASE_DATE_FORMAT),
            $value->getEndDate()->format(self::DATABASE_DATE_FORMAT)
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        // splitting database value ["2010-10-10", "2011-11-11") into two date strings
        [$startDate, $endDate] = explode(',', str_replace(['[', ')'], '', $value));

        return new Period(
            \DateTimeImmutable::createFromFormat(self::DATABASE_DATE_FORMAT, $startDate),
            \DateTimeImmutable::createFromFormat(self::DATABASE_DATE_FORMAT, $endDate)
        );
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'daterange';
    }

    public function getName()
    {
        return self::DATE_RANGE_TYPE;
    }
}
