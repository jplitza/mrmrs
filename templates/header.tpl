<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="de" xml:lang="de">
<head>
  <title>Mr. &amp; Mrs. Wahlen</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="style.css" type="text/css" />
{literal}
  <!--[if IE]>
    <style type="text/css">
      #login fieldset {
        padding-left: 160px;
        width: 120px;
      }
      #login fieldset legend {
        margin: 0;
        margin-left: -70px;
      }
    </style>
  <![endif]-->
  <!--[if lt IE 6]>
    <style type="text/css">
      #login fieldset {
        width: 280px;
      }
      .message {
        width: 408px;
      }
    </style>
  <![endif]-->
  <script type="text/javascript">
    function check()
    {
      list = document.getElementsByTagName('select');
      list2 = new Array()
      for(i = 0; i < list.length; i++)
        if(list[i].value == '')
          list2.push(i)
      if(list2.length > 0)
        return confirm("Du hast nicht in jeder Kategorie gewählt, bist du sicher, dass du deine Stimme trotzdem abgeben willst?")
      return true
    }
  </script>
{/literal}
</head>
<body>
