<!--<form id="labnol" method="get" action="https://www.google.com/search">-->
<form id="labnol" method="get" action="http://192.168.202.252/ems/View/search.php">

  <div class="speech">
    <input type="text" name="q" id="transcript" placeholder="Speak"  />
    <img onclick="startDictation()" src="//i.imgur.com/cHidSVu.gif" />
  </div>
</form>
<style>
  .speech {border: 1px solid #DDD; width: 300px; padding: 0; margin: 0}
  .speech input {border: 0; width: 240px; display: inline-block; height: 30px;}
  .speech img {float: right; width: 40px }
</style>
<script>
  function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

      var recognition = new webkitSpeechRecognition();

      recognition.continuous = false;
      recognition.interimResults = false;

      recognition.lang = "en-US";
      recognition.start();

      recognition.onresult = function(e) {
        document.getElementById('transcript').value
                                 = e.results[0][0].transcript;
        recognition.stop();
        document.getElementById('labnol').submit();
      };

      recognition.onerror = function(e) {
        recognition.stop();
      }

    }
  }
</script>