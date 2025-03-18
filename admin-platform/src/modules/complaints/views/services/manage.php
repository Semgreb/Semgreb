<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="javascript:void(0);" class="btn btn-primary _btnNewService">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_service'); ?>
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php render_datatable([
                            [
                                'name'     =>    '#',
                                'th_attrs' => ['class' => 'text-center'],
                            ],
                            _l('name'),
                            _l('options'),
                        ], 'services', ['number-index-1']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('complaints/services/service'); ?>
<?php init_tail(); ?>
<script>
    $(function() {
        initDataTable('.table-services', window.location.href, [0], [0], 'undefined', [1, 'asc']);

        $(".table-services tbody").on("click", "._btn_edit", function() {
            let id = $(this).data("id");
            var name = $(this).data('name');
            $('#additional').append(hidden_input('id', id));
            $('#complaint-service-modal input[name="name"]').val(name);
            $('#complaint-service-modal').modal('show');
        });

        $('._btnNewService').click(function() {
            $('#complaint-service-modal').modal('show');
            $('.edit-title').addClass('hide');
        });
    });
</script>
</body>

</html>