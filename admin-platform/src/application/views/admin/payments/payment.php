<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-5">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                    <?php echo _l('payment_edit_for_invoice'); ?>
                    <a href="<?php echo admin_url('invoices/list_invoices/' . $payment->invoiceid); ?>">
                        <?php echo format_invoice_number($payment->invoice->id); ?>
                    </a>
                </h4>
                <div class="col-md-12 no-padding">
                    <div class="panel_s">
                        <div class="panel-body">
                            <?php echo form_open($this->uri->uri_string()); ?>

                            <?php echo render_input('amount', 'payment_edit_amount_received', $payment->amount, 'number'); ?>
                            <?php echo render_date_input('date', 'payment_edit_date', _d($payment->date)); ?>
                            <?php echo render_select('paymentmode', $payment_modes, ['id', 'name'], 'payment_mode', $payment->paymentmode); ?>
                            <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
                                data-title="<?php echo _l('payment_method_info'); ?>"></i>
                            <?php echo render_input('paymentmethod', 'payment_method', $payment->paymentmethod); ?>
                            <?php echo render_input('transactionid', 'payment_transaction_id', $payment->transactionid); ?>
                            <?php echo render_textarea('note', 'note', $payment->note, ['rows' => 7]); ?>
                            <?php hooks()->do_action('before_admin_edit_payment_form_submit', $payment); ?>
                            <div class="btn-bottom-toolbar text-right">
                                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="tw-flex tw-justify-between tw-mb-2.5">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-0">
                        <?php echo _l('payment_view_heading'); ?>
                    </h4>
                    <div class="tw-self-start">
                        <div class="btn-group">
                            <a href="#" data-toggle="modal" data-target="#payment_send_to_client"
                                class="payment-send-to-client btn-with-tooltip btn btn-default">
                                <i class="fa-regular fa-envelope"></i></span>
                            </a>

                            <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa-regular fa-file-pdf"></i>
                                <?php if (is_mobile()) {
    echo ' PDF';
} ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="hidden-xs">
                                    <a
                                        href="<?php echo admin_url('payments/pdf/' . $payment->paymentid . '?output_type=I'); ?>">
                                        <?php echo _l('view_pdf'); ?>
                                    </a>
                                </li>
                                <li class="hidden-xs">
                                    <a href="<?php echo admin_url('payments/pdf/' . $payment->paymentid . '?output_type=I'); ?>"
                                        target="_blank">
                                        <?php echo _l('view_pdf_in_new_window'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo admin_url('payments/pdf/' . $payment->paymentid); ?>">
                                        <?php echo _l('download'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo admin_url('payments/pdf/' . $payment->paymentid . '?print=true'); ?>"
                                        target="_blank">
                                        <?php echo _l('print'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <?php if (has_permission('payments', '', 'delete')) { ?>
                        <a href="<?php echo admin_url('payments/delete/' . $payment->paymentid); ?>"
                            class="btn btn-danger _delete">
                            <i class="fa fa-remove"></i>
                        </a>
                        <?php } ?>
                    </div>
                </div>

                <div class="panel_s -tw-mt-1.5">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <address class="tw-text-neutral-500">
                                    <?php echo format_organization_info(); ?>
                                </address>
                            </div>
                            <div class="col-sm-6 text-right">
                                <address class="tw-text-neutral-500">
                                    <?php echo format_customer_info($payment->invoice, 'payment', 'billing', true); ?>
                                </address>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <h3 class="text-uppercase tw-font-medium tw-text-neutral-600">
                                <?php echo _l('payment_receipt'); ?>
                            </h3>
                        </div>
                        <div class="col-md-12 mtop40">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="tw-text-neutral-500"><?php echo _l('payment_date'); ?> <span
                                            class="pull-right bold"><?php echo _d($payment->date); ?></span></p>
                                    <hr class="tw-my-2" />
                                    <p class="tw-text-neutral-500"><?php echo _l('payment_view_mode'); ?>
                                        <span class="pull-right bold">
                                            <?php echo $payment->name; ?>
                                            <?php if (!empty($payment->paymentmethod)) {
    echo ' - ' . $payment->paymentmethod;
}
                                            ?>
                                        </span>
                                    </p>
                                    <?php if (!empty($payment->transactionid)) { ?>
                                    <hr class="tw-my-2" />
                                    <p class="tw-text-neutral-500"><?php echo _l('payment_transaction_id'); ?>: <span
                                            class="pull-right bold"><?php echo $payment->transactionid; ?></span></p>
                                    <?php } ?>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <div class="payment-preview-wrapper">
                                        <?php echo _l('payment_total_amount'); ?><br />
                                        <?php echo app_format_money($payment->amount, $payment->invoice->currency_name); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mtop30">
                            <h4 class="tw-font-medium tw-text-neutral-600">
                                <?php echo _l('payment_for_string'); ?>
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-bordered !tw-mt-0">
                                    <thead>
                                        <tr>
                                            <th><?php echo _l('payment_table_invoice_number'); ?></th>
                                            <th><?php echo _l('payment_table_invoice_date'); ?></th>
                                            <th><?php echo _l('payment_table_invoice_amount_total'); ?></th>
                                            <th><?php echo _l('payment_table_payment_amount_total'); ?></th>
                                            <?php if ($payment->invoice->status != Invoices_model::STATUS_PAID
                                                    && $payment->invoice->status != Invoices_model::STATUS_CANCELLED) { ?>
                                            <th><span class="text-danger"><?php echo _l('invoice_amount_due'); ?></span>
                                            </th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo format_invoice_number($payment->invoice->id); ?></td>
                                            <td><?php echo _d($payment->invoice->date); ?></td>
                                            <td><?php echo app_format_money($payment->invoice->total, $payment->invoice->currency_name); ?>
                                            </td>
                                            <td><?php echo app_format_money($payment->amount, $payment->invoice->currency_name); ?>
                                            </td>
                                            <?php if ($payment->invoice->status != Invoices_model::STATUS_PAID
                                                        && $payment->invoice->status != Invoices_model::STATUS_CANCELLED) { ?>
                                            <td class="text-danger">
                                                <?php echo app_format_money(get_invoice_total_left_to_pay($payment->invoice->id, $payment->invoice->total), $payment->invoice->currency_name); ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="btn-bottom-pusher"></div>
    </div>
</div>
<?php $this->load->view('admin/payments/send_to_client'); ?>
<?php init_tail(); ?>
<script>
$(function() {
    appValidateForm($('form'), {
        amount: 'required',
        date: 'required'
    });
});
</script>
</body>

</html>