<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Logger;

use Bramus\Monolog\Formatter\ColorSchemes\ColorSchemeInterface;
use Bramus\Monolog\Formatter\ColorSchemes\DefaultScheme;
use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class ColoredJsonFormatter extends JsonFormatter
{
    /**
     * The Color Scheme to use
     */
    private ColorSchemeInterface $colorScheme;

    /**
     * @param int $format The format of the message
     */
    public function __construct(?ColorSchemeInterface $colorScheme = null, $format = JsonFormatter::BATCH_MODE_JSON, bool $appendNewline = true, bool $ignoreEmptyContextAndExtra = false, bool $includeStacktraces = false)
    {
        // Store the Color Scheme
        if (! $colorScheme) {
            $this->colorScheme = new DefaultScheme();
        } else {
            $this->colorScheme = $colorScheme;
        }

        // Call Parent Constructor
        parent::__construct($format, $appendNewline, $ignoreEmptyContextAndExtra, $includeStacktraces);
    }

    /**
     * Gets The Color Scheme
     */
    public function getColorScheme(): ColorSchemeInterface
    {
        return $this->colorScheme;
    }

    /**
     * Sets The Color Scheme
     */
    public function setColorScheme(ColorSchemeInterface $colorScheme): void
    {
        $this->colorScheme = $colorScheme;
    }

    public function format(LogRecord $record): string
    {
        // Get the Color Scheme
        $colorScheme = $this->getColorScheme();

        // Let the parent class to the formatting, yet wrap it in the color linked to the level
        return $colorScheme->getColorizeString($record->level->value) . trim(parent::format($record)) . $colorScheme->getResetString() . "\n";
    }
}
