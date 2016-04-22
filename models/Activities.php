<?php
/**
 * Created by PhpStorm.
 * User: rogelio
 * Date: 9/12/14
 * Time: 10:10 PM
 */

namespace app\models;


class Activities {

    const TYPE_TIMED = 'timed';
    const TYPE_CONSUMPTION = 'consumption';

    private static $data = [
        1 => [
            'code' => 'Z',
            'name' => 'Sleep time in bed - ASLEEP',
            'type' => self::TYPE_TIMED,
            'image' => 'asleep.png',
            'options' => [],
        ],
        2 => [
            'code' => 'A',
            'name' => 'Sleep time in bed - AWAKE',
            'type' => self::TYPE_TIMED,
            'image' => 'awake.png',
            'options' => [],
        ],
        3 => [
            'code' => 'E',
            'name' => 'Exercise',
            'type' => self::TYPE_TIMED,
            'image' => 'exercise.png',
            'options' => [],
        ],
        4 => [
            'code' => 'R',
            'name' => 'Relaxation Exercise',
            'type' => self::TYPE_TIMED,
            'image' => 'relaxation.png',
            'options' => [],
        ],
        5 => [
            'code' => 'S',
            'name' => 'Stressful Event (Anywhere)',
            'type' => self::TYPE_TIMED,
            'image' => 'stress.png',
            'options' => [],
        ],
        6 => [
            'code' => 'O',
            'name' => 'Sleep time Out of bed',
            'type' => self::TYPE_TIMED,
            'image' => 'outofbed.png',
            'options' => [
                '1' => 'Use bathroom',
                '2' => 'Pain',
                '3' => 'Unknown - Spontaneous',
                '4' => 'Nightmares - VividDreams',
                '5' => 'Environment Stimuli - Noise',
                '6' => 'Environment Stimuli - Lights',
                '7' => 'Environment Stimuli - Change in Room Temperature',
            ],
        ],
        7 => [
            'code' => 'C',
            'name' => 'Caffeine',
            'type' => self::TYPE_CONSUMPTION,
            'image' => 'cafeine.png',
            'options' => [
                1 => "Coffee, brewed, 5 oz",
                2 => "Coffee, instant, 5 oz",
                3 => "Red Bull, 8.2 oz",
                4 => "Sodas and Colas, 12 oz",
                5 => "Tea, black, 5 oz",
                6 => "Tea, green/white, 8 oz",
                7 => "Dark chocolate, 1 oz",
                8 => "Baking chocolate, 1 oz",
                9 => "Milk chocolate, 1 oz",
                10 => "Iced Tea, 12 oz",
                11 => "Espresso, 2 oz",
                12 => "Cappuccino, 8 oz",
                13 => "Latte, 8 oz",
                14 => "Hot cocoa, 8 oz",
                15 => "Chocolate milk, 8 oz",
                16 => "Decaf coffe, 5 oz",
                17 => "Alergy and cold remedies",
                18 => "OTC stimulants",
            ],
        ],
        8 => [
            'code' => 'N',
            'name' => 'Nicotine',
            'type' => self::TYPE_CONSUMPTION,
            'image' => 'nicotine.png',
            'options' => [],
        ],
        9 => [
            'code' => 'M',
            'name' => 'Sleep Medication',
            'type' => self::TYPE_CONSUMPTION,
            'image' => 'medication.png',
            'options' => [],
        ],
        10 => [
            'code' => 'A',
            'name' => 'Alcohol',
            'type' => self::TYPE_CONSUMPTION,
            'image' => 'alcohol.png',
            'options' => [],
        ],
        11 => [
            'code' => 'F',
            'name' => 'Food',
            'type' => self::TYPE_CONSUMPTION,
            'image' => 'food.png',
            'options' => [],
        ],
        12 => [
            'code' => 'TVB',
            'name' => 'TV - Watching in Bed',
            'type' => self::TYPE_TIMED,
            'image' => 'tvinbed.png',
            'options' => [],
        ],
        13 => [
            'code' => 'TVO',
            'name' => 'TV - Watching out of Bed',
            'type' => self::TYPE_TIMED,
            'image' => 'tvoutofbed.png',
            'options' => [],
        ],
        14 => [
            'code' => 'RE',
            'name' => 'Reading',
            'type' => self::TYPE_TIMED,
            'image' => 'reading.png',
            'options' => [],
        ],
    ];

    public static function getActivity($id) {
        return isset(self::$data[$id]) ? self::$data[$id] : null;
    }

    public static function getActivitiesDropDown() {
        $result = [];
        foreach(self::$data as $id => $data) {
            $result[$id] = $data['name'];
        }
        return $result;
    }

    private static $caffeine = [
        1 => 120,
        2 => 75,
        3 => 80,
        4 => 45,
        5 => 47,
        6 => 18,
        7 => 20,
        8 => 30,
        9 => 6,
        10 => 70,
        11 => 85,
        12 => 20,
        13 => 60,
        14 => 21,
        15 => 5,
        16 => 3,
        17 => 28,
        18 => 150,
    ];

    public static function getMgCaffeine($option)
    {
        return isset(self::$caffeine[$option]) ? self::$caffeine[$option] : 0;
    }

}