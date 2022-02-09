<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-3</title>
</head>
<body>
    <form method="POST">
      <br>
      <!--名前の入力フォーム-->
  　　<label for="name">名前</label><br>
  　　<input type="text" name="name" placeholder="名前"><!--requiredで入力必須にする-->
  　　<br><br>
  　　<!--コメントの入力フォーム-->
  　　<label for="comment">コメント</label>
  　　<input type="text" name="comment" placeholder="コメント">
  　　<br>
  　　<!--送信ボタン-->
  　　<input type="submit" value="送信">
  　　<br><br>
  　　<!--消去の入力フォーム-->
  　　<label for="comment">削除</label>
  　　<input type="number" name = "delete" placeholder="削除対象番号"><!--削除対象番号を入力-->
  　　<br>
  　　<!--削除ボタン-->
  　　<input type="submit" name="submit" value = "削除"><!--削除対象番号を送信-->
　　</form>
    <hr>
    <?php
      $filename = 'mission_3.txt';
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
	  elseif(!empty($_POST['delete'])){
	      $delete = $_POST['delete'];
	      $array = file($filename); #1行1要素の配列変数
	      $fp = fopen($filename, 'w');#削除番号以外で新たにテキストファイルを作るのでwriteモードで読み込み
	      for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
	          $ele = explode('<>',$array[$i]);#一行ずつ分割
              if($ele[0]!=$delete){#投票番号と削除する投稿番号が異なるとき
                  fwrite($fp, $array[$i]);
              }
	      }
	       fclose($fp);
	       echo '投票番号'.$delete.'番削除完了！<br><hr>';
	       echo '【投稿一覧】<br>';
	       $lines = file($filename);
	       foreach($lines as $line){
              echo $line.'<br>';
           }
	  }
	  else{
	      echo '【過去の投稿】<br>';
          $lines = file($filename, FILE_IGNORE_NEW_LINES);
          foreach($lines as $line){
              $lists = explode('<>',$line);
              echo "$lists[0] $lists[1] $lists[2] $lists[3] <br>"  ;
              #foreach($lists as $list){
              #echo $list.'<br>';
             #}
          }
	  }
	  echo '<hr>';
    ?>
</body>
</html>