<html>
<head>
  <style>
    .firepad {
      width: 700px;
      height: 250px;
      background-color: #0abe51; /*dark orange background */
    }

    #v_center {
      position: relative;
      top: 5%;
    }

  </style>
  <script type="text/javascript">
    var room_name='';
    var room_hash='';
    
    <?php 
    if(isset($_GET["hash"])){
      ?>
      room_hash='<?php echo $_GET["hash"]; ?>';
      <?php
    } ?>
    
  </script>
  <script src="js/jquery.min.js"></script>

  <!-- Firebase -->
  <script src="js/firebase.js"></script>

  <!-- CodeMirror -->
  <script src="js/codemirror.js"></script>
  <link rel="stylesheet" href="css/codemirror.css" />

  <!-- Firepad -->
  <link rel="stylesheet" href="css/firepad.css" />
  <script src="js/firepad.min.js"></script>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="css/bootstrap.min.css" />

</head>

<body onload="init()">
  <div id="room_name" style="visibility:hidden">
    <div class="btn-group btn-group-justified" role="group">
      <div class="btn-group" role="group">
         <a type="button" class="btn btn-primary" onclick="copyToClipboard()"><span class="glyphicon glyphicon-copy" aria-hidden="true" ></span>&nbsp;Copy Room id</a>
      </div>
      <div class="btn-group" role="group">
        <a type="button" class="btn btn-danger" href="https://livepad.herokuapp.com/">Leave Room&nbsp;<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>
      </div>
    </div>
  </div>

  <div id="firepad"></div>
  <div class="container" id="v_center">
    <div id="initial" style="visibility:hidden" >
      <div align="center"><img src="img/logo.png" alt="logo" width="200px" height="200px"/></div>
      <form method="GET" action="/">
        <div class="form-group">
          <div class="input-group">   
            <input type="text" name="hash" id="hash" value="new" style="visibility:hidden" >
          </div>
        </div>
        <button type="submit" class="btn btn-success btn-lg btn-block">New Room</button>
      </form>
      <hr>  
      <form method="GET" action="/">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-addon"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></div>
            <input class="form-control input-lg" type="text" name="hash" id="hash" placeholder="Enter Room Id" required maxlength='25' >
          </div>
        </div>
        <button type="submit" class="btn btn-info btn-block">Enter Room</button>
      </form>
    </div>
  </div>
  
  <script>

    function copyToClipboard() {

      var success   = true,
      range     = document.createRange(),
      selection;

    // Create a temporary element off screen.
    var tmpElem = $('<div>');
    tmpElem.css({
      position: "absolute",
      left:     "-1000px",
      top:      "-1000px",
    });
    // Add the input value to the temp element.
    tmpElem.text(room_name);
    $("body").append(tmpElem);
    // Select temp element.
    range.selectNodeContents(tmpElem.get(0));
    selection = window.getSelection ();
    selection.removeAllRanges ();
    selection.addRange (range);
    document.execCommand ("copy", false, null);
    alert("Room id is copied.")

  }

  function init() {
      // Initialize Firebase.
      // TODO: replace with your Firebase project configuration.

      if(room_hash==""){
        
          document.getElementById('initial').style.visibility='visible';
      }
      else{
        var config = {
          apiKey: "AIzaSyDFOoADmScgPHUFDQy6nSFJP08YxyzceUY",
          authDomain: "flock-ide.firebaseapp.com",
          databaseURL: "https://flock-ide.firebaseio.com",
          storageBucket: "flock-ide.appspot.com",
          //messagingSenderId: "308366562372"
        };
        firebase.initializeApp(config);
          // Get Firebase Database reference.
          var firepadRef = getExampleRef(room_hash);

          document.getElementById('room_name').style.visibility='visible';
          // Create CodeMirror (with lineWrapping on).
          var codeMirror = CodeMirror(document.getElementById('firepad'), { lineWrapping: true });

          // Create a random ID to use as our user ID (we must give this to firepad and FirepadUserList).
          var userId = Math.floor(Math.random() * 9999999999).toString();
          // Create Firepad (with rich text toolbar and shortcuts enabled).
          var firepad = Firepad.fromCodeMirror(firepadRef, codeMirror, {
            richTextShortcuts: true,
            richTextToolbar: true,
            defaultText: 'Share Room id and collaborate with your friends.\nRoom id:   '+firepadRef.key,
            userId: userId
          });
          $('.powered-by-firepad').hide();


        }
      }
      function getExampleRef(hash) {
        var ref = firebase.database().ref();
        if (hash!="new") {
          ref = ref.child(hash);
        } else {
        ref = ref.push(); // generate unique location.
      }
      room_name=ref.key;
      return ref;
    }

  </script>

</body>
</html>
