 <!-- Footer -->
 <!-- <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Yonathan Rizky <?= date('Y');?></span>
          </div>
        </div>
      </footer> -->
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

  <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document" style="margin-top:100px;">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="myModalLabel">Import Data Customer</h4>

			</div>
        	<div>
			<?php echo form_open_multipart("customer/upload",'class="form-horizontal"'); ?>
				<div class="modal-body">
					<div class="form-group row">
					<div class="col-md-12">
						<label for="exampleInputFile">Pilih File Excel</label>
							<?= form_upload(array('id' => 'txtFileImport', 'name' => 'file'))?>
							<small id="fileImport" class="form-text text-muted">Import Data Customer Via Excel, Format File Harus .XLS (Excel 2003-2007).</small>
					</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Batal</button>
					<button type="submit" value="Upload" name="btnSubmit" class="btn btn-primary"><i class="fa fa-upload"></i> Import Data</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url();?>assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url();?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


  <!-- Core plugin JavaScript-->
  <script src="<?= base_url();?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

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
        type: 'POST',
        data: {
          menuId: menuId,
          roleId : roleId
        },
        success: function(){
          document.location.href ="<?= base_url('admin/roleAccess/'); ?>" + roleId;
        }
      });
    });

    var table;
    $(document).ready(function() { 
        //datatables
        table = $('#table').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [], 
             
            "ajax": {
                "url": "<?= base_url('customer/get_data')?>",
                "type": "POST"
            },
             
            "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            },
            ], 
        }); 
    });

    $(document).on("click", ".delete", function(e) {
           var id = $(this).attr("Id"); 
           e.preventDefault();
           bootbox.confirm("Are you sure delete ?", function(result) {
               if (result) {
                     $.ajax({  
                     url:"<?= base_url(); ?>customer/delete",  
                     method:"POST",  
                     data:{Id:id},  //Id memanggil database dengan kolom Id, id memanggil variable dengan attr Id
                     success:function(data)  
                     {                            
                          table.ajax.reload();  
                     }  
                });

               }   
           });
       });

    $(document).ready(function(){
            $( "#customer" ).autocomplete({
              source: "<?= base_url('transaksi/get_autocomplete/?');?>"
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
      myForm.total.value =  z.toFixed(0);
    }

    function hitungJual(){

    var myForm = document.hitungRateJual;
    var x=eval(myForm.rate_valas.value);
    var y=eval(myForm.jumlah.value);
    var z= y * x;
    myForm.total.value = z.toFixed(0);
    }

    function hitungTotalTambahStock(){

    var myForm = document.stock;
    var x=eval(myForm.stock.value);
    var y=eval(myForm.rate.value);
    var z= y * x;
    myForm.total.value = z.toFixed(0);
    }
  </script>

</body>

</html>
