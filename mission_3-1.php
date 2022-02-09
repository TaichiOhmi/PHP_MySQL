<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-1</title>
</head>
<body>
    <form method="POST">
  　　名前
  　　<input type="text" name="name" required><!--requiredで入力必須にする-->
  　　<br>
  　　コメント
  　　<input type="text" name="comment" required>
  　　<br>
  　　<input type="submit" value="送信">
　　</form>
    
    <?php
      if(!empty($_POST['name']) and !empty($_POST['comment'])){
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $post_date = date("Y/m/d H:i:s");
        $filename = 'mission_3.txt';
        $fp = fopen($filename, 'a'); 
        $lines = count(file($filename)); #fileの行数をカウントしてlinesに代入
        $post_number = $lines + 1;#numの中身に1を足す
        $post_message = $post_number.'<>'.$name.'<>'.$comment.'<>'.$post_date;
        fwrite($fp, $post_message . PHP_EOL);
        fclose($fp);
        
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        foreach($lines as $line){
            echo $line.'<br>';
        }
      }
    ?>
</body>
</html>