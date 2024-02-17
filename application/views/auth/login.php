<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Login Page</h1>
                                </div>
                                <?php if ($this->session->flashdata('message')) { ?>
                                    <div class="alert <?= $this->session->flashdata('message_type') ?>alert-info" role="alert">
                                        <?php echo $this->session->flashdata('message'); ?>
                                    </div>
                                <?php } ?>
                                <form class="user" method="post" action="<?= site_url('auth') ?>">
                                    <?= '<input type="hidden" name="' . $this->security->get_csrf_token_name() . '" value="' . $this->security->get_csrf_hash() . '" />'; ?>
                                    <div class="mb-3">
                                        <label class="form-label" for="user_id">User ID</label>
                                        <input type="text" class="form-control form-control-user" id="user_id" name="user_id" placeholder="Enter User ID..." value="<?= set_value('user_id') ?>" autofocus />
                                        <?= form_error('user_id', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" autofocus />
                                        <?= form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= site_url('auth/registration') ?>">Create an Account!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>