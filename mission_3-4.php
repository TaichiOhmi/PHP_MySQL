<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-4</title>
</head>
<body>
<?php
      #file名を決める
      $filename = 'mission_3.txt';
      $ed_name = '';
      $ed_comment = '';
      #新規投稿
      if(!empty($_POST['name']) and !empty($_POST['comment']) and empty($_POST['edit_flag'])){
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $post_date = date("Y/m/d H:i:s");
        if(file_exists($filename)){
            $array = file($filename);
            $ele = explode("<>",$array[count($array) - 1]);
            $post_number = $ele[0] + 1;
        }else{   
            $post_number = 1;
        }
        $post_message = $post_number.'<>'.$name.'<>'.$comment.'<>'.$post_date;
        $fp = fopen($filename, 'a'); 
        fwrite($fp, $post_message . PHP_EOL);
        fclose($fp);
        echo '新規投稿完了！';
	  }
	  #削除
	  elseif(!empty($_POST['delete']) and empty($_POST['name']) and empty($_POST['comment'])){
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
	       echo '投票番号'.$delete.'番削除完了！<br>';
	  }
	  #編集する行の取り出し
	  elseif(!empty($_POST['edit']) and empty($_POST['name']) and empty($_POST['comment'] and empty($_POST['edit_flag']))){
	      $edit = $_POST['edit'];
	      $array = file($filename); #1行1要素の配列変数
	      $fp = fopen($filename, 'r');#
	      for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
	          $ele = explode('<>',$array[$i]);#一行ずつ分割
              if($ele[0]==$edit){#
                  $ed_name = $ele[1];
                  $ed_comment = $ele[2];
              }
	      }
	      fclose($fp);
	      echo '編集して送信してください';
	  }
	  #編集する行の差し替え
	  elseif(!empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['edit_flag'])){
	      $name = $_POST['name'];
	      $comment = $_POST['comment'];
	      $edit_num = $_POST['edit_flag'];
	      $array = file($filename);
          for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
	          $ele=explode("<>",$array[$i]);
              if($ele[0]==$edit_num){
                  $str = "$edit_num<>$name<>$comment<>$ele[3]";
                  $date = $ele[3];
              }
          }
          for($j=0;$j<count($array);$j=$j+ 1){
              if(explode('<>',$array[$j])[3]==$date){
                  $array[$j] = $str;
              }
          }
          
          #if(explode("<>",$array[$edit_num - 1])[3]==$date){
          #    $array[$edit_num - 1] = $str;
          #}
          #$array[count($array)] = $array[count($array)].'<br>';
          file_put_contents($filename , $array);
          echo '編集が完了しました！';
	  }
	  else{
	  }
	  echo '<hr>';
    ?>
    <form method="POST">
      <br>
      <!--名前の入力フォーム-->
      　　　　　 　名前
  　　<input type="text" name="name" value='<?php if(isset($ed_name)){echo $ed_name;}?>' placeholder="名前"><!--requiredで入力必須にする-->
  　　<br>
  　　<!--コメントの入力フォーム-->
  　　コメント
  　　<input type="text" name="comment" value='<?php if(isset($ed_comment)){echo $ed_comment;}?>' placeholder="コメント">
  　　<br>
  　　<!--送信ボタン-->
  　　　　　　　　　　　　　　　　
  　　<input type="submit" name = 'submit' value="送信">
  　　<br>
  　　<!--消去の入力フォーム-->
  　　　　削除
  　　<input type="number" name = "delete" min='1' placeholder="削除対象番号"><!--削除対象番号を入力-->
  　　<br>
  　　<!--削除ボタン-->
  　　　　　　　　　　　　　　　　
  　　<input type="submit" name="submit" value = "削除"><!--削除対象番号を送信-->
  　　<br>
  　　<!--編集番号指定用フォーム-->
  　　　　編集
  　　<input type="number" name = "edit" min='1' placeholder="編集対象番号"><!--編集対象番号を入力-->
  　　<br>
  　　<!--編集ボタン-->
  　　　　　　　　　　　　　　　　
  　　<input type="submit" name="submit" value = "編集"><br><!--削除対象番号を送信-->
  　　<!--テキストボックス-->
  　　<input type="hidden" name="edit_flag" size="10" maxlength="10000" value='<?php if(isset($edit)){echo $edit;}?>'>
　　</form>
    <hr>
    <?php
      if(file_exists($filename)){
          echo '【投稿一覧】<br>';
          $lines = file($filename,FILE_SKIP_EMPTY_LINES);
          foreach($lines as $line){
              #echo $line.'<br>';
              $lists = explode('<>',$line);
              echo "$lists[0] $lists[1] $lists[2] $lists[3]<br>";
          }
      }
    ?>
</body>
</html>