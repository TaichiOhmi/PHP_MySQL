<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
<h1>「掲示バーン！！」</h1>
<?php
    # DB接続設定 
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    #PDO::ATTR_ERRMODE, SQL実行でエラーが起こった際にどう処理するかを指定.デフォルトは PDO::ERRMODE_SILENT 
    #PDO::ERRMODE_WARNING はSQLで発生したエラーをPHPのWarningとして報告
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));#PDOは「PHP Data Objects」
    #$pdo = new PDO('mysql:dbname=tb230970db;host=localhost', 'tb-230970', 'NXVKSHRscD', [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
    
    #もし、このテーブルが存在していなかったらこのテーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS tb1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "post_date datetime,"
    . "password TEXT"
    .");";
    #id<>name<>comment<>date<>password<>
    #ユーザー入力を伴わないクエリに関しては単に PDO::query メソッドを実行すればいい．返り値は PDOStatement となります．
    $stmt = $pdo->query($sql);
?>
<?php
    #変数定義
    $name = filter_input(INPUT_POST, 'name');
    $comment = filter_input(INPUT_POST, 'comment');
    $new_password = filter_input(INPUT_POST, 'new_password');
    $delete = filter_input(INPUT_POST, 'delete');
    $del_password = filter_input(INPUT_POST, 'del_password');
    $edit = filter_input(INPUT_POST, 'edit');
    $ed_password = filter_input(INPUT_POST, 'ed_password');
    $edit_flag = filter_input(INPUT_POST, 'edit_flag');
    $date = new DateTime();
    $date = $date->format('Y-m-d H:i:s');
    
    #新規投稿
    if (!empty($name) && !empty($comment) && !empty($new_password) && empty($delete) &&empty($edit) && empty($edit_flag)){
        $sql = $pdo -> prepare("INSERT INTO tb1 (name, comment, post_date, password) VALUES (:name, :comment, :post_date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindValue(':post_date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $new_password, PDO::PARAM_STR);
        $sql -> execute();
    }
    
    #削除
    elseif (!empty($delete) && !empty($del_password) && empty($name) && empty($comment) && empty($edit) && empty($edit_flag)){
        $id = $delete;
        $sql = 'delete from tb1 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    #編集する行のデータの取得
    elseif (!empty($edit) && !empty($ed_password) && empty($name) && empty($comment) && empty($delete) && empty($edit_flag)){
        $sql = 'SELECT * FROM tb1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            if ($row['id'] == $edit){
                $ed_name = $row['name'];
                $ed_comment = $row['comment'];
                $ed_date = $row['post_date'];
                $ed_password = $row['password'];
            }
        }
    }
    
    #編集する行のデータの差し替え
    elseif (!empty($edit_flag) && !empty($name) && !empty($comment) && !empty($new_password) && empty($delete) && empty($edit)){
        $sql = 'UPDATE tb1 SET name=:name,comment=:comment,password=:password WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $edit_flag, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':password', $new_password, PDO::PARAM_STR);
        $stmt->execute(); 
    }
?>
<hr>
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
  　<input type="password" name="new_password" value='<?php if(isset($ed_password)){echo $ed_password;}?>' placeholder='パスワード'>  　　
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
  　<br><br>
</form>
<hr>
<h2>過去の投稿</h2>
<?php
    #データベース表示
    $sql = 'SELECT * FROM tb1';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].'<>'.$row['name'].'<>'.$row['comment'].'<>'.$row['post_date'];
        echo "<br>";
    }
?>
</body>
</html>
