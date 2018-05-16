<?php

class Poker
{
    /**
     * @var array
     */
    protected $colors = array(
        'spade'   => 0,
        'heart'   => 1,
        'club'    => 2,
        'diamond' => 3,
    );
    /**
     * @var array
     */
    protected $figures = array(
        '2'  => 0,
        '3'  => 1,
        '4'  => 2,
        '5'  => 3,
        '6'  => 4,
        '7'  => 5,
        '8'  => 6,
        '9'  => 7,
        '10' => 8,
        'J'  => 9,
        'Q'  => 10,
        'K'  => 11,
        'A'  => 12,
    );
    /**
     * @var array
     */
    protected $type = array(
        'bz'  => 0,
        'ths' => 1,
        'th'  => 2,
        'sz'  => 3,
        'dz'  => 4,
        'dp'  => 5,
    );
    /**
     * @var array
     */
    protected $cards;

    public function shuffleCards()
    {
        $cards = array();
        foreach ($this->colors as $color => $value) {
            foreach ($this->figures as $figure => $value2) {
                $cards[] = array($color, $figure);
            }
        }
        shuffle($cards);
        $this->cards = $cards;
    }

    /**
     * 判断牌的种类
     * @param $cards
     * @return array
     */
    public function checkCards($cards)
    {
        // 冒泡排序
        $max = sizeof($cards) - 1;
        for ($j = 0; $j < $max; $j++) {
            for ($i = 0; $i < $max - $j; $i++) {
                if ($this->figures[$cards[$i][1]] < $this->figures[$cards[$i + 1][1]]) {
                    $card          = $cards[$i];
                    $cards[$i]     = $cards[$i + 1];
                    $cards[$i + 1] = $card;
                }
            }
        }
        $bz          = true;
        $th          = true;
        $sz          = true;
        $dz          = false;
        $color_flag  = '';
        $figure_flag = 0;

        $figures = array();
        $colors  = array();
        foreach ($cards as $key => $card) {
            $figures[$key] = $figure = $this->figures[$card[1]];
            $colors[$key]  = $color  = $this->colors[$card[0]];
            if (!$key) {
                $color_flag  = $color;
                $figure_flag = $figure;
            } else {
                // 同花
                if ($color != $color_flag) {
                    $th = false;
                }
                // 豹子
                if ($figure != $figure_flag) {
                    $bz = false;
                }
                // 对子
                if ($figure == $figure_flag) {
                    $dz = true;
                }
                // 顺子
                if ($figures[$key - 1] - 1 != $figure) {
                    $sz = false;
                }
            }
        }
        // 顺子
        if ($figures == array(12, 1, 0)) {
            $figures = array(1, 0, -1);
            $sz      = true;
        }
        // 对子
        $dz = $dz && !$bz;
        // 同花顺
        $ths = $th && $sz;
        // 牌型
        // $type = 'dp';
        $type = 5;
        foreach ($this->type as $key => $value) {
            if (isset($$key) && $$key) {
                $type = $this->type[$key];
                break;
            }
        }
        return compact('type', 'figures', 'colors');
    }

    /**
     * @return array|bool
     */
    public function pickCards($number = 3)
    {
        if (empty($this->cards)) {
            $this->shuffleCards();
        }
        if (sizeof($this->cards) < $number) {
            return false;
        }
        $cards = array();
        for ($i = 0; $i < $number; $i++) {
            $cards[] = array_shift($this->cards);
        }
        return $cards;
    }

