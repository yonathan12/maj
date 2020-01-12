<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-7">
      <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
          <div class="row">
            <div class="col-lg">
              <div class="p-5">
                <div class="text-center">
                  <h1 class="h4 text-gray-900 mb-4">Login Page</h1>
                  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('message');?>"> </div>
                  <?php $this->session->flashdata('message')?$this->session->flashdata('message'):''?>
                </div>
                <form class="user" method="post" action="<?= base_url();?>auth" onsubmit="return validasi(this)" role="form">
                  <div class="form-group">
                    <input type="text" class="form-control form-control-user" id="email" aria-describedby="emailHelp" placeholder="Email" name="email" value="<?= set_value('email'); ?>">
                    <?= form_error('email','<small class="text-danger pl-3">','</small>'); ?>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="password" placeholder="Password" name="password">
                    <?= form_error('password','<small class="text-danger pl-3">','</small>'); ?>
                  </div>
                  <button type="submit" class="btn btn-primary btn-user btn-block">
                    Login
                  </button>
                </form>
                <hr>
                <div class="text-center">
                  <a class="small" href="<?= base_url(); ?>auth/forgotpassword">Lupa Password</a>
                </div>                  
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>