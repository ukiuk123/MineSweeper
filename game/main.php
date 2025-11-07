<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>マインスイーパー</title>
<style>
body {
    background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
    text-align: center;
}
h1 {
    margin-top: 30px;
    color: #374151;
    letter-spacing: 0.1em;
    font-size: 2.2em;
    text-shadow: 1px 1px 0 #fff, 2px 2px 4px #b0b0b0;
}
.game-board {
    display: inline-block;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(60,60,100,0.15);
    padding: 32px 24px 24px 24px;
    margin-top: 30px;
}
table {
    border-collapse: collapse;
}
td {
    width: 60px;
    height: 60px;
    text-align: center;
    vertical-align: middle;
    border: 2px solid #a5b4fc;
    background: #f1f5f9;
    font-size: 1.8em;
    border-radius: 8px;
    box-shadow: 0 1px 2px #e0e7ff inset;
}
td.opened {
    background: #e0e7ff;
    font-weight: bold;
}
td.bomb {
    background: #fee2e2;
}
button {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #a5b4fc 0%, #93c5fd 100%);
    border: 1.5px solid #64748b;
    border-radius: 8px;
    cursor: pointer;
    box-shadow: 0 2px 4px #c7d2fe;
    margin: 5px;
}
button:hover {
    background: linear-gradient(135deg, #818cf8 0%, #60a5fa 100%);
    box-shadow: 0 4px 8px #a5b4fc;
}
.status {
    margin: 18px 0 10px 0;
    font-size: 1.1em;
    color: #64748b;
    letter-spacing: 0.05em;
}
.clear-message {
    color: #059669;
    font-size: 1.3em;
    font-weight: bold;
    margin-top: 18px;
    text-shadow: 0 1px 0 #fff;
}
a {
    display: inline-block;
    margin: 24px 0 0 0;
    padding: 10px 28px;
    background: #818cf8;
    color: #ffffff;
    border-radius: 8px;
    font-size: 1.1em;
    box-shadow: 0 2px 8px #c7d2fe;
}
a:hover {
    background: #6366f1;
}
</style>
</head>
<body>
<h1>マインスイーパー</h1>
<hr>
<div class="game-board">
<?php
session_start();
// session登録と呼び出し
if(isset($_GET['bombs'])){
    $_SESSION['bombs'] = $_GET['bombs'];
    $bombs = $_SESSION['bombs'];    // 爆弾の個数の変数
} else {
    $bombs = $_SESSION['bombs'];
}

if(isset($_GET['size'])){
    $_SESSION['size'] = $_GET['size'];
    $size = $_SESSION['size'];      // マップサイズの変数
} else {
    $size = $_SESSION['size'];
}

if (isset($_GET['reset']) && $_GET['reset'] == '1') {
    unset($_SESSION['map']);        // マップリセット
}

// 初期マップ生成
if (!isset($_SESSION['map'])) {
    $start_flag = false;
    $map = array_fill(0, $size, array_fill(0, $size, 0)); // size x size の初期行列を設定
    $data = set_bomb($map, $bombs, $size);  // set_bomb関数で爆弾の位置と周囲の数字を設定
    $map = $data[0];
    $bomb_place = $data[1];
    $_SESSION['map'] = $map;
    $_SESSION['bomb_place'] = $bomb_place;

    for($k = 0; $k < $size; $k++){      // マップ内に０となるマスがあるかを探索
        if(in_array('0', array_column($map, $k))){
            $start_flag = true;
        }
    }

    if(!$start_flag){   // ０となるマスがない場合エラー
        echo "<script>alert('初期マスを生成できません。爆弾の数を減らしてください');</script>";
        echo '<script>window.location.href="top.php";</script>';
    }

    while($start_flag){     // ０となるマスの中からランダムに一つ開ける
        $i = rand(0,$size-1);
        $j = rand(0,$size-1);
        $cell_name = $i . '_' . $j;
        for($k = 0; $k < $size; $k++){
            if(in_array('0', array_column($map, $k))){
                if($map[$i][$j] == "0"){
                    $opened[] = $cell_name;
                    $start_flag = false;
                    break;
                }
            }
        }
    }

} else {
    $map = $_SESSION['map'];    // マップの行列
    $bomb_place = $_SESSION['bomb_place'];      // 爆弾の位置の配列
}

if(isset($_POST['opened']) ){
    $opened = $_POST['opened'];     // すでに開けられたマスの配列
} else {
    if(!isset($opened)) $opened = [];
}

if (isset($_POST['open'])) {        // 直前に開けられたマスをすでに開けられたマスの配列に追加
    if (!in_array($_POST['open'], $opened)) {
        $opened[] = $_POST['open'];
    }
}
// マップ描画
echo '<form method="post" action="main.php">';
echo '<div class="status">爆弾: '.$bombs.'　マップサイズ: '.$size.'×'.$size.'</div>';
echo '<table>';

$unopened = array();        // まだ開けられていないマスの配列
$gameover = false;
for ($i = 0; $i < $size; $i++) {
    echo '<tr>';
    for ($j = 0; $j < $size; $j++) {
        $cell_name = $i . '_' . $j;
        $cell_value = $map[$i][$j];
        $td_class = '';
        if (in_array($cell_name, $opened)) {
            if($cell_value == "B"){     // すでに開けられたマスが爆弾だったらゲームオーバー
                $td_class = 'bomb opened';
                $gameover = true;
            } else {
                $td_class = 'opened';
            }
        }
        echo '<td class="'.$td_class.'">';
        if (in_array($cell_name, $opened)) {    // 開けたマスの値を表示
            if($cell_value == "B"){
                echo "💣";
            } else {
                $colors = [
                    1 => "#2563eb",
                    2 => "#16a34a",
                    3 => "#dc2626",
                    4 => "#7c3aed",
                    5 => "#ea580c",
                    6 => "#0891b2",
                    7 => "#be185d",
                    8 => "#52525b"
                ];
                $color = isset($colors[$cell_value]) ? $colors[$cell_value] : "#374151";
                echo '<span style="color:'.$color.';">'.($cell_value == 0 ? "" : $cell_value).'</span>';    // 設定された色と値を表示
            }
        } else {
            echo '<button type="submit" name="open" value="'.$cell_name.'"></button>';      //まだ開けられていないマスにはボタンを表示
            $unopened[] = $cell_name;
        }
        echo '</td>';
    }
    echo '</tr>';
}
echo '</table>';

$is_clear = is_nobombs($bomb_place,$unopened);      // クリアしているかを判定

if($gameover){
    echo '<div class="clear-message">💥 ゲームオーバー！</div>';
} elseif($is_clear){
    echo '<div class="clear-message">🎉 全ての爆弾の場所を特定しました！</div>';
}

foreach ($opened as $cell) {
    echo '<input type="hidden" name="opened[]" value="'.$cell.'">';     // すでに開けられたマスを配列として送る
}
echo '</form>';
?>
</div>
<br>
<a href="top.php">スタート画面に戻る</a>         <!--スタート画面に戻るボタンを表示 -->
</body>
</html>

<?php
function set_bomb($map, $bombs, $size){     // 爆弾位置と周囲の数字を設定する関数
    for($bomb = 0; $bomb < $bombs; $bomb++){
        $i = rand(0, $size - 1);
        $j = rand(0, $size - 1);
        $cell_name = $i . '_' . $j;

        if($map[$i][$j] == 0){      // i,jが爆弾出ないときそのマスに爆弾をセット
            $map[$i][$j] = "B";
            $bomb_place[] = $cell_name;
        } else {
            $bomb--;
        }
    }

    for($i = 0; $i < $size; $i++){      // 爆弾の周囲８マスの値を１増加
        for($j = 0; $j < $size; $j++){
            if($map[$i][$j] == "B"){
                if($i - 1 > -1 && $map[$i-1][$j] != "B"){
                    $map[$i-1][$j] += 1;
                }
                if($j - 1 > -1 && $map[$i][$j-1] != "B"){
                    $map[$i][$j-1] += 1;
                }
                if($i - 1 > -1 && $j - 1 > -1 && $map[$i-1][$j-1] != "B"){
                    $map[$i-1][$j-1] += 1;
                }
                if($i + 1 < $size && $map[$i+1][$j] != "B"){
                    $map[$i+1][$j] += 1;
                }
                if($j + 1 < $size && $map[$i][$j+1] != "B"){
                    $map[$i][$j+1] += 1;
                }
                if($i + 1 < $size && $j + 1 < $size && $map[$i+1][$j+1] != "B"){
                    $map[$i+1][$j+1] += 1;
                }
                if($i + 1 < $size && $j - 1 > -1 && $map[$i+1][$j-1] != "B"){
                    $map[$i+1][$j-1] += 1;
                }
                if($i - 1 > -1 && $j + 1 < $size && $map[$i-1][$j+1] != "B"){
                    $map[$i-1][$j+1] += 1;
                }
            }
        }
    }
    return [$map,$bomb_place];
}

function is_nobombs($bomb_place,$unopened){     // 爆弾以外のマスがすべて開かれたかどうかを判別する関数
    if(count(array_diff($unopened,$bomb_place)) == 0){      // まだ開けられていないマスの配列と爆弾のマスの配列の要素が一致したらtrueを返す
        return true;
    } else {
        return false;
    }
}
?>