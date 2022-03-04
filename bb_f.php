<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
<h1>テーマ「題名！」</h1>
<?php
    //PHPエラーを非表示
    error_reporting(0);

    #変数定義
    $filename = 'm3_new.txt';
    $name = filter_input(INPUT_POST, 'name');
    $comment = filter_input(INPUT_POST, 'comment');
    $new_password = filter_input(INPUT_POST, 'new_password');
    $delete = filter_input(INPUT_POST, 'delete');
    $input_del_pass = filter_input(INPUT_POST, 'del_password');
    $edit = filter_input(INPUT_POST, 'edit');
    $input_ed_pass = filter_input(INPUT_POST, 'ed_password');
    $edit_flag = filter_input(INPUT_POST, 'edit_flag');
    $post_date = date("Y/m/d H:i:s");
    unset($ed_name);
    unset($ed_comment);
    unset($ed_pass);
    if(file_exists($filename)){
        $array = file($filename);}
    
    #新規投稿
    if(!empty( $name & $comment & $new_password ) && empty( $edit_flag )){
        if(file_exists($filename) and count(file($filename), FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES) > 0){
            $end_row = end($array);
            $ele = explode("<>", $end_row);
            $post_number = $ele[0] + 1;
        }
        else{   
            $post_number = 1;
        }
        $post_message = $post_number.'<>'.$name.'<>'.$comment.'<>'.$post_date.'<>'.$new_password.'<>';
        file_put_contents($filename, $post_message.PHP_EOL, FILE_APPEND);
        echo "<hr>"."新規投稿完了！";
	}
	
    #削除
    elseif(!empty( $delete & $input_del_pass) && empty( $name & $comment )){
        foreach($array as $line){
            $ele = explode('<>',$line);#一行ずつ分割
            if($ele[0]==$delete){
                $dp = $ele[4];
            }
        }
        if( $dp == $input_del_pass ){#削除したい行のパスワードと入力されたパスワードが同じ時、
            $fp = fopen($filename, 'w');#削除番号以外で新たにテキストファイルを作るのでwriteモードで読み込み
            foreach($array as $line){#行の数だけ繰り返す
                $ele = explode('<>', $line);#一行ずつ分割
                if($ele[0]!=$delete){#投票番号と削除する投稿番号が異なるとき
                    fwrite($fp, $line);#その行を書き込む
                }#投票番号と削除する投稿番号が同じ時は書き込まない→結果的に削除できている。
            }
            fclose($fp);
            echo "<hr>".'投稿番号'.$delete.'番削除完了！<br>';
        }
        else{echo "<hr>".'パスワードが違うかもしれません';}
    }
    
    #編集する行の取り出し
    elseif(!empty( $edit & $input_ed_pass ) && empty( $name & $comment & $edit_flag)){
        foreach($array as $line){
            $ele = explode('<>',$line);#一行ずつ分割
            if($ele[0]==$edit){
                $ep = $ele[4];
            }
        }
        if($ep == $input_ed_pass){
            $fp = fopen($filename, 'r');#
            foreach($array as $line){#行の数だけ繰り返す
                $ele = explode('<>', $line);#一行ずつ分割
                if($ele[0] == $edit){#
                    $ed_name = $ele[1];
                    $ed_comment = $ele[2];
                    $ed_pass = $ele[4];
                }
            }
            fclose($fp);
            echo "<hr>".'編集したい項目を編集後、送信ボタンを押してください！';
        }
        else{echo "<hr>".'パスワードが違うかもしれません';}
	}
    	  
    #編集する行の差し替え
    elseif(!empty($name & $comment & $new_password & $edit_flag)){
        for($i=0;$i<count($array);$i++){#配列を一行ずつ取り出す
            $ele=explode("<>",$array[$i]);#行を要素に分割
            if($ele[0]==$edit_flag){#投稿番号が編集番号と同じ時、
                $array[$i] = "$edit_flag<>$name<>$comment<>$ele[3]<>$new_password<>".PHP_EOL;#$strに編集した後の文を作っておく。
            }
        }
        file_put_contents($filename , $array);#ファイルに書き込む
        echo "<hr>"."編集完了！";
	}
	
	echo '<hr>';
	
    ?>
<form method="POST">
    <br>
    <!--名前の入力フォーム-->
    <label for="name">　　　　　名前：</label>
    <input type="text" name="name" value='<?php if(isset($ed_name)){echo $ed_name;}?>' placeholder="名前"><!--requiredで入力必須にする-->
    <br>
    <!--コメントの入力フォーム-->
    <label for="comment">　　　コメント：</label>
    <input type="text" name="comment" value='<?php if(isset($ed_comment)){echo $ed_comment;}?>' placeholder="コメント">
    <br>
    <!--新規投稿パスワードの入力ホーム-->
    <label for="new_password">　　パスワード：</label>
    <input type="password" name="new_password" value='<?php if(isset($ed_pass)){echo $ed_pass;}?>' placeholder='パスワード'>  　　
    <!--送信ボタン-->
    <input type="submit" name = 'submit' value="送信">
    
    <br><br><hr><br>
    <!--削除番号の入力フォーム-->
    <label for="delete">削除したい番号：</label>
    <input type="number" name = "delete" min="1" placeholder="削除対象番号"><!--削除対象番号を入力-->
    <br>
    <!--削除パスワードの入力ホーム-->
    <label for="del_password">　　パスワード：</label>
    <input type="password" name="del_password" placeholder='パスワード'>  　　
    <!--削除ボタン-->
    <input type="submit" name="submit" value = "削除"><!--削除対象番号を送信-->
    <br><br><hr><br>
    
    <!--編集番号指定用フォーム-->
    <label for="edit">編集したい番号：</label>
    <input type="number" name = "edit" min="1" placeholder="編集対象番号"><!--編集対象番号を入力-->
    <br>
    <!--パスワードの入力ホーム-->
    <label for="ed_password">　　パスワード：</label>
    <input type="password" name="ed_password" placeholder='パスワード'>  　　
    <!--編集ボタン-->
    <input type="submit" name="submit" value = "編集"><!--編集対象番号を送信-->
    <br>
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
