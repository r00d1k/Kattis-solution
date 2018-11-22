<?php
/**
 * Created by PhpStorm.
 * User: r00d1k
 * Date: 11/21/18
 * Time: 11:37 PM
 */

class AvgDestinationCalculator
{
    private $directions = [];

    public function __construct($directions) {
        $directions = array_map(
            function ($item) {
                return explode(' ', $item);
            },
            $directions
        );

        foreach ($directions as $direction) {
            $raw_instructions = array_slice($direction, 2);
            $instructions = [];
            while (current($raw_instructions)) {
                $command = array_shift($raw_instructions);
                $value = array_shift($raw_instructions);
                $instructions[] = ['command' => $command, 'value' => $value];
            }
            array_push($this->directions, [
                'x' => $direction[0],
                'y' => $direction[1],
                'instructions' => $instructions
            ]);
        }
    }

    public function run() {
        $destinations = [];
        $sum_x = 0.0;
        $sum_y = 0.0;

        foreach ($this->directions as $direction) {
            foreach ($direction['instructions'] as $instruction) {
                $direction = $this->{$instruction['command']}($direction, $instruction['value']);
            }

            array_push($destinations, $direction);

            $sum_x += $direction['x'];
            $sum_y += $direction['y'];
        }

        $average_destination = [
            'x' => $sum_x / count($destinations),
            'y' => $sum_y / count($destinations)
        ];

        $distance_between_worst_directions_and_averaged_destination = 0;

        foreach ($destinations as $destination) {
            $distance = $this->calculateDistance($destination, $average_destination);
            if ($distance > $distance_between_worst_directions_and_averaged_destination) {
                $distance_between_worst_directions_and_averaged_destination = $distance;
            }
        }

        printf(
            "%.4f %.4f %.4f\n",
            round($average_destination['x'], 4),
            round($average_destination['y'], 4),
            round(sqrt($distance_between_worst_directions_and_averaged_destination), 4)
        );
    }

    private function start($position, $value) {
        $position['direction'] = $value;
        return $position;
    }

    private function turn($position, $value) {
        $position['direction'] += $value;
        return $position;
    }

    private function walk($position, $value) {
        $position['x'] += $value * cos(deg2rad($position['direction']));
        $position['y'] += $value * sin(deg2rad($position['direction']));
        return $position;
    }

    private function calculateDistance($p1, $p2) {
        return pow($p1['x'] - $p2['x'], 2) + pow($p1['y'] - $p2['y'], 2);
    }
}