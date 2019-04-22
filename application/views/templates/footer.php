 <!-- Footer -->
 <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Yonathan Rizky <?= date('Y');?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?= base_url();?>auth/logout">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url();?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="<?= base_url();?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url();?>assets/js/sb-admin-2.min.js"></script>

  <script>

  $('.custom-file-input').on('change', function(){
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });

  $('.form-check-input').on('click',function()
  {
    const menuId = $(this).data('menu');
    const roleId = $(this).data('role');

    $.ajax({
      url: "<?= base_url('admin/changeAccess'); ?>",
      type: 'post',
      data: {
        menuId: menuId,
        roleId : roleId
      },
      success: function(){
        document.location.href ="<?= base_url('admin/roleAccess/'); ?>" + roleId;
      }
    });
  });
  
  $(document).ready(function() {
    $('#example').DataTable();
  } );
  $(document).ready(function() {
    $('#table2').DataTable();
  } );

  function hitungBeli(){

    var myForm = document.hitungRateBeli;
    var x=eval(myForm.rate_valas.value);
    var y=eval(myForm.jumlah.value);
    var z= x * y;
    myForm.hasil.value =  z.toFixed(0);
  }

  function hitungJual(){

  var myForm = document.hitungRateJual;
  var x=eval(myForm.rate_valas.value);
  var y=eval(myForm.jumlah.value);
  var z= y / x;
  myForm.hasil.value = z.toFixed(0);
  }
  </script>

</body>

</html>
