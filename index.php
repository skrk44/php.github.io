<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>project</title>
</head>

<body>

  <?php
  ini_set('display_errors', 0);
  $name = $_POST["name"];
  $comment = $_POST["comment"];
  $pass = $_POST["pass"];
  $delete = $_POST["delete"];
  $delpass = $_POST["delpass"];
  $edit = $_POST["edit"];
  $editnum = $_POST["editnum"];
  $editpass = $_POST["editpass"];
  $date = date("Y/m/d/ H:i:s");
  $filename = "project.txt";

  //編集選択機能
  if (!empty($edit) && !empty($editpass) && file_exists($filename)) {
    $edlines = file($filename);
    $Num =  count($edlines);
    for ($i = 0; $i <= $Num; $i++) {
      $edDate = explode("<>", $edlines[$i]);
      if ($edit == $edDate[0] && $editpass == $edDate[4]) {
        $edName = $edDate[1];
        $edComment = $edDate[2];
      }
    }
  }

  if (!empty($comment) && !empty($name) && !empty($pass)) {
    if (!empty($editnum)) {
      //配列にファイルの各行をコピーする
      $lines = file($filename);
      //$Num=配列の総数
      $Num =  count($lines);
      //書き込みモードにしてテキストファイルを書き直す
      $fp_edit = fopen($filename, "w");
      //投稿番号を読み取ってeditnumと比較し違ったら書き込み
      for ($i = 0; $i <= $Num; $i++) {
        $strs = explode("<>", $lines[$i]);
        if ($strs[0] != $editnum) {
          fwrite($fp_edit, $lines[$i]);
        } else {
          //num=投稿番号
          $num = $editnum;
          fwrite(
            $fp_edit,
            $num . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" . $pass . "<>" . PHP_EOL
          );
        }
      }
      fclose($fp_edit);
    } else {
      //追記モードで書き込み
      $fp = fopen($filename, "a");
      //num=投稿番号
      if (file_exists($filename)) {
        $num = count(file($filename)) + 1;
      } else {
        $num = 1;
      }
      fwrite(
        $fp,
        $num . "<>" . $name . "<>" . $comment . "<>" . $date . "<>" . $pass . "<>" . PHP_EOL
      );
      fclose($fp);
    }
  }


  if (!empty($delete) && !empty($delpass) && file_exists($filename)) {
    $dellines = file($filename);
    $delfp = fopen($filename, "w");
    foreach ($dellines as $delline) {
      $deldata = explode("<>", $delline);
      if ($delete == $deldata[0] && $delpass == $deldata[4]) {
        fwrite($delfp, "削除済み\n");
      } else {
        fwrite($delfp, $delline);
      }
    }
    fclose($delfp);
  }



  ?>

  <form action="" method="post">
    <label>
      <input type="text" name="name" placeholder="名前" value="<?php if ($edName) {
                                                                echo $edName;
                                                              } ?>">
      <br>
      <input type="text" name="comment" placeholder="コメント" value="<?php if ($edComment) {
                                                                    echo $edComment;
                                                                  } ?>">
      <input type="hidden" name="editnum" placeholder="" value="<?php if ($edit) {
                                                                  echo $edit;
                                                                } ?>">
      <br>
      <input type="text" name="pass" placeholder="パスワード">
      <input type="submit" name="btn1">
    </label>
    <br>
    <br>
    <label>
      <input type="text" name="delete" placeholder="削除対象番号">
      <br>
      <input type="text" name="delpass" placeholder="パスワード">
      <input type="submit" name="btn2" value="削除">
    </label>
    <br>
    <br>
    <label>
      <input type="text" name="edit" placeholder="編集対象番号">
      <br>
      <input type="text" name="editpass" placeholder="パスワード">
      <input type="submit" name="btn3" value="編集">
    </label>
  </form>

  <?php
  if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
      $str = explode("<>", $line);

      echo $str[0] . " ";
      echo $str[1] . " ";
      echo $str[2] . " ";
      echo $str[3] . "<br>";
    }
  }
  ?>
</body>

</html>
