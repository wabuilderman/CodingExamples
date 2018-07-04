<?php
  function createPost($user, $date, $text)
  {
    return "<post user=\"" . $user . "\" date=\"" . $date . "\"> <div> " . $text .  " </div> </post>" . "\n";
  }

  function parseUser($line)
  {
    $user = '';
    /* skip the first 12 characters */
    $i = 11;
    while($line[++$i])
    {
      if($line[$i] == "\"")
      {
        break;
      }
      $user .= $line[$i];
    }
    return $user;
  }

  /*--------------------------------------------------------------------------*\
  | Function: walkToLine                                                       |
  |----------------------------------------------------------------------------|
  | Description: Walks a handle to the start of the desired line               |
  | Input(s):    $handle: The handle to modify                                 |
  |              $line:   The line to seek (0-indexed)                         |
  | Output(s):   RETURN:  The handle that has been modified                    |
  \*--------------------------------------------------------------------------*/
  function walkToLine($handle, $line)
  {
    $i = 0;               /* Loop variable                 */
    rewind($handle);      /* Start at the beginning        */
    while (++$i <= $line) /* Loop till the line is reached */
    {
      fgets($handle);     /* Move the handle forward       */
    }
    return $handle;       /* Return the modified handle    */
  }

  function addPost($post)
  {
    $filename = 'postData.data';
    $handle = fopen($filename, 'a')
          or die('Cannot open file: '.$filename);
    fwrite($post);
  }

  function getAllPosts($array)
  {
    $filename = 'postData.data';
    $handle = fopen($filename, 'r')
          or die('Cannot open file: '.$filename);
    while (($line = fgets($handle)) !== false)
    {
      $array[] = $line . "<br>";
    }
    return $array;
  }

  if(isset($_POST['method']))
  {
    if($_POST['method'] == "getAllPosts")
    {
      $array = [];
      $array = getAllPosts($array);
      echo json_encode($array);
    }
  }
  else
  {
      echo json_encode(array("This isn't Correct"));
  }
?>
