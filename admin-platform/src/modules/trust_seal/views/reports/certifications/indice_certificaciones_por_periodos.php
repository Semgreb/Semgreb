<div class="row">
    <div class="col-md-12">
        <div class="panel_s">
            <div class="panel-body">

                <div class="weekly-ticket-opening no-shadow tw-mb-10" style="display:none;">
                    <h4 class="tw-font-semibold  tw-flex tw-items-center tw-text-lg" style="height: 20px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="tw-w-5 tw-h-5 tw-mr-1.5 tw-text-neutral-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>

                        <?php echo $label_report; ?>
                    </h4>
                    <small class="tw-mb-8" style="margin-left: 25px;"><?php echo $label_report_title; ?></small>
                    <br />
                    <br />
                    <br />
                    <div class="relative" style="max-height:350px;">
                        <canvas class="chart" id="indice-certificaciones-chart" height="350"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    var chart;
    var chart_data = <?php echo $_statistics_; ?>;

    function init_tickets_weekly_chart() {
        if (typeof(chart) !== 'undefined') {
            chart.destroy();
        }

        chart = new Chart($('#indice-certificaciones-chart'), {
            type: 'bar',
            data: chart_data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                // scales: {
                //     yAxes: [{
                //         ticks: {
                //             beginAtZero: true,
                //         }
                //     }]
                // }
            }
        });


        // Weekly ticket openings statistics
        // chart = new Chart($('#weekly-ticket-openings-chart'), {
        //     type: 'line',
        //     data: chart_data,
        //     options: {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         legend: {
        //             display: false,
        //         },
        //         scales: {
        //             yAxes: [{
        //                 ticks: {
        //                     beginAtZero: true,
        //                 }
        //             }]
        //         }
        //     }
        // });
    }

    slideToggle('.weekly-ticket-opening', init_tickets_weekly_chart);
</script>