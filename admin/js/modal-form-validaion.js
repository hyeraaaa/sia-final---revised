$(document).ready(function() {
    $('form').on('submit', function(e) {
        var isValid = true;

        // First Name Validation
        var firstName = $('#firstName').val();
        if (firstName.trim() === '') {
            isValid = false;
            $('#firstName').addClass('is-invalid');
            $('#firstNameError').show();
        } else {
            $('#firstName').removeClass('is-invalid');
            $('#firstNameError').hide();
        }

        // Last Name Validation
        var lastName = $('#lastName').val();
        if (lastName.trim() === '') {
            isValid = false;
            $('#lastName').addClass('is-invalid');
            $('#lastNameError').show();
        } else {
            $('#lastName').removeClass('is-invalid');
            $('#lastNameError').hide();
        }

        // Email Validation - must end with .com
        var email = $('#email').val();
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[cC][oO][mM]$/;
        if (!emailPattern.test(email)) {
            isValid = false;
            $('#email').addClass('is-invalid');
            $('#emailError').show();
        } else {
            $('#email').removeClass('is-invalid');
            $('#emailError').hide();
        }

        // Contact Number Validation - must start with 9 and have 10 digits
        var contactNumber = $('#contactNumber').val();
        var contactPattern = /^9\d{9}$/;
        if (!contactPattern.test(contactNumber)) {
            isValid = false;
            $('#contactNumber').addClass('is-invalid');
            $('#errorMsg').show();
        } else {
            $('#contactNumber').removeClass('is-invalid');
            $('#errorMsg').hide();
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});

$(document).ready(function() {
    $('form').on('submit', function(e) {
        var isValid = true;

        // First Name Validation
        var firstName = $('#editFirstName').val();
        if (firstName.trim() === '') {
            isValid = false;
            $('#editFirstName').addClass('is-invalid');
            $('#editFirstNameError').show();
        } else {
            $('#editFirstName').removeClass('is-invalid');
            $('#editFirstNameError').hide();
        }

        // Last Name Validation
        var lastName = $('#editLastName').val();
        if (lastName.trim() === '') {
            isValid = false;
            $('#editLastName').addClass('is-invalid');
            $('#editLastNameError').show();
        } else {
            $('#editLastName').removeClass('is-invalid');
            $('#editLastNameError').hide();
        }

        // Email Validation - must end with .com
        var email = $('#editEmail').val();
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[cC][oO][mM]$/;
        if (!emailPattern.test(email)) {
            isValid = false;
            $('#editEmail').addClass('is-invalid');
            $('#editEmailError').show();
        } else {
            $('#editEmail').removeClass('is-invalid');
            $('#editEmailError').hide();
        }

        // Contact Number Validation - must start with 9 and have 10 digits
        var contactNumber = $('#editContactNumber').val();
        var contactPattern = /^9\d{9}$/;
        if (!contactPattern.test(contactNumber)) {
            isValid = false;
            $('#editContactNumber').addClass('is-invalid');
            $('#editErrorMsg').show();
        } else {
            $('#editContactNumber').removeClass('is-invalid');
            $('#editErrorMsg').hide();
        }

        // If form is not valid, prevent submission
        if (!isValid) {
            e.preventDefault();
        }
    });
});
