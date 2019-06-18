<?php

namespace Vyuldashev\Monolog\Loki;

use DateTime;
use Monolog\Formatter\FormatterInterface;

class LokiFormatter implements FormatterInterface
{
    /**
     * Formats a log record.
     *
     * @param array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        // TODO extra
        return json_encode([
            'streams' => [
                [
                    'labels' => static::formatLabels($record),
                    'entries' => [
                        [
                            'ts' => $record['datetime']->format(DateTime::ATOM),
                            'line' => $record['message'],
                        ],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Formats a set of log records.
     *
     * @param array $records A set of records to format
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $streams = [];

        foreach ($records as $record) {
            $streams[] = [
                'labels' => json_encode(static::formatLabels($record), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'entries' => [
                    [
                        'ts' => $record['datetime']->format(DateTime::ATOM),
                        'line' => $record['message'],
                    ],
                ],
            ];
        }

        // TODO extra
        return json_encode([
            'streams' => $streams,
        ], JSON_UNESCAPED_UNICODE);
    }

    protected static function formatLabels(array $record)
    {
        $labels = array_merge(
            [
                'level' => $record['level'],
                'level_name' => $record['level_name'],
                'channel' => $record['channel'],
            ],
            $record['context']
        );

        foreach ($labels as $name => $value) {
            $escapedLabels[] = $name . '="' . static::escapeLabelValue($value) . '"';
        }

        return '{' . implode(',', $escapedLabels) . '}';
    }

    protected static function escapeLabelValue($value)
    {
        return str_replace(["\\", "\n", '"'], ["\\\\", "\\n", "\\\""], $value);
    }
}