    /**
     * 牌组大小
     * @param $player1
     * @param $player2
     * @return bool
     */
    public function compare($player1, $player2)
    {
        $type1 = $player1['check']['type'];
        $type2 = $player2['check']['type'];
        if ($type1 != $type2) {
            return $type1 < $type2;
        }
        $figure1 = $player1['check']['figures'];
        $figure2 = $player2['check']['figures'];
        $color1  = $player1['check']['colors'];
        $color2  = $player2['check']['colors'];

        switch ($type1) {
            case 0:
                return $figure1[0] > $figure2[0];
                break;
            case 1:
            case 3:
                if ($figure1 == $figure2) {
                    return $color1[0] < $color2[0];
                } else {
                    return $figure1[0] > $figure2[0];
                }
                break;
            case 4:
                $dui1 = $figure1[1];
                $dui2 = $figure2[1];
                if ($dui1 != $dui2) {
                    return $dui1 > $dui2;
                }
                $dui1 = array_sum($figure1) - $dui1;
                $dui2 = array_sum($figure2) - $dui2;
                if ($dui1 == $dui2) {
                    $i1 = $figure1[0] == $dui1 ? 2 : 0;
                    $i2 = $figure2[0] == $dui2 ? 2 : 0;
                    return $color1[$i1] < $color2[$i2];
                } else {
                    return $dui1 > $dui2;
                }
                break;
            case 2:
            default:
                if ($figure1[0] == $figure2[0]) {
                    return $color1[0] < $color2[0];
                } else {
                    return $figure1[0] > $figure2[0];
                }
                break;
        }
    }

    public function play($number = 3)
    {
        if ($number < 2 || $number > 17) {
            $number = 2;
        }
        $this->shuffleCards();
        $players = array();
        for ($i = 0; $i < $number; $i++) {
            $cards     = $this->pickCards();
            $players[] = array(
                'cards' => $cards,
                'check' => $this->checkCards($cards),
            );
        }
        return $this->order($players);
    }

