<!DOCTYPE html>
<html lang="ja">
<head>
<style>
    h1 {
        margin-top: 30px;
        color: #374151;
        letter-spacing: 0.1em;
        font-size: 2.2em;
        text-shadow: 1px 1px 0 #fff, 2px 2px 4px #b0b0b0;
        text-align: center;
    }
    label {
        margin: 18px 0 10px 0;
        font-size: 1.1em;
        color: #64748b;
        letter-spacing: 0.05em;
        text-align: left;
    }
    form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    p {
        margin: 18px 0 10px 0;
        text-align: center;
        font-size: 1.5em;
        color: #3c4653ff;
        letter-spacing: 0.05em;
    }
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        
    }
    button {
        display: inline-block;
        margin: 24px 0 0 0;
        padding: 10px 28px;
        background: #818cf8;
        color: #ffffff;
        border-radius: 8px;
        font-size: 1.1em;
        box-shadow: 0 2px 8px #c7d2fe;
        cursor: pointer;
    }
    button:hover {
        background: #6366f1;
    }
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 0.9em;
        letter-spacing: 0.05em;
        color: #2b323bff;
    }
    .flex {
        display: flex;
        padding: 10px;
        text-align: center;
    }
    .flex div {
        width: 50%;
        margin: 10px;
        padding: 10px;
    }
    .exp {
        text-align: left;
        font-size: 1.2em;
        color: #3c4653ff;
        letter-spacing: 0.05em;
    }
</style>
<meta charset=UTF-8">
<title>
web game
</title>
</head>
<body>
<h1>
マインスイーパー
</h1>
<hr>
<!--ゲームの初期設定 -->
<form method="get" action="main.php" onsubmit="return cancel()">
    <label>マップサイズ:
    <input type="number" id="size" name="size" min="1" value="9"></label>
    <br>
    <label>爆弾数:
    <input type="number" id="bombs" name="bombs" min="1" value="10"></label>
    <br>
    <input type="hidden" name="reset" value="1">
    <button type="submit">ゲームスタート</button>
</form>
<script type="text/javascript">
    function cancel(){      // マスの個数より爆弾の個数が多いとエラーを返す
        var size = document.getElementById('size').value;
        var bombs = document.getElementById('bombs').value;
        if(size ** 2 < bombs){
            alert('マップサイズに対して爆弾の数が多すぎます。爆弾の数を減らしてください');
            return false;
        }
    }
</script>
<p>遊び方</p>
<hr>
<div class="flex">
    <div><img src="image.png"></div>
    <div class="exp">
            ・すべての爆弾の位置以外のマスを開けることを目指すゲームです。
        <br>・安全なマスをクリックすると、隣接する8マスにある爆弾の数が数字で表示されます。
        <br>・空白のマスは隣接する８マスは安全なことを示します。
        <br>・爆弾の位置を開くとゲームオーバーです。
    </div>
</div>
</body>
</html>