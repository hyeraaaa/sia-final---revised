 <!-- Logout Modal -->
 <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModal" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-sm">
         <div class="modal-content custom" style="border-radius: 15px;">
             <div class="modal-header pb-1" style="border: none">
                 <h1 class="modal-title fs-5" id="exampleModalLabel">Logout?</h1>
                 <button type="button" class="btn-close delete-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body py-0" style="border: none;">
                 <p style="font-size: 15px;">Are you sure you want to sign out?</p>
             </div>
             <div class="modal-footer pt-0" style="border: none;">
                 <button type="button" class="btn go-back-btn" data-bs-dismiss="modal">Cancel</button>
                 <button type="button" class="btn delete-btn" id="confirm-logout-btn">Yes, Logout</button>
             </div>
         </div>
     </div>
 </div>

 <script>
     function confirmLogout() {
         $('#logoutModal').modal('show');
     }

     document.getElementById('confirm-logout-btn').addEventListener('click', function() {
         window.location.href = '../../login/logout.php';
     });
 </script>