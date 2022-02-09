<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
<h1>テーマ「今の気持ち」</h1>
<?php
      #file名を決める
      $filename = 'Mission_3.txt';
      $ed_name = '';
      $ed_comment = '';
      $ed_pass = '';

      #新規投稿
      if(!empty($_POST['name']) and !empty($_POST['comment']) and empty($_POST['edit_flag']) and !empty($_POST['new_password'])){
        $name = $_POST['name'];
        $comment = $_POST['comment'];
        $post_date = date("Y/m/d H:i:s");
        $pass = $_POST['new_password'];
        if(file_exists($filename) and count(file($filename), FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES) > 0){
            $array = file($filename);
            $ele = explode("<>",$array[count($array) - 1]);
            $post_number = $ele[0] + 1;
        }else{   
            $post_number = 1;
        }
        $post_message = $post_number.'<>'.$name.'<>'.$comment.'<>'.$post_date.'<>'.$pass.'<>';
        $fp = fopen($filename, 'a'); 
        fwrite($fp, $post_message . PHP_EOL);
        fclose($fp);
        echo "<hr>"."新規投稿完了！";
	  }
	  #削除
	  elseif(!empty($_POST['delete']) and empty($_POST['name']) and empty($_POST['comment']) and !empty($_POST['del_password'])){
	      $delete = $_POST['delete'];
	      $input_del_pass = $_POST['del_password'];
	      $array = file($filename); #1行1要素の配列変数
          for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
              $ele = explode('<>',$array[$i]);#一行ずつ分割
              if($ele[0] == $delete){
                  $del_num = $ele[0];
                  $del_pass = $ele[4];
              }
          }
	      if($del_pass == $input_del_pass){
              $fp = fopen($filename, 'w');#削除番号以外で新たにテキストファイルを作るのでwriteモードで読み込み
              for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
            	  $ele = explode('<>',$array[$i]);#一行ずつ分割
                  if($ele[0]!=$delete){#投票番号と削除する投稿番号が異なるとき
                      fwrite($fp, $array[$i]);	    
                  }
              }
              fclose($fp);
    	      echo "<hr>".'投稿番号'.$delete.'番削除完了！<br>';	   
          }else{echo "<hr>".'パスワードが違うかもしれません';}
	  }
	  #編集する行の取り出し
	  elseif(!empty($_POST['edit']) and empty($_POST['name']) and empty($_POST['comment']) and empty($_POST['edit_flag']) and !empty($_POST['ed_password'])){
	      $edit = $_POST['edit'];
	      $input_ed_pass =  $_POST['ed_password'];
	      $array = file($filename); #1行1要素の配列変数
          for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
              $ele = explode('<>',$array[$i]);#一行ずつ分割
              if($ele[0] == $edit){
                  $ed_num = $ele[0];
                  $ed_pass = $ele[4];
              }
          }	      
          if($ed_pass == $input_ed_pass){
    	      $fp = fopen($filename, 'r');#
        	  for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
        	      $ele = explode('<>',$array[$i]);#一行ずつ分割
                  if($ele[0] == $edit){#
                      $ed_name = $ele[1];
                      $ed_comment = $ele[2];
                  }
        	  }
        	  fclose($fp);
        	  echo "<hr>".'編集したい項目を編集後、送信ボタンを押してください！';
    	  }
    	  else{echo "<hr>".'パスワードが違うかもしれません';}
	  }
    	  
	  #編集する行の差し替え
	  elseif(!empty($_POST['name']) and !empty($_POST['comment']) and !empty($_POST['edit_flag']) and !empty($_POST['new_password'])){
	      $name = $_POST['name'];
	      $comment = $_POST['comment'];
	      $pass = $_POST['new_password'];
	      $edit_num = $_POST['edit_flag'];
	      $array = file($filename);
          for($i=0;$i<count($array);$i=$i+ 1){#行の数だけ繰り返す
	          $ele=explode("<>",$array[$i]);
              if($ele[0]==$edit_num){
                  $str = "$edit_num<>$name<>$comment<>$ele[3]".'<>'.$pass.'<>'.PHP_EOL;
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
          echo "<hr>"."編集完了！";
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
  　　<!--パスワードの入力ホーム-->
  　パスワード
  　　<input type="password" name="new_password" value='<?php if(isset($ed_pass)){echo $ed_pass;}?>' placeholder='パスワード'>  　　
  　　<!--送信ボタン-->
  　　<input type="submit" name = 'submit' value="送信">
  　　<br><br><hr><br>
  　　<!--消去の入力フォーム-->
  　　　　削除
  　　<input type="number" name = "delete" min="1" placeholder="削除対象番号"><!--削除対象番号を入力-->
  　　<!--パスワードの入力ホーム-->
  　パスワード
  　　<input type="password" name="del_password" placeholder='パスワード'>  　　
  　　<!--削除ボタン-->
  　　<input type="submit" name="submit" value = "削除"><!--削除対象番号を送信-->
  　　<br><br><hr><br>
  　　<!--編集番号指定用フォーム-->
  　　　　編集
  　　<input type="number" name = "edit" min="1" placeholder="編集対象番号"><!--編集対象番号を入力-->
  　　<!--パスワードの入力ホーム-->
  　パスワード
  　　<input type="password" name="ed_password" placeholder='パスワード'>  　　
  　　<!--編集ボタン-->
  　　<input type="submit" name="submit" value = "編集"><!--削除対象番号を送信-->
  　　<!--テキストボックス-->
  　　<input type="hidden" name="edit_flag" size="10" maxlength="10000" value='<?php if(isset($edit)){echo $edit;}?>'>
  　　<br>
　　</form>
    <hr>
    <?php
      if(file_exists($filename)){
          echo '【投稿一覧】<br>';
          $lines = file($filename,FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
          foreach($lines as $line){
              #echo $line.'<br>';
              $lists = explode('<>',$line);
              echo "$lists[0] $lists[1] $lists[2] $lists[3]<br>";
          }
      }
    ?>
</body>
</html>