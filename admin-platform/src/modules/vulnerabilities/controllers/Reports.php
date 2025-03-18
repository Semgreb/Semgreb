<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends AdminController
{
    private $color = array(
        '#6495ED', '#40E0D0', '#FF7F50', '#FFBF00', '#DE3163', '#9FE2BF', '#7B68EE', '#2E8B57', '#66CDAA', '#7FFFD4',
        '#48D1CC', '#B0C4DE', '#00BFFF', '#4169E1', '#CD853F', '#708090', '#5F9EA0', '#AFEEEE', '#00FFFF', '#008080'
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model');
    }

    public function process_vulnerabilities($reporte = '', $tipo_filtros = '', $fecha_desde = '', $fecha_hasta = '')
    {

        if ($this->input->is_ajax_request()) {
            $this->load->library('REPORT_CONSTANT');
            $label_report = '';
            $label_title = '';

            foreach (get_reports_vulnerabilities() as $status) {
                if (sprintf('report_vulnerabilities%s', $status['code']) == $reporte) {
                    $label_report = $status['translate_name'];
                    break;
                }
            }

            $label_title =  _l($reporte . "_title");

            $data['label_report'] = $label_report;
            $data['label_report_title'] =  $label_title;

            $chart = [
                'labels'   => [],
                'datasets' => [
                    [
                        'label'           => [],
                        'backgroundColor' => [],
                        //   'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                        //  'borderColor'     => '#c53da9',
                        'borderWidth'     => 1,
                        'data'            => [],
                        'hoverBackgroundColor' => []
                    ],
                ],
            ];

            switch ($reporte) {

                case REPORT_CONSTANT::TASA_RIESGO_VULNERABILIDADES:
                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'v.date');
                    $result = $this->Reports_model->total_tasas_riesgo_vulnerabilidad($extraWhere);
                    $i = 0;

                    $state  = "";
                    foreach ($result as $info) {

                        foreach (get_risk_scan() as $qualification) {
                            if ($qualification['status'] == strtoupper($info['risk'])) {
                                $state  = $qualification['translate_name'];
                                break;
                            }
                        }

                        $chart['labels'][$i] =  $state;

                        //$chart['labels'][$i] =  $status;;
                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][0]['backgroundColor'][$i] = $this->color[$i];
                        $chart['datasets'][0]['data'][$i] = $info['cantidad'];
                        $chart['datasets'][0]['hoverBackgroundColor'][$i] = adjust_color_brightness($this->color[$i], -20);

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('vulnerabilities/reports/report_por_periodos', $data, true);
                    break;

                case REPORT_CONSTANT::INDICE_SITIO_RIESGO_VULNERABILIDADES:
                    $extraWhere = $this->get_where_report_period($tipo_filtros,  $fecha_desde,  $fecha_hasta, 'date');
                    $result = $this->Reports_model->tasas_riesgo_vulnerabilidad($extraWhere);

                    $i = 0;
                    $state  = "";
                    foreach ($result as $info) {

                        foreach (get_risk_scan() as $qualification) {
                            if ($qualification['status'] == strtoupper($info['risk'])) {
                                $state  = $qualification['translate_name'];
                                break;
                            }
                        }

                        $chart['labels'][$i] = $state;

                        //$chart['labels'][$i] =  $status;;
                        // $chart['datasets'][0]['label'][$i] = $this->color[$i];
                        $chart['datasets'][0]['backgroundColor'][$i] = $this->color[$i];
                        $chart['datasets'][0]['data'][$i] = $info['cantidad'];
                        $chart['datasets'][0]['hoverBackgroundColor'][$i] = adjust_color_brightness($this->color[$i], -20);

                        $i++;
                    }

                    $data['_statistics_'] = json_encode($chart);
                    echo  $this->load->view('vulnerabilities/reports/report_por_periodos', $data, true);
                    break;
            }
        } else {

            // $data['certifications_years']  = $this->Reports_model->get_distinct_certifications_years();
            $data['vulnerabilities_years']  = $this->Reports_model->get_distinct_vulnerabilities_years();
            $this->app_scripts->add('report-js', module_dir_url('trust_seal', 'assets/js/chart.js'), 'admin', ['app-js']);
            $data['title']   = _l('reports_certification');
            $this->load->view('vulnerabilities/reports/process_vulnerabilities', $data);
        }
    }

    private function get_where_report_period($months_report, $report_from, $report_to, $field = 'date')
    {
        $custom_date_select = '';
        if ($months_report != '') {
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                    date('Y-m-d', strtotime(date('Y-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = 'AND (' . $field . ' BETWEEN "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                    '" AND "' .
                    date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($report_from);
                $to_date   = to_sql_date($report_to);
                if ($from_date == $to_date) {
                    $custom_date_select = 'AND ' . $field . ' = "' . $this->db->escape_str($from_date) . '"';
                } else {
                    $custom_date_select = 'AND (' . $field . ' BETWEEN "' . $this->db->escape_str($from_date) . '" AND "' . $this->db->escape_str($to_date) . '")';
                }
            }
        }

        return $custom_date_select;
    }
}
