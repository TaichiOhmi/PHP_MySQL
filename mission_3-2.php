<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-2</title>
</head>
<body>
    <form method="POST">
  　　<label for="name">名前</label>
  　　<input type="text" name="name" required><!--requiredで入力必須にする-->
  　　<br>
  　　<label for="comment">コメント</label>
  　　<input type="text" name="comment" required>
  　　<br>
  　　<input type="submit" value="送信">
　　</form>
    
    <?php
      $filename = 'mission_3.txt';
      $lines = file($filename, FILE_IGNORE_NEW_LINES);
      foreach($lines as $line){
          $lists = explode('<>',$line);
          foreach($lists as $list){
            echo $list.'<br>';
          }
      }
    
      if(!empty($_POST['name']) and !empty($_POST['comment'])){
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $post_date = date("Y/m/d H:i:s");
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