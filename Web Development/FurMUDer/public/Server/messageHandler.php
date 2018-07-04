<?php
  function GetLastMessage()
  {
    $line = '';
    
    $f = fopen('messageDatabase.txt', 'r');
    $cursor = -1;

    fseek($f, $cursor, SEEK_END);
    $char = fgetc($f);

    /* Trim trailing newline chars of the file */
    while ($char === "\n" || $char === "\r") {
        fseek($f, $cursor--, SEEK_END);
        $char = fgetc($f);
    }

    /* Read until the start of file or first newline char */
    while ($char !== false && $char !== "\n" && $char !== "\r") {
        /* Prepend the new char */
        $line = $char . $line;
        fseek($f, $cursor--, SEEK_END);
        $char = fgetc($f);
    }

    $LastMessage = json_decode(ltrim(stripslashes($line), ", "));
    
    fclose($f);
    
    return $LastMessage;
  }
  
  header("Content-Type: application/json");
  $post = file_get_contents("php://input");
  $message = json_decode(stripslashes($post));
  
  if($message->PostType === "message")
  {
    $LastMessage = GetLastMessage();
    if($LastMessage)
    {
      $message->messageID = GetLastMessage()->messageID + 1;
    }
    $post = json_encode($message);
    
    if($LastMessage)
      $post = ',' . $post;
    else
      $post = ' ' . $post;
    
    $outFile = fopen("messageDatabase.txt", "a");
    fwrite($outFile, $post . "\n");
    fclose($outFile);
    echo json_encode($message);
  }
  else if($message->PostType === "getMessageData")
  {
    echo "[\n";
    echo file_get_contents("messageDatabase.txt");
    echo "]";
  }
  else
  {
    echo json_encode($message);
  }
?>