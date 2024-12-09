<!-- Add Student Modal -->

<div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="studentModalLabel">
                    <i class="bi bi-person-plus-fill me-2"></i>Add New Student
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addStudentForm" method="POST" action="" enctype="multipart/form-data">
                    <!-- Name Section -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name">
                                <label for="firstName">First Name</label>
                            </div>
                            <div id="firstNameError" class="invalid-feedback" style="display: none;">
                                Please enter your first name.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name">
                                <label for="lastName">Last Name</label>
                            </div>
                            <div id="lastNameError" class="invalid-feedback" style="display: none;">
                                Please enter your last name.
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                        <label for="email">Email Address</label>
                        <div id="emailError" class="invalid-feedback" style="display: none;">
                            Please enter a valid email address ending with .com.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input type="text" class="form-control" id="contactNumber" name="contactNumber" placeholder="9XX XXX XXXX" maxlength="10">
                        </div>
                        <div id="errorMsg" class="invalid-feedback" style="display: none;">
                            Please enter a valid phone number.
                        </div>
                        <small class="text-muted">Format: 9XX XXX XXXX</small>
                    </div>


                    <!-- Academic Information -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select id="yearLevel" name="yearLevel" class="form-select">
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                                <label for="yearLevel">Year Level</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-floating">
                                <select id="department" name="department" class="form-select">
                                    <option value="CICS">CICS</option>
                                    <option value="CABE">CABE</option>
                                    <option value="CAS">CAS</option>
                                    <option value="CE">CE</option>
                                    <option value="CIT">CIT</option>
                                    <option value="CTE">CTE</option>
                                </select>
                                <label for="department">Department</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select id="course" name="course" class="form-select">
                            <option value="BSBA">Bachelor of Science in Business Administration</option>
                            <option value="BSMA">Bachelor of Science in Management Accounting</option>
                            <option value="BSP">Bachelor of Science in Psychology</option>
                            <option value="BAC">Bachelor of Arts in Communication</option>
                            <option value="BSIE">Bachelor of Science in Industrial Engineering</option>
                            <option value="BSIT-CE">Bachelor of Industrial Technology - Computer Technology</option>
                            <option value="BSIT-Electrical">Bachelor of Industrial Technology - Electrical Technology</option>
                            <option value="BSIT-Electronic">Bachelor of Industrial Technology - Electronics Technology</option>
                            <option value="BSIT-ICT">Bachelor of Industrial Technology - ICT</option>
                            <option value="BSIT">Bachelor of Science in Information Technology</option>
                            <option value="BSE">Bachelor of Secondary Education</option>
                        </select>
                        <label for="course">Course</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="submit" class="btn btn-danger" form="addStudentForm" id="openConfirmationModalBtn">
                    <i class="bi bi-save me-2"></i>Save Student
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Save</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to save this student?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmSaveBtn">Confirm Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#addStudentForm').on('submit', function(e) {
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
    </script>
</div>

<!-- Edit Student -->

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="editStudentModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Student
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editStudentForm" method="POST" action="">
                    <input type="hidden" name="student_id" id="editStudentId">

                    <!-- Name Section -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="editFirstName" name="firstName" placeholder="Enter first name">
                                <label for="editFirstName">First Name</label>
                                <div id="editFirstNameError" class="invalid-feedback" style="display: none;">Please enter a valid first name.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="editLastName" name="lastName" placeholder="Enter last name">
                                <label for="editLastName">Last Name</label>
                                <div id="editLastNameError" class="invalid-feedback" style="display: none;">Please enter a valid last name.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="editEmail" name="email" placeholder="Enter email">
                        <label for="editEmail">Email Address</label>
                        <div id="editEmailError" class="invalid-feedback" style="display: none;">Please enter a valid email address ending with .com.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input type="text" class="form-control" id="contactNumberEdit" name="contactNumber" placeholder="9XX XXX XXXX" maxlength="10">
                        </div>
                        <div id="contactNumberError" class="invalid-feedback" style="display: none;">
                            Please enter a valid phone number starting with 9 and in the format: 9XX XXX XXXX.
                        </div>
                        <small class="text-muted">Format: 9XX XXX XXXX</small>
                    </div>

                    <!-- Academic Information -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select id="editYearLevel" name="yearLevel" class="form-select">
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                                <label for="editYearLevel">Year Level</label>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-floating">
                                <select id="editDepartment" name="department" class="form-select">
                                    <option value="CICS">CICS</option>
                                    <option value="CABE">CABE</option>
                                    <option value="CAS">CAS</option>
                                    <option value="CE">CE</option>
                                    <option value="CIT">CIT</option>
                                    <option value="CTE">CTE</option>
                                </select>
                                <label for="editDepartment">Department</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select id="editCourse" name="course" class="form-select">
                            <option value="BSBA">Bachelor of Science in Business Administration</option>
                            <option value="BSMA">Bachelor of Science in Management Accounting</option>
                            <option value="BSP">Bachelor of Science in Psychology</option>
                            <option value="BAC">Bachelor of Arts in Communication</option>
                            <option value="BSIE">Bachelor of Science in Industrial Engineering</option>
                            <option value="BSIT-CE">Bachelor of Industrial Technology - Computer Technology</option>
                            <option value="BSIT-Electrical">Bachelor of Industrial Technology - Electrical Technology</option>
                            <option value="BSIT-Electronic">Bachelor of Industrial Technology - Electronics Technology</option>
                            <option value="BSIT-ICT">Bachelor of Industrial Technology - ICT</option>
                            <option value="BSIT">Bachelor of Science in Information Technology</option>
                            <option value="BSE">Bachelor of Secondary Education</option>
                        </select>
                        <label for="editCourse">Course</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="submit" class="btn btn-danger" form="editStudentForm">
                    <i class="bi bi-save me-2"></i>Update Student
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#editStudentForm").submit(function(event) {
                let isValid = true;

                // First Name validation
                if ($("#editFirstName").val() === "") {
                    isValid = false;
                    $("#editFirstName").addClass("is-invalid");
                    $("#editFirstNameError").show(); // Show error message
                } else {
                    $("#editFirstName").removeClass("is-invalid");
                    $("#editFirstNameError").hide(); // Hide error message
                }

                // Last Name validation
                if ($("#editLastName").val() === "") {
                    isValid = false;
                    $("#editLastName").addClass("is-invalid");
                    $("#editLastNameError").show(); // Show error message
                } else {
                    $("#editLastName").removeClass("is-invalid");
                    $("#editLastNameError").hide(); // Hide error message
                }

                // Email validation (must end with .com)
                const email = $("#editEmail").val();
                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com)$/;
                if (email === "" || !emailPattern.test(email)) {
                    isValid = false;
                    $("#editEmail").addClass("is-invalid");
                    $("#editEmailError").show(); // Show error message
                } else {
                    $("#editEmail").removeClass("is-invalid");
                    $("#editEmailError").hide(); // Hide error message
                }

                // Contact Number validation (must start with 9 and be 10 digits)
                const contactNumber = $("#contactNumberEdit").val();
                const contactNumberPattern = /^9\d{9}$/;
                if (contactNumber === "" || !contactNumberPattern.test(contactNumber)) {
                    isValid = false;
                    $("#contactNumberEdit").addClass("is-invalid");
                    $("#contactNumberError").show(); // Show error message
                } else {
                    $("#contactNumberEdit").removeClass("is-invalid");
                    $("#contactNumberError").hide(); // Hide error message
                }

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });
        });
    </script>
</div>