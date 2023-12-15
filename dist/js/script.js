document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        var preloader = document.querySelector('.preloader');
        preloader.style.display = 'none';
    }, 1500);
});

$(document).ready(function () {      
  handleNavlink();
  handlePasswordCheck();

  toastr.options = {
       timeOut: 3000,
       progressBar: true,
       closeButton: true,
       showMethod: 'fadeIn',
       hideMethod: 'fadeOut',
       borderRadius: '8px',
       positionClass: 'toast-top-right',
    };
});

function handleNavlink() {
    var currentPage = window.location.search.substring(1);
    $('.main-nav-link').removeClass('activeMe');

    $('.main-nav-link').each(function () {
        var href = $(this).attr('href');
        if (href.indexOf(currentPage) !== -1) {
            $(this).addClass('activeMe');
        }
    });
}

function handlePasswordCheck() {
    var isPasswordMatch = false;
    toggleSubmitButton();

    $('#passwordConAddUser').on('input', function() {
        var password = $('#passwordAddUser').val();
        var confirmPassword = $('#passwordConAddUser').val();
        var errorElement = $('#passwordError');

        if (password !== confirmPassword) {
            errorElement.text('Password do not match.');
            isPasswordMatch = false;
        } else {
            errorElement.text('');
            isPasswordMatch = true;
        }
        toggleSubmitButton();
    });

    function toggleSubmitButton() {
        $('#btnSubmitAddUser').prop('disabled', !isPasswordMatch);
    }
}