    public function order($players)
    {
        $max = sizeof($players) - 1;
        for ($j = 0; $j < $max; $j++) {
            for ($i = 0; $i < $max - $j; $i++) {
                if ($this->compare($players[$i + 1], $players[$i])) {
                    $player          = $players[$i];
                    $players[$i]     = $players[$i + 1];
                    $players[$i + 1] = $player;
                }
            }
        }
        return $players;
    }
    protected static function combination($array, $number)
    {
        $r = array();
        $n = count($array);
        if ($number <= 0 || $number > $n) {
            return $r;
        }
        for ($i = 0; $i < $n; $i++) {
            $t = array($array[$i]);
            if ($number == 1) {
                $r[] = $t;
            } else {
                $b = array_slice($array, $i + 1);
                $c = self::combination($b, $number - 1);
                foreach ($c as $v) {
                    $r[] = array_merge($t, $v);
                }
            }
        }
        return $r;
    }
}
class NiuNiu extends Poker
{
    /**
     * [$figures description]
     * @var array
     */
    protected $figures = array(
        'A'  => 1,
        '2'  => 2,
        '3'  => 3,
        '4'  => 4,
        '5'  => 5,
        '6'  => 6,
        '7'  => 7,
        '8'  => 8,
        '9'  => 9,
        '10' => 10,
        'J'  => 11,
        'Q'  => 12,
        'K'  => 13,
    );
    /**
     * @var array
     */
    protected $type = array(
        'wx' => 0,
        'zd' => 1,
        'wh' => 2,
        'sh' => 3,
        'nn' => 4,
        'n9' => 5,
        'n8' => 6,
        'n7' => 7,
        'n6' => 8,
        'n5' => 9,
        'n4' => 10,
        'n3' => 11,
        'n2' => 12,
        'n1' => 13,
        'mn' => 14,
    );
    public function checkCards($cards)
    {
        // 冒泡排序
        $max = sizeof($cards) - 1;
        for ($j = 0; $j < $max; $j++) {
            for ($i = 0; $i < $max - $j; $i++) {
                if ($this->figures[$cards[$i][1]] < $this->figures[$cards[$i + 1][1]]) {
                    $card          = $cards[$i];
                    $cards[$i]     = $cards[$i + 1];
                    $cards[$i + 1] = $card;
                }
            }
        }
        $h  = 0;
        $yn = false;

        $figures = array();
        $colors  = array();

        $figures_flag  = array();
        $figures_count = array();
        foreach ($cards as $key => $card) {
            $figure = $this->figures[$card[1]];
            if (isset($figures_count[$figure])) {
                $figures_count[$figure]++;
            } else {
                $figures_count[$figure] = 1;
            }
            if ($figure > 10) {
                $h++;
            } else if ($figure < 10) {
                $fit = false;
                foreach ($figures_flag as $k => $v) {
                    if ($figure + $v == 10) {
                        $fit = true;
                        unset($figures_flag[$k]);
                        break;
                    }
                }
                if (!$fit) {
                    $figures_flag[] = $figure;
                }
            }
            $figures[$key] = $figure;
            $colors[$key]  = $this->colors[$card[0]];
        }
        $res = array_sum($figures_flag) % 10;
        if (sizeof($figures_flag) < 3) {
            $yn = true;
        } else {
            rsort($figures_flag);
            $data = self::combination($figures_flag, 3);
            foreach ($data as $value) {
                if (array_sum($value) % 10 == 0) {
                    $yn = true;
                    break;
                }
            }
        }
        $wh = $h == 5;
        $sh = !$res && $h == 4;
        $wx = array_sum($figures) < 10;
        $zd = false;
        if (sizeof($figures_count) < 3) {
            foreach ($figures_count as $value) {
                if ($value == 4) {
                    $zd = true;
                    break;
                }
            }
        }
        // 牌型
        $type = 'mn';
        foreach ($this->type as $key => $value) {
            if (isset($$key) && $$key) {
                $type = $key;
            }
        }
        if ($yn && $type == 'mn') {
            if (!$res) {
                $type = 'nn';
            } else {
                $type = 'n' . $res;
            }
        }
        $type = $this->type[$type];
        return compact('type', 'figures', 'colors');
    }
    /**
     * 牌组大小
     * @param $player1
     * @param $player2
     * @return bool
     */
    public function compare($player1, $player2)
    {
        $type1 = $player1['check']['type'];
        $type2 = $player2['check']['type'];
        if ($type1 != $type2) {
            return $type1 < $type2;
        }
        $figure1 = $player1['check']['figures'];
        $figure2 = $player2['check']['figures'];
        $color1  = $player1['check']['colors'];
        $color2  = $player2['check']['colors'];

        if ($figure1[0] == $figure2[0]) {
            return $color1[0] < $color2[0];
        } else {
            return $figure1[0] > $figure2[0];
        }
    }
    public function play($number = 3)
    {
        if ($number < 2 || $number > 10) {
            $number = 2;
        }
        $this->shuffleCards();
        $players = array();
        for ($i = 0; $i < $number; $i++) {
            $cards     = $this->pickCards(5);
            $players[] = array(
                'cards' => $cards,
                'check' => $this->checkCards($cards),
            );
        }
        return $this->order($players);
    }
}
class DeZhou extends Poker
{
    /**
     * @var array
     */
    protected $type = array(
        'thds' => 0,
        'ths'  => 1,
        't4'   => 2,
        'hl'   => 3,
        'th'   => 4,
        'sz'   => 5,
        't3'   => 6,
        'd2'   => 7,
        'd1'   => 8,
        'gp'   => 9,
    );
    public $gp = array();
    public function checkCards($cards)
    {
        if (sizeof($cards) == 5) {
            $th          = true;
            $sz          = true;
            $t4          = false;
            $t3          = false;
            $color_flag  = false;
            $figure_flag = false;
            $figures     = array();
            $colors      = array();
            foreach ($cards as $key => $value) {
                $colors[$key]  = $color  = $this->colors[$value[0]];
                $figures[$key] = $figure = $this->figures[$value[1]];
                if (!$key) {
                    $color_flag = $color;
                } else {
                    if ($color_flag != $color) {
                        $th = false;
                    }
                    if ($figures[$key - 1] - 1 != $figure) {
                        $sz = false;
                    }
                }
            }
            $d = 0;
            foreach (array_count_values($figures) as $key => $value) {
                switch ($value) {
                    case 4:
                        $t4 = true;
                        break;
                    case 3:
                        $t3 = true;
                        break;
                    case 2:
                        $d++;
                        break;
                    default:
                        break;
                }
            }
            if ($figures == array(12, 3, 2, 1, 0)) {
                $sz = true;
            }
            $d2   = $d == 2;
            $d1   = $d == 1;
            $ths  = $th && $sz;
            $hl   = $t3 && $d1;
            $thds = $ths && $figures == array(12, 11, 10, 9, 8);
            $type = 'gp';
            foreach ($this->type as $key => $value) {
                if (isset($$key) && $$key) {
                    $type = $key;
                    break;
                }
            }
            $type = $this->type[$type];
            return compact('type', 'figures', 'colors');
        } else {
            $cards = array_merge($cards, $this->gp);
            // 冒泡排序
            $max = sizeof($cards) - 1;
            for ($j = 0; $j < $max; $j++) {
                for ($i = 0; $i < $max - $j; $i++) {
                    if ($this->figures[$cards[$i][1]] < $this->figures[$cards[$i + 1][1]]) {
                        $card          = $cards[$i];
                        $cards[$i]     = $cards[$i + 1];
                        $cards[$i + 1] = $card;
                    }
                }
            }
            $cards = self::combination($cards, 5);
            foreach ($cards as $key => $value) {
                $cards[$key] = array(
                    'cards' => $value,
                    'check' => $this->checkCards($value),
                );
            }
            $max = sizeof($cards) - 1;
            for ($j = 0; $j < $max; $j++) {
                for ($i = 0; $i < $max - $j; $i++) {
                    if ($this->compare($cards[$i + 1], $cards[$i])) {
                        $card          = $cards[$i];
                        $cards[$i]     = $cards[$i + 1];
                        $cards[$i + 1] = $card;
                    }
                }
            }
            return $cards[0]['check'];
        }
    }
    public function compare($player1, $player2)
    {
        $type1 = $player1['check']['type'];
        $type2 = $player2['check']['type'];
        if ($type1 != $type2) {
            return $type1 < $type2;
        }
        $figures1 = $player1['check']['figures'];
        $figures2 = $player2['check']['figures'];
        if ($figures1 == $figures2) {
            return false;
        }
        $color1 = $player1['check']['colors'];
        $color2 = $player2['check']['colors'];

        switch ($type1) {
            case 7:
                // 两对
                $figures1 = array_count_values($figures1);
                $figures2 = array_count_values($figures2);
                arsort($figures1);
                arsort($figures2);
                $figures1 = array_keys($figures1);
                $figures2 = array_keys($figures2);
                if ($figures1[1] > $figures1[2]) {
                    $value       = $figures1[1];
                    $figures1[1] = $figures1[2];
                    $figures1[2] = $value;
                }
                if ($figures2[1] > $figures2[2]) {
                    $value       = $figures2[1];
                    $figures2[1] = $figures2[2];
                    $figures2[2] = $value;
                }
                break;
            case 2:
            // 四条
            case 3:
            // 葫芦
            case 6:
            // 三条
            case 8:
                // 一对
                $figures1 = array_count_values($figures1);
                $figures2 = array_count_values($figures2);
                arsort($figures1);
                arsort($figures2);
                $figures1 = array_keys($figures1);
                $figures2 = array_keys($figures2);
                break;
            case 5:
            // 同花顺
            case 1:
                //顺子
                if ($figures1 == array(12, 3, 2, 1, 0)) {
                    return false;
                }
                if ($figures2 == array(12, 3, 2, 1, 0)) {
                    return true;
                }
                return $figures1[0] > $figures2[0];
                break;
            case 0:
            // 皇家同花顺
            case 4:
            // 同花
            default:
                // 高牌
                break;
        }
        foreach ($figures1 as $key => $value) {
            if ($value != $figures2[$key]) {
                return $value > $figures2[$key];
            }
        }
        return false;
    }
    public function play($number = 2)
    {
        if ($number < 2 || $number > 23) {
            $number = 2;
        }
        if (empty($this->cards)) {
            $this->shuffleCards();
        }
        $this->flop();
        $players = array();
        for ($i = 0; $i < $number; $i++) {
            $cards     = $this->pickCards(2);
            $players[] = array(
                'cards' => $cards,
                'check' => $this->checkCards($cards),
            );
        }
        return $this->order($players);
    }
    public function flop($cards = array())
    {
        if (empty($cards)) {
            $this->gp = array_merge($this->gp, $this->pickCards(5 - sizeof($this->gp)));
        } else {
            if (empty($this->cards)) {
                $this->shuffleCards();
            }
            foreach ($this->cards as $key => $value) {
                if (in_array($value, $cards)) {
                    unset($this->cards[$key]);
                }
            }
            $this->gp = $cards;
        }
    }
}