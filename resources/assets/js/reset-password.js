var submit = document.getElementById('submit');
var password = document.getElementById('password');
var confirmPassword = document.getElementById('passwordConfirm');
var confirm = document.getElementById('confirm');
var capital = document.getElementById('capital');
var length = document.getElementById('length');

password.onfocus = function() {
  document.getElementById('message').style.display = 'block';
};

password.onblur = function() {
  document.getElementById('message').style.display = 'none';
};

password.onkeyup = function() {
  var upperCaseLetters = /[A-Z]/g;
  if (password.value.match(upperCaseLetters)) {
    capital.classList.remove('invalid');
    capital.classList.add('valid');
  } else {
    capital.classList.remove('valid');
    capital.classList.add('invalid');
  }

  if (password.value.length >= 8) {
    length.classList.remove('invalid');
    length.classList.add('valid');
  } else {
    length.classList.remove('valid');
    length.classList.add('invalid');
  }
};

confirmPassword.onfocus = function() {
  document.getElementById('messageConfirm').style.display = 'block';
};

confirmPassword.onblur = function() {
  document.getElementById('messageConfirm').style.display = 'none';
};

confirmPassword.onkeyup = function() {
  if (password.value == confirmPassword.value) {
    confirm.classList.remove('invalid');
    confirm.classList.add('valid');
    submit.disabled = false;
  }
  if (password.value != confirmPassword.value) {
    confirm.classList.remove('valid');
    confirm.classList.add('invalid');
    submit.disabled = true;
  }
};
