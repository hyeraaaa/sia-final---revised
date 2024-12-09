<div class="modal fade" id="changeCoverPhotoModal" tabindex="-1" aria-labelledby="changeCoverPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="changeCoverPhotoModalLabel">
                    <i class="bi bi-image me-2"></i>Change Cover Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="upload-container mb-4">
                    <div class="upload-image-container d-flex flex-column align-items-center justify-content-center bg-light rounded-3 position-relative" style="height: 300px; border: 2px dashed #ccc;">
                        <div class="preview-area w-100 h-100 d-flex align-items-center justify-content-center p-2 px-3">
                            <img id="cover-image-preview" src="#" alt="Cover Photo Preview" class="img-fluid rounded-3" style="display: none; max-height: 100%; object-fit: cover;">
                            <div id="upload-cover-interface" class="text-center" style="display: block;">
                                <i class="bi bi-cloud-arrow-up display-4 text-muted"></i>
                                <p class="mt-3 mb-1 text-muted">Drag & Drop your image here</p>
                                <p class="small text-muted mb-3">or</p>
                                <button type="button" class="btn btn-danger btn-sm ms-auto me-auto" id="browse-button" onclick="document.getElementById('coverPhotoInput').click()">
                                    <i class="bi bi-folder me-2"></i>Browse Files
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" id="delete-cover-image" style="display: none;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="small text-muted mt-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Allowed formats: JPG, PNG, GIF. Max size: 2MB.
                    </div>
                    <div class="text-danger small mt-2" id="coverErrorMessage" style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmCoverSubmit">
                    <i class="bi bi-check2-circle me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="coverPhotoSuccessModal" tabindex="-1" aria-labelledby="coverPhotoSuccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center">
            <div class="modal-body p-4">
                <div class="mb-2">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                </div>
                <h6 class="modal-title mb-1" id="coverPhotoSuccessModalLabel">Success</h6>
                <p class="small mb-3">Cover photo changed successfully!</p>
                <button type="button" class="btn btn-success btn-sm w-100" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<input type="file" id="coverPhotoInput" style="display: none;" />

<script>
    document.getElementById('coverPhotoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('cover-image-preview');
        const deleteButton = document.getElementById('delete-cover-image');
        const uploadInterface = document.getElementById('upload-cover-interface');
        const errorMessage = document.getElementById('coverErrorMessage');

        errorMessage.style.display = 'none';

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                deleteButton.style.display = 'block';
                uploadInterface.style.display = 'none';
                document.getElementById('browse-button').style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
            deleteButton.style.display = 'none';
            uploadInterface.style.display = 'block';
            document.getElementById('browse-button').style.display = 'block';
        }
    });

    document.getElementById('delete-cover-image').addEventListener('click', function() {
        const preview = document.getElementById('cover-image-preview');
        const fileInput = document.getElementById('coverPhotoInput');
        const errorMessage = document.getElementById('coverErrorMessage');

        preview.src = '#';
        preview.style.display = 'none';
        this.style.display = 'none';
        fileInput.value = '';
        document.getElementById('upload-cover-interface').style.display = 'block';
        document.getElementById('browse-button').style.display = 'block';

        errorMessage.style.display = 'none';
    });

    document.getElementById('confirmCoverSubmit').addEventListener('click', function(event) {
        event.preventDefault();

        const preview = document.getElementById('cover-image-preview');
        const errorMessage = document.getElementById('coverErrorMessage');

        if (preview.style.display !== 'block') {
            errorMessage.textContent = "Please insert a cover photo.";
            errorMessage.style.display = 'block';
        } else {
            const confirmation = confirm("Are you sure you want to save changes to the cover photo?");
            if (confirmation) {
                const form = document.querySelector('form[action="upload_photo.php"]');

                const successModal = new bootstrap.Modal(document.getElementById('coverPhotoSuccessModal'));
                successModal.show();

                setTimeout(() => {
                    form.submit();
                }, 2000);
            }
        }
    });
</script>